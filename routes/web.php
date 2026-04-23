<?php

use App\Http\Controllers\ArticleController;
use App\Http\Controllers\FeedController;
use App\Http\Controllers\SitemapController;
use App\Services\ArticleRepository;
use Illuminate\Support\Facades\Route;

Route::get('/', fn (ArticleRepository $articles) => view('home', [
    'latestArticle' => $articles->all()->first(),
]))->name('home');
Route::view('/uses', 'uses')->name('uses');
Route::view('/talks', 'talks')->name('talks');
Route::view('/colophon', 'colophon')->name('colophon');
Route::view('/privacy', 'privacy')->name('privacy');

Route::get('/articles', [ArticleController::class, 'index'])->name('articles.index');
Route::get('/articles/{year}/{month}/{day}/{slug}', [ArticleController::class, 'show'])
    ->where(['year' => '\d{4}', 'month' => '\d{2}', 'day' => '\d{2}'])
    ->name('articles.show');

Route::get('/feed.xml', FeedController::class)->name('feed');
Route::get('/sitemap.xml', SitemapController::class)->name('sitemap');
