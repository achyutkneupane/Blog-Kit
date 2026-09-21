<?php

declare(strict_types=1);

namespace App\Services\Markdown;

use App\Models\Blog;
use App\Models\Category;
use Illuminate\Support\Collection;

final class CategoryMarkdownBuilder
{
    public function __construct(
        private readonly Frontmatter $frontmatter,
    ) {}

    public function build(Category $category): string
    {
        $blogs = $this->articles($category);

        $frontmatter = $this->frontmatter->render([
            'title' => $category->name,
            'type' => 'category',
            'locale' => app()->getLocale(),
            'slug' => $category->slug,
            'url' => route('category.view', $category),
            'markdown_url' => route('category.markdown', $category),
            'article_count' => $blogs->count(),
            'updated_at' => $category->updated_at?->toIso8601String(),
        ]);

        $lines = ['# '.$category->name, '', '## Articles', ''];

        $blogs->each(function (Blog $blog) use (&$lines): void {
            $seo = $blog->getDynamicSEOData();

            $lines[] = sprintf('- [%s](%s): %s', $seo->title, route('blog.view', $blog), $seo->description);
            $lines[] = sprintf('- [%s](%s)', $seo->title, route('blog.markdown', $blog));
            $lines[] = '';
        });

        return $frontmatter."\n".mb_trim(implode("\n", $lines))."\n";
    }

    /** @return Collection<int, Blog> */
    private function articles(Category $category): Collection
    {
        return $category->blogs()
            ->with(['author', 'seo'])
            ->latest('published_at')
            ->get()
            ->filter(fn (Blog $blog): bool => $blog->isIndexable())
            ->values();
    }
}
