<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProcessController;

/*
|--------------------------------------------------------------------------
| Processes
|--------------------------------------------------------------------------
*/

// Processes
Route::post('process/site-update', [ProcessController::class, 'siteUpdate']);