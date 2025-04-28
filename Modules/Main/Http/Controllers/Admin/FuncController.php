<?php

namespace Modules\Main\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Modules\Formtools\Models\FormModel;
use Modules\Main\Models\Modules;
use Modules\ModulesController as Controller;

class FuncController extends Controller {

    function delete()
    {
        $all = $this->request->all();
        if($all['cloud_type']=="module" || $all['cloud_type']=="plugin"){
            $check = Modules::query()
                ->where("cloud_type",$all['cloud_type'])
                ->where("identification",$all['m'])
                ->count();
        }elseif ($all['cloud_type']=="theme"){
            $check = DB::table("themes")->where("identification", $all['m'])->count();
        }
        if($check){
            return back()->with('errormsg','此标识不能直接删除');
        }
        if($all['cloud_type']=="module"){
            $deletePath = MODULE_PATH . '/' . $all['m'];
            $url = url("admin/module");
        }else if($all['cloud_type']=="plugin"){
            $deletePath = PLUGIN_PATH . '/' . $all['m'];
            $url = url("admin/plugin");
        }else if ($all['cloud_type']=="theme"){
            $deletePath = THEME_PATH . '/' . $all['m'];
            $url = url("admin/theme");
        }

        del_dir_files($deletePath);

        return redirect($url)->with('successmsg', "删除成功!");

    }

    //模块列表
    function module(Request $request) {
        $get = $request->all();
        //获取模块下的模块文件夹和配置
        $local_modules = $content = getDirContent(MODULE_PATH) ?: [];

        //获取已安装的module
        $dataList = Modules::query()
            ->where("cloud_type",'module')
            ;
        $dataList = $dataList->orderBy("order","desc")
            ->orderBy("updated_at","desc")
            ->get()
            ->toArray();
        foreach ($dataList as $module) {
            $modules_install_datas[$module['identification']] = $module;
        }

        //判断未安装的模块
        foreach ($modules_install_datas as $key => $value) {
            if (in_array($value['identification'], $local_modules)) {
                foreach ($content as $k => $v) {
                    if ($v == $value['identification']) {
                        unset($content[$k]); //移除已安装的key
                    }
                }
            }
            if ($get['type'] && $get['type'] != $value['type']) {
                unset($modules_install_datas[$key]);
            }

            if ($get['keyword'] && !strstr($value['name'], $get['keyword'])) {
                unset($modules_install_datas[$key]);
            }
        }
        foreach ($modules_install_datas as $key => $value) {
            if (file_exists(MODULE_PATH . $value['identification'] . '/Config/config.php')) {
                $config = include MODULE_PATH . $value['identification'] . '/Config/config.php';
                $modules_install_datas[$key] = array_merge($value, $config);
            } else {
                unset($modules_install_datas[$key]);
            }
        }

        //获取未安装的配置信息
        $modules_not_install_datas = [];
        foreach ($content as $k => $v) {
            $file = MODULE_PATH . $v . '/Config/config.php';
            if (file_exists($file)) {
                $configArr = include($file);
                if ($configArr['identification'] && !in_array($configArr['identification'], ['Main', 'Install'])) $modules_not_install_datas[$v] = include($file);
            }
        }


        return view('admin/func/feature', [
            'modules_install_datas' => $modules_install_datas,
            'modules_not_install_datas' => $modules_not_install_datas,
            'local_modules' => $local_modules
        ]);
    }

    //插件管理
    function plugin() {

        //获取模块下的模块文件夹和配置
        $local_plugin = getDirContent(PLUGIN_PATH) ?: [];
        $dataList = Modules::query()
            ->where("cloud_type",'plugin')
            ->orderBy("updated_at","desc")
            ->get()
            ->toArray();
        foreach ($dataList as $module) {
            $plugin_install_datas[$module['identification']] = $module;
        }
//        $plugin_install_datas = cache()->get(\Mxzcms\Modules\cache\CacheKey::PluginsActive);
        $plugin_not_install_datas = [];
        //判断未安装的模块
        foreach ($plugin_install_datas as $data) {
            $k = array_search($data['identification'], $local_plugin);
            unset($local_plugin[$k]);
        }
        foreach ($plugin_install_datas as $key => $value) {
            if (file_exists(PLUGIN_PATH . $value['identification'] . '/Config/config.php')) {
                $config = include PLUGIN_PATH . $value['identification'] . '/Config/config.php';
                $plugin_install_datas[$key] = array_merge($value, $config);
            } else {
                unset($plugin_install_datas[$key]);
            }
        }

        foreach ($local_plugin as $k => $value) {
            $plugin_not_install_datas[$k] = include PLUGIN_PATH . $value . '/Config/config.php';
        }
        return view('admin/func/plugin', [
            'plugin_install_datas' => $plugin_install_datas,
            'plugin_not_install_datas' => $plugin_not_install_datas,
            'local_modules' => []
        ]);
    }

