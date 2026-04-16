<?php

namespace App\Services;

use App\DataObjects\Article;
use App\Support\Markdown\FigureParagraphRenderer;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;
use League\CommonMark\Environment\Environment;
use League\CommonMark\Extension\CommonMark\CommonMarkCoreExtension;
use League\CommonMark\Extension\CommonMark\Node\Block\FencedCode;
use League\CommonMark\Extension\CommonMark\Node\Block\IndentedCode;
use League\CommonMark\Extension\FrontMatter\FrontMatterExtension;
use League\CommonMark\Extension\FrontMatter\Output\RenderedContentWithFrontMatter;
use League\CommonMark\Extension\GithubFlavoredMarkdownExtension;
use League\CommonMark\MarkdownConverter;
use League\CommonMark\Node\Block\Paragraph;
use Spatie\CommonMarkHighlighter\FencedCodeRenderer;
use Spatie\CommonMarkHighlighter\IndentedCodeRenderer;

class ArticleRepository
{
    private const array LANGUAGES = ['php', 'javascript', 'typescript', 'css', 'scss', 'html', 'xml', 'bash', 'shell', 'json', 'yaml', 'sql', 'diff', 'markdown'];

    public function __construct(private readonly string $path)
    {
        //
    }

    /** @return Collection<int, Article> */
    public function all(): Collection
    {
        if (! is_dir($this->path)) {
            return collect();
        }

        $files = glob($this->path.'/*.md') ?: [];

        return collect($files)
            ->map(fn (string $file) => $this->load($file))
            ->filter()
            ->sortByDesc(fn (Article $article) => $article->date->timestamp)
            ->values();
    }

    public function find(int $year, int $month, int $day, string $slug): ?Article
    {
        $filename = sprintf('%04d-%02d-%02d-%s.md', $year, $month, $day, $slug);
        $path = $this->path.'/'.$filename;

        if (! is_file($path)) {
            return null;
        }

        return $this->load($path);
    }

    private function load(string $path): ?Article
    {
        $filename = basename($path, '.md');

        if (! preg_match('/^(\d{4})-(\d{2})-(\d{2})-(.+)$/', $filename, $matches)) {
            return null;
        }

        $date = Carbon::createFromFormat('Y-m-d', "{$matches[1]}-{$matches[2]}-{$matches[3]}")?->startOfDay();

        if (! $date) {
            return null;
        }

        $slug = $matches[4];
        $mtime = filemtime($path) ?: 0;
        $cacheKey = 'article.'.$filename.'.'.$mtime;

        [$title, $description, $html] = Cache::rememberForever(
            $cacheKey,
            fn () => $this->parse(file_get_contents($path) ?: '', $slug)
        );

        return new Article(
            date: $date,
            slug: $slug,
            title: $title,
            description: $description,
            html: $html,
        );
    }

    /** @return array{0: string, 1: string, 2: string} */
    private function parse(string $markdown, string $slug): array
    {
        $result = $this->converter()->convert($markdown);
        $html = $result->getContent();

        $frontMatter = $result instanceof RenderedContentWithFrontMatter ? $result->getFrontMatter() : [];
        $title = is_array($frontMatter) && isset($frontMatter['title']) ? (string) $frontMatter['title'] : $this->extractTitle($html, $slug);
        $description = is_array($frontMatter) && isset($frontMatter['description']) ? (string) $frontMatter['description'] : $this->extractDescription($html);

        return [$title, $description, $html];
    }

    private function converter(): MarkdownConverter
    {
        $environment = new Environment;
        $environment->addExtension(new CommonMarkCoreExtension);
        $environment->addExtension(new GithubFlavoredMarkdownExtension);
        $environment->addExtension(new FrontMatterExtension);
        $environment->addRenderer(FencedCode::class, new FencedCodeRenderer(self::LANGUAGES));
        $environment->addRenderer(IndentedCode::class, new IndentedCodeRenderer(self::LANGUAGES));
        $environment->addRenderer(Paragraph::class, new FigureParagraphRenderer, 10);

        return new MarkdownConverter($environment);
    }

    private function extractTitle(string $html, string $fallback): string
    {
        if (preg_match('/<h1[^>]*>(.*?)<\/h1>/s', $html, $m)) {
            return trim(strip_tags($m[1]));
        }

        return Str::of($fallback)->replace('-', ' ')->title()->toString();
    }

    private function extractDescription(string $html): string
    {
        if (preg_match('/<p[^>]*>(.*?)<\/p>/s', $html, $m)) {
            return Str::of(strip_tags($m[1]))->squish()->limit(160)->toString();
        }

        return '';
    }
}
