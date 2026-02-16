<?php

declare(strict_types=1);

namespace App\Livewire;

use App\Enums\PageType;
use App\Models\Blog;
use App\Models\Category;
use App\Models\StaticPage;
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

    public function render()
    {
        $this->blogs = Blog::query()
            ->when($this->search, function ($query) {
                $query->where('title', 'like', '%'.$this->search.'%');
            })
            ->when($this->selectedCategories, function ($query) {
                $query->whereHas('categories', function ($q) {
                    $q->whereIn('categories.id', $this->selectedCategories);
                });
            })
            ->orderBy('published_at', 'desc')
            ->get();

        $staticPage = StaticPage::query()
            ->whereType(PageType::IndexPage)
            ->whereName('blog')
            ->first();

        return view('livewire.list-blogs', compact('staticPage'));
    }
}
