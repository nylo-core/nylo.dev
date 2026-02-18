<?php

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\RunInSeparateProcess;
use Tests\TestCase;

class LlmMarkdownDocsTest extends TestCase
{
    /**
     * GPTBot User-Agent should receive a markdown response.
     */
    #[RunInSeparateProcess]
    public function test_gptbot_user_agent_gets_markdown(): void
    {
        $response = $this->get('/docs/7.x/installation', [
            'User-Agent' => 'Mozilla/5.0 AppleWebKit/537.36 (KHTML, like Gecko; compatible; GPTBot/1.0)',
        ]);

        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'text/markdown; charset=UTF-8');
        $response->assertSee('# Installation');
    }

    /**
     * The ?format=md query parameter should return markdown.
     */
    #[RunInSeparateProcess]
    public function test_format_md_query_param_gets_markdown(): void
    {
        $response = $this->get('/docs/7.x/installation?format=md');

        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'text/markdown; charset=UTF-8');
        $response->assertSee('# Installation');
    }

    /**
     * Accept: text/markdown header should return markdown.
     */
    #[RunInSeparateProcess]
    public function test_accept_text_markdown_header_gets_markdown(): void
    {
        $response = $this->get('/docs/7.x/installation', [
            'Accept' => 'text/markdown',
        ]);

        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'text/markdown; charset=UTF-8');
        $response->assertSee('# Installation');
    }

    /**
     * Normal browser request should still return an HTML view.
     */
    #[RunInSeparateProcess]
    public function test_normal_browser_gets_html(): void
    {
        $response = $this->get('/docs/7.x/installation');

        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'text/html; charset=UTF-8');
        $response->assertViewIs('docs.template');
    }

    /**
     * Localized route should also return markdown for LLM requests.
     */
    #[RunInSeparateProcess]
    public function test_localized_route_returns_markdown_for_llm(): void
    {
        $response = $this->get('/en/docs/7.x/installation?format=md');

        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'text/markdown; charset=UTF-8');
        $response->assertSee('# Installation');
    }

    /**
     * Invalid page should return 404 for LLM requests.
     */
    #[RunInSeparateProcess]
    public function test_invalid_page_returns_404_for_llm(): void
    {
        $response = $this->get('/docs/7.x/nonexistent-page-xyz?format=md');

        $response->assertStatus(404);
    }

    /**
     * Blade expressions should be resolved in the returned markdown.
     */
    #[RunInSeparateProcess]
    public function test_blade_expressions_are_resolved_in_markdown(): void
    {
        $response = $this->get('/docs/7.x/installation?format=md');

        $response->assertStatus(200);
        // Blade expressions like {{ $version }} should not appear raw
        $response->assertDontSee('{{ $version }}');
        $response->assertDontSee('{!! $version !!}');
    }
}
