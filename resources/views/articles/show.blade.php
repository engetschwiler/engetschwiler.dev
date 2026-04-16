<x-page :title="$article->title.' - Yves Engetschwiler'">
    <x-slot name="description">{{ $article->description }}</x-slot>
    <x-slot name="pan">page-article</x-slot>

    <div class="flex space-x-4">
        <x-link href="{{ route('articles.index') }}">&larr; Articles</x-link>

        <x-text>{{ $article->date->format('j F Y') }}</x-text>
    </div>

    <x-h1>{{ $article->title }}</x-h1>

    <article class="prose prose-zinc dark:prose-invert max-w-none">
        {!! $article->html !!}
    </article>
</x-page>
