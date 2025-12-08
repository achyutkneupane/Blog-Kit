<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Blog;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class RenderBlogView extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request, Blog $blog): View|Factory
    {
        views($blog)->record();

        return view('components.page.blog-view', compact('blog'));
    }
}
