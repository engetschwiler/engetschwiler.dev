<!DOCTYPE html>
<html lang="en">
    <head>
        @include('partials.meta')

        @vite(['resources/css/app.css', 'resources/js/app.js'])

        @stack('head')

        <script>
            if (localStorage.theme === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
                document.documentElement.classList.add('dark')
            } else {
                document.documentElement.classList.remove('dark')
            }
        </script>
    </head>

    <body class="font-sans overflow-y-scroll bg-zinc-100 dark:bg-emerald-950">
        <div class="max-w-3xl mx-auto p-6 lg:p-8 relative">
            <div class="absolute top-4 right-4" x-data="themeToggle()">
                <button @click="toggle()" class="p-2 hover:bg-zinc-200 dark:hover:bg-emerald-900 dark:text-zinc-200 rounded-full border-transparent border-2 active:border-transparent focus-within:border-zinc-600 focus:outline-none focus:border-dotted dark:focus:border-zinc-300" :title="isDark ? 'Switch to light mode' : 'Switch to dark mode'">
                    <svg x-cloak xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true" data-slot="icon" class="w-6 h-6" x-show="!isDark"><path d="M12 2.25a.75.75 0 0 1 .75.75v2.25a.75.75 0 0 1-1.5 0V3a.75.75 0 0 1 .75-.75ZM7.5 12a4.5 4.5 0 1 1 9 0 4.5 4.5 0 0 1-9 0ZM18.894 6.166a.75.75 0 0 0-1.06-1.06l-1.591 1.59a.75.75 0 1 0 1.06 1.061l1.591-1.59ZM21.75 12a.75.75 0 0 1-.75.75h-2.25a.75.75 0 0 1 0-1.5H21a.75.75 0 0 1 .75.75ZM17.834 18.894a.75.75 0 0 0 1.06-1.06l-1.59-1.591a.75.75 0 1 0-1.061 1.06l1.59 1.591ZM12 18a.75.75 0 0 1 .75.75V21a.75.75 0 0 1-1.5 0v-2.25A.75.75 0 0 1 12 18ZM7.758 17.303a.75.75 0 0 0-1.061-1.06l-1.591 1.59a.75.75 0 0 0 1.06 1.061l1.591-1.59ZM6 12a.75.75 0 0 1-.75.75H3a.75.75 0 0 1 0-1.5h2.25A.75.75 0 0 1 6 12ZM6.697 7.757a.75.75 0 0 0 1.06-1.06l-1.59-1.591a.75.75 0 0 0-1.061 1.06l1.59 1.591Z"></path></svg>

                    <svg x-cloak xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true" data-slot="icon" class="w-6 h-6" x-show="isDark"><path fill-rule="evenodd" d="M9.528 1.718a.75.75 0 0 1 .162.819A8.97 8.97 0 0 0 9 6a9 9 0 0 0 9 9 8.97 8.97 0 0 0 3.463-.69.75.75 0 0 1 .981.98 10.503 10.503 0 0 1-9.694 6.46c-5.799 0-10.5-4.7-10.5-10.5 0-4.368 2.667-8.112 6.46-9.694a.75.75 0 0 1 .818.162Z" clip-rule="evenodd"></path></svg>
                </button>
            </div>

            <main role="main">
                {{ $slot }}
            </main>

            <footer class="text-sm mt-24 flex justify-between">
                <div>
                    <x-text>&copy; {{ date('Y') }}</x-text>
                </div>

                <div class="flex space-x-4">
                    <x-link href="/colophon">Colophon</x-link>
                    <x-link href="/privacy">Privacy</x-link>
                </div>
            </footer>
        </div>
    </body>
</html>
