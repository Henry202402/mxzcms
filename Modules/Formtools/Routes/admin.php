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
use Modules\Auth\Http\Middleware\CheckPermission;
use Modules\Main\Http\Middleware\CheckLoginByAdmin;

Route::prefix('formtools')->middleware([CheckLoginByAdmin::class, CheckPermission::class])->namespace('Admin')->group(function () {

    Route::group(['as' => '模型管理@'], function () {
        Route::get('/index', 'HomeController@index')->name('模型列表');
        Route::any('/modelAdd', 'HomeController@modelAdd')->name('添加模型');
        Route::any('/modelEdit', 'HomeController@modelEdit')->name('编辑模型');
        Route::get('/modelDelete', 'HomeController@modelDelete')->name('删除模型');

        Route::any('/fieldList', 'HomeController@fieldList')->name('字段列表');
        Route::any('/fieldAdd', 'HomeController@fieldAdd')->name('添加字段');
        Route::any('/fieldEdit', 'HomeController@fieldEdit')->name('编辑字段');
        Route::any('/fieldDel', 'HomeController@fieldDel')->name('删除字段');
        Route::any('/fieldMove', 'HomeController@fieldMove')->name('移动字段');
    });

    Route::group(['as' => '追加模型@'], function () {
        Route::any('/model', 'ModelController@loadModel')->name('追加模型');
    });


    Route::any('/testAdd', 'TestController@Add');
    Route::any('/testEdit', 'TestController@Edit');
    Route::any('/testdo', 'TestController@Handle');
    Route::any('/testindex', 'TestController@index');


});
