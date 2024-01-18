<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LandingController;

/* Web Routes
|-------------------------------------------------------------------------- */

Route::controller(LandingController::class)->group(function () {
	Route::get('/', 'index')->name('landing.index');
	Route::get('resources', 'resources')->name('resources.index');
	Route::get('ecosystem', 'ecosystem')->name('ecosystem.index');
	Route::get('download', 'download')->name('landing.download')->middleware('throttle:5,1');
	Route::get('privacy-policy', 'privacyPolicy')->name('landing.privacy-policy');
	Route::get('terms-and-conditions', 'termsAndConditions')->name('landing.terms-and-conditions');
    Route::get('tutorials/{version}/{page?}', 'tutorials')->name('tutorials.index');
	Route::get('docs/{version}/{page?}', 'docs')->name('landing.docs');
});

// Redirects
Route::redirect('/docs', '/docs/' . array_key_last(config('project.doc-index')['versions']) . '/installation', 301);
