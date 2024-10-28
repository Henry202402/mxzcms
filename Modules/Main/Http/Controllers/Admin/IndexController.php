<?php

namespace Modules\Main\Http\Controllers\Admin;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Modules\Main\Models\Member;
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
        if (function_exists("gd_info")) {
            $gd = gd_info();
            $gdinfo = $gd['GD Version'];
        } else $gdinfo = getTranslateByKey('unknown');

        $freetype = $gd["FreeType Support"] ? getTranslateByKey('support') : getTranslateByKey('not_supported');
        $allowurl = ini_get("allow_url_fopen") ? getTranslateByKey('support') : getTranslateByKey('not_supported');
        $max_upload = ini_get("file_uploads") ? ini_get("upload_max_filesize") : "Disabled";
        $max_ex_time = ini_get("max_execution_time") . getTranslateByKey('second');
        $memory_limit = get_cfg_var("memory_limit") ? get_cfg_var("memory_limit") : getTranslateByKey('not');
        $version = DB::select("select version() as version")[0]->version;
        $zip = extension_loaded('zip') ? getTranslateByKey('support') : getTranslateByKey('not_supported');

        if(function_exists('shell_exec')){
            $output = shell_exec('composer --version');
            if (strpos($output, 'Composer version') !== false) {
                $composer = [];
                preg_match('/Composer version (\S+)/', $output, $composer);
                $composer = $composer[0];
            } else {
                $composer = 'Composer 未安装';
            }
        }elseif (function_exists('exec')) {
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
            "total_theme_count" => intval($total_theme_count)
        ]);
    }


    //清空缓存
    public function clearCache() {
        //var_dump(Artisan::call("view:clear")); php artisan optimize:clear composer dump-autoload --optimize
        try {
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
            return ["status" => 200, "msg" => "清理成功"];
        }catch (\Exception $exception) {
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
        return redirect($url);
    }
}
