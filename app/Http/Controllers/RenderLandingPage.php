<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Enums\PageType;
use App\Models\Blog;
use App\Models\StaticPage;
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
        $featured = Blog::whereIsFeatured(1)->get();
        $latest = Blog::query()->except($featured)->orderBy('published_at', 'desc')->get();

        $landingPage = StaticPage::query()
            ->whereType(PageType::LandingPage)
            ->first();

        if ($landingPage) {
            views($landingPage)->record();
        }

        return view('components.page.landing-page', compact('featured', 'latest', 'landingPage'));
    }
}
