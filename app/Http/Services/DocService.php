<?php

namespace App\Http\Services;

use Illuminate\Support\Str;

/**
 * Class DocService
 *
 * @phpstan-type TutorialItem array{label: string, link: string}
 * @phpstan-type TocItem array{text: string, anchor: string|null, title: string|null, children: array<int, mixed>}
 */
class DocService
{
    /**
     * Get latest version for Nylo.
     */
    public function getLastestVersionNylo(): string
    {
        return array_key_last(config('project.doc-index')['versions']);
    }

    /**
     * Performs a check to see if doc version is old.
     *
     * @param  string  $version
     */
    public function isViewingOldDocs($version): bool
    {
        $latestVersionOfNylo = $this->getLastestVersionNylo();

        return $latestVersionOfNylo != $version;
    }

    /**
     * Finds the correct section that a page belongs too.
     *
     * @param  string  $version
     * @param  string  $page
     */
    public function findDocSection($version, $page): string
    {
        foreach (config('project.doc-index')['versions'][$version] as $key => $docLink) {
            if (in_array($page, $docLink)) {
                return $key;
            }
        }

        return '';
    }

    /**
     * Finds the correct Tutorial section that a page belongs too.
     *
     * @param  string  $version
     * @param  string  $page
     */
    public function findTutorialSection($version, $page): string
    {
        /** @var array<string, array<int, TutorialItem>> $tutorialSections */
        $tutorialSections = config('project.doc-tutorials')['versions'][$version];
        foreach ($tutorialSections as $key => $docLink) {
            $docLabels = collect($docLink)->map(function ($doc) {
                return $doc['label'];
            })->toArray();
            if (in_array($page, $docLabels)) {
                return $key;
            }
        }

        return '';
    }

    /**
     * Checks if doc-tutorials contains a valid $version
     *
     * @param  string  $version
     */
    public function containsTutorialsForVersion($version): void
    {
        abort_if(array_key_exists($version, config('project.doc-tutorials')['versions']) == false, 403, "No tutorials found for $version");
    }

    /**
     * Checks if the docs page exists in the resource path and then returns the path.
     *
     * @param  string  $version
     * @param  string  $page
     */
    public function checkIfDocExists($version, $page, ?string $locale = null): string
    {
        // Try locale-specific path first (e.g. resources/docs/7.x/en/installation.md)
        if ($locale) {
            $localePath = base_path().'/resources/docs/'.$version.'/'.$locale.'/'.$page.'.md';
            if (file_exists($localePath)) {
                return $localePath;
            }

            // Fall back to English locale
            if ($locale !== 'en') {
                $enPath = base_path().'/resources/docs/'.$version.'/en/'.$page.'.md';
                if (file_exists($enPath)) {
                    return $enPath;
                }
            }
        }

        // Fall back to legacy non-locale path (for 6.x and older docs)
        $legacyPath = base_path().'/resources/docs/'.$version.'/'.$page.'.md';
        abort_if(! file_exists($legacyPath), 404);

        return $legacyPath;
    }

    /**
     * Checks if the tutorials docs page exists in the resource path and then returns the path.
     *
     * @param  string  $version
     * @param  string  $page
     */
    public function checkIfTutorialsExists($version, $page): string
    {
        return 'docs/'.$version.'/tutorials/'.$page;
    }

    /**
     * Returns the tutorial meta data
     *
     * @param  string  $version
     * @param  string  $page
     * @return TutorialItem|array{}
     */
    public function getTutorial($version, $page): array
    {
        $docsIndex = config('project.doc-tutorials');
        /** @var array<string, array<int, TutorialItem>> $versions */
        $versions = $docsIndex['versions'][$version];
        foreach ($versions as $docs) {
            $tutorial = collect($docs)->where('label', $page)->first();
            if ($tutorial !== null) {
                return $tutorial;
            }
        }

        return [];
    }

    /**
     * Check if the docs contain a certain page.
     *
     * @param  string  $version
     * @param  string  $page
     */
    public function checkDocsContainPage($version, $page): bool
    {
        $docsIndex = config('project.doc-index');
        $versions = $docsIndex['versions'][$version];
        $versionArray = array_values($versions);
        $docValues = collect($versionArray)->flatten()->toArray();

        return in_array($page, $docValues);
    }

