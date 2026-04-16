<?php

namespace App\DataObjects;

use Illuminate\Support\Carbon;

final readonly class Article
{
    public function __construct(
        public Carbon $date,
        public string $slug,
        public string $title,
        public string $description,
        public string $html,
    ) {}

    public function url(): string
    {
        return route('articles.show', [
            'year' => $this->date->format('Y'),
            'month' => $this->date->format('m'),
            'day' => $this->date->format('d'),
            'slug' => $this->slug,
        ]);
    }
}
