<x-page title="Articles - Yves Engetschwiler">
    <x-slot name="description">
        Articles and notes on Laravel, PHP, and web development by Yves Engetschwiler.
    </x-slot>
    <x-slot name="pan">page-articles</x-slot>

    @push('head')
        <script type="application/ld+json">
        {!! json_encode([
            '@context' => 'https://schema.org',
            '@type' => 'Blog',
            '@id' => route('articles.index').'#blog',
            'url' => route('articles.index'),
            'name' => 'Articles — Yves Engetschwiler',
            'description' => 'Articles and notes on Laravel, PHP, and web development by Yves Engetschwiler.',
            'inLanguage' => 'en',
            'author' => ['@id' => url('/#person')],
            'publisher' => ['@id' => url('/#person')],
            'isPartOf' => ['@id' => url('/#website')],
            'blogPost' => $articles->map(fn ($a) => [
                '@type' => 'BlogPosting',
                'headline' => $a->title,
                'description' => $a->description,
                'url' => $a->url(),
                'datePublished' => $a->date->toAtomString(),
                'author' => ['@id' => url('/#person')],
            ])->all(),
        ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}
        </script>
    @endpush

    <div class="flex space-x-4">
        <x-link href="/">&larr; Back</x-link>

        <x-text>Articles</x-text>
    </div>

    <x-h1>Articles</x-h1>

    <x-text class="my-2 text-sm opacity-75">
        Subscribe via <x-link href="{{ route('feed') }}">RSS</x-link>.
    </x-text>

    @if ($articles->isEmpty())
        <x-text class="italic">No articles yet.</x-text>
    @else
        <div class="space-y-8 md:space-y-12">
            @foreach ($articles as $article)
                <section>
                    <x-h2 class="italic font-serif">
                        <strong>
                            <x-link href="{{ $article->url() }}">{{ $article->title }}</x-link>
                        </strong>
                    </x-h2>

                    <x-text class="my-2 text-sm opacity-75">{{ $article->date->format('j F Y') }}</x-text>

                    @if ($article->description !== '')
                        <x-text class="my-4">{{ $article->description }}</x-text>
                    @endif
                </section>
            @endforeach
        </div>
    @endif
</x-page>
