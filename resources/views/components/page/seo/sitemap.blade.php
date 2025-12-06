<?xml version="1.0" encoding="UTF-8"?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:xhtml="http://www.w3.org/1999/xhtml" xmlns:image="http://www.google.com/schemas/sitemap-image/1.1">
    @php
    $pages = \App\Models\StaticPage::query()
        ->withGlobalScope('contentPage', new \App\Models\Scopes\ContentPageOnly())
        ->orderBy('updated_at', 'desc')
        ->get();
    $landingPage = \App\Models\StaticPage::query()
        ->whereSlug('landing-page')
        ->first();
    $blogs = \App\Models\Blog::query()
            ->whereNotNull('published_at')
            ->whereDate('published_at', '<=', now())
            ->orderBy('published_at', 'desc')
            ->get();

    $settings = app(\App\Settings\SiteSettings::class);
    $ogImage = $settings->og_image;
    $ogImage = $ogImage ? asset('/storage/'.$ogImage) : null;
    @endphp
    <url>
        <loc>{{ route('landing-page') }}</loc>
        <lastmod>{{ $landingPage->created_at->toAtomString() }}</lastmod>
        <changefreq>daily</changefreq>
        <priority>1.0</priority>
        <image:image>
            <image:loc>{{ $landingPage->seoDetails->og_image ?? $ogImage }}</image:loc>
            <image:title>{{ $landingPage->seoDetails->meta_title ?? $landingPage->title }} | {{ $settings->name }}</image:title>
            <image:caption>{{ $landingPage->seoDetails->meta_description }}</image:caption>
        </image:image>
    </url>
    @foreach ($blogs as $blog)
    <url>
        <loc>{{ route('blog.view', $blog) }}</loc>
        <lastmod>{{ \Carbon\Carbon::parse($blog->published_at)->toAtomString() }}</lastmod>
        <changefreq>daily</changefreq>
        <priority>0.8</priority>
        <image:image>
            <image:loc>{{ $blog->seoDetails->og_image ? asset('/storage/'.$blog->seoDetails->og_image) : $ogImage }}</image:loc>
            <image:title>{{ $blog->seoDetails->meta_title }}</image:title>
            <image:caption>{{ $blog->seoDetails->meta_description }}</image:caption>
        </image:image>
    </url>
    @endforeach
    @foreach( $pages as $page)
    <url>
        <loc>{{ route('page.view', $page) }}</loc>
        <lastmod>{{ \Carbon\Carbon::parse($page->updated_at)->toAtomString() }}</lastmod>
        <changefreq>monthly</changefreq>
        <priority>0.6</priority>
        <image:image>
            <image:loc>{{ $page->seoDetails->og_image ? asset('/storage/'.$page->seoDetails->og_image) : $ogImage }}</image:loc>
            <image:title>{{ $page->seoDetails->meta_title ?? $page->title }} | {{ $settings->name }}</image:title>
            <image:caption>{{ $page->seoDetails->meta_description }}</image:caption>
        </image:image>
    </url>
    @endforeach
</urlset>
