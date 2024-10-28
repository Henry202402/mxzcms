<?php

namespace Modules\Install\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Modules\Main\Http\Controllers\Admin\FuncController;
use Modules\Main\Models\Member;
use Modules\Main\Services\ServiceModel;
use Modules\ModulesController;
use Modules\System\Models\Setting;

class InstallController extends ModulesController {

    public function __construct(Request $request) {
        parent::__construct($request);
        //判断是否安装过)
        if (self::checkInstall()) {
            throw new \Exception("CMS已经安装过，如需重新安装，请删除Public目录下install.lock文件。");
        }

        view()->share([
            'cms_name' => config('app.name'),
            'cms_url' => config('app.url'),
        ]);
    }

    //检查是否安装
    public static function checkInstall() {
        $temp = true;
        if(!is_file(public_path() . '/install.lock')){
            $temp = false;
//            try {
//                if (Schema::hasTable("modules")){
//                    file_put_contents(public_path() . '/install.lock',date("Y-m-d H:i:s"));
//                }else{
//                    $temp = false;
//                }
//            }catch (\Exception $exception){
//                $temp = false;
//            }
        }
        return $temp;
    }

    //事件目录列表
    public static function listenersDirList() {
        $array = [];

        if (self::checkInstall()) {
            $pluginList = \cache()->get(\Mxzcms\Modules\cache\CacheKey::PluginsActive) ?: [];
            foreach ($pluginList as $plugin) {
                if ($plugin['status']) {
                    $array[] = base_path('Plugins/' . $plugin['identification'] . '/Listeners');
                }
            }
            $moduleList = \cache()->get(\Mxzcms\Modules\cache\CacheKey::ModulesActive) ?: [];
            foreach ($moduleList as $module) {
                if ($module['status']) {
                    $array[] = base_path('Modules/' . $module['identification'] . '/Listeners');
                }
            }
        } else {
            $array = [
                base_path('Modules/System/Listeners'),
                base_path('Modules/Auth/Listeners'),
                base_path('Modules/Formtools/Listeners'),
            ];
        }
        return $array;
    }

