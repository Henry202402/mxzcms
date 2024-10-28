<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

use Illuminate\Support\Facades\Route;
use Modules\Main\Http\Middleware\CheckLoginByAdmin;

Route::prefix('formtools')->middleware([CheckLoginByAdmin::class])->namespace('Api')->group(function () {
    Route::get('/index', 'HomeController@index');

});
