<x-page title="Uses - Development Tools & Tech Stack">
    <x-slot name="description">
        Tools, software, and hardware used for web development. Laravel, PHP, VS Code, and the complete development environment of a senior web developer.
    </x-slot>
    <x-slot name="pan">page-uses</x-slot>

    <div class="flex space-x-4">
        <x-link href="/">&larr; Back</x-link>

        <x-text>Uses</x-text>
    </div>
    <x-h1>A part of the amount of tools in my dev stack</x-h1>

    <div class="space-y-8 md:space-y-12">
        <section>
            <div class="rounded-xl bg-gray-200 p-1 shadow-inner dark:bg-emerald-900">
                <img class="rounded-lg border border-zinc-400 dark:border-zinc-700" src="{{ asset('images/workstation.jpg') }}" alt="My workstation">
            </div>
        </section>

        <section>
            <x-h2 class="italic font-serif">Hardware</x-h2>

            <x-text class="my-4">Hardware I use every day.</x-text>

            <ul>
                <li><x-text>Macbook Pro M1</x-text></li>
                <li><x-text>Mac Mini M1</x-text></li>
                <li><x-text>Mac Mini M4</x-text></li>
                <li><x-text>Logitech MX Keyboard & Mx Master 3 and 4 mouse</x-text></li>
                <li><x-text>Apple AirPods pro</x-text></li>
            </ul>
        </section>

        <section>
            <x-h2 class="italic font-serif">Software</x-h2>

            <x-text class="my-4">Apps I’ve been using regularly this year.</x-text>

            <ul>
                <li><x-text>Visual Studio Code</x-text></li>
                <li><x-text>Laravel Herd</x-text></li>
                <li><x-text>Iterm2</x-text></li>
                <li><x-text>Postman</x-text></li>
                <li><x-text>TablePlus</x-text></li>
                <li><x-text>Brave Browser</x-text></li>
                <li><x-text>Transmit</x-text></li>
                <li><x-text>Figma</x-text></li>
                <li><x-text>Slack</x-text></li>
                <li><x-text>Kaleidoscope</x-text></li>
                <li><x-text>ColorSnapper2</x-text></li>
                <li><x-text>Xnapper</x-text></li>
                <li><x-text>Rectangle</x-text></li>
                <li><x-text>Raycast</x-text></li>
                <li><x-text>Popcorn</x-text></li>
                <li><x-text>IINA</x-text></li>
                <li><x-text>Spotify</x-text></li>
            </ul>
        </section>
    </div>
</x-page>
