<?php

namespace Modules\Main\Http\Controllers\Admin;

use App\Support\PackageManifest\CompatibilityChecker;
use App\Support\PackageManifest\LifecyclePolicy;
use App\Support\PackageManifest\PackageManifest;
use App\Support\Telemetry\StatisticReporter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Modules\Formtools\Models\FormModel;
use Modules\Main\Models\Modules;
use Modules\ModulesController as Controller;

class FuncController extends Controller {

    private const LEVEL_LABELS = [
        PackageManifest::LEVEL_CORE => '核心层',
        PackageManifest::LEVEL_BASE => '基础层',
        PackageManifest::LEVEL_OPTIONAL => '可选层',
        PackageManifest::LEVEL_BUSINESS => '业务层',
        PackageManifest::LEVEL_EXTENSION => '扩展层',
    ];

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
            $deletePath = modules_base_path($all['m']);
            $url = url("admin/module");
        }else if($all['cloud_type']=="plugin"){
            $deletePath = plugins_base_path($all['m']);
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
        $local_modules = $content = getDirContent(modules_base_path()) ?: [];

        //获取已安装的module
        $dataList = Modules::query()
            ->where("cloud_type",'module')
            ;
        $dataList = $dataList->orderBy("order","desc")
            ->orderBy("id","desc")
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
            $config = PackageManifest::load($value['identification'], Modules::Module);
            if ($config) {
                $modules_install_datas[$key] = $this->decorateInstalledPackage(array_merge($value, $config), Modules::Module);
            } else {
                unset($modules_install_datas[$key]);
            }
        }

        //获取未安装的配置信息
        $modules_not_install_datas = [];
        foreach ($content as $k => $v) {
            $configArr = PackageManifest::load($v, Modules::Module);
            if ($configArr) {
                if ($configArr['identification'] && !in_array($configArr['identification'], ['Main', 'Install'])) {
                    $modules_not_install_datas[$v] = $this->decorateLocalPackage($configArr);
                }
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
        $local_plugin = getDirContent(plugins_base_path()) ?: [];
        $dataList = Modules::query()
            ->where("cloud_type",'plugin')
            ->orderBy("order","desc")
            ->orderBy("id","desc")
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
            $config = PackageManifest::load($value['identification'], Modules::Plugin);
            if ($config) {
                $plugin_install_datas[$key] = $this->decorateInstalledPackage(array_merge($value, $config), Modules::Plugin);
            } else {
                unset($plugin_install_datas[$key]);
            }
        }

        foreach ($local_plugin as $k => $value) {
            $manifest = PackageManifest::load($value, Modules::Plugin);
            if ($manifest) {
                $plugin_not_install_datas[$k] = $this->decorateLocalPackage($manifest);
            }
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
        $themeQuery = DB::table('themes');
        if (\Illuminate\Support\Facades\Schema::hasTable('themes') && \Illuminate\Support\Facades\Schema::hasColumn('themes', 'order')) {
            $themeQuery->orderBy('order', 'desc');
        }
        $themes_install_datas = $themeQuery->orderBy("status", "asc")
            ->orderBy('id', 'desc')
            ->get()
            ->toArray();
        $themes_not_install_datas = [];
        //判断未安装的模块
        foreach ($themes_install_datas as $data) {
            $k = array_search($data->identification, $local_themes);
            unset($local_themes[$k]);
        }
        foreach ($themes_install_datas as $key => $value) {
            $manifest = PackageManifest::load($value->identification, Modules::Theme);
            if (!$manifest) {
                unset($themes_install_datas[$key]);
                continue;
            }
            $themes_install_datas[$key] = $this->decorateInstalledTheme($value, $manifest);
        }

        foreach ($local_themes as $k => $value) {
            $manifest = PackageManifest::load($value, Modules::Theme);
            if ($manifest) {
                $themes_not_install_datas[$k] = (object) $this->decorateInstallablePackage($manifest);
            }
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
            StatisticReporter::reportBlocked('EnableBlocked', '', \Modules\Main\Models\Modules::Theme, 'identification_required');
            return back()->with('errormsg', '请选择主题');
        }
        //安装主题
        $checkData = DB::table("themes")->where("identification", $all['m'])->first();
        if (!$checkData) {
            StatisticReporter::reportBlocked('EnableBlocked', $all['m'], \Modules\Main\Models\Modules::Theme, 'not_installed');
            return back()->with('errormsg', '主题不存在！');
        }
        DB::table("themes")->update(['status' => 2]);
        $themeConfig['status'] = 1;
        $themeConfig['updated_at'] = date("Y-m-d H:i:s");
        $res = DB::table("themes")->where("identification", $all['m'])->update($themeConfig);
        if ($res) {
            cache()->forever('theme', $all['m']);
            StatisticReporter::reportSuccess('Enable', $all['m'], \Modules\Main\Models\Modules::Theme);
            return redirect("admin/theme")->with('successmsg', '主题启用成功！');
        }
        return back()->with("errormsg", "主题启用失败！");
    }

    function changeThemeTop(Request $request)
    {
        $get = $request->all();
        $get['m'] = isset($get['m']) ? trim((string) $get['m']) : '';
        $get['top'] = isset($get['top']) ? intval($get['top']) : 1;
        if ($get['m'] === '') {
            return back()->with('errormsg', '主题标识为必传项');
        }
        if (!\Illuminate\Support\Facades\Schema::hasTable('themes') || !\Illuminate\Support\Facades\Schema::hasColumn('themes', 'order')) {
            return back()->with('errormsg', '当前数据库不支持主题置顶，请先执行升级迁移');
        }

        $theme = DB::table('themes')->where('identification', $get['m'])->first();
        if (!$theme) {
            return back()->with('errormsg', '主题不存在！');
        }

        $order = $get['top'] === 1 ? 9999 : 0;
        $updated = DB::table('themes')->where('identification', $get['m'])->update([
            'order' => $order,
            'updated_at' => date('Y-m-d H:i:s'),
        ]);
        if ($updated) {
            return back()->with('successmsg', $get['top'] === 1 ? '已置顶' : '已取消置顶');
        }
        return back()->with('errormsg', '操作失败，重新再试!');
    }

    function setting() {
        $themeIdentification = request()->query('m', 'default');
        StatisticReporter::reportSuccess('Usage', $themeIdentification, \Modules\Main\Models\Modules::Theme, [
            'entry' => 'admin_theme_setting',
        ]);
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
        if (!$get['m']) {
            StatisticReporter::reportBlocked('StatusChangeBlocked', '', $get['cloud_type'], 'identification_required');
            return back()->with('errormsg', '标识为必传项');
        }

        //判断是否安装
        $module = new \Modules\Main\Models\Modules();
        $find = $module->where('identification', '=', $get['m'])->where('cloud_type', '=', $get['cloud_type'])->first();
        if (!$find) {
            StatisticReporter::reportBlocked('StatusChangeBlocked', $get['m'], $get['cloud_type'], 'not_installed');
            return back()->with('errormsg', '未安装!');
        }
        if ((int) $get['status'] !== 1) {
            $manifest = PackageManifest::load($get['m'], $get['cloud_type'])
                ?: PackageManifest::normalize(['identification' => $get['m'], 'name' => $get['m']], $get['cloud_type'], $get['m']);
            $disablePolicy = LifecyclePolicy::canDisable($manifest);
            if (!$disablePolicy['allowed']) {
                StatisticReporter::reportBlocked('DisableBlocked', $get['m'], $get['cloud_type'], $disablePolicy['message']);
                return back()->with('errormsg', $disablePolicy['message']);
            }
            $dependents = CompatibilityChecker::findDependents($manifest);
            if (!empty($dependents)) {
                StatisticReporter::reportBlocked('DisableBlocked', $get['m'], $get['cloud_type'], 'has_dependents', [
                    'dependents' => implode(',', $dependents),
                ]);
                return back()->with('errormsg', '当前包已被依赖，无法禁用：' . implode('、', $dependents));
            }
        }
        $res = $module->where('identification', '=', $get['m'])->where('cloud_type', '=', $get['cloud_type'])->update(['status' => $get['status']]);
        if ($res) {
            StatisticReporter::reportSuccess((int) $get['status'] === 1 ? 'Enable' : 'Disable', $get['m'], $get['cloud_type']);
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

    function changeTop(Request $request)
    {
        $get = $request->all();
        $get['m'] = isset($get['m']) ? trim((string) $get['m']) : '';
        $get['top'] = isset($get['top']) ? intval($get['top']) : 1;
        $get['cloud_type'] = isset($get['cloud_type']) ? trim((string) $get['cloud_type']) : \Modules\Main\Models\Modules::Module;
        if ($get['m'] === '') {
            return back()->with('errormsg', '模块标识为必传项');
        }
        if (!in_array($get['cloud_type'], [\Modules\Main\Models\Modules::Module, \Modules\Main\Models\Modules::Plugin], true)) {
            return back()->with('errormsg', 'cloud_type 参数错误');
        }

        $module = new \Modules\Main\Models\Modules();
        $res = $module->where('identification', '=', $get['m'])
            ->where('cloud_type', $get['cloud_type'])
            ->first();
        if (!$res) {
            return back()->with('errormsg', '模块未安装!');
        }

        $order = $get['top'] === 1 ? 9999 : 0;
        $updated = $module->where('identification', '=', $get['m'])
            ->where('cloud_type', $get['cloud_type'])
            ->update(['order' => $order]);

        if ($updated) {
            return back()->with('successmsg', $get['top'] === 1 ? '已置顶' : '已取消置顶');
        }

        return back()->with('errormsg', '操作失败，重新再试!');
    }

    //下载模块
    function onlineCloudList() {
        if (cacheGlobalSettingsByKey('use_of_cloud') != 1) return back();
        $all = request()->all();
        $all['cloud_type'] = trim((string) ($all['cloud_type'] ?? 'module')) ?: 'module';
        $all['index'] = max(0, intval($all['index'] ?? 0));
        switch ($all['cloud_type']) {
            case 'module':
                $view = 'admin/online/module';
                $local_data = getDirContent(modules_base_path()) ?: [];
                foreach ($local_data as $k => $v) {
                    $config = PackageManifest::load($v, Modules::Module);
                    if ($config) {
                        $local_data[$v] = [
                            "version" => $config['version']
                        ];
                    }
                    unset($local_data[$k]);
                }
                break;
            case 'plugin':
                $local_data = getDirContent(plugins_base_path()) ?: [];
                foreach ($local_data as $k => $v) {
                    $config = PackageManifest::load($v, Modules::Plugin);
                    if ($config) {
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
                        $local_data[$v] = [
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
            'cate_pid' => intval($all['cate_pid'] ?? 0),
            'cate_id' => intval($all['cate_id'] ?? 0),
            'platform' => trim((string) ($all['platform'] ?? '')),
            'isfree' => trim((string) ($all['isfree'] ?? '')),
            'sort' => trim((string) ($all['sort'] ?? '')),
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
        if (!empty($listDatas['list']['data']) && is_array($listDatas['list']['data'])) {
            foreach ($listDatas['list']['data'] as $index => $item) {
                $listDatas['list']['data'][$index] = $this->decorateCloudPackage($item, $all['cloud_type']);
            }
        }
        StatisticReporter::reportSuccess('Usage', ($all['cloud_type'] ?? 'unknown') . '-market', $all['cloud_type'] ?? 'unknown', [
            'entry' => 'admin_online_cloud',
            'page' => $params['page'],
            'limit' => $params['limit'],
            'search' => $params['search'],
            'order' => $params['order'],
            'by' => $params['by'],
        ]);

        return view($view, [
            'listDatas' => $listDatas,
            'local_data' => $local_data,
            'currentFilters' => $all,
        ]);
    }

    private function decorateInstalledPackage(array $package, string $packageType): array
    {
        $disablePolicy = LifecyclePolicy::canDisable($package);
        $uninstallPolicy = LifecyclePolicy::canUninstall($package);
        $dependents = CompatibilityChecker::findDependents($package);
        $dependencyNames = collect($package['dependencies'] ?? [])
            ->map(function ($dependency) {
                $type = ($dependency['type'] ?? '') === Modules::Plugin ? '插件' : '模块';
                $name = $dependency['name'] ?? '';
                $version = $dependency['version'] ?? '*';
                return trim($type . ':' . $name . ($version && $version !== '*' ? ' ' . $version : ''));
            })
            ->filter()
            ->values()
            ->toArray();

        $disableReason = '';
        if (!$disablePolicy['allowed']) {
            $disableReason = $disablePolicy['message'];
        } elseif (!empty($dependents)) {
            $disableReason = '已被依赖：' . implode('、', $dependents);
        }

        $uninstallReason = '';
        if (!$uninstallPolicy['allowed']) {
            $uninstallReason = $uninstallPolicy['message'];
        } elseif (!empty($dependents)) {
            $uninstallReason = '已被依赖：' . implode('、', $dependents);
        }

        $package['level_label'] = self::LEVEL_LABELS[$package['level'] ?? ''] ?? '未分级';
        $package['disable_allowed'] = $disableReason === '';
        $package['disable_reason'] = $disableReason;
        $package['uninstall_allowed'] = $uninstallReason === '';
        $package['uninstall_reason'] = $uninstallReason;
        $package['dependents'] = $dependents;
        $package['dependency_names'] = $dependencyNames;
        $package['compatibility_summary'] = $this->buildCompatibilitySummary($package);
        $package['package_type_label'] = $packageType === Modules::Plugin
            ? '插件'
            : (($package['type'] ?? '') === 'system' ? '内置模块' : '功能模块');

        return $package;
    }

    private function decorateLocalPackage(array $package): array
    {
        $package['level_label'] = self::LEVEL_LABELS[$package['level'] ?? ''] ?? '未分级';
        $package['dependency_names'] = collect($package['dependencies'] ?? [])
            ->map(function ($dependency) {
                $type = ($dependency['type'] ?? '') === Modules::Plugin ? '插件' : '模块';
                $name = $dependency['name'] ?? '';
                $version = $dependency['version'] ?? '*';
                return trim($type . ':' . $name . ($version && $version !== '*' ? ' ' . $version : ''));
            })
            ->filter()
            ->values()
            ->toArray();
        $package['compatibility_summary'] = $this->buildCompatibilitySummary($package);
        $package = $this->decorateInstallablePackage($package);

        return $package;
    }

    private function decorateInstallablePackage(array $package): array
    {
        $installReason = CompatibilityChecker::checkInstallable($package);

        $package['install_allowed'] = $installReason === null;
        $package['install_reason'] = $installReason ?: '';

        return $package;
    }

    private function decorateInstalledTheme(object $theme, array $manifest): object
    {
        $theme->name = $manifest['name'] ?? $theme->identification;
        $theme->description = $manifest['description'] ?? '';
        $theme->author = $manifest['author'] ?? '';
        $theme->version = $manifest['version'] ?? '';
        $theme->preview = $manifest['preview'] ?? ($theme->preview ?? 'preview.png');
        $theme->level = $manifest['level'] ?? PackageManifest::LEVEL_EXTENSION;
        $theme->level_label = self::LEVEL_LABELS[$theme->level] ?? '未分级';
        $theme->dependency_names = collect($manifest['dependencies'] ?? [])
            ->map(function ($dependency) {
                $type = ($dependency['type'] ?? '') === Modules::Plugin ? '插件' : '模块';
                $name = $dependency['name'] ?? '';
                $version = $dependency['version'] ?? '*';
                return trim($type . ':' . $name . ($version && $version !== '*' ? ' ' . $version : ''));
            })
            ->filter()
            ->values()
            ->toArray();
        $theme->compatibility_summary = $this->buildCompatibilitySummary($manifest);

        $uninstallPolicy = LifecyclePolicy::canUninstall($manifest);
        $theme->uninstall_allowed = $uninstallPolicy['allowed'] && (int) $theme->status !== 1;
        $theme->uninstall_reason = !$uninstallPolicy['allowed']
            ? $uninstallPolicy['message']
            : ((int) $theme->status === 1 ? '当前主题正在使用中，不能卸载' : '');

        return $theme;
    }


    private function decorateCloudPackage(array $package, string $packageType): array
    {
        $normalized = PackageManifest::normalize($package, $packageType, $package['identification'] ?? '');
        $normalized['level_label'] = self::LEVEL_LABELS[$normalized['level'] ?? ''] ?? '未分级';
        $normalized['compatibility_summary'] = $this->buildCompatibilitySummary($normalized);
        $normalized['dependency_names'] = collect($normalized['dependencies'] ?? [])
            ->map(function ($dependency) {
                $type = ($dependency['type'] ?? '') === Modules::Plugin ? '插件' : '模块';
                $name = $dependency['name'] ?? '';
                $version = $dependency['version'] ?? '*';
                return trim($type . ':' . $name . ($version && $version !== '*' ? ' ' . $version : ''));
            })
            ->filter()
            ->values()
            ->toArray();
        $normalized = $this->decorateInstallablePackage($normalized);
        $normalized = $this->decorateCloudDownloadPrompt($normalized);

        return $normalized;
    }

    private function decorateCloudDownloadPrompt(array $package): array
    {
        $installType = intval($package['install_type'] ?? 1);
        $price = floatval($package['price'] ?? 0);
        $prompt = trim((string) ($package['download_prompt'] ?? ''));

        $defaultPrompt = '';
        $promptTag = '';
        if ($installType === 2) {
            $promptTag = '私有化部署';
            $defaultPrompt = '该应用采用私有化部署方式交付，请先联系开发者确认授权、交付内容、部署环境和售后支持。';
        } elseif ($price > 0) {
            $promptTag = '收费资源';
            $defaultPrompt = '该应用为收费资源，请先联系开发者完成购买或授权，再继续下载和安装。';
        } elseif ($prompt !== '') {
            $promptTag = '下载提示';
        }

        if ($prompt === '' && $defaultPrompt !== '') {
            $prompt = '<p>' . e($defaultPrompt) . '</p>';
        }

        $package['download_prompt_html'] = $prompt;
        $package['download_prompt_title'] = $promptTag ?: '下载提示';
        $package['should_prompt_before_install'] = $prompt !== '';
        $package['prompt_allows_continue_download'] = $prompt !== '' && $installType !== 2 && $price <= 0;
        $package['prompt_requires_license_check'] = $prompt !== '' && $installType !== 2 && $price > 0;
        $package['prompt_continue_button_text'] = $price > 0 ? '校验授权后下载' : '继续下载';

        return $package;
    }
    private function buildCompatibilitySummary(array $package): string
    {
        $compatibility = $package['compatibility'] ?? [];
        $parts = [];
        foreach (['cms' => 'CMS', 'php' => 'PHP', 'laravel' => 'Laravel'] as $key => $label) {
            $value = $compatibility[$key] ?? '*';
            if ($value !== '' && $value !== '*') {
                $parts[] = $label . ':' . $value;
            }
        }

        return implode(' | ', $parts);
    }
}
