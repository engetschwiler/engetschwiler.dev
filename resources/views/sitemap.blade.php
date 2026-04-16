<?php
$staticPages = [
    ['route' => 'home', 'priority' => '1.00'],
    ['route' => 'uses', 'priority' => '0.80'],
    ['route' => 'talks', 'priority' => '0.80'],
    ['route' => 'articles.index', 'priority' => '0.80'],
    ['route' => 'colophon', 'priority' => '0.80'],
    ['route' => 'privacy', 'priority' => '0.80'],
];
$defaultLastmod = now()->toAtomString();
?><?= '<?xml version="1.0" encoding="UTF-8"?>'."\n" ?>
<urlset
      xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"
      xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
      xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9
            http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd">
@foreach ($staticPages as $page)
<url>
  <loc>{{ route($page['route']) }}</loc>
  <lastmod>{{ $defaultLastmod }}</lastmod>
  <priority>{{ $page['priority'] }}</priority>
</url>
@endforeach
@foreach ($articles as $article)
<url>
  <loc>{{ $article->url() }}</loc>
  <lastmod>{{ $article->date->toAtomString() }}</lastmod>
  <priority>0.70</priority>
</url>
@endforeach
</urlset>
