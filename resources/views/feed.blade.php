<?php
$siteTitle = 'Yves Engetschwiler';
$siteDescription = 'Articles and notes on Laravel, PHP, and web development by Yves Engetschwiler.';
$siteUrl = route('articles.index');
$feedUrl = route('feed');
$lastBuild = $articles->isNotEmpty() ? $articles->first()->date->toRfc2822String() : now()->toRfc2822String();
?><?= '<?xml version="1.0" encoding="UTF-8"?>'."\n" ?>
<rss version="2.0"
     xmlns:atom="http://www.w3.org/2005/Atom"
     xmlns:content="http://purl.org/rss/1.0/modules/content/"
     xmlns:dc="http://purl.org/dc/elements/1.1/">
<channel>
  <title>{{ $siteTitle }}</title>
  <link>{{ $siteUrl }}</link>
  <description>{{ $siteDescription }}</description>
  <language>{{ str_replace('_', '-', app()->getLocale()) }}</language>
  <lastBuildDate>{{ $lastBuild }}</lastBuildDate>
  <atom:link href="{{ $feedUrl }}" rel="self" type="application/rss+xml" />
@foreach ($articles as $article)
  <item>
    <title>{{ $article->title }}</title>
    <link>{{ $article->url() }}</link>
    <guid isPermaLink="true">{{ $article->url() }}</guid>
    <pubDate>{{ $article->date->toRfc2822String() }}</pubDate>
    <dc:creator>{{ $siteTitle }}</dc:creator>
    @if ($article->description !== '')
    <description>{{ $article->description }}</description>
    @endif
    <content:encoded><![CDATA[{!! $article->html !!}]]></content:encoded>
  </item>
@endforeach
</channel>
</rss>
