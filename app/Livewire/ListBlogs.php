<?php

declare(strict_types=1);

namespace App\Livewire;

use App\Enums\PageType;
use App\Models\Blog;
use App\Models\Category;
use App\Models\StaticPage;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection;
use Livewire\Attributes\Url;
use Livewire\Component;

class ListBlogs extends Component
{
    /** @var Collection<Category> */
    public Collection $categories;

    /** @var Collection<Blog> */
    public Collection $blogs;

    #[Url]
    public $search = '';

    #[Url]
    public $selectedCategories = [];

    public function mount(): void
    {
        $this->categories = Category::query()
            ->orderBy('name')
            ->get();
    }

    public function resetFilters(): void
    {
        $this->search = '';
        $this->selectedCategories = [];
    }

    public function render(): Factory|View
    {
        $this->blogs = Blog::query()
            ->when($this->search, function ($query): void {
                $query->where('title', 'like', '%'.$this->search.'%');
            })
            ->when($this->selectedCategories, function ($query): void {
                $query->whereHas('categories', function ($q): void {
                    $q->whereIn('categories.id', $this->selectedCategories);
                });
            })
            ->latest('published_at')
            ->get();

        $staticPage = StaticPage::query()
            ->whereType(PageType::IndexPage)
            ->whereName('blog')
            ->first();

        return view('livewire.list-blogs', compact('staticPage'));
    }
}
