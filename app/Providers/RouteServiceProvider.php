<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * This namespace is applied to your controller routes.
     *
     * In addition, it is set as the URL generator's root namespace.
     *
     * @var string
     */
    protected $namespace = 'App\Http\Controllers';

    /**
     * The path to the "home" route for your application.
     *
     * @var string
     */
    public const HOME = '/home';

    /**
     * Define your route model bindings, pattern filters, etc.
     *
     * @return void
     */
    public function boot()
    {
        //

        parent::boot();
    }

    /**
     * Define the routes for the application.
     *
     * @return void
     */
    public function map()
    {
        $this->mapApiRoutes();

        $this->mapWebRoutes();
        
        $this->mapGitHubActionsRoutes();
        
        $this->mapGitHubWebhookRoutes();
        
        $this->mapWebProcessRoutes();
    }

    /**
     * Define the "web" routes for the application.
     *
     * These routes all receive session state, CSRF protection, etc.
     *
     * @return void
     */
    protected function mapWebRoutes()
    {
        Route::middleware('web')
            ->namespace($this->namespace)
            ->group(base_path('routes/web.php'));
    }

    /**
     * Define the "api" routes for the application.
     *
     * These routes are typically stateless.
     *
     * @return void
     */
    protected function mapApiRoutes()
    {
        Route::prefix('api')
            ->middleware('api')
            ->namespace($this->namespace)
            ->group(base_path('routes/api.php'));
    }

    /**
     * Define the "gh_actions" routes for the application.
     *
     * These routes are typically stateless.
     *
     * @return void
     */
    protected function mapGitHubActionsRoutes()
    {
        Route::prefix('api/github/actions')
            ->middleware('gh_actions')
            ->namespace($this->namespace)
            ->group(base_path('routes/api_github_actions.php'));
    }

    /**
     * Define the "gh_webhook" routes for the application.
     *
     * These routes are typically stateless.
     *
     * @return void
     */
    protected function mapGitHubWebhookRoutes()
    {
        Route::prefix('api/github/webhook')
            ->middleware('gh_webhook')
            ->namespace($this->namespace)
            ->group(base_path('routes/api_github_webhook.php'));
    }

    /**
     * Define the "web_process" routes for the application.
     *
     * These routes are typically stateless.
     *
     * @return void
     */
    protected function mapWebProcessRoutes()
    {
        Route::middleware('web_process')
            ->namespace($this->namespace)
            ->group(base_path('routes/processes.php'));
    }
}
