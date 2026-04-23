---
title: "Building Alpage: What I Learned Writing a Native macOS Dev Environment in Swift, with AI as a Pair"
description: A native macOS menu bar app that manages a local web dev environment
---

## Why I Started

So here's the thing. I didn't set out to build a competitor to Laravel Herd or Valet. I just had two questions I'd been sitting on for a while, and I wanted answers.

The first one was about Swift. I'd written small utilities, a bit of SwiftUI, a few CLI helpers — but nothing big. Nothing that forced me through every layer of the stack. I wanted to know: if I really committed to native Swift for a serious project, how far could I actually go? Could I do the menu bar stuff, the background daemons, the privilege escalation, the TLS certificates, the process orchestration, the design system — all of it — without reaching for something else?

The second question was about AI. Everyone talks about it. I wanted to find out for myself how useful it really is as a pair when you're deep in a real codebase. Not for autocompleting a `for` loop — I mean for architecture decisions, for debugging a weird `launchctl` issue at 11pm, for remembering how `Security.framework` works when you haven't touched it in a year. Is it a genuine productivity boost, or is it a fancy rubber duck?

So I picked a project. **Alpage**. A native macOS menu bar app that manages a local web dev environment — PHP, Node, databases, HTTPS, DNS, reverse-proxy routing — from a single SwiftUI interface, with a companion CLI. And I used it as my training ground. What follows is what I built, how it fits together, and what I actually learned doing it this way.

![The Alpage logo — a calm, native macOS identity for a local dev environment](/img/articles/2026-04-23-alpage-application/logo.png "Alpage — a native macOS menu bar app for local web development")

## What It Does

I picked a problem I know well, on purpose. I wanted the friction to be Swift friction, not domain friction. Every PHP developer on macOS has lived the same cycle: Homebrew, a broken FPM socket, MAMP, then Valet, then Herd, then an expired certificate that eats a morning. I wanted my own version of that story, as a calm native app.

In practice, Alpage does this:

- **PHP versions from 5.6 to 8.6**, each with its own FPM pool on a deterministic port.
- **Multiple Node versions**, one active globally, overridable per project from the CLI.
- **Databases** — MySQL, MariaDB, Postgres — each installed as an isolated binary.
- **Automatic site discovery** from "parked" folders. Drop a project into `~/Sites` and it's live at `project.test`.
- **HTTPS out of the box**, through Caddy and self-signed certs that get added to the keychain automatically.
- **DNS for `.test`** through dnsmasq and a resolver entry under `/etc/resolver/`.
- **LAN sharing and tunneling** for mobile testing.
- **Valet compatibility**, because I use Valet on other machines and didn't want to burn that bridge.

The whole thing runs from a menu bar icon and one JSON file at `~/Library/Application Support/Alpage/config.json`. That rule — one source of truth, and it's a file I can open in any text editor — was one of the first things I decided, and I never regretted it.

![The Alpage sites view showing automatically discovered projects with their drivers and HTTPS status](/img/articles/2026-04-23-alpage-application/sites.png "Sites — parked folders show up automatically, each with its driver and HTTPS state")

## How the Code Is Laid Out

I made it a Swift Package with two targets: the GUI app and a CLI. It looks like this:

```
Alpage/                # macOS GUI (SwiftUI)
├── Models/            # Codable structs
├── Views/             # SwiftUI views
├── ViewModels/        # @Observable state managers
├── Services/          # Downloads, process execution
├── Helpers/           # Config generators, certs, I/O
└── DesignSystem/      # Flow tokens and components

AlpageCLI/
└── Commands/          # One file per CLI command

Package.swift          # Shares Models/Helpers across both targets
```

One thing I really wanted to figure out was: how do you share code between a SwiftUI app and a CLI without the whole thing turning into a mess? Turns out, it's just a disciplined `Package.swift` with `exclude` and `sources` lists. The models, the config generators, the site manager — all of that is plain Swift, no UI. The CLI just links it. The SwiftUI side stays on its own side of the wall. When I add a feature to `SiteManager`, both the GUI and the CLI get it for free.

Halfway through the project I realized this is really just MVVM taken seriously. Views are dumb. View models are `@MainActor @Observable`. Everything reusable lives below the view model layer, in plain Swift. That discipline is what kept the project shippable as it grew.

## The Five Managers

Most of what's interesting lives in five view models.

**`SiteManager`** is the heart of it. It watches the parked folders, merges them with any linked sites, and spits out a list of `Site` structs — each with a path, a TLD, a driver (Laravel, WordPress, plain PHP, static, SPA, proxy), HTTPS and LAN flags, and an optional PHP override. A filesystem monitor keeps the list live, so dropping a new folder into `~/Sites` just works.

