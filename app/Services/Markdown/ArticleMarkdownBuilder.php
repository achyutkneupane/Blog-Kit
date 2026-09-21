<?php

declare(strict_types=1);

namespace App\Services\Markdown;

use App\Models\Blog;

final class ArticleMarkdownBuilder
{
    public function __construct(
        private readonly Frontmatter $frontmatter,
        private readonly HtmlToMarkdown $htmlToMarkdown,
    ) {}

    public function build(Blog $blog): string
    {
        $blog->loadMissing(['categories', 'seo']);

        $seo = $blog->getDynamicSEOData();

        $frontmatter = $this->frontmatter->render([
            'title' => $seo->title,
            'description' => $seo->description,
            'slug' => $blog->slug,
            'type' => 'article',
            'locale' => app()->getLocale(),
            'url' => route('blog.view', $blog),
            'markdown_url' => route('blog.markdown', $blog),
            'canonical' => $seo->url,
            'published_at' => $seo->published_time?->toIso8601String(),
            'updated_at' => $seo->modified_time?->toIso8601String(),
            'reading_time' => $blog->minutes_read,
            'categories' => $blog->categories->pluck('name')->values()->all(),
            'tags' => array_values($seo->tags ?? []),
            'image' => $seo->image,
            'seo' => [
                'title' => $seo->title,
                'description' => $seo->description,
                'keywords' => array_values((array) $blog->tags),
                'robots' => $seo->robots,
            ],
        ]);

        $body = '# '.$blog->title."\n\n".$this->htmlToMarkdown->convert((string) $blog->content);

        return $frontmatter."\n".mb_trim($body)."\n";
    }
}
