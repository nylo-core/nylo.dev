<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GitHubActionsController;

/*
|--------------------------------------------------------------------------
| GitHub WebHooks
|--------------------------------------------------------------------------
*/

// GitHub WebHooks
Route::post('{repo}/release', [GitHubActionsController::class, 'release'])
		->whereIn('repo', ['nylo'])
		->name('repo.release');
