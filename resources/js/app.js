import Alpine from 'alpinejs'

Alpine.data('themeToggle', () => ({
    isDark: false,

    init() {
        this.isDark = document.documentElement.classList.contains('dark')
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
    }
}))

window.Alpine = Alpine

Alpine.start()
