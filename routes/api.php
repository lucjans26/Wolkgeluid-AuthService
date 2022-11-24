<?php

use App\Http\Controllers\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::group(['middleware' => ['web']], function () {
    Route::get('/auth/google/redirect', [AuthController::class, 'redirect']);
    Route::get('/auth/google/callback', [AuthController::class, 'callback']);
});

Route::post('/auth/logout', [AuthController::class, 'logout'])->middleware(['auth:sanctum']);