    function theme() {
        //获取模块下的模块文件夹和配置
        $local_themes = getDirContent(THEME_PATH) ?: [];
        $themes_install_datas = DB::table('themes')
            ->orderBy("status", "asc")
            ->get()
            ->toArray();
        $themes_not_install_datas = [];
        //判断未安装的模块
        foreach ($themes_install_datas as $data) {
            $k = array_search($data->identification, $local_themes);
            unset($local_themes[$k]);
        }
        foreach ($themes_install_datas as $key => $value) {
            $config = json_decode(file_get_contents(THEME_PATH . $value->identification . '/config.json'), true);
            $value->name = $config['name'];
            $value->description = $config['description'];
            $value->author = $config['author'];
            $value->version = $config['version'];
            $themes_install_datas[$key] = $value;
        }

        foreach ($local_themes as $k => $value) {
            $themes_not_install_datas[$k] = json_decode(file_get_contents(THEME_PATH . $value . '/config.json'));
        }
        return view('admin/func/theme', [
            'themes_install_datas' => $themes_install_datas,
            'themes_not_install_datas' => $themes_not_install_datas,
            'local_themes' => []
        ]);
    }

    //主题安装
    function themeInstall() {
        $request = \request();
        try {
            $res = hook('Install', ['moduleName' => 'System', 'cloud_type' => \Modules\Main\Models\Modules::Theme])[0];
        } catch (\Exception $exception) {
            $res = returnArr(0, $exception->getMessage());
        }
        if ($res['data']['url']) {
            return redirect($res['data']['url'])->with('successmsg', $res['msg']);
        } else {
            if ($request->return_type == 'api') {
                return $res;
            } else {
                return back()->with($res['status'] == 200 ? 'successmsg' : 'errormsg', $res['msg']);
            }
        }
    }

    //主题卸载
    function themeUninstall() {
        $request = \request();
        try {
            $res = hook('UnInstall', ['moduleName' => 'System', 'cloud_type' => \Modules\Main\Models\Modules::Theme])[0];
        } catch (\Exception $exception) {
            $res = returnArr(0, $exception->getMessage());
        }
        if ($res['data']['url']) {
            return redirect($res['data']['url'])->with('successmsg', $res['msg']);
        } else {
            if ($request->return_type == 'api') {
                return $res;
            } else {
                return back()->with($res['status'] == 200 ? 'successmsg' : 'errormsg', $res['msg']);
            }
        }
    }

    function changeThemeStatus() {
        $all = $this->request->all();
        if (!$all['m']) {
            return back()->with('errormsg', '请选择主题');
        }
        //安装主题
        $checkData = DB::table("themes")->where("identification", $all['m'])->first();
        if (!$checkData) {
            return back()->with('errormsg', '主题不存在！');
        }
        DB::table("themes")->update(['status' => 2]);
        $themeConfig['status'] = 1;
        $themeConfig['updated_at'] = date("Y-m-d H:i:s");
        $res = DB::table("themes")->where("identification", $all['m'])->update($themeConfig);
        if ($res) {
            cache()->forever('theme', $all['m']);
            return redirect("admin/theme")->with('successmsg', '主题启用成功！');
        }
        return back()->with("errormsg", "主题启用失败！");
    }

    function setting() {

        return view('admin/func/themeSetting', []);
    }

