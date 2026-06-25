<?php

namespace Modules\Main\Http\Controllers\Admin;


use App\Support\Telemetry\StatisticReporter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Modules\Main\Helper\Func;
use Modules\Main\Models\Member;
use Modules\Main\Models\Modules;
use Modules\Main\Services\ServiceModel;
use Modules\ModulesController as Controller;
use function back;
use function env;
use function get_dir_size;
use function getTranslateByKey;
use function redirect;
use function session;
use function storage_path;
use function view;
use const UPLOADPATH;

class IndexController extends Controller {
    //首页
    public function index() {
        //GD库信息
        $gd = [];
        if (function_exists("gd_info")) {
            $gd = gd_info();
            $gdinfo = $gd['GD Version'];
        } else $gdinfo = getTranslateByKey('unknown');

        $freetype = !empty($gd["FreeType Support"]) ? getTranslateByKey('support') : getTranslateByKey('not_supported');
        $allowurl = ini_get("allow_url_fopen") ? getTranslateByKey('support') : getTranslateByKey('not_supported');
        $max_upload = ini_get("file_uploads") ? ini_get("upload_max_filesize") : "Disabled";
        $max_ex_time = ini_get("max_execution_time") . getTranslateByKey('second');
        $memory_limit = get_cfg_var("memory_limit") ? get_cfg_var("memory_limit") : getTranslateByKey('not');
        $version = DB::select("select version() as version")[0]->version;
        $zip = extension_loaded('zip') ? getTranslateByKey('support') : getTranslateByKey('not_supported');

        if (function_exists('shell_exec')) {
            $output = shell_exec('composer --version');
            if (strpos($output, 'Composer version') !== false) {
                $composer = [];
                preg_match('/Composer version (\S+)/', $output, $composer);
                $composer = $composer[0];
            } else {
                $composer = 'Composer 未安装';
            }
        } elseif (function_exists('exec')) {
            $output = exec('composer --version');
            if (strpos($output, 'Composer version') !== false) {
                $composer = [];
                preg_match('/Composer version (\S+)/', $output, $composer);
                $composer = $composer[0];
            } else {
                $composer = 'Composer 未安装';
            }
        } else {
            $composer = '系统函数未开启';
        }

        //已安装的扩展
        $loadedExtensions = implode(",", get_loaded_extensions());
        //被禁用的函数
        $disableFunctions = ini_get("disable_functions");

        //获取统计
        $total_member = Member::query()->where("status", 1)->count();

        //拓展总数
        $total_modules_count = DB::table("modules")->where("cloud_type", \Modules\Main\Models\Modules::Module)->count();
        //插件数量
        $total_plugin_count = DB::table("modules")->where("cloud_type", \Modules\Main\Models\Modules::Plugin)->count();
        //主题数
        $total_theme_count = DB::table("themes")->count();
        $enabledModulesCount = DB::table("modules")
            ->where("cloud_type", \Modules\Main\Models\Modules::Module)
            ->where("status", 1)
            ->count();
        $enabledPluginCount = DB::table("modules")
            ->where("cloud_type", \Modules\Main\Models\Modules::Plugin)
            ->where("status", 1)
            ->count();
        $activeTheme = DB::table("themes")->where("status", 1)->first();
        $historicalEntryList = Func::getHistoricalEntry();

        $requiredExtensions = ['fileinfo', 'mbstring', 'openssl', 'pdo_mysql', 'tokenizer', 'xml', 'xmlwriter', 'zip', 'exif', 'imagick', 'redis', 'json', 'curl'];
        $requiredFunctions = ['fopen', 'mkdir', 'rmdir', 'unlink', 'copy', 'exec', 'passthru', 'shell_exec', 'system', 'popen', 'proc_open', 'pcntl_signal'];
        $disabledFunctionList = array_values(array_filter(array_map('trim', explode(',', (string) $disableFunctions))));
        $missingExtensions = array_values(array_filter($requiredExtensions, function ($extension) {
            return !extension_loaded($extension);
        }));
        $disabledRequiredFunctions = array_values(array_filter($requiredFunctions, function ($function) use ($disabledFunctionList) {
            return in_array($function, $disabledFunctionList, true) || !function_exists($function);
        }));

        $dashboardStats = [
            [
                'label' => '会员数量',
                'value' => $total_member,
                'desc' => '当前启用中的站点会员',
                'icon' => 'icon-users',
                'tone' => 'primary',
                'url' => url('admin/member/user/userList'),
            ],
            [
                'label' => '已装模块',
                'value' => intval($total_modules_count),
                'desc' => '其中启用 ' . intval($enabledModulesCount) . ' 个',
                'icon' => 'icon-grid',
                'tone' => 'indigo',
                'url' => url('admin/module'),
            ],
            [
                'label' => '已装插件',
                'value' => intval($total_plugin_count),
                'desc' => '其中启用 ' . intval($enabledPluginCount) . ' 个',
                'icon' => 'icon-puzzle',
                'tone' => 'success',
                'url' => url('admin/plugin'),
            ],
            [
                'label' => '主题数量',
                'value' => intval($total_theme_count),
                'desc' => '当前主题：' . ($activeTheme->name ?? $activeTheme->identification ?? '未启用'),
                'icon' => 'icon-picture',
                'tone' => 'warning',
                'url' => url('admin/theme'),
            ],
        ];

        $environmentCards = [
            ['label' => 'CMS 版本', 'value' => config("app.app_version"), 'desc' => 'Laravel ' . app()::VERSION],
            ['label' => 'PHP 版本', 'value' => PHP_VERSION, 'desc' => 'MySQL ' . $version],
            ['label' => '上传限制', 'value' => $max_upload, 'desc' => '内存 ' . $memory_limit],
            ['label' => '执行时长', 'value' => $max_ex_time, 'desc' => 'Composer ' . $composer],
        ];

        $healthChecks = [
            ['label' => 'GD 扩展', 'value' => $gdinfo, 'status' => 'ok'],
            ['label' => 'FreeType', 'value' => $freetype, 'status' => $freetype === getTranslateByKey('support') ? 'ok' : 'warn'],
            ['label' => 'allow_url_fopen', 'value' => $allowurl, 'status' => $allowurl === getTranslateByKey('support') ? 'ok' : 'warn'],
            ['label' => 'ZIP 扩展', 'value' => $zip, 'status' => $zip === getTranslateByKey('support') ? 'ok' : 'warn'],
            ['label' => '缺失扩展', 'value' => $missingExtensions ? implode('、', $missingExtensions) : '未发现缺失', 'status' => $missingExtensions ? 'warn' : 'ok'],
            ['label' => '受限函数', 'value' => $disabledRequiredFunctions ? implode('、', $disabledRequiredFunctions) : '未发现受限', 'status' => $disabledRequiredFunctions ? 'warn' : 'ok'],
        ];
        return view("admin/index/index", [
            "gdinfo" => $gdinfo,
            "freetype" => $freetype,
            "allowurl" => $allowurl,
            "max_upload" => $max_upload,
            "max_ex_time" => $max_ex_time,
            "memory_limit" => $memory_limit,
            "zip" => $zip,
            "composer" => $composer,
            "loadedExtensions" => $loadedExtensions,
            "disableFunctions" => $disableFunctions,
            "version" => $version,
            "total_member" => $total_member,
            "total_modules_count" => intval($total_modules_count),
            "total_plugin_count" => intval($total_plugin_count),
            "total_theme_count" => intval($total_theme_count),
            "dashboardStats" => $dashboardStats,
            "historicalEntryList" => $historicalEntryList,
            "environmentCards" => $environmentCards,
            "healthChecks" => $healthChecks,
            "missingExtensions" => $missingExtensions,
            "disabledRequiredFunctions" => $disabledRequiredFunctions,
            "requiredExtensions" => $requiredExtensions,
            "requiredFunctions" => $requiredFunctions,
            "activeTheme" => $activeTheme,
        ]);
    }


