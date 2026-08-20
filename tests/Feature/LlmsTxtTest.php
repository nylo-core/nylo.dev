<?php

namespace Tests\Feature;

use App\Http\Services\DocService;
use Tests\TestCase;

class LlmsTxtTest extends TestCase
{
    /**
     * The llms.txt route should serve plain text.
     */
    public function test_llms_txt_is_served_as_plain_text(): void
    {
        $response = $this->get('/llms.txt');

        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'text/plain; charset=UTF-8');
    }

    /**
     * The document should open with the H1 and summary blockquote the spec expects.
     */
    public function test_llms_txt_starts_with_a_title_and_summary(): void
    {
        $content = $this->get('/llms.txt')->getContent();

        $this->assertStringStartsWith('# '.config('app.name'), (string) $content);
        $this->assertStringContainsString('> '.config('app.name').' is a micro-framework for Flutter', (string) $content);
    }

    /**
     * Agents need to be told the markdown escape hatch exists.
     */
    public function test_llms_txt_advertises_the_markdown_format(): void
    {
        $response = $this->get('/llms.txt');

        $response->assertSee('?format=md', false);
        $response->assertSee('Accept: text/markdown', false);
    }

    /**
     * Every page in the doc index for the latest version should be listed once,
     * grouped under its section heading.
     */
    public function test_llms_txt_lists_every_page_of_the_latest_version(): void
    {
        $version = app(DocService::class)->getLastestVersionNylo();
        $sections = config('project.doc-index')['versions'][$version];

        $content = (string) $this->get('/llms.txt')->getContent();

        foreach ($sections as $section => $pages) {
            $this->assertStringContainsString('## '.str($section)->headline(), $content);

            foreach ($pages as $page) {
                $url = route('landing.docs.default', ['version' => $version, 'page' => $page]);

                $this->assertSame(
                    1,
                    substr_count($content, '('.$url.')'),
                    "Expected {$page} to be listed exactly once in llms.txt."
                );
            }
        }
    }

    /**
     * Listed pages should carry a description pulled from the page itself.
     */
    public function test_llms_txt_entries_include_descriptions(): void
    {
        $content = (string) $this->get('/llms.txt')->getContent();

        $this->assertStringContainsString(
            '[Router]('.route('landing.docs.default', ['version' => '7.x', 'page' => 'router'])
                .'): Routes allow you to define the different pages in your app',
            $content
        );
    }

    /**
     * Descriptions are plain prose: no blade output, html tags or code fences.
     */
    public function test_llms_txt_descriptions_are_plain_text(): void
    {
        $content = (string) $this->get('/llms.txt')->getContent();

        $this->assertStringNotContainsString('{{', $content);
        $this->assertStringNotContainsString('```', $content);
        $this->assertStringNotContainsString('<a href', $content);
        $this->assertStringNotContainsString('<div', $content);
    }

    /**
     * Emphasis markers are stripped without damaging snake_case identifiers.
     */
    public function test_llms_txt_preserves_snake_case_identifiers(): void
    {
        $this->get('/llms.txt')->assertSee('flutter_launcher_icons', false);
    }

    /**
     * robots.txt should point crawlers at the index.
     */
    public function test_robots_txt_references_llms_txt(): void
    {
        $robots = (string) file_get_contents(public_path('robots.txt'));

        $this->assertStringContainsString('Llms: https://nylo.dev/llms.txt', $robots);
        $this->assertStringContainsString('Sitemap: https://nylo.dev/sitemap.xml', $robots);
    }
}
