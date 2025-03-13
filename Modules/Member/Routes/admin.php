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
use Modules\Member\Http\Middleware\CheckLoginByAdmin;
use Modules\Auth\Http\Middleware\CheckPermission;

Route::prefix('member')->middleware([CheckLoginByAdmin::class, CheckPermission::class])->namespace('Admin')->group(function () {
    Route::get('/index', 'HomeController@index');

    //用户
    Route::group(["prefix" => "user", 'as' => '账号管理@'], function () {
        Route::any('userList', "UserController@userList")->name('用户列表');
        Route::any('userAdd', "UserController@userAdd")->name('添加用户');
        Route::any('userDetail', "UserController@userDetail")->name('用户详情');

        Route::any('userAuthList', "AuthController@userAuthList")->name('认证列表');
        Route::any('userAuthAdd', "AuthController@userAuthAdd")->name('提交认证');
        Route::any('userAuthEdit', "AuthController@userAuthEdit")->name('编辑认证');
        Route::any('userAuthDelete', "AuthController@userAuthDelete")->name('删除认证');
        Route::any('userAuthAudit', "AuthController@userAuthAudit")->name('审核认证');
        Route::any('userAuthRecordList', "AuthController@userAuthRecordList")->name('认证记录列表');
    });

    //等级管理
    Route::group(["prefix" => "level", 'as' => '等级管理@'], function () {
        Route::any('vipList', "VipController@vipList")->name('vip列表');
        Route::any('vipAdd', "VipController@vipAdd")->name('添加vip');
        Route::any('vipEdit', "VipController@vipEdit")->name('编辑vip');
        Route::any('vipDelete', "VipController@vipDelete")->name('删除vip');

    });

    //对账中心
    Route::group(["prefix" => "finance", 'as' => '系统管理@'], function () {
        Route::any('walletList', "WalletController@walletList")->name('钱包列表');
        Route::any('walletAdd', "WalletController@walletAdd")->name('钱包列表');

        Route::any('flowRecord', "FinanceController@flowRecord")->name('流水记录');
        Route::any('flowRecordDetail', "FinanceController@flowRecordDetail")->name('流水记录详情');

    });

    //系统管理
    Route::group(["prefix" => "setting", 'as' => '系统管理@'], function () {
        Route::any('baseConfig', "BaseController@baseConfig")->name('基本配置');
        Route::any('baseConfigSubmit', "BaseController@baseConfigSubmit")->name('提交基本配置');

        Route::any('messageList', "MessageController@messageList")->name('站内信列表');
        Route::any('messageDetail', "MessageController@messageDetail")->name('站内信详情');

    });
});
