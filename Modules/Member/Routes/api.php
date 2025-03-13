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


Route::prefix('member')->middleware([])->namespace('Api')->group(function () {
    Route::group(['prefix' => 'login'], function () {
        Route::any('getPublicCode', 'LoginController@getPublicCode');
        Route::any('returnCode/{param}', 'LoginController@returnCode');
        Route::any('publicGetSignConfig', 'LoginController@publicGetSignConfig');
    });

    Route::group(['prefix' => 'jwt'], function () {
        Route::match(['post'], 'getCode', 'JWTController@getCode');
        Route::match(['post'], 'login', 'JWTController@login');
        Route::match(['post'], 'forgot', 'JWTController@forgot');
        Route::match(['post'], 'updatePhone', 'JWTController@updatePhone');
        Route::match(['get', 'post'], 'logout', 'JWTController@logout');
        Route::match(['get', 'post'], 'refreshToken', 'JWTController@refreshToken');
        Route::match(['get', 'post'], 'getUserInfo', 'JWTController@getUserInfo');

        Route::match(['get'], 'getOpenid', 'JWTController@getOpenid');
    });

    Route::group(['prefix' => 'order'], function () {
        Route::any('getVipList', 'HomeController@getVipList');
        Route::any('buyVip', 'OrderController@buyVip');
    });

    Route::group(['prefix' => 'user'], function () {
        Route::any('getUserWallet', 'UserController@getUserWallet');
    });
});
