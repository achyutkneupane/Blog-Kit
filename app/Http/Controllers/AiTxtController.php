<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Settings\SiteSettings;
use Illuminate\Http\Response;

final class AiTxtController
{
    public function __invoke(SiteSettings $settings): Response
    {
        $content = implode("\n", [
            '# ai.txt for '.$settings->name,
            '',
            'User-Agent: *',
            'Allow: /',
            'Disallow: /admin',
            'Disallow: /pulse',
            '',
            'User-Agent: GPTBot',
            'Allow: /',
            '',
            'User-Agent: ClaudeBot',
            'Allow: /',
            '',
            'User-Agent: PerplexityBot',
            'Allow: /',
            '',
            'User-Agent: Google-Extended',
            'Allow: /',
            '',
            'Sitemap: '.url('/sitemap.xml'),
            '',
        ]);

        return response($content, 200, ['Content-Type' => 'text/plain; charset=UTF-8']);
    }
}
