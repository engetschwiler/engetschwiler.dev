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
