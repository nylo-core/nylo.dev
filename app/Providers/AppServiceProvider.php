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
            $link = route('larecipe.show', [
                    'version' => config('larecipe.versions.default'), 
                    'page' => config('larecipe.docs.landing')
                ]);

            $view->with('docsIndex', $link);
        });
    }
}
