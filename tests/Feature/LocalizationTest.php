<?php

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\RunInSeparateProcess;
use Tests\TestCase;

class LocalizationTest extends TestCase
{
    /**
     * Test that the root URL serves English content directly.
     */
    public function test_root_serves_english_content(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
        $this->assertEquals('en', app()->getLocale());
    }

    /**
     * Test that all supported locales load the home page.
     */
    public function test_all_locales_load_home_page(): void
    {
        $locales = array_keys(config('localization.supported_locales'));

        foreach ($locales as $locale) {
            $response = $this->get("/{$locale}");
            $response->assertStatus(200);
        }
    }

    /**
     * Test that privacy policy page loads for all locales.
     */
    public function test_privacy_policy_loads_for_all_locales(): void
    {
        $locales = array_keys(config('localization.supported_locales'));

        foreach ($locales as $locale) {
            $response = $this->get("/{$locale}/privacy-policy");
            $response->assertStatus(200);
        }
    }

    /**
     * Test that terms and conditions page loads for all locales.
     */
    public function test_terms_and_conditions_loads_for_all_locales(): void
    {
        $locales = array_keys(config('localization.supported_locales'));

        foreach ($locales as $locale) {
            $response = $this->get("/{$locale}/terms-and-conditions");
            $response->assertStatus(200);
        }
    }

    /**
     * Test that an invalid locale returns 404.
     */
    public function test_invalid_locale_returns_404(): void
    {
        $response = $this->get('/xx');

        $response->assertStatus(404);
    }

    /**
     * Test that docs routes serve English content without locale prefix.
     */
    #[RunInSeparateProcess]
    public function test_docs_routes_work_without_locale(): void
    {
        $response = $this->get('/docs/7.x/installation');

        $response->assertStatus(200);
    }

    /**
     * Test that docs routes work with locale prefix.
     */
    #[RunInSeparateProcess]
    public function test_docs_routes_work_with_locale(): void
    {
        $response = $this->get('/en/docs/7.x/installation');

        $response->assertStatus(200);
    }

    /**
     * Test that the app locale is set correctly.
     */
    public function test_app_locale_is_set_correctly(): void
    {
        $this->get('/zh');
        $this->assertEquals('zh', app()->getLocale());

        $this->get('/fr');
        $this->assertEquals('fr', app()->getLocale());
    }
}
