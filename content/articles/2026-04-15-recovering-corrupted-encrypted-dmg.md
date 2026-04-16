---
title: "Recovering a Corrupted Encrypted macOS DMG: A Deep Dive"
description: Data loss has a cruel quality when it strikes without warning — a cable pulled loose, a disk disconnected mid-write, and suddenly an encrypted DMG refuses to mount.
---

**TL;DR**: An encrypted DMG file became corrupted after an abrupt disk disconnection. We spent a full day attempting to recover the data, reverse-engineering the `encrcdsa` v2 format, patching HFS+ B-tree headers, dumping process memory with SIP disabled, and ultimately hitting the wall of Apple Silicon's hardware AES acceleration. Here's everything we tried, what worked, and what didn't.

## The Problem

An AES-128 encrypted `.dmg` file (~4.7 GB) stopped mounting after what was likely an abrupt disconnection of an external HDD. The error: `hdiutil: attach failed - no mountable file systems`.

The password was correct — `hdiutil` prompted for it and accepted it without error. But the volume wouldn't mount.

---

## Initial Diagnosis

### Step 1: Verify the file

```bash
hdiutil verify /path/to/Encrypted.dmg
hdiutil attach -noverify Encrypted.dmg
```

Both failed with "no mountable file systems". The file size was suspiciously small on the external drive (~20 MB instead of ~4.7 GB), but a full-sized copy existed elsewhere.

### Step 2: Check the filesystem

```bash
hdiutil attach -noverify -nomount Encrypted.dmg
# → /dev/disk11s2  Apple_HFS
sudo fsck_hfs -ry /dev/rdisk11s2
```

Result:
```
The volume name is Archive
Invalid B-tree node size
(8, 0)
** The volume Archive could not be verified completely.
```

This "Invalid B-tree node size" error became our constant companion for the next several hours.

### Step 3: Inspect the image info

```bash
hdiutil imageinfo Encrypted.dmg
```

Key findings:
- Format: **UDRW** (raw read/write)
- Encryption: **AES-128**
- Partition scheme: **Apple** (legacy)
- Internal filesystem: **HFS+**
- No checksum (`Checksummed: false`)
- Data offset: `122880` bytes

---

## Understanding the Encryption Format

The file starts with the magic bytes `encrcdsa` — this is Apple's proprietary encrypted disk image format, version 2.

### Header structure (`cencrypted_v2_pwheader`)

