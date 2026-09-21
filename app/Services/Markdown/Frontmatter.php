<?php

declare(strict_types=1);

namespace App\Services\Markdown;

final class Frontmatter
{
    /**
     * Render a deterministic, double-quoted YAML frontmatter block.
     *
     * @param  array<string, mixed>  $data
     */
    public function render(array $data): string
    {
        $lines = ['---'];

        foreach ($data as $key => $value) {
            $lines = [...$lines, ...$this->renderKey((string) $key, $value, 0)];
        }

        $lines[] = '---';

        return implode("\n", $lines)."\n";
    }

    /**
     * @return array<int, string>
     */
    private function renderKey(string $key, mixed $value, int $depth): array
    {
        $indent = str_repeat('  ', $depth);

        if (! is_array($value)) {
            return [$indent.$key.': '.$this->scalar($value)];
        }

        if ($value === []) {
            return [$indent.$key.': []'];
        }

        if (array_is_list($value)) {
            $lines = [$indent.$key.':'];

            foreach ($value as $item) {
                $lines[] = $indent.'  - '.$this->scalar($item);
            }

            return $lines;
        }

        $lines = [$indent.$key.':'];

        foreach ($value as $childKey => $childValue) {
            $lines = [...$lines, ...$this->renderKey((string) $childKey, $childValue, $depth + 1)];
        }

        return $lines;
    }

    private function scalar(mixed $value): string
    {
        return match (true) {
            $value === null => 'null',
            is_bool($value) => $value ? 'true' : 'false',
            is_int($value), is_float($value) => (string) $value,
            default => (string) json_encode((string) $value, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE),
        };
    }
}
