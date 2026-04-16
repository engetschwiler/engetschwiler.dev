import Alpine from 'alpinejs'

Alpine.data('themeToggle', () => ({
    isDark: false,

    init() {
        this.isDark = document.documentElement.classList.contains('dark')
        this.updateThemeColor()
    },

    toggle() {
        this.isDark = !this.isDark

        if (this.isDark) {
            document.documentElement.classList.add('dark')
            localStorage.theme = 'dark'
        } else {
            document.documentElement.classList.remove('dark')
            localStorage.theme = 'light'
        }

        this.updateThemeColor()
    },

    updateThemeColor() {
        const metaThemeColor = document.querySelector('meta[name="theme-color"]')
        if (metaThemeColor) {
            metaThemeColor.setAttribute('content', this.isDark ? '#022c22' : '#f4f4f5')
        }
    }
}))

window.Alpine = Alpine

Alpine.start()

const copyIcon = `<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round" class="w-4 h-4" aria-hidden="true"><rect x="9" y="9" width="13" height="13" rx="2"/><path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"/></svg>`
const checkIcon = `<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-4 h-4" aria-hidden="true"><polyline points="20 6 9 17 4 12"/></svg>`

function attachCopyButtons() {
    document.querySelectorAll('.prose pre').forEach((pre) => {
        if (pre.dataset.copyAttached === 'true') return

        const code = pre.querySelector('code')
        if (!code) return

        pre.dataset.copyAttached = 'true'

        const button = document.createElement('button')
        button.type = 'button'
        button.className = 'code-copy-button'
        button.setAttribute('aria-label', 'Copy code to clipboard')
        button.setAttribute('title', 'Copy')
        button.innerHTML = copyIcon

        button.addEventListener('click', async () => {
            try {
                await navigator.clipboard.writeText(code.innerText)
                button.innerHTML = checkIcon
                button.classList.add('is-copied')
                button.setAttribute('title', 'Copied!')
                setTimeout(() => {
                    button.innerHTML = copyIcon
                    button.classList.remove('is-copied')
                    button.setAttribute('title', 'Copy')
                }, 1500)
            } catch {
                // Clipboard unavailable (insecure context, denied permission); no-op.
            }
        })

        pre.appendChild(button)
    })
}

if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', attachCopyButtons)
} else {
    attachCopyButtons()
}
