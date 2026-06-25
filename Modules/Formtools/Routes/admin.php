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
        Route::any('/modelStatistics', 'HomeController@modelStatistics')->name('模型统计');
        Route::any('/synmodel', 'HomeController@synmodel')->name('恢复默认模型配置');
        Route::any('/seedDemoContent', 'HomeController@seedDemoContent')->name('灌入演示内容');
        Route::any('/resetModelData', 'HomeController@resetModelData')->name('重建模型结构与演示数据');
        Route::any('/getModel', 'HomeController@getModel')->name('从数据表获取模型结构');


    });

    Route::group(['as' => '页面管理@'], function () {
        Route::get('/pageList', 'PageController@index')->name('页面列表');
        Route::any('/pageAdd', 'PageController@pageAdd')->name('添加页面');
        Route::any('/pageEdit', 'PageController@pageEdit')->name('编辑页面');
        Route::get('/pagePreview', 'PageController@preview')->name('预览页面');
        Route::get('/pageSetHome', 'PageController@pageSetHome')->name('设置首页页面');
        Route::get('/pageDelete', 'PageController@pageDelete')->name('删除页面');
        Route::get('/pageCopy', 'PageController@pageCopy')->name('复制页面');
    });

    Route::group(['as' => '页面分类@'], function () {
        Route::get('/pageCategoryList', 'PageCategoryController@index')->name('页面分类列表');
        Route::any('/pageCategoryAdd', 'PageCategoryController@add')->name('添加页面分类');
        Route::any('/pageCategoryEdit', 'PageCategoryController@edit')->name('编辑页面分类');
        Route::get('/pageCategoryDelete', 'PageCategoryController@delete')->name('删除页面分类');
    });

    Route::group(['as' => '追加模型@'], function () {
        Route::any('/model', 'ModelController@loadModel')->name('追加模型');
    });

    Route::group(['as' => '模块设置@'], function () {
        Route::any('/setting', 'SettingController@setting')->name('基本设置');
    });

    Route::group(['as' => '开发示例@'], function () {
        Route::get('/demo', 'TestController@index')->name('FormTool 示例');
        Route::any('/demo/add', 'TestController@add')->name('新增示例');
        Route::any('/demo/edit', 'TestController@edit')->name('编辑示例');
        Route::get('/demo/detail', 'TestController@detail')->name('详情示例');
        Route::post('/demo/save', 'TestController@save')->name('保存示例');
        Route::get('/demo/delete', 'TestController@delete')->name('删除示例');
    });


});
