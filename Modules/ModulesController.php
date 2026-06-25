<?php

namespace Modules;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Modules\Main\Models\Modules;
use Modules\Main\Services\ServiceModel;


class ModulesController extends BaseController {
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
    public $request;
    public $pageData;

    public function __construct() {
        $this->request = request();
        $this->pageData = getURIByRoute($this->request);
        if (file_exists(module_path($this->pageData['moduleName']) . '/vendor/autoload.php')) {
            require_once module_path($this->pageData['moduleName']) . '/vendor/autoload.php';
        }
    }

    public function GetModuleSetIndex() {
        $module_name = $this->request->module_name_first ?: ServiceModel::getModuleIndex();//获取设置前台首页的模块
        //模块名称存在
        if ($module_name) {
            //查找对应模块下方的Home里的Home控制器文件是否存在
            $path = module_path($module_name->identification, "Http/Controllers/Home/HomeController.php");
            if (file_exists($path)) {
                //查找对应模块下方的Home里的Home控制器
                $class_filename = '\Modules\\' . $module_name->identification . '\Http\Controllers\Home\HomeController';
                //加载home控制器的index方法进行调用
                return call_user_func([new $class_filename($this->request), 'index']);
            }
        }
    }

    public function __destruct() {
        // TODO: Implement __destruct() method.
        // Store the session data...
        $time = microtime(true) - LARAVEL_START;
        $msg = "当前运行时间:" . substr($time, 0, 4) . 's,内存使用:' . (memory_get_usage(true) / 1024 / 1024) . 'M';
        $array = ['statusCode' => 200, 'code' => '程序运行超时', 'message' => $msg, 'path' => url()->full(), 'duration_ms' => intval($time * 1000), 'memory_usage' => round(memory_get_usage(true) / 1024 / 1024, 2) . 'M'];
        if ($time > 5) {
            $module = getURIByRoute($this->request)['moduleName'];
            hook("Loger", [
                'module' => $module,
                'type' => "system",
                'two_type' => 'slow',
                'params' => \Plugins\Logger\Lib\Logger::normalizeSystemContext('slow', $array, [
                    'module' => $module,
                    'requestid' => $this->request->requestid ?? '',
                    'path' => $array['path'],
                ]),
                'remark' => $array['code'],
                'unique_id' => "",
                'requestid' => $this->request->requestid
            ]);
        }

    }
}
