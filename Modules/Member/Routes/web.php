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

Route::middleware([\Modules\Main\Http\Middleware\CheckHomeLogin::class])->namespace('Home')->group(function () {
    Route::group(["prefix" => "member"], function () {
        Route::get('', ["uses" => "MemberController@index"]);
        Route::get('/index', ["uses" => "MemberController@index"]);
        Route::any('/mine', ["uses" => "MemberController@mine"]);
        Route::any('/password', ["uses" => "MemberController@updatePassword"]);
        Route::any('/email', ["uses" => "MemberController@updateEmail"]);
        Route::any('/phone', ["uses" => "MemberController@updatePhone"]);

        Route::any('/myMembers', ["uses" => "MemberController@myMembers"]);


        Route::any('/signIn', ["uses" => "MemberController@signIn"]);
        Route::any('/signinRecord', ["uses" => "MemberController@signinRecord"]);

        Route::any('/myVip', ["uses" => "MemberController@myVip"]);
        Route::any('/vipPay', ["uses" => "MemberController@vipPay"]);
        Route::any('/checkVipPayStatus', ["uses" => "MemberController@checkVipPayStatus"]);
        Route::any('/vipRecord', ["uses" => "MemberController@vipRecord"]);

        Route::any('/myWallet', ["uses" => "MemberController@myWallet"]);
        Route::any('/myBill', ["uses" => "MemberController@myBill"]);

        Route::any('/myRealName', ["uses" => "AuthController@myRealName"]);
        Route::any('/addRealName', ["uses" => "AuthController@addRealName"]);
        Route::any('/editRealName', ["uses" => "AuthController@editRealName"]);

        Route::any('/message', ["uses" => "MessageController@messageList"]);
        Route::any('/message/detail', ["uses" => "MessageController@messageDetail"]);
        Route::any('/message/read', ["uses" => "MessageController@messageRead"]);
        Route::any('/message/delete', ["uses" => "MessageController@messageDelete"]);
        Route::any('/message/getUserNoReadMessage', ["uses" => "MessageController@getUserNoReadMessage"]);
    });
});