    //安装引导页面
    public function index(Request $request) {
        $params = $request->all();
        $params['install'] = isset($params['install']) ? $params['install'] : 1;
        switch (intval($params['install'])) {
            case 2: //检测环境
                $data = [];
                $data['phpversion'] = @phpversion();
                $data['os'] = PHP_OS;
                $tmp = function_exists('gd_info') ? gd_info() : [];
                // $server             = $_SERVER["SERVER_SOFTWARE"];
                // $host               = $this->request->host();
                // $name               = $_SERVER["SERVER_NAME"];
                // $max_execution_time = ini_get('max_execution_time');
                // $allow_reference    = (ini_get('allow_call_time_pass_reference') ? '<font color=green>[√]On</font>' : '<font color=red>[×]Off</font>');
                // $allow_url_fopen    = (ini_get('allow_url_fopen') ? '<font color=green>[√]On</font>' : '<font color=red>[×]Off</font>');
                // $safe_mode          = (ini_get('safe_mode') ? '<font color=red>[×]On</font>' : '<font color=green>[√]Off</font>');

                $err = 0;
                if (empty($tmp['GD Version'])) {
                    $gd = '<font color=red>[×]Off</font>';
                    $err++;
                } else {
                    $gd = '<font color=green>[√]On</font> ' . $tmp['GD Version'];
                }

                if (version_compare($data['phpversion'], '8.0.0', '>=') && version_compare($data['phpversion'], '8.2.2', '<=')) {
                    $data['phpversion_msg'] = '<i class="fa fa-check correct"></i> ' . $data['phpversion'];
                } else {
                    $data['phpversion_msg'] = '<i class="fa fa-remove error"></i> ' . $data['phpversion'];
                    $err++;
                }

                if (class_exists('pdo')) {
                    $data['pdo'] = '<i class="fa fa-check correct"></i> 已开启';
                } else {
                    $data['pdo'] = '<i class="fa fa-remove error"></i> 未开启';
                    $err++;
                }

                if (extension_loaded('pdo_mysql')) {
                    $data['pdo_mysql'] = '<i class="fa fa-check correct"></i> 已开启';
                } else {
                    $data['pdo_mysql'] = '<i class="fa fa-remove error"></i> 未开启';
                    $err++;
                }

                if (extension_loaded('curl')) {
                    $data['curl'] = '<i class="fa fa-check correct"></i> 已开启';
                } else {
                    $data['curl'] = '<i class="fa fa-remove error"></i> 未开启';
                    $err++;
                }

                if (extension_loaded('gd')) {
                    $data['gd'] = '<i class="fa fa-check correct"></i> 已开启';
                } else {
                    $data['gd'] = '<i class="fa fa-remove error"></i> 未开启';
                    if (function_exists('imagettftext')) {
                        $data['gd'] .= '<br><i class="fa fa-remove error"></i> FreeType Support未开启';
                    }
                    $err++;
                }

                if (extension_loaded('mbstring')) {
                    $data['mbstring'] = '<i class="fa fa-check correct"></i> 已开启';
                } else {
                    $data['mbstring'] = '<i class="fa fa-remove error"></i> 未开启';
                    if (function_exists('imagettftext')) {
                        $data['mbstring'] .= '<br><i class="fa fa-remove error"></i> FreeType Support未开启';
                    }
                    $err++;
                }

                if (extension_loaded('fileinfo')) {
                    $data['fileinfo'] = '<i class="fa fa-check correct"></i> 已开启';
                } else {
                    $data['fileinfo'] = '<i class="fa fa-remove error"></i> 未开启';
                    $err++;
                }

                if (ini_get('file_uploads')) {
                    $data['upload_size'] = '<i class="fa fa-check correct"></i> ' . ini_get('upload_max_filesize');
                } else {
                    $data['upload_size'] = '<i class="fa fa-remove error"></i> 禁止上传';
                }

                if (function_exists('session_start')) {
                    $data['session'] = '<i class="fa fa-check correct"></i> 支持';
                } else {
                    $data['session'] = '<i class="fa fa-remove error"></i> 不支持';
                    $err++;
                }

                if (version_compare($data['phpversion'], '8.0.0', '>=') && version_compare($data['phpversion'], '8.2.2', '<') && ini_get('always_populate_raw_post_data') != -1) {
                    $data['always_populate_raw_post_data'] = '<i class="fa fa-remove error"></i> 未关闭';
                    $data['show_always_populate_raw_post_data_tip'] = true;
                    $err++;
                } else {
                    $data['always_populate_raw_post_data'] = '<i class="fa fa-check correct"></i> 已关闭';
                }

                $folders = [
                    "app",
                    "bootstrap/cache",
                    "config",
                    "Modules",
                    "Plugins",
                    "public",
                    "storage",
                    "vendor",
                    ".env"
                ];
                $newFolders = [];
                foreach ($folders as $dir) {
                    $testDir = base_path() . "/" . $dir;
                    self::sp_dir_create($testDir);
                    if (!is_file($testDir)) {
                        if (self::sp_testwrite($testDir)) {
                            $newFolders[$dir]['w'] = true;
                        } else {
                            $newFolders[$dir]['w'] = false;
                            $err++;
                        }
                    } else {
                        if (is_writable($testDir)) {
                            $newFolders[$dir]['w'] = true;
                        } else {
                            $newFolders[$dir]['w'] = false;
                            $err++;
                        }
                    }

                    if (is_readable($testDir)) {
                        $newFolders[$dir]['r'] = true;
                    } else {
                        $newFolders[$dir]['r'] = false;
                        $err++;
                    }
                }
                $data['folders'] = $newFolders;
                view()->share(['data' => $data]);
                break;
            case 4: //创建数据 - 提交

                /**
                 * 设置数据库配置
                 */
                if (isset($params['dbhost'])) $env['DB_HOST'] = $params['dbhost'];
                if (isset($params['dbname'])) $env['DB_DATABASE'] = $params['dbname'];
                if (isset($params['dbuser'])) $env['DB_USERNAME'] = $params['dbuser'];
                if (isset($params['dbpw'])) $env['DB_PASSWORD'] = $params['dbpw'];
                if (isset($params['dbprefix'])) $env['DB_PREFIX'] = $params['dbprefix'];
                if (isset($env)) {
                    $row = $this->saveDBInfo($request);
                    if ($row['status'] != 200) return back()->with('error', $row['msg']);
                    $install['env'] = $env;
                }

                /**
                 * 网站SEO
                 */
                $settings = [];
                $in_database = [
                    'website_name',
                    'website_keys',
                    'website_desc',
                ];
                foreach ($params as $key => $value) {
                    if (in_array($key, $in_database)) {
                        if ($key == 'website_reg_rqstd') {
                            $value = implode(",", $value);
                        }
                        array_push($settings, ['key' => $key, 'value' => $value]);
                    }
                }
                $install['settings'] = $settings;

                /**
                 * 设置管理员信息
                 */
                $install['admin'] = [
                    'username' => $params['manager'],
                    'email' => $params['manager_email'],
                    'password' => $params['manager_pwd'],
                ];

                /**
                 * SQL文件处理
                 */
                $con = mysqli_connect($params['dbhost'], $params['dbuser'], $params['dbpw']) or die('不能连接数据库 $DB_HOST');
                $sql = "CREATE DATABASE IF NOT EXISTS `{$env['DB_DATABASE']}` DEFAULT CHARACTER SET " . $params['dbcharset'];
                mysqli_query($con, $sql);

                session()->put('install', $install);
                break;
            default:
                # code...
                break;
        }
        return view('install/index/install' . $params['install']);
    }

