<x-page title="Articles - Yves Engetschwiler">
    <x-slot name="description">
        Articles and notes on Laravel, PHP, and web development by Yves Engetschwiler.
    </x-slot>
    <x-slot name="pan">page-articles</x-slot>

    <div class="flex space-x-4">
        <x-link href="/">&larr; Back</x-link>

        <x-text>Articles</x-text>
    </div>

    <x-h1>Articles</x-h1>

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