    /**
     * Generates the doc page contents and on-this-page array.
     *
     * @param  string  $mdDocPage
     * @param  string  $version
     * @return array{title: string, on-this-page: array<int, TocItem>, contents: string, rawMarkdown: string}
     */
    public function generateDocPage($mdDocPage, $version)
    {
        $markdown = file_get_contents($mdDocPage);
        abort_if($markdown === false, 404);

        $bladeContents = \Blade::render($markdown, ['version' => $version]);

        $onThisPage = [];

        // Extract the title from the first H1 heading (e.g., "# Configuration")
        $title = '';
        if (preg_match('/^#\s+(.+)$/m', $bladeContents, $titleMatch)) {
            $title = trim($titleMatch[1]);
            $bladeContents = preg_replace('/^#\s+.+\R*/m', '', $bladeContents, 1);
        }

        // Extract the TOC after the first --- marker until we hit a <div id= tag
        // Pattern: --- followed by newlines, then content, until we find <div id=
        if (preg_match('/---\s*\R+(.*?)(?=<div id=)/s', $bladeContents, $matches)) {
            $tocContent = $matches[1];

            // Parse the TOC content into a nested array
            $onThisPage = $this->parseTocToArray($tocContent);

            // Remove the TOC section (the --- and everything until <div id=)
            $bladeContents = preg_replace('/---\s*\R+.*?(?=<div id=)/s', '', $bladeContents, 1);
        }

        return [
            'title' => $title,
            'on-this-page' => $onThisPage,
            'contents' => $bladeContents,
            'rawMarkdown' => '# '.$title."\n\n".$bladeContents,
        ];
    }

    /**
     * Load a legal markdown document as HTML, falling back to English when no
     * translation exists for the locale.
     */
    public function loadLegalMarkdown(string $document, ?string $locale = null): string
    {
        $locale ??= app()->getLocale();
        $path = resource_path("legal/{$locale}/{$document}.md");

        if (! file_exists($path)) {
            $path = resource_path("legal/en/{$document}.md");
        }

        $contents = file_get_contents($path);
        abort_if($contents === false, 404);

        return Str::markdown($contents);
    }

    /**
     * Parse the table of contents markdown into a nested array structure.
     *
     * @param  string  $tocContent
     * @return array<int, TocItem>
     */
    private function parseTocToArray($tocContent): array
    {
        $lines = explode("\n", $tocContent);
        $result = [];
        $stack = [&$result];
        $lastIndent = -1;

        foreach ($lines as $line) {
            // Skip empty lines and the <a name="section-1"></a> anchor
            if (trim($line) === '' || str_contains($line, '<a name=')) {
                continue;
            }

            // Detect indentation level (count leading spaces, 2 spaces = 1 level)
            preg_match('/^(\s*)/', $line, $indentMatch);
            $indentStr = $indentMatch[1] ?? '';

            // Count tabs or convert spaces to indent levels (2 spaces = 1 level)
            if (str_contains($indentStr, "\t")) {
                $indent = substr_count($indentStr, "\t");
            } else {
                $indent = (int) (strlen($indentStr) / 2);
            }

            // Extract the line content
            $content = trim($line);

            // Check if it's a link or plain text
            if (preg_match('/^-\s*\[([^\]]+)\]\(#([^\s\)"]+)(?:\s+"([^"]*)")?\)/', $content, $match)) {
                // It's a link: - [Text](#anchor "title")
                $item = [
                    'text' => $match[1],
                    'anchor' => $match[2],
                    'title' => $match[3] ?? $match[1],
                    'children' => [],
                ];
            } elseif (preg_match('/^-\s*(.+)$/', $content, $match)) {
                // It's a section header without link: - Section Name
                $item = [
                    'text' => trim($match[1]),
                    'anchor' => null,
                    'title' => null,
                    'children' => [],
                ];
            } else {
                continue;
            }

            // Adjust stack based on indentation
            if ($indent > $lastIndent) {
                // Moving deeper - add to the last item's children
                if (! empty($result) || count($stack) > 1) {
                    $lastItem = &$stack[count($stack) - 1];
                    if (! empty($lastItem)) {
                        $lastKey = array_key_last($lastItem);
                        $stack[] = &$lastItem[$lastKey]['children'];
                    }
                }
            } elseif ($indent < $lastIndent) {
                // Moving back up - pop from stack
                $diff = $lastIndent - $indent;
                for ($i = 0; $i < $diff; $i++) {
                    if (count($stack) > 1) {
                        array_pop($stack);
                    }
                }
            }

            // Add item to current level
            $stack[count($stack) - 1][] = $item;
            $lastIndent = $indent;
        }

        return $result;
    }
}
