<?php

declare(strict_types=1);

namespace App\Services\Markdown;

use League\HTMLToMarkdown\HtmlConverter;

final class HtmlToMarkdown
{
    public function convert(string $html): string
    {
        $htmlConverter = new HtmlConverter([
            'header_style' => 'atx',
            'hard_break' => true,
            'strip_tags' => true,
            'remove_nodes' => 'script style',
        ]);

        return mb_trim($htmlConverter->convert($html));
    }
}
