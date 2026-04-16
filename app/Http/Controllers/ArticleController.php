<?php

namespace App\Http\Controllers;

use App\Services\ArticleRepository;
use Illuminate\Http\Response;

class ArticleController
{
    public function __construct(private readonly ArticleRepository $articles) {}

    public function index(): Response
    {
        return response()->view('articles.index', [
            'articles' => $this->articles->all(),
        ]);
    }

    public function show(string $year, string $month, string $day, string $slug): Response
    {
        $article = $this->articles->find((int) $year, (int) $month, (int) $day, $slug);

        abort_if($article === null, 404);

        return response()->view('articles.show', ['article' => $article]);
    }
}
