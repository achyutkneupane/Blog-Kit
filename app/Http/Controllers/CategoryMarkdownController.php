<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Category;
use App\Services\Markdown\CategoryMarkdownBuilder;
use Illuminate\Http\Response;

final class CategoryMarkdownController
{
    public function __invoke(Category $category, CategoryMarkdownBuilder $builder): Response
    {
        return response($builder->build($category), 200, [
            'Content-Type' => 'text/markdown; charset=UTF-8',
        ]);
    }
}