**`PhpManager`** downloads PHP versions from `static-php-cli` — pre-compiled binaries with FPM bundled. I very deliberately did *not* want to own a PHP build pipeline. Each version's FPM listens on a port computed as `9000 + (major × 10) + minor`. So PHP 8.3 is always 9083. The Caddyfile generator, the CLI, and the GUI all compute that same port independently and never disagree. Tiny decision, paid off constantly.

![The PHP management view with installable versions from 5.6 to 8.6, each with its own FPM pool](/img/articles/2026-04-23-alpage-application/php.png "PHP — every supported version installable side by side, each on a deterministic FPM port")

**`NodeManager`** is thin on purpose. Node is a runtime, not a daemon. I install the official release, symlink the active version, done.

**`ServiceManager`** handles the databases. Each engine has its own data directory, its own generated config, its own log, its own lifecycle. A small `DatabaseLinker` drops symlinks for `mysql`, `mariadb`, `psql` into `~/.local/bin` so they're on the PATH without fighting Homebrew.

![The services view listing MySQL, MariaDB and Postgres with their status and controls](/img/articles/2026-04-23-alpage-application/services.png "Services — each database engine runs isolated, with its own config, logs and lifecycle")

**`CaddyManager`** is the glue. It regenerates the Caddyfile from the current site list, manages the self-signed certs, and starts Caddy and dnsmasq as long-lived privileged processes through `launchctl`. Any interesting change in `SiteManager` eventually flows through `CaddyManager`.

## Everything Is a Generator

Here's a principle that kind of emerged on its own, and then ended up shaping the whole project: **every external tool Alpage talks to has its own generator module, and those generators just take the Swift models and emit text.**

- `CaddyfileGenerator` builds the Caddy config.
- `PhpFpmConfigGenerator` writes FPM pool files.
- `PhpIniGenerator` handles `memory_limit`, upload sizes, execution time.
- `DnsmasqConfigGenerator` writes dnsmasq and the macOS resolver entry.
- `DatabaseConfigGenerator` writes engine-specific configs.

The nice consequence is that the whole state of your machine is a deterministic function of `config.json`. Something drifted? An update failed halfway? I can just regenerate. I stole the idea from declarative provisioning tools, and it turns out it scales down really well to a single-user desktop app. This was probably the single most useful architectural insight I took from the whole project.

## The Annoying Parts: Sudo, Keychain, launchctl

Writing a local dev environment on modern macOS means dealing with a few realities. Ports 80 and 443 need privileges. Trusting a new root cert needs authorization. Background services need a lifecycle that survives sleep and reboot. None of this is exciting. All of it is where hobby projects usually stop and real apps begin.

Here's what I ended up building for it:

- `ProcessRunner` wraps `Process` and keeps track of long-running children.
- `ShellHelper` is an async wrapper around one-shot shell commands.
- `SudoersManager` installs narrowly scoped passwordless sudoers entries for Caddy and dnsmasq — scoped to the exact binaries Alpage ships, never blanket root.
- Caddy and dnsmasq run as LaunchAgents via `launchctl`, so they come back cleanly after a reboot.
- Certificates are generated with `Security.framework` directly; `security add-trusted-cert` installs the root into the keychain.
- A `ValetCompatibilityManager` mirrors the TLD, secured sites, and certs into `~/.config/valet/`, so I can flip between the two tools without re-configuring.

This is where AI earned its keep the most visibly. `Security.framework` is not a friendly API. Launchd plists are genuinely finicky. Sudoers syntax punishes you for typos. Being able to say "why does this `SecItemCopyMatching` return `errSecItemNotFound` when the item is clearly in the keychain?" and get a focused answer in seconds — that saved me the kind of afternoon I used to burn on Stack Overflow. But — and this is important — the AI was rarely right on the first try for anything that touched system APIs. It was right on the second or third try, after I pushed back with real error messages and real context. That's not a complaint. That's what a useful pair actually looks like.

## The CLI

The CLI is a single SwiftPM executable, one file per command:

| Command | What it does |
| --- | --- |
| `secure` / `unsecure` | Toggle HTTPS for a site. |
| `use <version>` | Set the default PHP version. |
| `isolate <version>` | Override PHP for the current directory's site. |
| `node use <version>` | Switch the active Node version. |
| `php <args>` / `composer <args>` | Run PHP or Composer with the site's resolved version. |
| `laravel <args>` | Proxy to the Laravel installer. |
| `db create/delete/export/import` | Manage databases. |
| `link` | Symlink the DB CLIs into `~/.local/bin`. |

