<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no, viewport-fit=cover">

<title>{!! $title ?? 'Yves Engetschwiler, web & applications since 2002' !!}</title>

<meta name="description" content="{{ $description ?? '' }}">
<meta property="og:title" content="{{ $title ?? '' }}"/>
<meta property="og:description" content="{{ $description ?? '' }}"/>
<meta property="og:image" content="{{ $card ?? url('/images/og-image.png') }}"/>
<meta property="og:image:width" content="1440">
<meta property="og:image:height" content="900">
<meta content="" property="og:image:alt">
<meta property="og:url" content="{{ request()->getUri() }}"/>
<meta property="og:type" content="website" />
<meta content="https://github.com/engetschwiler" property="og:see_also">
<meta content="https://www.linkedin.com/in/yves-engetschwiler/" property="og:see_also">
<meta content="https://pinkary.com/@interactive" property="og:see_also">
<meta content="https://x.com/yvesdesign" property="og:see_also">
<meta content="https://instagram.com/derailleurch" property="og:see_also">

<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:site" content="@yvesdesign">
<meta name="twitter:creator" content="@yvesdesign">
<meta name="twitter:title" content="{{ $title ?? '' }}">
<meta name="twitter:description" content="{{ $description ?? '' }}">
<meta name="twitter:image" content="{{ $card ?? url('/images/og-image.png') }}">
<meta name="twitter:image:width" content="1440">
<meta name="twitter:image:height" content="900">

<link href="https://engetschwiler.dev" hreflang="en" rel="home">

<link rel="sitemap" type="application/xml" title="Sitemap" href="/sitemap.xml" />

@if (isset($canonical) && $canonical)
<link rel="canonical" href="{{ $canonical }}" />
@endif

@if (isset($noIndex) && $noIndex)
<meta name="robots" content="noindex">
@endif

@include('partials.favicons')
