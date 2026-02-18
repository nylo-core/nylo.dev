<?php

use App\Http\Controllers\LandingController;
use Illuminate\Support\Facades\Route;

/* Web Routes
|-------------------------------------------------------------------------- */

// Root serves English content directly (no redirect)
Route::get('/', [LandingController::class, 'index'])
    ->middleware('locale')
    ->name('landing.home');

// Localized landing pages
Route::prefix('{locale}')
    ->middleware('locale')
    ->where(['locale' => 'en|zh|id|tr|hi|de|it|fr|ko|ja|pt|es|ru|vi|th|pl'])
    ->controller(LandingController::class)
    ->group(function () {
        Route::get('/', 'index')->name('landing.index');
        Route::get('privacy-policy', 'privacyPolicy')->name('landing.privacy-policy');
        Route::get('terms-and-conditions', 'termsAndConditions')->name('landing.terms-and-conditions');
        Route::get('docs/{version}/{page?}', 'docs')->where(['version' => '[\w.]+', 'page' => '[\w-]+'])->middleware('llm_markdown')->name('landing.docs');
    });

// Non-localized routes
Route::controller(LandingController::class)->group(function () {
    Route::get('download', 'download')->name('landing.download')->middleware('throttle:5,1');
    Route::get('tutorials/{version}/{page?}', 'tutorials')->where(['version' => '[\w.]+', 'page' => '[\w-]+'])->name('tutorials.index');
    Route::get('api/docs/{version}/{page?}', 'apiDocs')->where(['version' => '[\w.]+', 'page' => '[\w-]+'])->name('landing.api.docs');
    Route::get('learn-more/v7', 'learnMoreV7')->name('learn-more.v7');
});

/* Redirects
|-------------------------------------------------------------------------- */

// Serve docs without locale prefix as English (no redirect)
Route::get('docs/{version}/{page?}', function (string $version, string $page = 'installation') {
    return app(LandingController::class)->docs(null, $version, $page);
})->where(['version' => '[\w.]+', 'page' => '[\w-]+'])->middleware(['locale', 'llm_markdown'])->name('landing.docs.default');

Route::redirect('/docs', '/docs/'.array_key_last(config('project.doc-index')['versions']).'/installation', 301);

Route::redirect('/learn-more/v6', '/en/', 301);

Route::get('resources', [LandingController::class, 'resources'])->name('landing.resources');

Route::redirect('/ecosystem', '/en/', 301);
