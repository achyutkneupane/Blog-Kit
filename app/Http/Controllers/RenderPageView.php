<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Enums\PageType;
use App\Models\StaticPage;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class RenderPageView extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request, StaticPage $staticPage): View|RedirectResponse
    {
        if (in_array($staticPage->type, [PageType::LandingPage, PageType::IndexPage], true)) {
            return redirect()->to($staticPage->getURLValue());
        }

        views($staticPage)->record();

        return view('components.page.page-view', compact('staticPage'));
    }
}