    //测试数据库
    public function checkDbPwd(Request $request) {
        if ($request->isMethod('POST')) {
            $dbConfig = $request->all();
            $dbConfig['type'] = "mysql";
            try {
                config(['database.connections.mysql.host' => $dbConfig['hostname']]);
                config(['database.connections.mysql.username' => $dbConfig['username']]);
                config(['database.connections.mysql.password' => $dbConfig['password']]);
                config(['database.connections.mysql.database' => '']);
                DB::connection()->getPdo();
                $msg = '账号密码验证成功！';
                if ($dbConfig['database']) {
                    try {
                        DB::select('use ' . $dbConfig['database']);
                        return ['msg' => $msg . '数据库已存在！', 'status' => 200];
                    } catch (\Exception $e) {
                        return ['msg' => $msg . '数据库不存在将自动创建！', 'status' => 40000];
                    }
                }
                return ['msg' => $msg, 'status' => 200];
            } catch (\Exception $e) {
                return ['msg' => '数据库账号或密码不正确！' . $e->getMessage(), 'status' => 40000];
            }
        } else {
            return ['msg' => '非法请求方式!', 'status' => 40000];
        }
    }

    //保存数据库信息
    public function saveDBInfo(Request $request) {
        $all = $request->all();
        $env = [
            'DB_HOST' => $all['dbhost'],
            'DB_USERNAME' => $all['dbuser'],
            'DB_PASSWORD' => $all['dbpw'],
            'DB_DATABASE' => $all['dbname'],
            'DB_PREFIX' => $all['dbprefix'],
            'DB_CHARSET' => $all['dbcharset'],
        ];
        try {
            modifyEnv($env);
            return ['msg' => '保存数据库信息成功', 'status' => 200];
        } catch (\Exception $exception) {
            return ['msg' => $exception->getMessage(), 'status' => 0];
        }
    }

