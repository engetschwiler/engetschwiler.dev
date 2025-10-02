<x-page title="Uses - Development Tools & Tech Stack">
    <x-slot name="description">
        Tools, software, and hardware used for web development. Laravel, PHP, VS Code, and the complete development environment of a senior web developer.
    </x-slot>
    <x-slot name="pan">page-uses</x-slot>

    <div class="flex space-x-4">
        <a href="/" class="underline underline-offset-4 hover:text-emerald-500 transition-colors">&larr; Back</a>

        <span>Uses</span>
    </div>
    <h1 class="text-3xl md:text-6xl mt-24 mb-16 font-bold">A part of the amount of tools in my dev stack</h1>

    <div class="space-y-8 md:space-y-12">
        <section>
            <div class="rounded-xl bg-gray-200 p-1 shadow-inner">
                <img class="rounded-lg border border-zinc-400" src="{{ asset('images/workstation.jpg') }}" alt="My workstation">
            </div>
        </section>

        <section>
            <h2 class="italic font-serif">Hardware</h2>

            <p class="my-4">Hardware I use every day.</p>

            <ul>
                <li>Macbook Pro M1</li>
                <li>Mac Mini M4</li>
                <li>Logitech MX Keyboard & Mx Master 4</li>
                <li>Apple AirPods pro</li>
            </ul>
        </section>

        <section>
            <h2 class="italic font-serif">Software</h2>

            <p class="my-4">Apps I’ve been using regularly this year.</p>

            <ul>
                <li>Visual Studio Code</li>
                <li>Laravel Herd</li>
                <li>Iterm2</li>
                <li>Postman</li>
                <li>TablePlus</li>
                <li>Brave Browser</li>
                <li>Transmit</li>
                <li>Kaleidoscope</li>
                <li>ColorSnapper2</li>
                <li>Popcorn</li>
                <li>Spotify</li>
            </ul>
        </section>
    </div>
</x-page>
