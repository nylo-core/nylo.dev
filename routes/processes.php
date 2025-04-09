<?php

use App\Http\Controllers\ProcessController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Processes
|--------------------------------------------------------------------------
*/

// Processes
Route::post('process/site-update', [ProcessController::class, 'siteUpdate']);
