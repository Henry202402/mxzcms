<?php

namespace Modules\System\Listeners;

use App\Support\PackageManifest\CompatibilityChecker;
use App\Support\PackageManifest\LifecyclePolicy;
use App\Support\PackageManifest\PackageManifest;
use App\Support\Telemetry\StatisticReporter;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Modules\System\Models\Setting;
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

        return $res;
    }

    public function modulePluginUnInstall() {
        $get = \request()->all();
        $module_name = isset($get['m']) ? $get['m'] : '';
        $get['cloud_type'] = isset($get['cloud_type']) ? $get['cloud_type'] : '';
        if (!$module_name) {
            StatisticReporter::reportBlocked('UnInstallBlocked', '', $get['cloud_type'], 'identification_required');
            return returnArr(0, '参数有误');
        }

        $manifest = PackageManifest::load($module_name, $get['cloud_type'])
            ?: PackageManifest::normalize(['identification' => $module_name, 'name' => $module_name], $get['cloud_type'], $module_name);
        $packageDirectory = $manifest['directory'] ?? package_directory_name($module_name, $get['cloud_type']);
        $uninstallPolicy = LifecyclePolicy::canUninstall($manifest);
        if (!$uninstallPolicy['allowed']) {
            StatisticReporter::reportBlocked('UnInstallBlocked', $module_name, $get['cloud_type'], $uninstallPolicy['message']);
            return returnArr(0, $uninstallPolicy['message']);
        }

        //判断是否安装
        $module = new \Modules\Main\Models\Modules();

        $resData = $module->where('identification', '=', $module_name)->where('cloud_type', '=', $get['cloud_type'])->first();
        if (!$resData) {
            StatisticReporter::reportBlocked('UnInstallBlocked', $module_name, $get['cloud_type'], 'not_installed');
            return returnArr(0, '未安装');
        }
        if ($resData->status == 1) {
            StatisticReporter::reportBlocked('UnInstallBlocked', $module_name, $get['cloud_type'], 'package_enabled');
            return returnArr(0, '请先禁用，再进行‘卸载’操作');
        }

        $dependents = CompatibilityChecker::findDependents($manifest);
        if (!empty($dependents)) {
            StatisticReporter::reportBlocked('UnInstallBlocked', $module_name, $get['cloud_type'], 'has_dependents', [
                'dependents' => implode(',', $dependents),
            ]);
            return returnArr(0, '当前包已被依赖，无法卸载：' . implode('、', $dependents));
        }

        if (is_file(module_path($get['m'], 'lock')) && $get['cloud_type'] == \Modules\Main\Models\Modules::Module) {
            StatisticReporter::reportBlocked('UnInstallBlocked', $module_name, $get['cloud_type'], 'lock_file_exists');
            return returnArr(0, '请先删除模块下的锁文件[lock]');
        }

        try {
            $res = $module->where('identification', '=', $module_name)->where('cloud_type', '=', $get['cloud_type'])->delete();
            if ($res) {

                if ($get['cloud_type'] == \Modules\Main\Models\Modules::Module) {
                    @rmdir(public_path("views/modules/".strtolower($module_name)));
                    $deletePath = modules_base_path($packageDirectory);

                    try {
                        Artisan::call('migrate:rollback', [
                            '--path' => modules_relative_path($packageDirectory . '/Database/Migrations/install'),
                            '--force' => 1,
                            '--step' => 100000000,
                        ]);
                    }catch (Exception $exception){

                    }
                    Setting::query()->where("module",$module_name)->delete();
                }

                if ($get['cloud_type'] == \Modules\Main\Models\Modules::Plugin) {
                    $deletePath = plugins_base_path($packageDirectory);
                }

                if ($resData->form =="cloud") {
                    del_dir_files($deletePath);
                }



                StatisticReporter::reportSuccess('UnInstall', $module_name, $get['cloud_type'], [
                    'source' => $resData->form ?? 'cloud',
                    'directory' => $packageDirectory,
                ]);
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
            StatisticReporter::reportBlocked('UnInstallBlocked', '', \Modules\Main\Models\Modules::Theme, 'identification_required');
            return returnArr(0, '请选择主题');
        }
        if ($all['m'] == "default") {
            StatisticReporter::reportBlocked('UnInstallBlocked', $all['m'], \Modules\Main\Models\Modules::Theme, 'default_theme_protected');
            return returnArr(0, '系统主题无法卸载');
        }


        //卸载主题
        $checkData = DB::table("themes")->where("identification", $all['m'])->first();
        if (!$checkData) {
            StatisticReporter::reportBlocked('UnInstallBlocked', $all['m'], \Modules\Main\Models\Modules::Theme, 'not_installed');
            return returnArr(0, '主题未安装');
        }
        if ($checkData->status == 1) {
            StatisticReporter::reportBlocked('UnInstallBlocked', $all['m'], \Modules\Main\Models\Modules::Theme, 'theme_in_use');
            return returnArr(0, '主题正在使用中，无法卸载');
        }

        $res = DB::table("themes")->where("identification", $all['m'])->delete();
        if ($res) {
            if ($checkData->form =="cloud") {
                del_dir_files(THEME_PATH . '/' . $all['m']);
            }
            StatisticReporter::reportSuccess('UnInstall', $all['m'], \Modules\Main\Models\Modules::Theme, [
                'source' => $checkData->form ?? 'cloud',
            ]);
            return returnArr(200, '主题卸载成功',['url'=>url("admin/theme")]);
        }
        return returnArr(0, '主题卸载失败');
    }

}
