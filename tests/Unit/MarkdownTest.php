<?php

declare(strict_types=1);

use App\Services\Markdown\Frontmatter;
use App\Services\Markdown\HtmlToMarkdown;

it('renders deterministic double quoted frontmatter', function (): void {
    $frontmatter = new Frontmatter;

    $rendered = $frontmatter->render([
        'title' => 'Markdown Article',
        'reading_time' => 3,
        'draft' => false,
        'canonical' => null,
        'categories' => ['Laravel', 'PHP'],
        'seo' => ['robots' => 'index, follow'],
        'tags' => [],
    ]);

    $expected = <<<'MD'
---
title: "Markdown Article"
reading_time: 3
draft: false
canonical: null
categories:
  - "Laravel"
  - "PHP"
seo:
  robots: "index, follow"
tags: []
---
MD;

    expect($rendered)->toBe($expected."\n");
});

it('converts rich editor html to markdown', function (): void {
    $markdown = (new HtmlToMarkdown)->convert(
        '<p>Intro paragraph.</p><h2>What is included</h2><ul><li>Filament admin</li><li>Livewire pages</li></ul>'
    );

    expect($markdown)->toContain('Intro paragraph.')
        ->and($markdown)->toContain('## What is included')
        ->and($markdown)->toContain('- Filament admin')
        ->and($markdown)->toContain('- Livewire pages');
});
