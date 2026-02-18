<?php

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\RunInSeparateProcess;
use Tests\TestCase;

class DocsPages7xTest extends TestCase
{
    /**
     * Provides all 7.x page slugs from the doc-index config.
     *
     * @return array<string, array{string}>
     */
    public static function sevenXPagesProvider(): array
    {
        $app = require __DIR__.'/../../config/project/doc-index.php';
        $sections = $app['versions']['7.x'];

        $cases = [];
        foreach ($sections as $pages) {
            foreach ($pages as $page) {
                $cases[$page] = [$page];
            }
        }

        return $cases;
    }

    /**
     * Provides all 7.x page slugs crossed with all supported locales.
     *
     * @return array<string, array{string, string}>
     */
    public static function sevenXPagesWithLocalesProvider(): array
    {
        $docIndex = require __DIR__.'/../../config/project/doc-index.php';
        $localization = require __DIR__.'/../../config/localization.php';

        $sections = $docIndex['versions']['7.x'];
        $locales = array_keys($localization['supported_locales']);

        $cases = [];
        foreach ($locales as $locale) {
            foreach ($sections as $pages) {
                foreach ($pages as $page) {
                    $cases["{$locale}/{$page}"] = [$locale, $page];
                }
            }
        }

        return $cases;
    }

    /**
     * Test each 7.x doc page loads via /docs/7.x/{page}.
     */
    #[DataProvider('sevenXPagesProvider')]
    #[RunInSeparateProcess]
    public function test_docs_7x_page_loads(string $page): void
    {
        $response = $this->get("/docs/7.x/{$page}");

        $response->assertStatus(200);
    }

    /**
     * Test each 7.x doc page loads via /{locale}/docs/7.x/{page}.
     */
    #[DataProvider('sevenXPagesWithLocalesProvider')]
    #[RunInSeparateProcess]
    public function test_docs_7x_page_loads_for_locale(string $locale, string $page): void
    {
        $response = $this->get("/{$locale}/docs/7.x/{$page}");

        $response->assertStatus(200);
    }
}
