<?php

namespace App\Http\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

/**
 * Builds the /llms.txt index described at https://llmstxt.org.
 *
 * The file gives AI agents a curated entry point into the documentation so they
 * do not have to crawl the sitemap, which spans every locale and every version.
 */
class LlmsTxtService
{
    public function __construct(private readonly DocService $docService) {}

    /**
     * Build the llms.txt contents for a version, defaulting to the latest.
     *
     * Building reads every page in the index, so the result is cached. Set
     * LLMS_TXT_CACHE_MINUTES=0 to rebuild on every request while editing docs.
     */
    public function generate(?string $version = null): string
    {
        $version = $version ?: $this->docService->getLastestVersionNylo();

        $cacheMinutes = (int) config('project.meta.llms_txt_cache_minutes', 60);

        return Cache::remember("llms-txt-{$version}", now()->addMinutes($cacheMinutes), fn () => $this->build($version));
    }

    /**
     * Assemble the document from the doc index.
     */
    private function build(string $version): string
    {
        $sections = config('project.doc-index')['versions'][$version] ?? [];

        $lines = [
            '# '.config('app.name'),
            '',
        ];

        $summary = $this->summaryFor($version);
        if ($summary !== '') {
            $lines[] = '> '.$summary;
            $lines[] = '';
        }

        $lines[] = 'This is the documentation for '.config('app.name').' v'.$version.', the current release.';
        $lines[] = 'Every page listed below is also available as raw markdown: append `?format=md` to any';
        $lines[] = 'documentation URL, or send an `Accept: text/markdown` request header.';
        $lines[] = '';

        foreach ($sections as $section => $pages) {
            $lines[] = '## '.Str::headline($section);
            $lines[] = '';

            foreach ($pages as $page) {
                $lines[] = $this->lineFor($version, $page);
            }

            $lines[] = '';
        }

        $lines[] = '## Optional';
        $lines[] = '';
        $lines[] = '- [Source code](https://github.com/nylo-core/nylo): the '.config('app.name').' framework on GitHub.';
        $lines[] = '- [Flutter packages](https://pub.dev/publishers/nylo.dev/packages): packages published by '.config('app.name').' on pub.dev.';

        $previous = $this->previousVersion($version);
        if ($previous !== null) {
            $lines[] = '- [Documentation for v'.$previous.']('.route('landing.docs.default', ['version' => $previous, 'page' => 'installation']).'): the previous release, for apps that have not upgraded yet.';
        }
        $lines[] = '';

        return implode("\n", $lines);
    }

    /**
     * The release immediately before the given version, if there is one.
     */
    private function previousVersion(string $version): ?string
    {
        $previous = null;

        // Array keys widen to int|string, but version identifiers are strings.
        foreach (array_keys(config('project.doc-index')['versions']) as $candidate) {
            $candidate = (string) $candidate;

            if ($candidate === $version) {
                return $previous;
            }

            $previous = $candidate;
        }

        return null;
    }

    /**
     * Build a single markdown list item for a documentation page.
     */
    private function lineFor(string $version, string $page): string
    {
        $url = route('landing.docs.default', ['version' => $version, 'page' => $page]);

        [$title, $description] = $this->titleAndDescription($version, $page);

        $line = '- ['.$title.']('.$url.')';

        return $description === '' ? $line : $line.': '.$description;
    }

    /**
     * Read a page's H1 title and a one line description from its markdown.
     *
     * @return array{0: string, 1: string}
     */
    private function titleAndDescription(string $version, string $page): array
    {
        $path = base_path().'/resources/docs/'.$version.'/en/'.$page.'.md';

        if (! file_exists($path)) {
            return [Str::headline($page), ''];
        }

        // generateDocPage() blade renders the markdown and strips the table of
        // contents, so `contents` starts at the first real section of the page.
        $doc = $this->docService->generateDocPage($path, $version);

        $title = $doc['title'] !== '' ? $doc['title'] : Str::headline($page);

        return [$title, $this->extractDescription($doc['contents'])];
    }

    /**
     * Pull the first sentence of prose out of a rendered documentation page.
     */
    private function extractDescription(string $contents): string
    {
        // Fenced code blocks would otherwise be mistaken for prose.
        $contents = preg_replace('/```.*?```/s', '', $contents) ?? $contents;

        foreach (preg_split('/\R/', $contents) ?: [] as $line) {
            $line = trim($line);

            if ($line === '') {
                continue;
            }

            // Skip headings, HTML/blade output, lists, tables and blockquotes.
            if (preg_match('/^(#|<|[-*+]\s|\d+\.\s|\||>)/', $line)) {
                continue;
            }

            $text = $this->toPlainText($line);

            if ($text !== '') {
                return $text;
            }
        }

        return '';
    }

    /**
     * Reduce a line of markdown to a single plain text sentence.
     */
    private function toPlainText(string $line): string
    {
        $text = strip_tags($line);
        $text = preg_replace('/!?\[([^\]]*)\]\([^)]*\)/', '$1', $text) ?? $text;
        $text = $this->stripEmphasis($text);
        $text = trim(preg_replace('/\s+/', ' ', $text) ?? $text);

        if ($text === '') {
            return '';
        }

        // Cut at the first sentence ending, ignoring periods inside version
        // numbers such as "3.38.4" or abbreviations such as "e.g.".
        if (preg_match('/^(.{20,}?[.!?])\s+\p{Lu}/u', $text, $match)) {
            $text = $match[1];
        }

        return Str::limit($text, 200);
    }

    /**
     * Remove markdown emphasis markers without touching snake_case identifiers
     * such as flutter_launcher_icons or glob patterns such as *.dart.
     */
    private function stripEmphasis(string $text): string
    {
        $patterns = [
            '/\*\*(.+?)\*\*/u',
            '/(?<![\w_])__(.+?)__(?![\w_])/u',
            '/(?<![\w*])\*(?!\s)(.+?)(?<!\s)\*(?![\w*])/u',
            '/(?<![\w_])_(?!\s)(.+?)(?<!\s)_(?![\w_])/u',
        ];

        foreach ($patterns as $pattern) {
            $text = preg_replace($pattern, '$1', $text) ?? $text;
        }

        return $text;
    }

    /**
     * Use the intro paragraph of what-is-nylo as the document summary.
     */
    private function summaryFor(string $version): string
    {
        [, $description] = $this->titleAndDescription($version, 'what-is-nylo');

        return $description;
    }
}
