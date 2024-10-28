<?php
error_reporting(E_ALL ^ E_NOTICE);
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2023/5/16
 * Time: 17:41
 */
$array = scandir(__DIR__ . '/Child');
foreach ($array as $file) {
    if ($file != '.' && $file != '..' && strpos($file, '.php') !== false) {
        include_once 'Child/' . $file;
    }
}

