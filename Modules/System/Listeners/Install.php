<?php

namespace Modules\System\Listeners;

use App\Support\PackageManifest\CompatibilityChecker;
use App\Support\PackageManifest\PackageManifest;
use App\Support\Telemetry\StatisticReporter;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;

class Install {

    public function handle(\Modules\System\Events\Install $event) {
        //事件逻辑 ...
        $data = $event->data;
        $request = \request()->all();
        $request = array_merge($request,$data);
        $cloud_type = $request['cloud_type'];

        if ($cloud_type == \Modules\Main\Models\Modules::Plugin) {
            //插件
            $res = $this->modulePluginInstall();
        } elseif ($cloud_type == \Modules\Main\Models\Modules::Module) {
            //模块
            $res = $this->modulePluginInstall();
        } elseif ($cloud_type == \Modules\Main\Models\Modules::Theme) {
            //主题
            $res = $this->themesInstall();
        }

        return $res;
    }

    public function modulePluginInstall() {
        set_time_limit(0);
        $get = \request()->all();
        if (!$get['m']) return returnArr(0, '参数有误!');
        $manifest = PackageManifest::load($get['m'], $get['cloud_type']);
        if (!$manifest) {
            StatisticReporter::reportBlocked('InstallBlocked', $get['m'], $get['cloud_type'], 'manifest_missing');
            return returnArr(0, '配置不完整，请重新下载!');
        }
        $packageDirectory = $manifest['directory'] ?? package_directory_name($manifest['identification'], $get['cloud_type']);

        $module = new \Modules\Main\Models\Modules();
        $installableError = CompatibilityChecker::checkInstallable($manifest);
        if ($installableError) {
            StatisticReporter::reportBlocked('InstallBlocked', $manifest['identification'], $get['cloud_type'], $installableError);
            return returnArr(0, $installableError);
        }

        //判断是否安装

        $res = $module->where('identification', '=', $manifest['identification'])
            ->where("cloud_type", "=", $get['cloud_type'])
            ->first();
        if ($res) {
            StatisticReporter::reportBlocked('InstallBlocked', $manifest['identification'], $get['cloud_type'], 'already_installed');
            return returnArr(0, '请勿重复安装!');
        }

        //安装操作
        $inster_modules_data['name'] = $manifest['name'];
        $inster_modules_data['identification'] = $manifest['identification'];
        $inster_modules_data['cloud_type'] = $get['cloud_type'];
        $inster_modules_data['type'] = $manifest['type'] ?? 'function';
        $inster_modules_data['domain'] = $manifest['ui']['domain'] ?? 'n';//默认没有绑定域名
        $inster_modules_data['status'] = 1;
        $inster_modules_data['form'] = $get['form'] ?? "cloud";
        $inster_modules_data['created_at'] = $inster_modules_data['updated_at'] = date('Y-m-d H:i:s');
        $res = $module->insert($inster_modules_data);
        if ($res) {
            $packageBasePath = [
                \Modules\Main\Models\Modules::Module => modules_base_path(),
                \Modules\Main\Models\Modules::Plugin => plugins_base_path(),
            ];
            $lock_file = $packageBasePath[$get['cloud_type']] . '/' . $packageDirectory . '/lock';
            @chmod($lock_file, 0777);
            @unlink($lock_file);
            $lock_file = fopen($lock_file, 'w+');//创建 锁文件
            fwrite($lock_file, date('Y-m-d H:i:s'));//写入

            if ($get['cloud_type'] == \Modules\Main\Models\Modules::Module) {
                @symlink(module_path($manifest['identification'], "Resources/views"),
                    public_path("views/modules/".strtolower($manifest['identification']))
                );

                try {

                    Artisan::call('migrate', [
                        '--path' => modules_relative_path($packageDirectory . '/Database/Migrations/install'),
                        '--force' => 1,
                    ]);
                    Artisan::call('db:seed', [
                        '--class' => "Modules\\".$manifest['identification']."\Database\Seeders\DatabaseSeeder",
                        '--force' => 1,
                    ]);

                }catch (\Exception $exception){

                }
            }

            if ($get['return_type'] == 'api') {
                StatisticReporter::reportSuccess('Install', $manifest['identification'], $get['cloud_type'], [
                    'source' => $get['form'] ?? 'cloud',
                    'directory' => $packageDirectory,
                ]);
                return returnArr(200, $manifest['name'] . '安装成功；');
            } else {
                StatisticReporter::reportSuccess('Install', $manifest['identification'], $get['cloud_type'], [
                    'source' => $get['form'] ?? 'cloud',
                    'directory' => $packageDirectory,
                ]);
                if ($get['cloud_type'] == \Modules\Main\Models\Modules::Plugin) {
                    return returnArr(200, $manifest['name'] . '安装成功；', ['url' => url('admin/plugin')]);
                } else {
                    return returnArr(200, $manifest['name'] . '安装成功；', ['url' => url('admin/module')]);
                }
            }
        }
        return returnArr(0, '安装失败，重新再试!');
    }

    public function themesInstall() {
        $all = \request()->all();
        if (!$all['m']) {
            StatisticReporter::reportBlocked('InstallBlocked', '', \Modules\Main\Models\Modules::Theme, 'identification_required');
            return returnArr(0, '请选择主题');
        }
        //安装主题
        $checkData = DB::table("themes")->where("identification","=", $all['m'])->count();
        if( $checkData != 0 ) {
            StatisticReporter::reportBlocked('InstallBlocked', $all['m'], \Modules\Main\Models\Modules::Theme, 'already_installed');
            return returnArr(0, '主题已存在');
        }

        $themeConfig = PackageManifest::load($all['m'], \Modules\Main\Models\Modules::Theme);
        if (!$themeConfig) {
            StatisticReporter::reportBlocked('InstallBlocked', $all['m'], \Modules\Main\Models\Modules::Theme, 'manifest_missing');
            return returnArr(0, '主题配置不完整，请重新下载!');
        }

        $installableError = CompatibilityChecker::checkInstallable($themeConfig);
        if ($installableError) {
            StatisticReporter::reportBlocked('InstallBlocked', $themeConfig['identification'], \Modules\Main\Models\Modules::Theme, $installableError);
            return returnArr(0, $installableError);
        }

        $inster['name'] = $themeConfig['name'];
        $inster['identification'] = $all['m'];
        $inster['preview'] = $themeConfig['preview'];
        $inster['status'] = 2;
        $inster['form'] = $all['form'] ?? "cloud";
        $inster['created_at'] = $inster['updated_at'] = date("Y-m-d H:i:s");
        $res = DB::table("themes")->insert($inster);
        if ($res) {
            StatisticReporter::reportSuccess('Install', $themeConfig['identification'], \Modules\Main\Models\Modules::Theme, [
                'source' => $all['form'] ?? 'cloud',
            ]);
            return returnArr(200, '主题安装成功', ['url' => url("admin/theme")]);
        }
        return returnArr(0, '主题安装失败');
    }

}