The CLI is directory-aware — `isolate`, `php`, and `composer` figure out the current site from the working directory. And because the CLI and GUI share the same models, there's no divergent logic. I isolate a site from the terminal, it shows up in the GUI immediately.

## Modern Swift, End to End

On the pure Swift side, I pushed myself into modern idioms on purpose. I didn't want to fall back on what I already knew.

- **`@Observable` view models** instead of `ObservableObject` + `@Published`. No manual `objectWillChange`, no `@StateObject` noise.
- **`async`/`await` everywhere**. No Combine, no callback pyramids. Downloads, installs, process starts — all linear async functions, with `@Sendable` closures for progress.
- **`@MainActor` view models** so UI thread safety is a compile-time thing, not a convention.
- **Swift Testing framework** (`@Test`) instead of XCTest.
- **Codable JSON persistence** for the whole app state, through one `AppConfig` struct. No SQLite, no plist sprawl.

I could have written all this in 2019-era Swift and nobody would've noticed. But I specifically wanted to write it the way Swift wants to be written in 2026 — and honestly, the difference is real. The code is shorter. The concurrency story is coherent. The compiler catches entire classes of threading bugs I used to have to test my way out of.

## The Design System

About a third of the way in, I realized I was going to drown in visual inconsistency if I didn't stop and build a design system. So I did.

The `DesignSystem/` folder now holds `FlowTokens` (colors, typography, spacing, radius) and five reusable components: `FlowBadge`, `FlowStatusDot`, `FlowListRow`, `FlowToolbarButton`, `FlowSectionHeader`. The Inter font is bundled in the app, not loaded from the system. Dark mode runs through dynamic color providers, not a forked palette.

The full migration — from an initial `NavigationSplitView` to a three-column sidebar/list/detail layout with a persistent status bar — is written up in a `FLOW_REDESIGN_COMPLETE.md` inside the repo. Writing that doc as I went was itself an experiment. I wanted to see whether keeping an explicit design log helped the AI stay consistent across sessions. It did, noticeably. Sessions where I fed the design doc back in produced UI code that looked like it came from the same project. Sessions where I didn't, didn't.

![The Alpage settings panel with Flow design tokens applied across the interface](/img/articles/2026-04-23-alpage-application/settings.png "Settings — the Flow design system keeps every surface visually consistent")

## What I Actually Learned About Building with AI

Okay, this is the part I actually want to tell you about. Now that the project is in real daily use, here's what I honestly think.

**AI is great at the middle layer.** The glue code — shell helpers, file I/O, Codable structs, CLI arg parsing, SwiftUI boilerplate — is where I got the biggest speed-up by far. I could describe a `Site` model in a sentence and get a clean Codable struct back, then iterate from there. That layer used to take hours. Now it takes minutes.

**AI is only okay at architecture.** For the big calls — MVVM boundaries, how to share code between the GUI and CLI, how to model per-site PHP overrides — I had to do the thinking. If I asked the AI, it would happily hand me three plausible designs; it would not tell me which one I'd regret in three months. Those calls were mine. And honestly, that's how it should be.

**AI is genuinely useful against obscure system APIs, but you have to supervise it.** `Security.framework`, `launchctl`, sudoers, dnsmasq, code signing — the AI sped me up in every one of those. But I caught real mistakes every time. You have to treat it as an informed collaborator, not an oracle. That shift in mindset is everything.

**Writing things down helps the AI help you.** The `CLAUDE.md` in the repo, the design system doc, the PHP extensions notes — I didn't write those just for humans. I wrote them for the next AI session. Feeding explicit context back into the loop is the single highest-leverage habit I picked up on this project. If you're not doing it, start.

**Swift is a genuinely pleasant language to live in.** I knew that in theory. I didn't know it in my fingers until now.

## What's Next

Alpage runs on my machine every day. That was the bar I wanted to hit. There's a Sparkle auto-updater wired up, the Valet compatibility layer for anyone who wants to migrate, and a CLI that does everything the GUI does. From here, the work is mostly sharpening — more drivers, better onboarding, better diagnostics when a background service dies.

But the two questions I started with? I think I have my answers. Yes, you can go surprisingly far in pure native Swift on a project this size, without reaching for cross-platform frameworks or web wrappers. And yes, AI is a real collaborator for serious work — not magic, not a replacement for understanding, but honestly the most useful engineering tool I've added to my workflow in years.

Alpage is the evidence. The calm menu bar icon and the `.test` sites that just work — that's the product. The codebase behind them is, for me, the actual result.
