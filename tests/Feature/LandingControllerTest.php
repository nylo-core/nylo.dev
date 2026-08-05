<?php

namespace Tests\Feature;

use App\Models\OnlineEvent;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\RunInSeparateProcess;
use Tests\TestCase;

class LandingControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test that the home page loads successfully.
     */
    public function test_home_page_loads_successfully(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertViewIs('pages.index');
    }

    /**
     * Test that the home page shows the banner for an upcoming event.
     */
    public function test_home_page_shows_upcoming_event_banner(): void
    {
        cache()->forget('event');

        OnlineEvent::factory()->create([
            'title' => 'Nylo Launch Party',
            'link' => 'https://nylo.dev/events/launch-party',
        ]);

        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSee('Nylo Launch Party');
        $response->assertSee('https://nylo.dev/events/launch-party');
    }

    /**
     * Test that docs page loads for valid version and page.
     */
    #[RunInSeparateProcess]
    public function test_docs_page_loads(): void
    {
        $response = $this->get('/docs/7.x/installation');

        $response->assertStatus(200);
        $response->assertViewIs('docs.template');
        $response->assertViewHas('version', '7.x');
        $response->assertViewHas('page', 'installation');
    }

    /**
     * Test that docs redirects to latest version when no version specified.
     */
    public function test_docs_redirect_to_latest_version(): void
    {
        $response = $this->get('/docs');

        $response->assertStatus(301);
        $response->assertRedirect('/docs/7.x/installation');
    }

    /**
     * Test that /en/docs/... 301-redirects to the canonical non-prefixed URL.
     */
    public function test_en_docs_redirects_to_canonical(): void
    {
        $response = $this->get('/en/docs/7.x/installation');

        $response->assertStatus(301);
        $response->assertRedirect('/docs/7.x/installation');
    }

    /**
     * Test that docs with invalid version returns 404.
     */
    public function test_docs_with_invalid_version_returns_404(): void
    {
        $response = $this->get('/docs/invalid/installation');

        $response->assertStatus(404);
    }

    /**
     * Test that docs with invalid page returns 404.
     */
    public function test_docs_with_invalid_page_returns_404(): void
    {
        $response = $this->get('/docs/7.x/nonexistent-page');

        $response->assertStatus(404);
    }

    /**
     * Test that tutorials page loads for valid version and page.
     */
    #[RunInSeparateProcess]
    public function test_tutorials_page_loads(): void
    {
        $response = $this->get('/tutorials/5.x/introduction');

        $response->assertStatus(200);
        $response->assertViewIs('docs.tutorials');
        $response->assertViewHas('version', '5.x');
        $response->assertViewHas('page', 'introduction');
    }

    /**
     * Test that tutorials with invalid version returns 403.
     */
    public function test_tutorials_with_invalid_version_returns_403(): void
    {
        $response = $this->get('/tutorials/invalid/introduction');

        $response->assertStatus(403);
    }

    /**
     * Test that tutorials with invalid page returns 404.
     */
    public function test_tutorials_with_invalid_page_returns_404(): void
    {
        $response = $this->get('/tutorials/5.x/nonexistent-page');

        $response->assertStatus(404);
    }

    /**
     * Test that API docs returns HTML content from markdown.
     */
    public function test_api_docs_returns_html(): void
    {
        $response = $this->get('/api/docs/7.x/installation');

        $response->assertStatus(200);
        $this->assertStringContainsStringIgnoringCase(
            'text/html',
            $response->headers->get('content-type')
        );
    }

    /**
     * Test that API docs with invalid page returns 404.
     */
    public function test_api_docs_with_invalid_page_returns_404(): void
    {
        $response = $this->get('/api/docs/7.x/nonexistent-page');

        $response->assertStatus(404);
    }

    /**
     * Test that learn more v7 page loads.
     */
    public function test_learn_more_v7_loads(): void
    {
        $response = $this->get('/learn-more/v7');

        $response->assertStatus(200);
        $response->assertViewIs('pages.learn-more-v7');
    }

    /**
     * Test that learn more v6 redirects to home.
     */
    public function test_learn_more_v6_redirects(): void
    {
        $response = $this->get('/learn-more/v6');

        $response->assertStatus(301);
        $response->assertRedirect('/en/');
    }

    /**
     * Test that resources page loads.
     */
    public function test_resources_page_loads(): void
    {
        $response = $this->get('/resources');

        $response->assertStatus(200);
        $response->assertViewIs('pages.resources');
        $response->assertViewHas('latestVersionOfNylo');
    }

    /**
     * Test that privacy policy page loads.
     */
    public function test_privacy_policy_page_loads(): void
    {
        $response = $this->get('/en/privacy-policy');

        $response->assertStatus(200);
        $response->assertViewIs('pages.privacy-policy');
        $response->assertViewHas('content');
    }

    /**
     * Test that terms and conditions page loads.
     */
    public function test_terms_and_conditions_page_loads(): void
    {
        $response = $this->get('/en/terms-and-conditions');

        $response->assertStatus(200);
        $response->assertViewIs('pages.terms-and-conditions');
        $response->assertViewHas('content');
    }

    /**
     * Test that download redirects to GitHub releases.
     */
    public function test_download_redirects_to_github_releases(): void
    {
        $response = $this->get('/download');

        $response->assertStatus(301);
        $response->assertRedirect('https://github.com/nylo-core/nylo/releases');
    }

    /**
     * Test that ecosystem route redirects to home.
     */
    public function test_ecosystem_redirects_to_home(): void
    {
        $response = $this->get('/ecosystem');

        $response->assertStatus(301);
        $response->assertRedirect('/en/');
    }

    /**
     * Test that docs view contains required view data.
     */
    #[RunInSeparateProcess]
    public function test_docs_view_contains_required_data(): void
    {
        $response = $this->get('/docs/7.x/router');

        $response->assertStatus(200);
        $response->assertViewHas('mdDocPage');
        $response->assertViewHas('section');
        $response->assertViewHas('latestVersionOfNylo');
        $response->assertViewHas('viewingOldDocs');
        $response->assertViewHas('docsContainPage');
        $response->assertViewHas('docContents');
    }

    /**
     * Test viewing old docs shows viewingOldDocs as true.
     */
    #[RunInSeparateProcess]
    public function test_viewing_old_docs_flag_is_true_for_old_version(): void
    {
        $response = $this->get('/docs/6.x/installation');

        $response->assertStatus(200);
        $response->assertViewHas('viewingOldDocs', true);
    }

    /**
     * Test viewing latest docs shows viewingOldDocs as false.
     */
    #[RunInSeparateProcess]
    public function test_viewing_old_docs_flag_is_false_for_latest_version(): void
    {
        $response = $this->get('/docs/7.x/installation');

        $response->assertStatus(200);
        $response->assertViewHas('viewingOldDocs', false);
    }
}
