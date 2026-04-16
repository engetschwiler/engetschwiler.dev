<?php

namespace App\Support\Markdown;

use League\CommonMark\Extension\CommonMark\Node\Inline\Image;
use League\CommonMark\Node\Block\Paragraph;
use League\CommonMark\Node\Node;
use League\CommonMark\Renderer\Block\ParagraphRenderer;
use League\CommonMark\Renderer\ChildNodeRendererInterface;
use League\CommonMark\Renderer\NodeRendererInterface;
use League\CommonMark\Util\HtmlElement;

final class FigureParagraphRenderer implements NodeRendererInterface
{
    private readonly ParagraphRenderer $base;

    public function __construct()
    {
        $this->base = new ParagraphRenderer;
    }

    public function render(Node $node, ChildNodeRendererInterface $childRenderer): HtmlElement|string
    {
        if (! $node instanceof Paragraph) {
            return $this->base->render($node, $childRenderer);
        }

        $image = $node->firstChild();

        if (! $image instanceof Image || $image !== $node->lastChild() || $image->getTitle() === '') {
            return $this->base->render($node, $childRenderer);
        }

        return new HtmlElement('figure', [], [
            $childRenderer->renderNodes([$image]),
            new HtmlElement('figcaption', [], htmlspecialchars($image->getTitle(), ENT_QUOTES | ENT_SUBSTITUTE | ENT_HTML5)),
        ]);
    }
}
