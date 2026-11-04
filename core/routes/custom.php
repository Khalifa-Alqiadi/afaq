<?php

use App\Http\Controllers\Custom\CustomController;
use Illuminate\Support\Facades\Route;


Route::get('/social/facebook-share/{token}', [CustomController::class, 'resolveFacebookShare'])
    ->where('token', '[A-Za-z0-9]{8,32}')
    ->middleware('throttle:30,1')
    ->name('facebookShareResolve');
// private route example ( require login )
/*
Route::group(['prefix' => config('smartend.backend_path'), 'middleware' => ['auth', 'LanguageSwitcher']], function () {
    Route::get('/custom-page', [CustomController::class, 'custom_page']);
});
*/

// public route example
/*
Route::get('/custom-page', [CustomController::class, 'custom_page']);
*/
