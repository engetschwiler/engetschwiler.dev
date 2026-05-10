<x-page title="404 - Page not found - Yves Engetschwiler">
    <x-slot name="description">
        This page doesn't exist — but plenty of others do. Head back home or browse the articles.
    </x-slot>
    <x-slot name="pan">page-404</x-slot>

    @push('head')
        <meta name="robots" content="noindex">
    @endpush

    <x-text>Well, that's awkward 😅</x-text>
    <x-h1>404 — this page doesn't exist.</x-h1>

    <div class="space-y-8 md:space-y-12">
        <section>
            <x-text>You followed a link that goes nowhere, mistyped a URL, or stumbled in from a search engine that's a little out of date. It happens.</x-text>

            <x-text class="mt-4">If you were expecting something specific, the article you're looking for might have moved — try the archive.</x-text>
        </section>

        <section>
            <x-h2 class="italic font-serif mb-2">Where to go from here</x-h2>

            <ul class="space-y-2">
                <li><x-text><x-link href="{{ route('home') }}">Back to the home page</x-link></x-text></li>
                <li><x-text><x-link href="{{ route('articles.index') }}">Browse all articles</x-link></x-text></li>
                <li><x-text><x-link href="{{ route('sitemap.html') }}">Open the sitemap</x-link></x-text></li>
            </ul>
        </section>
    </div>
</x-page>