    //清空缓存
    public function clearCache() {
        //var_dump(Artisan::call("view:clear")); php artisan optimize:clear composer dump-autoload --optimize
        try {
            $mainHistoricalEntryList = Func::getHistoricalEntry();

            //PHP7.0不报错
            Artisan::call("view:clear");
            Artisan::call("cache:clear");
            Artisan::call("config:clear");
            Artisan::call("route:clear");
            Artisan::call("event:clear");
            Artisan::call("clear-compiled");

            //php artisan storage:link --force --no-ansi
            Artisan::call("storage:link --force --no-ansi");
            //重新生成缓存，判断运行模式（是否开启调式模式）
            if (!config("app.debug")) { //关闭调式模式，需要重新生成
//                 Artisan::call("config:cache");
//               Artisan::call("route:cache");
//                 Artisan::call("package:discover --ansi");
//                 Artisan::call("event:cache");
//               Artisan::call("optimize");
            }

            Func::saveHistoricalEntry(2, $mainHistoricalEntryList);
            return ["status" => 200, "msg" => "清理成功"];
        } catch (\Exception $exception) {
            return ["status" => 0, "msg" => $exception->getMessage() ?: "清理失败"];
        }
    }

    //更换语言包
    public function changeLang(Request $request) {
        $langList = ServiceModel::getLangList();
        $lang = $langList[$request->lang] ? $request->lang : 'zh-CN';
        $array = array(
            "icon" => UPLOADPATH . '',
            "shortcode" => $lang,
            "name" => $langList[$lang]
        );
        session()->put("admin_current_language", $array);
        return back();
    }

    public function entryModule(Request $request) {
        try {
            $url = hook('GetEntryModuleUrl', ['moduleName' => $request->m])[0];
        } catch (\Exception $exception) {
            return back()->with('pageDataMsg', $exception->getMessage())->with('pageDataStatus', '0');
        }
        $moduleData = ServiceModel::apiGetOne(Modules::TABLE_NAME, ['identification' => $request->m]);
        Func::saveHistoricalEntry(1, [
            'module' => $request->m,
            'url' => url('admin/entryModule?m=' . $request->m),
            'name' => $moduleData['name'] ?? $request->m,
        ]);
        StatisticReporter::reportSuccess('Usage', $request->m, Modules::Module, [
            'entry' => 'admin_entry_module',
        ]);
        return redirect($url);
    }
}
