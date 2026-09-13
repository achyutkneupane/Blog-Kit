<?php

declare(strict_types=1);

use App\Enums\PageType;
use App\Models\Blog;
use App\Models\Faq;
use App\Models\StaticPage;
use Illuminate\Support\Collection;
use Livewire\Component;

new class extends Component
{
    public Collection $featured;

    public Collection $latest;

    public Collection $faqs;

    public ?StaticPage $landingPage = null;

    public function mount(): void
    {
        $this->featured = Blog::whereIsFeatured(1)->get();
        $this->latest = Blog::query()->orderBy('published_at', 'desc')->get();
        $this->faqs = Faq::query()->active()->ordered()->limit(6)->get();

        $this->landingPage = StaticPage::query()
            ->whereType(PageType::LandingPage)
            ->first();

        if ($this->landingPage) {
            views($this->landingPage)->record();
        }
    }

    /** @return array<string, mixed> */
    public function faqSchema(): array
    {
        return [
            '@context' => 'https://schema.org',
            '@type' => 'FAQPage',
            'mainEntity' => $this->faqs->map(fn (Faq $faq): array => [
                '@type' => 'Question',
                'name' => $faq->question,
                'acceptedAnswer' => [
                    '@type' => 'Answer',
                    'text' => strip_tags($faq->answer),
                ],
            ])->values()->all(),
        ];
    }
};
?>

<div>
    <x-section.hero-section/>
    <x-shared.blog-list title="Featured Blogs" :blogs="$featured"/>
    <x-shared.blog-list title="Latest Blogs" :blogs="$latest"/>

    @if ($faqs->isNotEmpty())
        <x-section.faq-section :faqs="$faqs" />
    @endif
</div>

@push('seo')
    {!! seo()->for($landingPage) !!}
    @if ($faqs->isNotEmpty())
        <script type="application/ld+json">{!! json_encode($this->faqSchema(), JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}</script>
    @endif
@endpush
