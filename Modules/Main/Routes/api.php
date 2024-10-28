<?php

use Illuminate\Support\Facades\Route;
use Modules\Main\Http\Controllers\Home\HomeController;
use Modules\Main\Http\Controllers\Home\ModelController;
use Modules\Main\Http\Middleware\CheckHome;
use Modules\Main\Http\Middleware\CheckInstall;
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
Route::middleware([CheckInstall::class, CheckHome::class])->group(function () {
    Route::get('/list/{model}', [ModelController::class, 'list']);
    Route::get('/detail/{model}/{id}', [ModelController::class, 'detail']);
    Route::post('/handle/{model}', [ModelController::class, 'handle']);
});
