<?php


use Illuminate\Support\Facades\Route;
use Modules\Main\Http\Controllers\Home\HomeController;
use Modules\Main\Http\Controllers\Home\ModelController;
use Modules\Main\Http\Middleware\CheckHome;
use Modules\Main\Http\Middleware\CheckHomeLogin;

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
Route::middleware([CheckHome::class,\Modules\Main\Http\Middleware\AccessLog::class])->group(function () {
    Route::any('/', [HomeController::class, 'index']);
    Route::any('/index', [HomeController::class, 'index']);
    Route::get('/about', [HomeController::class, 'about']);
    Route::get('/contacts', [HomeController::class, 'contacts']);
    Route::get('/list/{model}', [ModelController::class, 'list']);
    Route::get('/detail/{model}/{id}', [ModelController::class, 'detail']);
    Route::post('/handle/{model}', [ModelController::class, 'handle']);

    Route::group(['namespace' => 'Home'], function () {
        Route::any('/login', ["uses" => "LoginController@login"]);
        Route::any('/register', ["uses" => "LoginController@register"]);
        Route::any('/forgot', ["uses" => "LoginController@forgot"]);
        Route::get('/logout', ["uses" => "LoginController@logout"]);
        Route::any('/sendCode', ["uses" => "LoginController@sendCode"]);
    });
});

Route::middleware([CheckHomeLogin::class])->namespace('Home')->group(function () {
    Route::group(["prefix" => "member"], function () {
        Route::get('', ["uses" => "MemberController@index"]);
        Route::get('/index', ["uses" => "MemberController@index"]);
        Route::any('/mine', ["uses" => "MemberController@mine"]);
        Route::any('/password', ["uses" => "MemberController@updatePassword"]);
        Route::any('/email', ["uses" => "MemberController@updateEmail"]);
        Route::any('/phone', ["uses" => "MemberController@updatePhone"]);

        Route::any('/message', ["uses" => "MemberController@messageList"]);
        Route::any('/message/detail', ["uses" => "MemberController@messageDetail"]);
        Route::any('/message/read', ["uses" => "MemberController@messageRead"]);
        Route::any('/message/delete', ["uses" => "MemberController@messageDelete"]);
    });
});

