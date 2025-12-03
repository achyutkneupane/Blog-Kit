<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Blog;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class RenderLandingPage extends Controller
{

    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request): View|Factory
    {
        $featured = Blog::query()
            ->get();
        return view('components.page.landing-page', compact('featured'));
    }
}
