<?php
error_reporting(E_ALL ^ E_NOTICE);
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2023/5/16
 * Time: 17:41
 */
$list = scandir(__DIR__);
foreach ($list as $dir) {
    if ($dir != '.' && $dir != '..' && is_dir(__DIR__."/{$dir}")) {
        $array = scandir(__DIR__ . "/{$dir}");
        foreach ($array as $file) {
            if ($file != '.' && $file != '..' && strpos($file, '.php') !== false) {
                include_once "{$dir}/" . $file;
            }
        }
    }
}

