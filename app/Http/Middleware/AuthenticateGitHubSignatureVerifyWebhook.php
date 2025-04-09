<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AuthenticateGitHubSignatureVerifyWebhook
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $xHubSignature256 = $request->header('X-Hub-Signature-256');
        if (empty($xHubSignature256)) {
            abort(403);
        }

        $verified = $this->verifySignature($xHubSignature256);

        if (! $verified) {
            abort(403);
        }

        return $next($request);
    }

    private function verifySignature($xHubSignature256)
    {
        $body = file_get_contents('php://input');
        $secret = config('project.meta.github_webhook_secret');

        return hash_equals('sha256='.hash_hmac('sha256', $body, $secret), $xHubSignature256);
    }
}
