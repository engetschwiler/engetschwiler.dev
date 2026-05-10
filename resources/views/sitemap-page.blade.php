<x-page title="Sitemap - Yves Engetschwiler">
    <x-slot name="description">
        Full sitemap of yves.engetschwiler.dev — main pages, articles, and machine-readable resources.
    </x-slot>
    <x-slot name="pan">page-sitemap</x-slot>

    <div class="flex space-x-4">
        <x-link href="/">&larr; Back</x-link>

        <x-text>Sitemap</x-text>
    </div>

    <x-h1>Sitemap</x-h1>

    <div class="space-y-8 md:space-y-12">
        <section>
            <x-h2 class="italic font-serif mb-2">Main pages</x-h2>

            <ul class="space-y-2">
                <li><x-text><x-link href="{{ route('home') }}">Home</x-link></x-text></li>
                <li><x-text><x-link href="{{ route('articles.index') }}">Articles</x-link></x-text></li>
                <li><x-text><x-link href="{{ route('talks') }}">Talks</x-link></x-text></li>
                <li><x-text><x-link href="{{ route('uses') }}">Uses</x-link></x-text></li>
                <li><x-text><x-link href="{{ route('colophon') }}">Colophon</x-link></x-text></li>
                <li><x-text><x-link href="{{ route('privacy') }}">Privacy</x-link></x-text></li>
            </ul>
        </section>

        <section>
            <x-h2 class="italic font-serif mb-2">Articles</x-h2>

            @if ($articles->isEmpty())
                <x-text class="italic">No articles yet.</x-text>
            @else
                <ul class="space-y-2">
                    @foreach ($articles as $article)
                        <li>
                            <x-text>
                                <x-link href="{{ $article->url() }}">{{ $article->title }}</x-link>
                                <span class="opacity-75">— {{ $article->date->format('j F Y') }}</span>
                            </x-text>
                        </li>
                    @endforeach
                </ul>
            @endif
        </section>

        <section>
            <x-h2 class="italic font-serif mb-2">Feeds &amp; resources</x-h2>

            <ul class="space-y-2">
                <li><x-text><x-link href="{{ route('feed') }}">RSS feed</x-link></x-text></li>
                <li><x-text><x-link href="{{ route('sitemap') }}">XML sitemap</x-link></x-text></li>
                <li><x-text><x-link href="{{ url('/llms.txt') }}">llms.txt</x-link></x-text></li>
            </ul>
        </section>
    </div>
</x-page>
