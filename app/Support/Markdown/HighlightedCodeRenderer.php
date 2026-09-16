<?php

namespace App\Support\Markdown;

use InvalidArgumentException;
use League\CommonMark\Extension\CommonMark\Node\Block\FencedCode;
use League\CommonMark\Node\Node;
use League\CommonMark\Renderer\ChildNodeRendererInterface;
use League\CommonMark\Renderer\NodeRendererInterface;
use League\CommonMark\Util\HtmlElement;
use Tempest\Highlight\Highlighter;

final class HighlightedCodeRenderer implements NodeRendererInterface
{
    public function __construct(private readonly Highlighter $highlighter = new Highlighter)
    {
        //
    }

    public function render(Node $node, ChildNodeRendererInterface $childRenderer): HtmlElement
    {
        if (! $node instanceof FencedCode) {
            throw new InvalidArgumentException('Node must be an instance of '.FencedCode::class);
        }

        $language = $node->getInfoWords()[0] ?? '';

        $attributes = $language === ''
            ? []
            : ['class' => 'language-'.$language];

        return new HtmlElement('pre', [], new HtmlElement('code', $attributes, $this->highlighter->parse($node->getLiteral(), $language)));
    }
}
