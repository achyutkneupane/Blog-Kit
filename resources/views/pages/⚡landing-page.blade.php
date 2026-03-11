<?php

use App\Enums\PageType;
use App\Models\Blog;
use App\Models\StaticPage;
use Illuminate\Support\Collection;
use Livewire\Component;

new class extends Component {
    public Collection $featured;
    public Collection $latest;
    public ?StaticPage $landingPage = null;

    public function mount(): void
    {
        $this->featured = Blog::whereIsFeatured(1)->get();
        $this->latest = Blog::query()->orderBy('published_at', 'desc')->get();

        $this->landingPage = StaticPage::query()
            ->whereType(PageType::LandingPage)
            ->first();

        if ($this->landingPage) {
            views($this->landingPage)->record();
        }
    }
};
?>

<div>
    <x-section.hero-section/>
    <x-shared.blog-list title="Featured Blogs" :blogs="$featured"/>
    <x-shared.blog-list title="Latest Blogs" :blogs="$latest"/>
</div>

@push('seo')
    {!! seo()->for($landingPage) !!}
@endpush
