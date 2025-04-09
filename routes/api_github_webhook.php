<?php

use App\Http\Controllers\GitHubActionsController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| GitHub WebHooks
|--------------------------------------------------------------------------
*/

// GitHub WebHooks
Route::post('{repo}/release', [GitHubActionsController::class, 'release'])
    ->whereIn('repo', ['nylo'])
    ->name('repo.release');
