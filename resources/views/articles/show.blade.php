<x-page :title="$article->title.' - Yves Engetschwiler'">
    <x-slot name="description">{{ $article->description }}</x-slot>
    <x-slot name="pan">page-article</x-slot>

    @push('head')
        <script type="application/ld+json">
        {!! json_encode([
            '@context' => 'https://schema.org',
            '@graph' => [
                [
                    '@type' => 'BlogPosting',
                    'headline' => $article->title,
                    'description' => $article->description,
                    'datePublished' => $article->date->toAtomString(),
                    'dateModified' => $article->date->toAtomString(),
                    'url' => $article->url(),
                    'mainEntityOfPage' => [
                        '@type' => 'WebPage',
                        '@id' => $article->url(),
                    ],
                    'image' => url('/images/og-image.png'),
                    'inLanguage' => 'en',
                    'wordCount' => $article->wordCount(),
                    'author' => ['@id' => url('/#person')],
                    'publisher' => ['@id' => url('/#person')],
                    'isPartOf' => ['@id' => url('/#website')],
                ],
                [
                    '@type' => 'BreadcrumbList',
                    'itemListElement' => [
                        ['@type' => 'ListItem', 'position' => 1, 'name' => 'Home', 'item' => url('/')],
                        ['@type' => 'ListItem', 'position' => 2, 'name' => 'Articles', 'item' => route('articles.index')],
                        ['@type' => 'ListItem', 'position' => 3, 'name' => $article->title, 'item' => $article->url()],
                    ],
                ],
            ],
        ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}
        </script>
    @endpush

    <div class="flex space-x-4">
        <x-link href="{{ route('articles.index') }}">&larr; Articles</x-link>

        <x-text>{{ $article->date->format('j F Y') }}</x-text>
    </div>

    <x-h1>{{ $article->title }}</x-h1>

    <article class="prose prose-zinc dark:prose-invert max-w-none">
        {!! $article->html !!}
    </article>
</x-page>
