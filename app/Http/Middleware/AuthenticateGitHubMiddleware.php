<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AuthenticateGitHubMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $ghApiKey = $request->header('X-GH-API-KEY');

        if (empty($ghApiKey) || $ghApiKey != config('project.meta.gh_auth_token')) {
            abort(403, 'API key is not valid');
        }

        return $next($request);
    }
}
