<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Blog;
use App\Settings\SiteSettings;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Cache;

final class LlmsBlogsTxtController
{
    public function __invoke(SiteSettings $settings): Response
    {
        $content = Cache::remember('seo:llms-blogs-txt', now()->addHour(), function () use ($settings): string {
            $lines = [
                '# '.$settings->name.': Articles',
                '',
                '> Complete index of published articles from '.$settings->name.', featured first.',
                '',
            ];

            $blogs = Blog::query()
                ->with('seo')
                ->orderByDesc('published_at')
                ->get();

            $this->append($lines, 'Featured', $blogs->where('is_featured', true)->values());
            $this->append($lines, 'Other articles', $blogs->where('is_featured', false)->values());

            return implode("\n", $lines)."\n";
        });

        return response($content, 200, ['Content-Type' => 'text/plain; charset=UTF-8']);
    }

    /**
     * @param  array<int, string>  $lines
     * @param  Collection<int, Blog>  $blogs
     */
    private function append(array &$lines, string $heading, Collection $blogs): void
    {
        $lines[] = '## '.$heading;
        $lines[] = '';

        $blogs->each(function (Blog $blog) use (&$lines): void {
            $seo = $blog->getDynamicSEOData();

            $lines[] = sprintf(
                '- [%s](%s): %s',
                $seo->title,
                route('blog.view', $blog),
                $seo->description,
            );
            $lines[] = sprintf('- [%s](%s)', $seo->title, route('blog.markdown', $blog));
            $lines[] = '';
        });
    }
}
