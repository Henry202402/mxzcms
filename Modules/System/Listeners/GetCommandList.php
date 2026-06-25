<?php

namespace Modules\System\Listeners;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Cache;
use Mxzcms\Modules\cache\CacheKey;
use Symfony\Component\Finder\Finder;
use Illuminate\Support\Str;

class GetCommandList {

    public function handle(\Modules\System\Events\GetCommandList $event) {
        //事件逻辑 ...
        $moduleName = ucfirst($event->data['moduleName']);
        //获取启用的模块
        $moduleList = Cache::get(CacheKey::ModulesActive);
        $list = [];
        $paths = [];//模块命令文件夹
        if (is_array($moduleList) && !empty($moduleList)) {
            foreach ($moduleList as $module) {
                $path = array_unique(Arr::wrap(module_path($module['identification'], 'Console/Commands')));
                $path = array_filter($path, function ($path) {
                    return is_dir($path);
                });
                if (!empty($path[0])) {
                    $paths[] = $path[0];
                }
            }
        } else {
            $moduleRoot = function_exists('package_root_relative')
                ? base_path(package_root_relative('module'))
                : (is_dir(base_path('modules')) ? base_path('modules') : base_path('Modules'));
            if (is_dir($moduleRoot)) {
                foreach (scandir($moduleRoot) ?: [] as $dir) {
                    if ($dir === '.' || $dir === '..') {
                        continue;
                    }
                    $commandPath = rtrim($moduleRoot, '\\/') . DIRECTORY_SEPARATOR . $dir . DIRECTORY_SEPARATOR . 'Console' . DIRECTORY_SEPARATOR . 'Commands';
                    if (is_dir($commandPath)) {
                        $paths[] = $commandPath;
                    }
                }
            }
        }
        if (empty($paths)) {
            return $list;
        }
        //循环获取文件夹的命令文件，
        foreach ((new Finder)->in($paths)->files() as $command) {
            $command = str_replace(
                ['/', '.php'],
                ['\\', ''],
                Str::after($command->getRealPath(), realpath(base_path()) . DIRECTORY_SEPARATOR)
            );
            if (!$command) {
                continue;
            }
            if (Str::startsWith($command, 'modules\\')) {
                $command = 'Modules\\' . Str::after($command, 'modules\\');
            }
            if (Str::startsWith($command, 'plugins\\')) {
                $command = 'Plugins\\' . Str::after($command, 'plugins\\');
            }
            $list[] = $command;
        }
        return $list;
    }

}
