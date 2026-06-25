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
use Modules\Formtools\Http\Controllers\Home\PageController as HomePageController;

Route::prefix('formtools')->namespace('Home')->group(function () {
    Route::get('/', 'HomeController@index');
    Route::get('/index', 'HomeController@index');
    Route::get('/page/{slug}', 'PageController@legacyRedirect')->where('slug', '.*');

});

Route::get('/p/{slug}', [HomePageController::class, 'show'])->where('slug', '.*');
