<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        view()->composer('pages.*', function ($view) {
            $latestVersionOfNylo = array_key_last(config('project.doc-index')['versions']);

            $view->with('latestVersionOfNylo', $latestVersionOfNylo);
        });
    }
}
