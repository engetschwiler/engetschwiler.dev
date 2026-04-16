<?php

test('sitemap returns a successful response', function () {
    $response = $this->get('/sitemap.xml');

    $response->assertStatus(200);
    $response->assertHeader('Content-Type', 'application/xml');
});

test('sitemap contains all static pages', function () {
    $content = $this->get('/sitemap.xml')->getContent();

    expect($content)->toContain(route('home'));
    expect($content)->toContain(route('uses'));
    expect($content)->toContain(route('talks'));
    expect($content)->toContain(route('articles.index'));
    expect($content)->toContain(route('colophon'));
    expect($content)->toContain(route('privacy'));
});

test('sitemap includes published articles', function () {
    $content = $this->get('/sitemap.xml')->getContent();

    expect($content)->toContain('/articles/2026/04/15/recovering-corrupted-encrypted-dmg');
});

test('sitemap is valid xml', function () {
    $content = $this->get('/sitemap.xml')->getContent();

    expect($content)->toContain('<?xml version="1.0" encoding="UTF-8"?>');
    expect($content)->toContain('<urlset');
    expect($content)->toContain('</urlset>');
});
