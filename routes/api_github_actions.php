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
		->whereIn('repo', [
            'nylo',
            'support',
            'framework',
            'media-pro',
            'permission-policy',
            'device-meta',
            'error-stack',
            'laravel-notify-fcm',
            'laravel-auth-slate',
            'scaffold-ui'
            ])
		->name('actions.version');
