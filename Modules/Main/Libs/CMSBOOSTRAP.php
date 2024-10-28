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
        is_writable(base_path("bootstrap/cache")) || chmod(base_path("bootstrap/cache"), 0777);
        is_dir(storage_path("logs")) || mkdir(storage_path("logs"), 0777, true);
        is_dir(storage_path("backup")) || mkdir(storage_path("backup"), 0777, true);

        is_dir(storage_path("download")) || mkdir(storage_path("download"), 0777, true);
        is_dir(storage_path("download/cms")) || mkdir(storage_path("download/cms"), 0777, true);
        is_dir(storage_path("download/module")) || mkdir(storage_path("download/modules"), 0777, true);
        is_dir(storage_path("download/plugin")) || mkdir(storage_path("download/plugin"), 0777, true);
        is_dir(storage_path("download/theme")) || mkdir(storage_path("download/theme"), 0777, true);

        is_dir(storage_path("framework")) || mkdir(storage_path("framework"), 0777, true);
        is_dir(storage_path("framework/sessions")) || mkdir(storage_path("framework/sessions"), 0777, true);
        is_dir(storage_path("framework/views")) || mkdir(storage_path("framework/views"), 0777, true);
        is_dir(storage_path("framework/cache")) || mkdir(storage_path("framework/cache"), 0777, true);
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
