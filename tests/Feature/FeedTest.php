<?php

test('feed returns a successful response', function () {
    $response = $this->get('/feed.xml');

    $response->assertStatus(200);
    $response->assertHeader('Content-Type', 'application/rss+xml; charset=UTF-8');
});

test('feed is valid rss 2.0 xml', function () {
    $content = $this->get('/feed.xml')->getContent();

    expect($content)->toContain('<?xml version="1.0" encoding="UTF-8"?>');
    expect($content)->toContain('<rss version="2.0"');
    expect($content)->toContain('<channel>');
    expect($content)->toContain('</channel>');
    expect($content)->toContain('</rss>');

    $document = new DOMDocument;
    expect(@$document->loadXML($content))->toBeTrue();
});

test('feed advertises self link', function () {
    $content = $this->get('/feed.xml')->getContent();

    expect($content)->toContain(route('feed'));
    expect($content)->toContain('rel="self"');
});

test('feed includes published articles', function () {
    $content = $this->get('/feed.xml')->getContent();

    expect($content)->toContain('/articles/2026/04/15/recovering-corrupted-encrypted-dmg');
    expect($content)->toContain('<pubDate>');
    expect($content)->toContain('<content:encoded>');
});

test('articles index advertises the feed', function () {
    $response = $this->get(route('articles.index'));

    $response->assertStatus(200);
    $response->assertSee(route('feed'), escape: false);
});

test('every page advertises the feed via link rel alternate', function () {
    $response = $this->get(route('articles.index'));

    $response->assertSee('rel="alternate"', escape: false);
    $response->assertSee('type="application/rss+xml"', escape: false);
});
