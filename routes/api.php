<?php

use App\Http\Controllers\API\BusinessesController;
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

/*Route::group
([
    "domain" =>
])*/

Route::prefix('business')->group(function () {
    Route::post('/', [BusinessesController::class, 'store']);
    Route::put('/', [BusinessesController::class, 'update']);
    Route::delete('/', [BusinessesController::class, 'destroy']);
    Route::get('/search', [BusinessesController::class, 'getData']);
});
