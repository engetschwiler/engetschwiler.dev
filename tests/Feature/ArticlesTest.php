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
