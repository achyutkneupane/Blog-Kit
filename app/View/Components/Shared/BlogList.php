<?php

declare(strict_types=1);

namespace App\View\Components\Shared;

use App\Models\Blog;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection;
use Illuminate\View\Component;

class BlogList extends Component
{
    /**
     * Create a new component instance.
     *
     * @param  Collection<Blog>  $blogs
     */
    public function __construct(
        public Collection $blogs,
        public ?string $title,
    ) {
        //
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.shared.blog-list');
    }
}
