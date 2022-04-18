<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', 'LandingController@index')->name('landing.index');
Route::get('resources', 'LandingController@resources')->name('resources.index');
Route::get('download', 'LandingController@download')->name('landing.download')->middleware('throttle:5,1');
Route::get('contributions', 'LandingController@contributions')->name('landing.contributions');
Route::get('privacy-policy', 'LandingController@privacyPolicy')->name('landing.privacy-policy');
Route::get('terms-and-conditions', 'LandingController@termsAndConditions')->name('landing.terms-and-conditions');