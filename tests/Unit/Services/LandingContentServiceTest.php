<?php

namespace Tests\Unit\Services;

use App\Http\Services\LandingContentService;
use Tests\TestCase;

class LandingContentServiceTest extends TestCase
{
    private LandingContentService $landingContent;

    protected function setUp(): void
    {
        parent::setUp();
        $this->landingContent = new LandingContentService;
    }

    /**
     * Test docsUrls returns the named links the landing page needs.
     */
    public function test_docs_urls_returns_named_links(): void
    {
        $urls = $this->landingContent->docsUrls('7.x');

        $this->assertEquals(['index', 'installation', 'metro', 'networking'], array_keys($urls));
        $this->assertStringContainsString('docs/7.x', $urls['index']);
        $this->assertStringContainsString('installation', $urls['installation']);
    }

    /**
     * Test docs URLs carry the active locale.
     */
    public function test_docs_urls_use_the_active_locale(): void
    {
        app()->setLocale('fr');

        $urls = $this->landingContent->docsUrls('7.x');

        $this->assertStringContainsString('/fr/docs/7.x', $urls['index']);
        $this->assertStringContainsString('/fr/docs/7.x/installation', $urls['installation']);
    }

    /**
     * Test every pillar links to its documentation page.
     */
    public function test_pillars_link_to_docs(): void
    {
        $pillars = $this->landingContent->pillars('7.x');

        $this->assertCount(5, $pillars);
        foreach ($pillars as $pillar) {
            $this->assertArrayHasKey('num', $pillar);
            $this->assertArrayHasKey('tag', $pillar);
            $this->assertArrayHasKey('title', $pillar);
            $this->assertArrayHasKey('copy', $pillar);
            $this->assertStringContainsString('docs/7.x', $pillar['url']);
        }
    }

    /**
     * Test every extra feature links to its documentation page.
     */
    public function test_extra_features_link_to_docs(): void
    {
        $features = $this->landingContent->extraFeatures('7.x');

        $this->assertCount(9, $features);
        foreach ($features as $feature) {
            $this->assertArrayHasKey('label', $feature);
            $this->assertStringContainsString('docs/7.x', $feature['url']);
        }
    }

    /**
     * Test explorerTabs returns the expected component keys.
     */
    public function test_explorer_tabs_returns_expected_keys(): void
    {
        $tabs = $this->landingContent->explorerTabs();

        $this->assertEquals([
            'routing',
            'authentication',
            'forms',
            'state-management',
            'events',
            'scheduler',
            'networking',
            'storage',
            'localization',
            'navigation-hub',
        ], array_keys($tabs));
    }

    /**
     * Test testimonials fill each layout slot.
     */
    public function test_testimonials_fill_each_layout_slot(): void
    {
        $testimonials = $this->landingContent->testimonials();

        $this->assertEquals(['featured', 'highlights', 'rest'], array_keys($testimonials));
        $this->assertArrayHasKey('quote', $testimonials['featured']);
        $this->assertArrayHasKey('author', $testimonials['featured']);
        $this->assertNotEmpty($testimonials['highlights']);
        $this->assertNotEmpty($testimonials['rest']);
    }

    /**
     * Test metroHighlights returns the bullet points.
     */
    public function test_metro_highlights_returns_bullets(): void
    {
        $this->assertCount(3, $this->landingContent->metroHighlights());
    }
}