    function preview() {
        $all = $this->request->all();
        $topMenu = hook('GetHomeMenu', ['moduleName' => 'System', 'position' => 'top'])[0];
        $bottomMenu = hook('GetHomeMenu', ['moduleName' => 'System', 'position' => 'bottom'])[0];
        $footerMenu = hook('GetHomeMenu', ['moduleName' => 'System', 'position' => 'footer'])[0];
        $models = FormModel::query()->where("show_home_page", "yes")->get();
        return view('themes.' . $all['m'] . '.index.index', [
            'homeMenu' => [
                'topMenu' => $topMenu,
                'bottomMenu' => $bottomMenu,
                'footerMenu' => $footerMenu,
            ],
            'models' => $models
        ]);
    }

    //模块安装
    function install(Request $request) {
        try {
            $res = hook('Install', ['moduleName' => 'System', 'cloud_type' => \Modules\Main\Models\Modules::Module])[0];
        } catch (\Exception $exception) {
            $res = returnArr(0, $exception->getMessage());
        }
        if ($res['data']['url']) {
            return redirect($res['data']['url'])->with('successmsg', $res['msg']);
        } else {
            if ($request->return_type == 'api') {
                return $res;
            } else {
                return back()->with($res['status'] == 200 ? 'successmsg' : 'errormsg', $res['msg']);
            }
        }
    }

    //卸载
    function uninstall(Request $request) {
        try {
            $res = hook('UnInstall', ['moduleName' => 'System', 'cloud_type' => \Modules\Main\Models\Modules::Module])[0];
        } catch (\Exception $exception) {
            $res = returnArr(0, $exception->getMessage());
        }
        if ($res['data']['url']) {
            return redirect($res['data']['url'])->with('successmsg', $res['msg']);
        } else {
            if ($request->return_type == 'api') {
                return $res;
            } else {
                return back()->with($res['status'] == 200 ? 'successmsg' : 'errormsg', $res['msg']);
            }
        }
    }

    //模块的启用与禁用操作流程处理
    function changeStatus(Request $request) {
        $get = $request->all();
        $get['m'] = isset($get['m']) ? $get['m'] : '';
        $get['status'] = isset($get['status']) ? $get['status'] : 1;
        $get['cloud_type'] = isset($get['cloud_type']) ? $get['cloud_type'] : '';
        if (!$get['m']) return back()->with('errormsg', '标识为必传项');

        //判断是否安装
        $module = new \Modules\Main\Models\Modules();
        $find = $module->where('identification', '=', $get['m'])->where('cloud_type', '=', $get['cloud_type'])->first();
        if (!$find) return back()->with('errormsg', '未安装!');
        $res = $module->where('identification', '=', $get['m'])->where('cloud_type', '=', $get['cloud_type'])->update(['status' => $get['status']]);
        if ($res) {
            if ($get['cloud_type'] == \Modules\Main\Models\Modules::Plugin) {
                Cache::put(\Mxzcms\Modules\cache\CacheKey::PluginsActive,
                    array_merge(\cache(\Mxzcms\Modules\cache\CacheKey::PluginsActive),
                        array(strtolower($get['m'])=>
                            array_merge(\cache(\Mxzcms\Modules\cache\CacheKey::PluginsActive)[strtolower($get['m'])],
                            ),["status" => $get['status']])
                    ),86400
                );
                return redirect(url('admin/plugin'))->with('successmsg', '操作成功!');
            } else {
                Cache::put(\Mxzcms\Modules\cache\CacheKey::ModulesActive,
                    array_merge(\cache(\Mxzcms\Modules\cache\CacheKey::ModulesActive),
                        array(strtolower($get['m'])=>
                            array_merge(\cache(\Mxzcms\Modules\cache\CacheKey::ModulesActive)[strtolower($get['m'])],
                        ),["status" => $get['status']])
                    ),86400
                );
                return redirect(url('admin/module'))->with('successmsg', '操作成功!');
            }
        } else {
            return back()->with('errormsg', '操作失败，重新再试!');
        }
    }

