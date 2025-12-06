@php use App\Models\StaticPage; @endphp
<x-layouts.app>
    <x-section.hero-section />
    <x-shared.blog-list title="Featured Blogs" :blogs="$featured" />
    <x-shared.blog-list title="Latest Blogs" :blogs="$latest" />
    @php
        $page = StaticPage::where('slug', 'landing-page')->withoutGlobalScopes()->first();
    @endphp
    @push('seo')
        {!! seo()->for($page) !!}
    @endpush
</x-layouts.app>
