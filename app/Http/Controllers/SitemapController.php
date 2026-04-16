<?php

namespace App\Http\Controllers;

use App\Services\ArticleRepository;
use Illuminate\Http\Response;

class SitemapController
{
    public function __construct(private readonly ArticleRepository $articles) {}

    public function __invoke(): Response
    {
        return response()
            ->view('sitemap', ['articles' => $this->articles->all()])
            ->header('Content-Type', 'application/xml');
    }
}
