<?php

test('home page returns a successful response', function () {
    $response = $this->get('/');

    $response->assertStatus(200);
});

test('uses page returns a successful response', function () {
    $response = $this->get('/uses');

    $response->assertStatus(200);
});

test('colophon page returns a successful response', function () {
    $response = $this->get('/colophon');

    $response->assertStatus(200);
});

test('privacy page returns a successful response', function () {
    $response = $this->get('/privacy');

    $response->assertStatus(200);
});

test('talks page returns a successful response', function () {
    $response = $this->get('/talks');

    $response->assertStatus(200);
});

test('home page contains expected title', function () {
    $response = $this->get('/');

    $response->assertSee('I’m a web and application developer', false);
});

test('uses page contains expected title', function () {
    $response = $this->get('/uses');

    $response->assertSee('A part of the amount of tools in my dev stack');
});

test('colophon page contains expected title', function () {
    $response = $this->get('/colophon');

    $response->assertSee('How this website is built');
});

test('privacy page contains expected title', function () {
    $response = $this->get('/privacy');

    $response->assertSee('Privacy Policy');
});

test('talks page contains expected title', function () {
    $response = $this->get('/talks');

    $response->assertSee('Talks and presentations');
});
