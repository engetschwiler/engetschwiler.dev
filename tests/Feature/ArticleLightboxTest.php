<?php

test('clicking an article image opens it large and clicking again closes it', function () {
    $page = visit('/articles/2026/11/26/i-rebuilt-my-terminal-around-the-way-i-work');

    $page->assertScript('document.querySelector("dialog").open', false);

    $page->click('article img[src$="terminal.webp"]')
        ->assertScript('document.querySelector("dialog").open', true)
        ->assertScript('document.querySelector("dialog img").src.endsWith("/terminal.webp")', true);

    $page->script('document.querySelector("dialog img").click()');

    $page->assertScript('document.querySelector("dialog").open', false);
});