By reading the raw header bytes and cross-referencing with the open-source [vfdecrypt](https://github.com/bizonix/vfdecrypt) project, we mapped the header:

```
0x000: "encrcdsa"          magic bytes
0x008: 0x00000002          version 2
0x00c: 0x00000010          IV size (16 bytes)
0x024: UUID
0x034: blocksize = 4096
0x068: KDF iterations = 1000
0x06c: KDF salt length = 20
0x070: KDF salt (20 bytes)
0x084: wrapped AES key (40 bytes)   ← CORRUPTED
0x0c8: wrapped HMAC-SHA1 key (48 bytes)
```

### Key derivation

The format uses **PBKDF2-SHA1** to derive a 3DES-EDE key (KEK) from the password, then uses a **3DES-EDE RFC 2630 key unwrap** to decrypt the actual AES and HMAC-SHA1 keys.

```python
import hashlib
from Crypto.Cipher import DES3

kek = hashlib.pbkdf2_hmac('sha1', password, salt, 1000, dklen=24)

IV = bytes([0x4a, 0xdd, 0xa2, 0x2c, 0x79, 0xe8, 0x21, 0x05])
cipher = DES3.new(kek, DES3.MODE_CBC, IV)
temp1 = cipher.decrypt(wrapped_key)
temp2 = bytes(reversed(temp1))
cipher2 = DES3.new(kek, DES3.MODE_CBC, temp2[:8])
cekicv = cipher2.decrypt(temp2[8:])
aes_key = cekicv[4:20]
```

We successfully extracted the **HMAC-SHA1 key**:
```
edc0f6de…
```

### The corruption

The wrapped AES key (40 bytes starting at offset `0x084`) was almost entirely zeroed out:

```
[00-14]: 00 00 00 00 ...  ← ZEROED (corrupted)
[15]:    0x08             ← OK
[16-23]: aa82a95d5ada84ff ← OK
[24-39]: 00 00 00 00 ...  ← ZEROED (corrupted)
```

Only 9 out of 40 bytes survived. The abrupt disconnection had interrupted macOS mid-write of the key blob.

### Why hdiutil still accepts the password

The password validation happens at the header level (PBKDF2 + 3DES unwrap), which is separate from the actual data decryption key. A corrupted wrapped key still "validates" because the password check is structural, not content-based.

---

## Filesystem Recovery Attempts

### Decrypting the volume

Despite the corrupted AES key, `hdiutil -nomount` successfully exposes the HFS+ partition:

```bash
hdiutil attach -noverify -nomount Encrypted.dmg
# → /dev/disk11s2  Apple_HFS
```

The HFS+ volume header at offset `0x400` reads `H+`, confirming the decryption layer works at the metadata level. **Important discovery**: `hdiutil` does not rely solely on the wrapped key stored in the header — it appears to derive or recover the key through another mechanism (possibly the Secure Enclave on Apple Silicon).

### TestDisk — backup volume header repair

```bash
sudo testdisk /dev/disk11s2
# → Advanced → Superblock
```

Result:
```
Volume header: HFS+ OK
Backup volume header: Bad
```

After running "Org. BS" (copy original to backup):
```
Volume header: HFS+ OK
Backup volume header: HFS+ OK
Sectors are identical. ✓
```

Progress — but fsck still failed.

### Identifying the real corruption

Comparing the HFS+ volume header of the corrupted volume against a working reference volume:

```
Offset  Corrupted              Reference
0x004:  80006000 (node=0x6000) 80002000 (node=0x2000) ← WRONG node size!
0x008:  6673636b ("fsck")      4846534a ("HFSJ")      ← dirty flag
```

The B-tree node size was set to `0x6000` (24576) instead of `0x2000` (8192). This single corrupted field caused every `fsck_hfs` invocation to fail with "Invalid B-tree node size".

### Manual volume header patching

```python
f = open('/dev/rdisk11s2', 'r+b')
f.seek(0x404)
f.write(b'\x80\x00\x20\x00')  # correct node size
f.seek(0x408)
f.write(b'HFSJ')               # clear dirty flag
f.close()
```

Still failed. The B-tree catalog header node itself was corrupted.

### B-tree catalog header node

The catalog B-tree starts at block `0x6426` → offset `0x6426000`. Reading that node:

```
Expected: 00 00 00 00 00 00 00 00 01 00 00 03 ...  (valid BTNodeDescriptor)
Found:     71 19 9a 0e 3b 3e 05 c1 7e 69 c2 90 ...  (garbage)
```

We patched it with the header node from the reference volume. `fsck_hfs` then reported:

```
Invalid node structure
Invalid B-tree node size
```

Two errors instead of one — technically progress, but the B-tree was too corrupted to repair automatically.

---

## Data Presence Confirmation

Despite the inability to mount the volume, we confirmed the data exists:

### Finding file names in the raw image

```python
data = open('archive_backup.img', 'rb').read()
# Search for UTF-16 BE file extensions
patterns = [b'\x00.\x00t\x00x\x00t', b'\x00.\x00p\x00d\x00f', ...]
```

Result: **2,656 file references** found

The data is physically present. The problem is purely navigational (broken B-tree index).

### Extracting catalog records

B-tree leaf nodes were located by scanning for valid `BTNodeDescriptor` signatures. File metadata was successfully parsed:

```
fileID: 15765
Logical size: 564659 bytes (Document.pdf)
Extent: start=16213, count=138
File offset: 0x3f55000
```

However, reading that offset produced encrypted data — confirming the AES decryption layer is not functioning correctly for file data blocks.

---

## The AES Decryption Mystery

### Per-sector IV calculation

According to vfdecrypt, the IV for each chunk is computed as:

```python
iv = HMAC-SHA1(hmac_key, chunk_no_big_endian)[:16]
```

With `dataBlockSize = 4096` (confirmed via `hdiutil -debug`), and the HMAC key we extracted, we tested all chunk_no values 0–200 against our known plaintext/ciphertext pair.

None matched. Our AES key candidates (derived from the corrupted wrapped key) were all wrong.

### The core dump attempt

With SIP disabled (Recovery Mode → `csrutil disable`), we used `gcore` to dump the `diskimages-helper` process memory:

```bash
sudo gcore $(pgrep diskimages-helper)
# → 5.4 GB core dump
```

We searched for:
1. The known plaintext/ciphertext pair
2. Intermediate AES-ECB values
3. Known patterns from the header
4. Round key schedules

**Nothing found.** Apple Silicon's hardware AES acceleration (via the Secure Enclave) processes cryptographic operations without leaving key material in accessible RAM. The keys never appear in the process's memory space in a recoverable form.

---

## Why the Data is Inaccessible

The fundamental issue is a two-layer problem:

1. **Wrapped AES key corrupted**: 31 of 40 bytes zeroed out during abrupt disconnection. Without the correct wrapped key, we cannot derive the AES-128 DEK (Data Encryption Key).

2. **Hardware AES on Apple Silicon**: Even though `hdiutil` can mount `02.dmg` (a sibling volume with identical corruption), the actual AES key operations happen inside the Secure Enclave and are never exposed to software.

The fact that `hdiutil` successfully mounts the sibling volume despite the same header corruption suggests Apple has an alternative key recovery path — possibly involving the Secure Enclave, the system Keychain, or a hardware-bound key derivation. This mechanism is not documented and is inaccessible to third-party tools.

---

## What a Professional Recovery Lab Would Do

A specialized lab (Ontrack, DriveSavers) would attempt:

1. **Brute-force the missing key bytes**: With 31 unknown bytes, this requires GPU farms. Even so, with 8 bytes of pure random IV, the search space (~2^248) is computationally infeasible without additional constraints.

2. **Hardware analysis**: Physical inspection of the storage medium for any residual key material in NAND cells.

3. **Apple Secure Enclave analysis**: Specialized hardware to query or bypass the SEP (Secure Enclave Processor) — if the key is bound to the hardware, recovery may be possible on the original machine.

---

## Lessons Learned

### For users
- **Never use an encrypted DMG as your only copy of important data.** If the file is corrupted, you lose everything.
- **Time Machine backs up DMG files** — even encrypted ones. A backup from before the corruption would have solved this in seconds.
- **Consider FileVault 2 with a recovery key** for system-level encryption, which has proper key escrow.

### For developers
- The `encrcdsa` v2 format does not implement checksumming by default (`Checksummed: false`). There is no way to detect partial corruption.
- The wrapped key blob should be backed up separately if you're building tools on top of encrypted DMGs.
- On Apple Silicon, AES key material is never accessible from userspace, by design.

### Technical summary
```
Format:     encrcdsa v2, AES-128-CBC
KDF:        PBKDF2-SHA1, 1000 iterations
Key wrap:   3DES-EDE RFC 2630
IV:         HMAC-SHA1(hmac_key, chunk_no)[:16]
Block size: 4096 bytes
Corruption: wrapped AES key zeroed (31/40 bytes)
Recovery:   Not possible without professional hardware tools
```

---

## Tools Used

| Tool | Purpose | Result |
|------|---------|--------|
| `hdiutil` | Mount, imageinfo, debug | Partial — mounts with password but no filesystem |
| `fsck_hfs` | B-tree repair | Failed — node size too corrupted |
| `TestDisk` | Volume header repair | Partial — backup header fixed |
| `PhotoRec` | File carving | Failed — AES layer prevents signature detection |
| `Disk Drill` | Advanced recovery | Found 4,482 files but extracted encrypted data |
| `gcore` + SIP disabled | Memory dump | Failed — Apple Silicon hardware AES |
| Python + pycryptodome | Manual key extraction | HMAC key recovered, AES key unrecoverable |

---

## Resources

- [vfdecrypt source](https://github.com/bizonix/vfdecrypt) — Reference implementation of `encrcdsa` decryption
- [Apple HFS+ Volume Format](https://developer.apple.com/library/archive/technotes/tn/tn1150.html) — HFS+ specification
- [RFC 2630](https://www.rfc-editor.org/rfc/rfc2630) — 3DES key wrap algorithm
- `man hdiutil` — Essential reading for DMG manipulation

---

*This write-up documents a real recovery attempt. If you're facing a similar situation, the key takeaway is: the earlier you stop writing to the affected disk and seek professional help, the better your chances.*
