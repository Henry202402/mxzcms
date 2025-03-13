<?php


use Illuminate\Support\Facades\Route;
use Modules\System\Http\Middleware\CheckModuleAdmin;
use Modules\Auth\Http\Middleware\CheckPermission;


Route::prefix('system')->middleware([CheckModuleAdmin::class, CheckPermission::class])->namespace('Admin')->group(function () {


    //安全与工具
    Route::group(["prefix" => "secure", 'as' => '安全与工具@'], function () {
        Route::any('secureConfig', "SecureController@secureConfig")->name('安全配置');
        Route::any('uploadsConfig', "SecureController@uploadsConfig")->name('上传配置');
        Route::any('cacheConfig', "SecureController@cacheConfig")->name('缓存配置');
        Route::any('toolSubmit', "SecureController@toolSubmit")->name('保存配置');

        Route::any('scheduledTasksList', "TaskController@scheduledTasksList")->name('定时任务列表');
        Route::any('scheduledTasksAdd', "TaskController@scheduledTasksAdd")->name('添加定时任务');
        Route::any('scheduledTasksEdit', "TaskController@scheduledTasksEdit")->name('编辑定时任务');
        Route::any('scheduledTasksDelete', "TaskController@scheduledTasksDelete")->name('删除定时任务');
        Route::any('scheduledTasksExecute', "TaskController@scheduledTasksExecute")->name('执行定时任务');
        Route::any('scheduledTasksLog', "TaskController@scheduledTasksLog")->name('定时任务日志');
    });


    //基本配置
    Route::group(["prefix" => "base", 'as' => '系统设置@'], function () {
        Route::any('baseConfig', "BaseController@baseConfig")->name('基本配置');
        Route::any('baseSubmit', "BaseController@baseSubmit")->name('保存配置');
    });

    //SEO配置
    Route::group(["prefix" => "seo", 'as' => 'SEO配置@'], function () {
        Route::any('config', "SeoController@seoConfig")->name('SEO配置');
        Route::any('submit', "SeoController@Submit")->name('提交处理');
    });

    //模块绑定域名
    Route::group(["prefix" => "setting", 'as' => '系统设置@'], function () {
        Route::any('moduleBindDomain', "SettingController@moduleBindDomain")->name('模块绑定域名');
        Route::any('moduleBindDomainSubmit', "SettingController@moduleBindDomainSubmit")->name('保存绑定域名');
    });


});
