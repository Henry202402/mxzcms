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
use Modules\Auth\Http\Middleware\CheckLoginByAdmin;
use Modules\Auth\Http\Middleware\CheckPermission;

Route::prefix('auth')->middleware([CheckLoginByAdmin::class, CheckPermission::class])->namespace('Admin')->group(function () {

//    Route::get('/index', 'HomeController@index')->name('首页');
//    Route::get('/index1', 'HomeController@index')->name('首页@首页1');

    Route::group(['prefix' => 'group', 'as' => '权限管理@'], function () {
        Route::get('/list', 'GroupController@list')->name('权限组列表');
        Route::get('/add', 'GroupController@add')->name('添加权限组');
        Route::get('/edit', 'GroupController@edit')->name('编辑权限组');
        Route::get('/delete', 'GroupController@delete')->name('删除权限组');
        Route::any('/handle', 'GroupController@handle')->name('操作权限');
        Route::get('/assignPermissions', 'GroupController@assignPermissions')->name('分配权限');
        Route::get('/groupUser', 'GroupController@groupUser')->name('组成员列表');
        Route::any('/groupUserAdd', 'GroupController@groupUserAdd')->name('添加组成员');
        Route::get('/groupUserDelete', 'GroupController@groupUserDelete')->name('删除组成员');
    });

    /*Route::group(['prefix' => 'other', 'as' => '其他@'], function () {
        Route::get('/test', 'HomeController@index')->name('测试');
    });*/
});
