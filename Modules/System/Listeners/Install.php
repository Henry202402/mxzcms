<?php

namespace Modules\System\Listeners;

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

        //统计安装功能
        if ($res['status'] == 200) {
            hook("Statistic",['moduleName'=>"System","action"=>"Install","identification"=>$request['m'],"type"=>$cloud_type]);
        }

        return $res;
    }

    public function modulePluginInstall() {
        set_time_limit(0);
        $get = \request()->all();
        if (!$get['m']) return returnArr(0, '参数有误!');
        //判断模块配置文件
        $cloud_type = [
            \Modules\Main\Models\Modules::Module => MODULE_PATH,
            \Modules\Main\Models\Modules::Plugin => PLUGIN_PATH,
        ];
        $file = $cloud_type[$get['cloud_type']] . '/' . $get['m'] . '/Config/config.php';
        if (!file_exists($file)) return returnArr(0, '配置不完整，请重新下载!');

        $modules_data = include($file);

        //判断版本限制

        //判断是否安装
        $module = new \Modules\Main\Models\Modules();
        $res = $module->where('identification', '=', $modules_data['identification'])
            ->where("cloud_type", "=", $get['cloud_type'])
            ->first();
        if ($res) return returnArr(0, '请勿重复安装!');

        //安装操作
        $inster_modules_data['name'] = $modules_data['name'];
        $inster_modules_data['identification'] = $modules_data['identification'];
        $inster_modules_data['cloud_type'] = $get['cloud_type'];
        $inster_modules_data['type'] = $modules_data['type'];
        $inster_modules_data['domain'] = $modules_data['domain'] ?? 'n';//默认没有绑定域名
        $inster_modules_data['status'] = 1;
        $inster_modules_data['form'] = $get['form'] ?? "cloud";
        $inster_modules_data['created_at'] = $inster_modules_data['updated_at'] = date('Y-m-d H:i:s');
        $res = $module->insert($inster_modules_data);
        if ($res) {
            $lock_file = fopen($cloud_type[$get['cloud_type']] . '/' . $get['m'] . '/lock', 'w+');//创建 锁文件
            fwrite($lock_file, date('Y-m-d H:i:s'));//写入

            if ($get['cloud_type'] == \Modules\Main\Models\Modules::Module) {
                @symlink(module_path($modules_data['identification'], "Resources/views"),
                    public_path("views/modules/".strtolower($modules_data['identification']))
                );
            }

            try {

                Artisan::call('migrate', [
                    '--path' => "Modules/{$modules_data['identification']}/Database/Migrations/install",
                    '--force' => 1,
                ]);
                Artisan::call('db:seed', [
                    '--class' => "Modules\\".$modules_data['identification']."\Database\Seeders\DatabaseSeeder",
                    '--force' => 1,
                ]);

            }catch (\Exception $exception){

            }


            if ($get['return_type'] == 'api') {
                return returnArr(200, $modules_data['name'] . '模块安装成功；');
            } else {
                if ($get['cloud_type'] == \Modules\Main\Models\Modules::Plugin) {
                    return returnArr(200, $modules_data['name'] . '模块安装成功；', ['url' => url('admin/plugin')]);
                } else {
                    return returnArr(200, $modules_data['name'] . '模块安装成功；', ['url' => url('admin/module')]);
                }
            }
        }
        return returnArr(0, '安装失败，重新再试!');
    }

    public function themesInstall() {
        $all = \request()->all();
        if (!$all['m']) {
            return returnArr(0, '请选择主题');
        }
        //判断版本限制

        //安装主题
        $checkData = DB::table("themes")->where("identification","=", $all['m'])->count();
        if( $checkData != 0 ) {
            return returnArr(0, '主题已存在');
        }

        $themeConfig = json_decode(file_get_contents(THEME_PATH . $all['m'] . '/config.json'), true);
        $inster['name'] = $themeConfig['name'];
        $inster['identification'] = $all['m'];
        $inster['preview'] = $themeConfig['preview'];
        $inster['status'] = 2;
        $inster['form'] = $all['form'] ?? "cloud";
        $inster['created_at'] = $inster['updated_at'] = date("Y-m-d H:i:s");
        $res = DB::table("themes")->insert($inster);
        if ($res) {
            return returnArr(200, '主题安装成功', ['url' => url("admin/theme")]);
        }
        return returnArr(0, '主题安装失败');
    }

}
