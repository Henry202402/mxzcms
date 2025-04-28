<?php

namespace Modules\System\Listeners;

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use PHPUnit\Exception;

class UnInstall {

    public function handle(\Modules\System\Events\UnInstall $event) {
        //事件逻辑 ...
        $request = array_merge($event->data,\request()->all());
        $cloud_type = $request['cloud_type'];

        if ($cloud_type == \Modules\Main\Models\Modules::Plugin) {
            //插件
            $res = $this->modulePluginUnInstall();
        } elseif ($cloud_type == \Modules\Main\Models\Modules::Module) {
            //模块
            $res = $this->modulePluginUnInstall();

        } elseif ($cloud_type == \Modules\Main\Models\Modules::Theme) {
            //主题
            $res = $this->themesUnInstall();
        }

        //统计卸载功能
        if ($res['status'] == 200) {
            hook("Statistic",['moduleName'=>"System","action"=>"UnInstall","identification"=>$request['m'],"type"=>$cloud_type]);
        }

        return $res;
    }

    public function modulePluginUnInstall() {
        $get = \request()->all();
        $module_name = isset($get['m']) ? $get['m'] : '';
        $get['cloud_type'] = isset($get['cloud_type']) ? $get['cloud_type'] : '';
        if (!$module_name) return returnArr(0, '参数有误');
        if (in_array($module_name, ['System'])) return returnArr(0, '内置模块不能卸载');

        //判断是否安装
        $module = new \Modules\Main\Models\Modules();

        $resData = $module->where('identification', '=', $module_name)->where('cloud_type', '=', $get['cloud_type'])->first();
        if (!$resData) return returnArr(0, '未安装');
        if ($resData->status == 1) return returnArr(0, '请先禁用，再进行‘卸载’操作');

        if (is_file(MODULE_PATH . '/' . $get['m'] . '/lock') && $get['cloud_type'] == \Modules\Main\Models\Modules::Module) return returnArr(0, '请先删除模块下的锁文件[lock]');

        try {
            $res = $module->where('identification', '=', $module_name)->where('cloud_type', '=', $get['cloud_type'])->delete();
            if ($res) {

                if ($get['cloud_type'] == \Modules\Main\Models\Modules::Module) {
                    @rmdir(public_path("views/modules/".strtolower($module_name)));
                    $deletePath = MODULE_PATH . '/' . $module_name;

                    try {
                        Artisan::call('migrate:rollback', [
                            '--path' => "Modules/{$module_name}/Database/Migrations/install",
                            '--force' => 1,
                            '--step' => 100000000,
                        ]);
                    }catch (Exception $exception){

                    }
                }

                if ($get['cloud_type'] == \Modules\Main\Models\Modules::Plugin) {
                    $deletePath = PLUGIN_PATH . '/' . $module_name;
                }

                if ($resData->form =="cloud") {
                    del_dir_files($deletePath);
                }



                $res = returnArr(200, '卸载成功');
                return $res;
            }
            return returnArr(0, '卸载失败，重新再试');

        } catch (\Exception $e) {
            return returnArr(0, '卸载失败，重新再试');
        }
    }

    public function themesUnInstall() {
        $all = \request()->all();
        if (!$all['m']) {
            return returnArr(0, '请选择主题');
        }
        if ($all['m'] == "default") {
            return returnArr(0, '系统主题无法卸载');
        }


        //卸载主题
        $checkData = DB::table("themes")->where("identification", $all['m'])->first();
        if (!$checkData) {
            return returnArr(0, '主题未安装');
        }
        if ($checkData->status == 1) {
            return returnArr(0, '主题正在使用中，无法卸载');
        }

        $res = DB::table("themes")->where("identification", $all['m'])->delete();
        if ($res) {
            if ($checkData->form =="cloud") {
                del_dir_files(THEME_PATH . '/' . $all['m']);
            }
            return returnArr(200, '主题卸载成功',['url'=>url("admin/theme")]);
        }
        return returnArr(0, '主题卸载失败');
    }

}
