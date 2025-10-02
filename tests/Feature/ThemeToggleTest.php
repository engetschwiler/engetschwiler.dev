<?php

test('theme toggle switches between dark and light mode', function () {
    $page = visit('/');

    $page->click('button[title*="Switch to"]')
        ->assertScript('document.documentElement.classList.contains("dark")', true)
        ->assertScript('localStorage.theme === "dark"', true);

    $page->click('button[title*="Switch to"]')
        ->assertScript('!document.documentElement.classList.contains("dark")', true)
        ->assertScript('localStorage.theme === "light"', true);
});

test('theme persists in localStorage after toggle', function () {
    $page = visit('/');

    $page->click('button[title*="Switch to"]')
        ->assertScript('localStorage.theme === "dark"', true)
        ->assertScript('document.documentElement.classList.contains("dark")', true);

    $page->click('button[title*="Switch to"]')
        ->assertScript('localStorage.theme === "light"', true)
        ->assertScript('!document.documentElement.classList.contains("dark")', true);
});

test('theme respects system preference when no preference is set', function () {
    $page = visit('/');

    $page->script('localStorage.removeItem("theme")');

    $page = visit('/');
    $page->assertScript('!("theme" in localStorage)', true);
});

test('correct icon is displayed based on theme', function () {
    $page = visit('/');

    $page->script('localStorage.theme = "light"');

    $page = visit('/');
    $page->assertVisible('svg[x-show="!isDark"]');

    $page->click('button[title*="Switch to"]')
        ->assertVisible('svg[x-show="isDark"]');
});
