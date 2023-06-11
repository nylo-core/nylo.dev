<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GitHubActionsController;

// GitHub Actions
Route::post('{repo}/version', [GitHubActionsController::class, 'version'])
		->name('actions.version');