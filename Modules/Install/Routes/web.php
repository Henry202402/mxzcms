<?php
use Illuminate\Support\Facades\Route;

//安装流程路由
Route::prefix('install')->group(function() {
    Route::any('/', 'InstallController@index');
    Route::any("/start", "InstallController@start");
    Route::any("/checkDbPwd", "InstallController@checkDbPwd");
    Route::any("/saveDBInfo", "InstallController@saveDBInfo");
    Route::any("/setDbConfig", "InstallController@setDbConfig");
    Route::any("/installModule", "InstallController@installModule");
    Route::any("/installModuleDB", "InstallController@installModuleDB");
    Route::any("/setSite", "InstallController@setSite");
});
