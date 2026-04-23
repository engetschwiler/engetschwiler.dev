<?php

namespace App\Http\Controllers;

use App\Services\ArticleRepository;
use Illuminate\Http\Response;

class FeedController
{
    public function __construct(private readonly ArticleRepository $articles) {}

    public function __invoke(): Response
    {
        return response()
            ->view('feed', ['articles' => $this->articles->all()->take(20)])
            ->header('Content-Type', 'application/rss+xml; charset=UTF-8');
    }
}
