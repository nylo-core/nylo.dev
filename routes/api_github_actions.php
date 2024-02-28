<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GitHubActionsController;

/*
|--------------------------------------------------------------------------
| GitHub Actions
|--------------------------------------------------------------------------
*/

// GitHub Actions
Route::post('{repo}/version', [GitHubActionsController::class, 'version'])
		->whereIn('repo', ['nylo', 'support', 'framework', 'media-pro', 'permission-policy', 'device-meta'])
		->name('actions.version');
