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
        foreach ($moduleList as $module) {
            $path = array_unique(Arr::wrap(module_path($module['identification'], 'Console/Commands')));
            $path = array_filter($path, function ($path) {
                return is_dir($path);
            });
            if ($path[0]) $paths[] = $path[0];
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
            if ($command) $list[] = $command;
        }
        return $list;
    }

}
