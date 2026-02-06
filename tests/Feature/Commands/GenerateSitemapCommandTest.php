<?php

namespace Tests\Feature\Commands;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\File;
use Tests\TestCase;

class GenerateSitemapCommandTest extends TestCase
{
    use RefreshDatabase;

    private string $sitemapPath;

    protected function setUp(): void
    {
        parent::setUp();
        $this->sitemapPath = public_path('sitemap.xml');

        // Backup existing sitemap if exists
        if (File::exists($this->sitemapPath)) {
            File::copy($this->sitemapPath, $this->sitemapPath.'.backup');
        }
    }

    protected function tearDown(): void
    {
        // Restore original sitemap
        if (File::exists($this->sitemapPath.'.backup')) {
            File::move($this->sitemapPath.'.backup', $this->sitemapPath);
        }

        parent::tearDown();
    }

    /**
     * Test that sitemap:generate command runs successfully.
     */
    public function test_sitemap_generate_command_runs_successfully(): void
    {
        $this->artisan('sitemap:generate')
            ->assertSuccessful()
            ->expectsOutputToContain('Sitemap generated successfully');
    }

    /**
     * Test that sitemap contains root URL.
     */
    public function test_sitemap_contains_root_url(): void
    {
        $this->artisan('sitemap:generate')->assertSuccessful();

        $content = File::get($this->sitemapPath);

        $this->assertStringContainsString('<loc>'.config('app.url').'</loc>', $content);
    }

    /**
     * Test that sitemap contains localized landing pages.
     */
    public function test_sitemap_contains_localized_landing_pages(): void
    {
        $this->artisan('sitemap:generate')->assertSuccessful();

        $content = File::get($this->sitemapPath);
        $locales = array_keys(config('localization.supported_locales'));

        foreach ($locales as $locale) {
            $this->assertStringContainsString("/{$locale}</loc>", $content);
        }
    }

    /**
     * Test that sitemap contains privacy and terms for all locales.
     */
    public function test_sitemap_contains_privacy_and_terms_for_all_locales(): void
    {
        $this->artisan('sitemap:generate')->assertSuccessful();

        $content = File::get($this->sitemapPath);
        $locales = array_keys(config('localization.supported_locales'));

        foreach ($locales as $locale) {
            $this->assertStringContainsString("/{$locale}/privacy-policy</loc>", $content);
            $this->assertStringContainsString("/{$locale}/terms-and-conditions</loc>", $content);
        }
    }

    /**
     * Test that sitemap contains resources page.
     */
    public function test_sitemap_contains_resources_page(): void
    {
        $this->artisan('sitemap:generate')->assertSuccessful();

        $content = File::get($this->sitemapPath);

        $this->assertStringContainsString('/resources</loc>', $content);
    }

    /**
     * Test that sitemap contains learn more page.
     */
    public function test_sitemap_contains_learn_more_page(): void
    {
        $this->artisan('sitemap:generate')->assertSuccessful();

        $content = File::get($this->sitemapPath);

        $this->assertStringContainsString('/learn-more/v7</loc>', $content);
    }

    /**
     * Test that sitemap contains documentation pages for latest version.
     */
    public function test_sitemap_contains_documentation_pages(): void
    {
        $this->artisan('sitemap:generate')->assertSuccessful();

        $content = File::get($this->sitemapPath);
        $latestVersion = '7.x';

        // Check some key documentation pages
        $this->assertStringContainsString("/docs/{$latestVersion}/installation</loc>", $content);
        $this->assertStringContainsString("/docs/{$latestVersion}/router</loc>", $content);
        $this->assertStringContainsString("/docs/{$latestVersion}/what-is-nylo</loc>", $content);
    }

    /**
     * Test that sitemap does not contain old version documentation.
     */
    public function test_sitemap_does_not_contain_old_version_docs(): void
    {
        $this->artisan('sitemap:generate')->assertSuccessful();

        $content = File::get($this->sitemapPath);

        // Should not contain old versions
        $this->assertStringNotContainsString('/docs/6.x/', $content);
        $this->assertStringNotContainsString('/docs/5.x/', $content);
        $this->assertStringNotContainsString('/docs/4.x/', $content);
    }

    /**
     * Test that sitemap is valid XML.
     */
    public function test_sitemap_is_valid_xml(): void
    {
        $this->artisan('sitemap:generate')->assertSuccessful();

        $content = File::get($this->sitemapPath);

        // Check it's valid XML
        $xml = simplexml_load_string($content);
        $this->assertNotFalse($xml);
        $this->assertEquals('urlset', $xml->getName());
    }

    /**
     * Test that sitemap contains priority values.
     */
    public function test_sitemap_contains_priority_values(): void
    {
        $this->artisan('sitemap:generate')->assertSuccessful();

        $content = File::get($this->sitemapPath);

        $this->assertStringContainsString('<priority>', $content);
    }
}
