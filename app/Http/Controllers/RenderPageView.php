<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\StaticPage;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class RenderPageView extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request, StaticPage $staticPage): View|Factory
    {
        return view('components.page.page-view', compact('staticPage'));
    }
}
