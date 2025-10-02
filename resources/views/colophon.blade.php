<x-page title="Colophon - Tech Stack & Credits">
    <x-slot name="description">
        Technical details about this website. Built with Laravel, Tailwind CSS, Alpine.js, and hosted with modern development tools.
    </x-slot>
    <x-slot name="pan">page-colophon</x-slot>

    <div class="flex space-x-4">
        <x-link href="/">&larr; Back</x-link>

        <x-text>Colophon</x-text>
    </div>
    <x-h1>How this website is built</x-h1>

    <div class="space-y-8 md:space-y-12">
        <section>
            <x-h2 class="italic font-serif">Framework & Backend</x-h2>

            <x-text class="my-4">The core technologies powering this site.</x-text>

            <ul>
                <li><x-text><x-link href="https://laravel.com" target="_blank">Laravel 12</x-link> - PHP Framework</x-text></li>
                <li><x-text>PHP 8.3+</x-text></li>
            </ul>
        </section>

        <section>
            <x-h2 class="italic font-serif">Frontend</x-h2>

            <x-text class="my-4">Frontend technologies and tooling.</x-text>

            <ul>
                <li><x-text><x-link href="https://tailwindcss.com" target="_blank">Tailwind CSS 4</x-link> - Utility-first CSS framework</x-text></li>
                <li><x-text><x-link href="https://alpinejs.dev" target="_blank">Alpine.js 3</x-link> - Lightweight JavaScript framework</x-text></li>
                <li><x-text><x-link href="https://vite.dev" target="_blank">Vite</x-link> - Build tool</x-text></li>
            </ul>
        </section>

        <section>
            <x-h2 class="italic font-serif">Development Tools</x-h2>

            <x-text class="my-4">Tools used for local development and testing.</x-text>

            <ul>
                <li><x-text><x-link href="https://herd.laravel.com" target="_blank">Laravel Herd</x-link> - Local development environment</x-text></li>
                <li><x-text><x-link href="https://pestphp.com" target="_blank">Pest</x-link> - Testing framework</x-text></li>
                <li><x-text><x-link href="https://laravel.com/docs/pint" target="_blank">Laravel Pint</x-link> - Code formatting</x-text></li>
                <li><x-text><x-link href="https://playwright.dev" target="_blank">Playwright</x-link> - Browser testing</x-text></li>
            </ul>
        </section>

        <section>
            <x-h2 class="italic font-serif">Credits</x-h2>

            <x-text class="my-4">This website is designed, developed, and maintained by <x-link href="/">Yves Engetschwiler</x-link>.</x-text>
        </section>
    </div>
</x-page>
