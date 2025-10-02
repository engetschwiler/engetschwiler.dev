<?php

use Illuminate\Support\Facades\Route;

Route::view('/', 'home')->name('home');
Route::view('/uses', 'uses')->name('uses');
Route::view('/colophon', 'colophon')->name('colophon');
Route::view('/privacy', 'privacy')->name('privacy');

Route::get('/sitemap.xml', function () {
    return response()->file(public_path('sitemap.xml'), [
        'Content-Type' => 'application/xml',
    ]);
})->name('sitemap');