    public function start(Request $request) {
        if (self::checkInstall()) {
            return ['msg' => "CMS已经安装过，如需重新安装，请删除Public目录下install.lock文件。", 'status' => 40000];
        }

        try {
            $migrate = Artisan::call('migrate', [
                '--path' => "Modules/Install/Database/Migrations/install",
                '--force' => 1,
            ]);
            Artisan::call('db:seed', [
                '--class' => "Modules\Install\Database\Seeders\DatabaseSeeder",
                '--force' => 1,
            ]);
            $installError = session('install.error');
            return ['status' => 200, 'msg' => "安装完成!", 'data' => ['done' => 1, 'error' => $installError]];

        } catch (\Exception $exception) {
            return ['msg' => $exception->getMessage(), 'status' => 40000];
        }

    }


    public function setDbConfig() {
        $env = session('install.env');
        self::writeDatabaseConfig($env);
        return ['msg' => '数据配置文件写入成功!', 'status' => 200];
    }

    //安装模块
    public function installModule() {
        //安装模块
        $request = \request();
        //$request->merge(['m' => 'System']);
        $request->merge(['cloud_type' => \Modules\Main\Models\Modules::Module]);
        $request->merge(['return_type' => 'api']);
        $func = new FuncController($request);
        $res = $func->install($request);
        if ($res['status'] == 200) {
            return ['msg' => $request->m . '模块安装成功', 'status' => 200];
        } else {
            return ['msg' => $request->m . '模块安装失败，' . $res['msg'], 'status' => 0];
        }
    }

    //安装数据库
    public function installModuleDB() {
        $request = \request();
        $m = $request->m;
        try {
            return ['status' => 200, 'msg' => $m . "模块数据库安装成功"];
        } catch (\Exception $exception) {
            return ['msg' => $exception->getMessage(), 'status' => 40000];
        }
    }

    public function setSite() {
        try {
            $admin = session('install.admin');
            $find = Member::query()->where('username', $admin['username'])->first();
            $add["uid"] = 1;
            $add["avatar"] = "avatar/avatar.jpg";
            $add['username'] = $admin['username'];
            $add['nickname'] = $admin['username'];
            $add['password'] = ServiceModel::getPassword($admin['password']);
            $add['status'] = 1;
            $add['email'] = $admin['email'];
            if ($find) {
                ServiceModel::whereUpdate(Member::TABLE_NAME, ['uid' => $find['uid']], $add);
            } else {
                $uid = ServiceModel::add(Member::TABLE_NAME, $add);
            }
            write_lock_file(public_path(), '', 'install.lock');
            foreach (session('install')['settings'] as $setting) {
                Setting::query()->where('key', $setting['key'])->where("module","Main")
                    ->update(['value' => $setting['value']]);
            }

        } catch (\Exception $e) {
            return ['msg' => "网站创建失败!" . $e->getMessage(), 'status' => 40000];
        }
        return ['msg' => '网站创建完成', 'status' => 200];
    }

    /**
     * 数据库配置项重写
     */
    private function writeDatabaseConfig($params = []) {
        $limit_unique = ['DB_PREFIX', 'DB_DATABASE', 'DB_USERNAME', 'DB_PASSWORD'];
        $update_env = [];
        foreach ($params as $key => $value) {
            if (in_array($key, $limit_unique)) $update_env[$key] = $value;
        }
        modifyEnv($update_env);//更新ENV配置文件中的数据库配置项
    }

    private static function sp_dir_create($path, $mode = 0777) {
        if (is_dir($path)) return true;
        $temp = explode('/', $path);
        $cur_dir = '';
        $max = count($temp) - 1;
        for ($i = 0; $i < $max; $i++) {
            $cur_dir .= $temp[$i] . '/';
            if (@is_dir($cur_dir)) continue;
            @mkdir($cur_dir, 0777, true);
            @chmod($cur_dir, 0777);
        }
        return is_dir($path);
    }

    private static function sp_testwrite($d) {
        $tfile = "_test.txt";
        $fp = @fopen($d . "/" . $tfile, "w");
        if (!$fp) {
            return false;
        }
        fclose($fp);
        $rs = @unlink($d . "/" . $tfile);
        if ($rs) {
            return true;
        }
        return false;
    }
}
