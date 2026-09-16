<?php

use App\Support\Markdown\HighlightedCodeRenderer;
use League\CommonMark\Environment\Environment;
use League\CommonMark\Extension\CommonMark\CommonMarkCoreExtension;
use League\CommonMark\Extension\CommonMark\Node\Block\FencedCode;
use League\CommonMark\MarkdownConverter;
use League\CommonMark\Node\Block\Paragraph;
use League\CommonMark\Renderer\ChildNodeRendererInterface;

function renderHighlighted(string $markdown): string
{
    $environment = new Environment;
    $environment->addExtension(new CommonMarkCoreExtension);
    $environment->addRenderer(FencedCode::class, new HighlightedCodeRenderer, 10);

    return (new MarkdownConverter($environment))->convert($markdown)->getContent();
}

test('highlights a fenced code block in the declared language', function () {
    $html = renderHighlighted("```js\nconst a = 1;\n```");

    expect($html)->toBe('<pre><code class="language-js"><span class="hl-keyword">const</span> a = 1;'."\n</code></pre>\n");
});

test('escapes code without a language', function () {
    $html = renderHighlighted("```\n<b>&</b>\n```");

    expect($html)->toBe("<pre><code>&lt;b&gt;&amp;&lt;/b&gt;\n</code></pre>\n");
});

test('escapes the language name in the class attribute', function () {
    $html = renderHighlighted("```a\"b\nx\n```");

    expect($html)->toContain('class="language-a&quot;b"');
});

test('rejects nodes that are not fenced code', function () {
    (new HighlightedCodeRenderer)->render(new Paragraph, Mockery::mock(ChildNodeRendererInterface::class));
})->throws(InvalidArgumentException::class);
