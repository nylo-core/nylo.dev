<?php

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Support\Facades\Route;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
        then: function () {
            Route::middleware('gh_actions')
                ->prefix('api/github/actions')
                ->group(base_path('routes/api_github_actions.php'));

            Route::middleware('gh_webhook')
                ->prefix('api/github/webhook')
                ->group(base_path('routes/api_github_webhook.php'));

            Route::middleware('web_process')
                ->group(base_path('routes/processes.php'));
        },
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->trustProxies(at: '*');

        $middleware->appendToGroup('gh_actions', [
            \Illuminate\Routing\Middleware\ThrottleRequests::class.':60,1',
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
            \App\Http\Middleware\AuthenticateGitHubMiddleware::class,
        ]);

        $middleware->appendToGroup('gh_webhook', [
            \Illuminate\Routing\Middleware\ThrottleRequests::class.':60,1',
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
            \App\Http\Middleware\AuthenticateGitHubSignatureVerifyWebhook::class,
        ]);

        $middleware->appendToGroup('web_process', [
            \Illuminate\Routing\Middleware\ThrottleRequests::class.':60,1',
            \App\Http\Middleware\WebProcessMiddleware::class,
        ]);

        $middleware->alias([
            'auth' => \App\Http\Middleware\Authenticate::class,
            'guest' => \App\Http\Middleware\RedirectIfAuthenticated::class,
            'locale' => \App\Http\Middleware\SetLocaleMiddleware::class,
        ]);
    })
    ->withSchedule(function (Schedule $schedule) {
        $schedule->command('sitemap:generate')->daily();
    })
    ->withExceptions(function (Exceptions $exceptions) {
        $exceptions->dontFlash([
            'password',
            'password_confirmation',
        ]);
    })
    ->create();
