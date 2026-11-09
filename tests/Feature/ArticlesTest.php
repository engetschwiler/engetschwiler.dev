<?php

test('articles index returns a successful response', function () {
    $response = $this->get('/articles');

    $response->assertStatus(200);
});

test('articles index lists the latest article', function () {
    $response = $this->get('/articles');

    $response->assertSee('Recovering a Corrupted Encrypted macOS DMG');
    $response->assertSee('15 April 2026');
});

test('article page returns a successful response', function () {
    $response = $this->get('/articles/2026/04/15/recovering-corrupted-encrypted-dmg');

    $response->assertStatus(200);
    $response->assertSee('Recovering a Corrupted Encrypted macOS DMG');
});

test('article with mismatched date returns 404', function () {
    $this->get('/articles/2025/01/01/recovering-corrupted-encrypted-dmg')->assertStatus(404);
});

test('article with unknown slug returns 404', function () {
    $this->get('/articles/2026/04/15/does-not-exist')->assertStatus(404);
});

test('article url with non-numeric date parts is rejected', function () {
    $this->get('/articles/abcd/04/15/recovering-corrupted-encrypted-dmg')->assertStatus(404);
});

test('translated article links to its french version', function () {
    $response = $this->get('/articles/2026/04/15/recovering-corrupted-encrypted-dmg');

    $response->assertSee('<link rel="alternate" hreflang="fr" href="https://bee-interactive.ch/blog/recovering-corrupted-encrypted-dmg" />', false);
    $response->assertSee('<link rel="alternate" hreflang="en" href="'.url('/articles/2026/04/15/recovering-corrupted-encrypted-dmg').'" />', false);
    $response->assertSee('Version française');
});

test('untranslated article has no french alternate', function () {
    $response = $this->get('/articles/2025/07/01/managing-my-life-from-excel-hell-to-laravel-paradise-with-taab');

    $response->assertStatus(200);
    $response->assertDontSee('hreflang="fr"', false);
    $response->assertDontSee('Version française');
});
