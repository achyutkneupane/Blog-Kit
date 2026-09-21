<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Blog;
use App\Services\Markdown\ArticleMarkdownBuilder;
use Illuminate\Http\Response;

final class BlogMarkdownController
{
    public function __invoke(Blog $blog, ArticleMarkdownBuilder $builder): Response
    {
        abort_unless($blog->isIndexable(), 404);

        return response($builder->build($blog), 200, [
            'Content-Type' => 'text/markdown; charset=UTF-8',
        ]);
    }
}
