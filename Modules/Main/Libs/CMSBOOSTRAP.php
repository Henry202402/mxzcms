<?php

namespace Modules\Main\Libs;

use Illuminate\Support\Facades\Artisan;

class CMSBOOSTRAP
{
    static public function boostrap()
    {
        $self = new self();
        $self->checkEnv();
        //设置错误级别
        error_reporting(E_ERROR); // E_ERROR  E_WARNING  E_PARSE  E_NOTICE  E_ALL
    }
    static public function checkEnvValue()
    {
        config("app.key") || Artisan::call('key:generate');
    }
    static public function checkDir()
    {
        $dirList = [
            base_path("bootstrap/cache"),
            storage_path("logs"),
            storage_path("backup"),
            storage_path("download"),
            storage_path("download/cms"),
            storage_path("download/module"),
            storage_path("download/plugin"),
            storage_path("download/theme"),
            storage_path("framework"),
            storage_path("framework/sessions"),
            storage_path("framework/views"),
            storage_path("framework/cache")
        ];
        foreach ($dirList as $dir) {
            is_dir($dir) || mkdir($dir, 0777, true);
            is_writable($dir) || chmod($dir, 0777);
        }
    }
    private function checkEnv()
    {
        $this->checkFunction();
        if (!file_exists(base_path(".env"))) {
            $env = file_get_contents(base_path(".env.example"));
            file_put_contents(base_path(".env"), $env);
        }
    }
    private function checkFunction()
    {
        if (!function_exists('file_get_contents')) {
            throw new \Exception("file_get_contents function is not exists");
        }
        if (!function_exists('file_put_contents')) {
            throw new \Exception("file_put_contents function is not exists");
        }
        if (!function_exists('getenv')) {
            throw new \Exception("getenv function is not exists");
        }
        if (!function_exists('fopen')) {
            throw new \Exception("fopen function is not exists");
        }
        if (!function_exists('chmod')) {
            throw new \Exception("chmod function is not exists");
        }
        if (!function_exists('unlink')) {
            throw new \Exception("unlink function is not exists");
        }
        if (!function_exists('symlink')) {
            throw new \Exception("symlink function is not exists");
        }


    }

}
