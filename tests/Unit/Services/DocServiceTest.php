<?php

namespace Tests\Unit\Services;

use App\Http\Services\DocService;
use App\Models\Download;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class DocServiceTest extends TestCase
{
    use RefreshDatabase;

    private DocService $docService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->docService = new DocService;
    }

    /**
     * Test that getLastestVersionNylo returns the latest version.
     */
    public function test_get_latest_version_nylo(): void
    {
        $latestVersion = $this->docService->getLastestVersionNylo();

        $this->assertEquals('7.x', $latestVersion);
    }

    /**
     * Test that isViewingOldDocs returns true for old versions.
     */
    public function test_is_viewing_old_docs_returns_true_for_old_version(): void
    {
        $this->assertTrue($this->docService->isViewingOldDocs('6.x'));
        $this->assertTrue($this->docService->isViewingOldDocs('5.x'));
        $this->assertTrue($this->docService->isViewingOldDocs('1.x'));
    }

    /**
     * Test that isViewingOldDocs returns false for latest version.
     */
    public function test_is_viewing_old_docs_returns_false_for_latest_version(): void
    {
        $this->assertFalse($this->docService->isViewingOldDocs('7.x'));
    }

    /**
     * Test that findDocSection returns correct section for page.
     */
    public function test_find_doc_section_returns_correct_section(): void
    {
        $section = $this->docService->findDocSection('7.x', 'installation');
        $this->assertEquals('getting-started', $section);

        $section = $this->docService->findDocSection('7.x', 'router');
        $this->assertEquals('basics', $section);

        $section = $this->docService->findDocSection('7.x', 'what-is-nylo');
        $this->assertEquals('introduction', $section);

        $section = $this->docService->findDocSection('7.x', 'themes-and-styling');
        $this->assertEquals('basics', $section);

        $section = $this->docService->findDocSection('7.x', 'state-management');
        $this->assertEquals('advanced', $section);
    }

    /**
     * Test that findDocSection returns empty string for non-existent page.
     */
    public function test_find_doc_section_returns_empty_for_nonexistent_page(): void
    {
        $section = $this->docService->findDocSection('7.x', 'nonexistent-page');
        $this->assertEquals('', $section);
    }

    /**
     * Test that checkIfDocExists returns path for existing doc.
     */
    public function test_check_if_doc_exists_returns_path_for_existing_doc(): void
    {
        $path = $this->docService->checkIfDocExists('7.x', 'installation', 'en');

        $this->assertStringContainsString('resources/docs/7.x/en/installation.md', $path);
        $this->assertFileExists($path);
    }

    /**
     * Test that checkIfDocExists aborts 404 for missing doc.
     */
    public function test_check_if_doc_exists_aborts_for_missing_doc(): void
    {
        $this->expectException(\Symfony\Component\HttpKernel\Exception\NotFoundHttpException::class);

        $this->docService->checkIfDocExists('7.x', 'nonexistent-page');
    }

    /**
     * Test that checkDocsContainPage returns true for existing page.
     */
    public function test_check_docs_contain_page_returns_true_for_existing(): void
    {
        $this->assertTrue($this->docService->checkDocsContainPage('7.x', 'installation'));
        $this->assertTrue($this->docService->checkDocsContainPage('7.x', 'router'));
        $this->assertTrue($this->docService->checkDocsContainPage('7.x', 'what-is-nylo'));
    }

    /**
     * Test that checkDocsContainPage returns false for non-existing page.
     */
    public function test_check_docs_contain_page_returns_false_for_nonexistent(): void
    {
        $this->assertFalse($this->docService->checkDocsContainPage('7.x', 'nonexistent-page'));
    }

    /**
     * Test that generateDocPage extracts title from markdown.
     */
    public function test_generate_doc_page_extracts_title(): void
    {
        $mdDocPage = $this->docService->checkIfDocExists('7.x', 'installation', 'en');
        $result = $this->docService->generateDocPage($mdDocPage, '7.x');

        $this->assertArrayHasKey('title', $result);
        $this->assertNotEmpty($result['title']);
        $this->assertEquals('Installation', $result['title']);
    }

    /**
     * Test that generateDocPage returns on-this-page array.
     */
    public function test_generate_doc_page_returns_on_this_page_array(): void
    {
        $mdDocPage = $this->docService->checkIfDocExists('7.x', 'installation', 'en');
        $result = $this->docService->generateDocPage($mdDocPage, '7.x');

        $this->assertArrayHasKey('on-this-page', $result);
        $this->assertIsArray($result['on-this-page']);
    }

    /**
     * Test that generateDocPage returns contents.
     */
    public function test_generate_doc_page_returns_contents(): void
    {
        $mdDocPage = $this->docService->checkIfDocExists('7.x', 'installation', 'en');
        $result = $this->docService->generateDocPage($mdDocPage, '7.x');

        $this->assertArrayHasKey('contents', $result);
        $this->assertNotEmpty($result['contents']);
    }

    /**
     * Test findTutorialSection returns correct section.
     */
    public function test_find_tutorial_section_returns_correct_section(): void
    {
        $section = $this->docService->findTutorialSection('5.x', 'introduction');
        $this->assertEquals('Getting Started', $section);

        $section = $this->docService->findTutorialSection('5.x', 'validation');
        $this->assertEquals('Deeper Dive', $section);

        $section = $this->docService->findTutorialSection('5.x', 'NyPullToRefresh');
        $this->assertEquals('Widgets', $section);
    }

    /**
     * Test containsTutorialsForVersion aborts for invalid version.
     */
    public function test_contains_tutorials_for_version_aborts_for_invalid_version(): void
    {
        $this->expectException(\Symfony\Component\HttpKernel\Exception\HttpException::class);

        $this->docService->containsTutorialsForVersion('invalid');
    }

    /**
     * Test getTutorial returns tutorial data.
     */
    public function test_get_tutorial_returns_tutorial_data(): void
    {
        $tutorial = $this->docService->getTutorial('5.x', 'introduction');

        $this->assertIsArray($tutorial);
        $this->assertArrayHasKey('label', $tutorial);
        $this->assertArrayHasKey('link', $tutorial);
        $this->assertEquals('introduction', $tutorial['label']);
    }

    /**
     * Test getTutorial returns empty array for nonexistent tutorial.
     */
    public function test_get_tutorial_returns_empty_for_nonexistent(): void
    {
        $tutorial = $this->docService->getTutorial('5.x', 'nonexistent');

        $this->assertIsArray($tutorial);
        $this->assertEmpty($tutorial);
    }

    /**
     * Test downloadFile returns zipball URL for valid project.
     */
    public function test_download_file_returns_zipball_url(): void
    {
        Http::fake([
            'api.github.com/repos/nylo-core/nylo/releases/latest' => Http::response([
                'name' => 'v7.0.0',
                'zipball_url' => 'https://github.com/nylo-core/nylo/zipball/v7.0.0',
            ], 200),
        ]);

        $url = $this->docService->downloadFile('nylo-core/nylo');

        $this->assertEquals('https://github.com/nylo-core/nylo/zipball/v7.0.0', $url);
    }

    /**
     * Test downloadFile creates download record.
     */
    public function test_download_file_creates_download_record(): void
    {
        Http::fake([
            'api.github.com/repos/nylo-core/nylo/releases/latest' => Http::response([
                'name' => 'v7.0.0',
                'zipball_url' => 'https://github.com/nylo-core/nylo/zipball/v7.0.0',
            ], 200),
        ]);

        $this->assertDatabaseCount('downloads', 0);

        $this->docService->downloadFile('nylo-core/nylo');

        $this->assertDatabaseCount('downloads', 1);
        $this->assertDatabaseHas('downloads', [
            'project' => 'nylo-core/nylo',
            'version' => 'v7.0.0',
        ]);
    }

    /**
     * Test downloadFile aborts for invalid project.
     */
    public function test_download_file_aborts_for_invalid_project(): void
    {
        $this->expectException(\Symfony\Component\HttpKernel\Exception\NotFoundHttpException::class);

        $this->docService->downloadFile('invalid/project');
    }
}
