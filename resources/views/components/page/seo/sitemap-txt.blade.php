@php
$pages = \App\Models\StaticPage::query()
        ->withGlobalScope('contentPage', new \App\Models\Scopes\ContentPageOnly())
        ->orderBy('updated_at', 'desc')
        ->get();
$blogs = \App\Models\Blog::query()
        ->whereNotNull('published_at')
        ->whereDate('published_at', '<=', now())
        ->orderBy('published_at', 'desc')
        ->get();

$urls = array_merge([
    route('landing-page'),
],
    $pages->map(fn($pg) => route('page.view', $pg))->toArray(),
    $blogs->map(fn($bl) => route('blog.view', $bl))->toArray(),
);
@endphp

@foreach ($urls as $url)
{{ $url }}
@endforeach
