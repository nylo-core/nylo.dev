<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ServeMarkdownForLlmMiddleware
{
    /**
     * Known LLM bot User-Agent substrings.
     *
     * @var string[]
     */
    private const LLM_USER_AGENTS = [
        'GPTBot',
        'ChatGPT-User',
        'ClaudeBot',
        'Claude-Web',
        'Anthropic',
        'CCBot',
        'PerplexityBot',
        'Cohere-AI',
        'Google-Extended',
        'Amazonbot',
        'AI2Bot',
        'Bytespider',
        'YouBot',
    ];

    /**
     * Flag the request if it comes from an LLM or explicitly asks for markdown.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if ($this->wantsMarkdown($request)) {
            $request->attributes->set('wants_markdown', true);
        }

        return $next($request);
    }

    /**
     * Determine if the request is from an LLM or explicitly asks for markdown.
     */
    private function wantsMarkdown(Request $request): bool
    {
        if ($request->query('format') === 'md') {
            return true;
        }

        if (str_contains($request->header('Accept', ''), 'text/markdown')) {
            return true;
        }

        $userAgent = $request->userAgent() ?? '';
        foreach (self::LLM_USER_AGENTS as $bot) {
            if (stripos($userAgent, $bot) !== false) {
                return true;
            }
        }

        return false;
    }
}
