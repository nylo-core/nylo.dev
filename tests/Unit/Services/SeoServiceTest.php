<?php

namespace Tests\Unit\Services;

use App\Http\Services\SeoService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\URL;
use Tests\TestCase;

class SeoServiceTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test that setDefaultSeo sets meta description.
     */
    public function test_set_default_seo_sets_meta_description(): void
    {
        // Create service which calls setDefaultSeo in constructor
        $this->get('/');

        $seo = \SEO::generate();

        $this->assertStringContainsString(config('app.name'), $seo);
        $this->assertStringContainsString('micro-framework for Flutter', $seo);
    }

    /**
     * Test that setTitle appends brand name.
     */
    public function test_set_title_appends_brand(): void
    {
        $seoService = new SeoService;
        $seoService->setTitle('Test Page');

        $seo = \SEO::generate();

        $this->assertStringContainsString('Test Page', $seo);
        $this->assertStringContainsString(config('app.name'), $seo);
    }

    /**
     * Test that setSeoViewingDocs sets article type.
     */
    public function test_set_seo_viewing_docs_sets_article_type(): void
    {
        URL::defaults(['locale' => 'en']);
        $seoService = new SeoService;
        $seoService->setSeoViewingDocs('installation', '7.x', 'getting-started');

        $seo = \SEO::generate();

        $this->assertStringContainsString('Installation', $seo);
        $this->assertStringContainsString('documentation', $seo);
    }

    /**
     * Test that setSeoViewingDocs handles NyloWidget pages correctly.
     */
    public function test_set_seo_viewing_docs_handles_ny_pages(): void
    {
        URL::defaults(['locale' => 'en']);
        $seoService = new SeoService;
        $seoService->setSeoViewingDocs('ny-state', '7.x', 'widgets');

        $seo = \SEO::generate();

        // NyState should be joined without space
        $this->assertStringContainsString('NyState', $seo);
    }

    /**
     * Test that setDefaultSeo sets Twitter site.
     */
    public function test_set_default_seo_sets_twitter_site(): void
    {
        $this->get('/');

        $seo = \SEO::generate();

        $this->assertStringContainsString('twitter:site', $seo);
        $this->assertStringContainsString('@nylo_dev', $seo);
    }

    /**
     * Test that setDefaultSeo sets canonical URL.
     */
    public function test_set_default_seo_sets_canonical_url(): void
    {
        $response = $this->get('/en/privacy-policy');

        $content = $response->getContent();

        $this->assertStringContainsString('canonical', $content);
    }
}
