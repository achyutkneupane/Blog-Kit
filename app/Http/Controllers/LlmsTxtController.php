<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Blog;
use App\Models\StaticPage;
use App\Settings\SiteSettings;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

final class LlmsTxtController
{
    public function __invoke(SiteSettings $settings): Response
    {
        $content = Cache::remember('seo:llms-txt', now()->addHour(), function () use ($settings): string {
            $lines = [
                '# '.$settings->name,
                '',
                '> '.$settings->description,
                '',
                '## Pages',
                '',
            ];

            StaticPage::query()->orderBy('type')->get()->each(function (StaticPage $page) use (&$lines): void {
                $url = $page->getURLValue();

                if (blank($url)) {
                    return;
                }

                $lines[] = sprintf(
                    '- [%s](%s): %s',
                    $page->title,
                    $url,
                    Str::limit((string) $page->description, 140),
                );
            });

            $lines[] = '';
            $lines[] = '## Blog';
            $lines[] = '';

            Blog::query()->latest('published_at')->get()->each(function (Blog $blog) use (&$lines): void {
                $lines[] = sprintf(
                    '- [%s](%s): %s',
                    $blog->title,
                    $blog->url,
                    Str::limit((string) $blog->description, 120),
                );
            });

            return implode("\n", $lines)."\n";
        });

        return response($content, 200, ['Content-Type' => 'text/plain; charset=UTF-8']);
    }
}
