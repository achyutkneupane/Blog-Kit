<?php

declare(strict_types=1);

use App\Models\Blog;
use App\Models\Category;
use Illuminate\Contracts\View\View;
use Illuminate\Pagination\LengthAwarePaginator;
use Livewire\Attributes\Computed;
use Livewire\Component;
use Livewire\WithPagination;

new class extends Component {
    use WithPagination;

    public Category $category;

    /** @return LengthAwarePaginator<int, Blog> */
    #[Computed]
    public function blogs(): LengthAwarePaginator
    {
        return $this->category->blogs()
            ->with('author')
            ->latest('published_at')
            ->paginate(9);
    }

    public function render(): View
    {
        return $this->view();
    }
};
?>

<main class="container-xl relative my-12 antialiased">
    <x-shared.breadcrumbs :items="[
        ['label' => 'Home', 'url' => route('landing-page')],
        ['label' => 'Blog', 'url' => route('blog.index')],
        ['label' => $category->name, 'url' => null],
    ]" />

    <header class="mb-8">
        <h1 class="text-4xl font-black tracking-tight text-neutral-900">
            {{ $category->name }}
        </h1>

        <p class="mt-3 text-neutral-500 leading-relaxed">
            {{ $this->blogs->total() }} {{ Str::plural('article', $this->blogs->total()) }} in {{ $category->name }}.
        </p>
    </header>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        @forelse($this->blogs as $blog)
            <livewire:components::single-blog :$blog :key="'category-'.$blog->getKey()" />
        @empty
            <div class="col-span-full py-20 text-center">
                <div class="bg-neutral-50 rounded-[2.5rem] py-12 border-2 border-dashed border-neutral-200">
                    <p class="text-neutral-500 font-medium">No articles published in this category yet.</p>
                </div>
            </div>
        @endforelse
    </div>

    @if ($this->blogs->hasPages())
        <div class="mt-10">
            {{ $this->blogs->links() }}
        </div>
    @endif
</main>

@push('seo')
    {!! seo()->for($category) !!}
@endpush