    //是否设置为网站首页模块
    function changeIndex(Request $request) {
        $get = $request->all();
        $get['m'] = isset($get['m']) ? $get['m'] : '';
        $get['is_index'] = isset($get['is_index']) ? $get['is_index'] : 1;
        if (!$get['m']) return back()->with('errormsg', '模块标识为必传项');
        if(cacheGlobalSettingsByKey('moduleHomeLock') =='lock'){
            return back()->with('errormsg', '操作失败，请先取消模块首页锁定!');
        }
        //判断是否安装
        $module = new \Modules\Main\Models\Modules();
        $res = $module->where('identification', '=', $get['m'])->where('cloud_type', \Modules\Main\Models\Modules::Module)->first();
        if (!$res) return back()->with('errormsg', '模块未安装!');
        $module->where('id', '>', 0)->where('identification', '<>', $get['m'])->where('cloud_type', \Modules\Main\Models\Modules::Module)->update(['is_index' => 0]);
        $res = $module->where('identification', '=', $get['m'])->where('cloud_type', \Modules\Main\Models\Modules::Module)->update(['is_index' => $get['is_index']]);
        if ($res) return back()->with('successmsg', '操作成功!');
        else return back()->with('errormsg', '操作失败，重新再试!');
    }

    function changeBack(Request $request) {
        $get = $request->all();
        $get['m'] = isset($get['m']) ? $get['m'] : '';
        $get['is_backend'] = isset($get['is_backend']) ? $get['is_backend'] : 1;
        if (!$get['m']) return back()->with('errormsg', '模块标识为必传项');
        //判断是否安装
        $module = new \Modules\Main\Models\Modules();
        $res = $module->where('identification', '=', $get['m'])->where('cloud_type', \Modules\Main\Models\Modules::Module)->first();
        if (!$res) return back()->with('errormsg', '模块未安装!');
        $module->where('id', '>', 0)->where('identification', '<>', $get['m'])->where('cloud_type', \Modules\Main\Models\Modules::Module)->update(['is_backend' => 0]);
        $res = $module->where('identification', '=', $get['m'])->where('cloud_type', \Modules\Main\Models\Modules::Module)->update(['is_backend' => $get['is_backend']]);
        if ($res) return back()->with('successmsg', '操作成功!');
        else return back()->with('errormsg', '操作失败，重新再试!');
    }

    //下载模块
    function onlineCloudList() {
        if (cacheGlobalSettingsByKey('use_of_cloud') != 1) return back();
        $all = request()->all();
        switch ($all['cloud_type']) {
            case 'module':
                $view = 'admin/online/module';
                $local_data = getDirContent(MODULE_PATH) ?: [];
                foreach ($local_data as $k => $v) {
                    if (file_exists(MODULE_PATH . $v . '/Config/config.php')) {
                        $config = include MODULE_PATH . $v . '/Config/config.php';
                        $local_data[$v] = [
                            "version" => $config['version']
                        ];
                    }
                    unset($local_data[$k]);
                }
                break;
            case 'plugin':
                $local_data = getDirContent(PLUGIN_PATH) ?: [];
                foreach ($local_data as $k => $v) {
                    if (file_exists(PLUGIN_PATH . $v . '/Config/config.php')) {
                        $config = include PLUGIN_PATH . $v . '/Config/config.php';
                        $local_data[$v] = [
                            "version" => $config['version']
                        ];
                    }
                    unset($local_data[$k]);
                }
                $view = 'admin/online/plugin';
                break;
            case 'theme':
                $local_data = getDirContent(public_path("views/themes")) ?: [];
                foreach ($local_data as $k => $v) {
                    if (file_exists(public_path("views/themes") . "/" . $v . '/config.json')) {
                        $config = json_decode(file_get_contents(public_path("views/themes") . "/" . $v . '/config.json'), true);
                        $local_data[ucfirst($v)] = [
                            "version" => $config['version']
                        ];
                    }
                    unset($local_data[$k]);
                }
                $view = 'admin/online/theme';
                break;
            default:
                returnArr(0, '参数错误');
        }
        $params = [
            'cloudtype' => $all['cloud_type'],
            'action' => 'get-app-list',
            'page' => $all['page'] ?: 1,
            'limit' => $all['limit'] ?: 10,
            'search' => $all['search'] ?: '',
            'order' => $all['order'] ?: 'id',
            'by' => $all['by'] ?: 'desc'
        ];

        $UPDATECMS = new \Modules\Main\Libs\UPDATECMS();
        $res = $UPDATECMS->appAction($params);
        $res = json_decode($res, true);
        if ($res['status'] != 200) {
            return back()->with('errormsg', "获取云端数据失败");
        }

        $listDatas = $res['data'];

        return view($view, [
            'listDatas' => $listDatas,
            'local_data' => $local_data
        ]);
    }
}
