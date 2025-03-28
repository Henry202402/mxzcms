<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Modules\System\Http\Controllers\Api\PayController;

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


Route::prefix('system')->group(function () {
    Route::any('pay/callback/{pay_method}', [PayController::class, 'callback']);
});
