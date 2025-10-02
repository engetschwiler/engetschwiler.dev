<?php

test('sitemap returns a successful response', function () {
    $response = $this->get('/sitemap.xml');

    $response->assertStatus(200);
    $response->assertHeader('Content-Type', 'application/xml');
});

test('sitemap contains all pages', function () {
    $content = file_get_contents(public_path('sitemap.xml'));

    expect($content)->toContain('https://engetschwiler.dev/');
    expect($content)->toContain('https://engetschwiler.dev/uses');
    expect($content)->toContain('https://engetschwiler.dev/colophon');
    expect($content)->toContain('https://engetschwiler.dev/privacy');
});

test('sitemap is valid xml', function () {
    $content = file_get_contents(public_path('sitemap.xml'));

    expect($content)->toContain('<?xml version="1.0" encoding="UTF-8"?>');
    expect($content)->toContain('<urlset');
    expect($content)->toContain('</urlset>');
});
