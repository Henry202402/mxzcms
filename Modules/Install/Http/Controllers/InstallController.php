<?php

namespace Modules\Install\Http\Controllers;

use App\Support\Installer\DatabasePreflight;
use App\Support\Installer\InstallLogger;
use App\Support\Installer\InstallerInspector;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
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
        $lockPath = public_path('install.lock');
        if (is_file($lockPath)) {
            return true;
        }

        try {
            if (!is_file(base_path('.env'))) {
                return false;
            }

            $dbHost = (string) env('DB_HOST');
            $dbDatabase = (string) env('DB_DATABASE');
            $dbUsername = (string) env('DB_USERNAME');
            if ($dbHost === '' || $dbDatabase === '' || $dbUsername === '') {
                return false;
            }

            $installed = Schema::hasTable('modules')
                || Schema::hasTable('members')
                || Schema::hasTable('settings');

            if (!$installed) {
                return false;
            }

            // @file_put_contents($lockPath, date('Y-m-d H:i:s'));
            // return true;
        } catch (\Throwable $exception) {
            return false;
        }
    }

    //事件目录列表
    public static function listenersDirList() {
        $array = [];

        if (self::checkInstall()) {
            $pluginList = \cache()->get(\Mxzcms\Modules\cache\CacheKey::PluginsActive) ?: [];
            foreach ($pluginList as $plugin) {
                if ($plugin['status']) {
                    $array[] = plugins_base_path($plugin['identification'] . '/Listeners');
                }
            }
            $moduleList = \cache()->get(\Mxzcms\Modules\cache\CacheKey::ModulesActive) ?: [];
            foreach ($moduleList as $module) {
                if ($module['status']) {
                    $array[] = modules_base_path($module['identification'] . '/Listeners');
                }
            }
        } else {
            $array = [
                modules_base_path('System/Listeners'),
                modules_base_path('Auth/Listeners'),
                modules_base_path('Formtools/Listeners'),
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
                $data = InstallerInspector::inspectForLegacyView();
                view()->share([
                    'data' => $data,
                    'installCheckSummary' => $this->buildInstallerCheckSummary($data['checks'] ?? []),
                ]);
                break;
            case 3: //创建数据
                view()->share([
                    'installDefaults' => $this->getInstallFormDefaults(),
                ]);
                break;
            case 4: //创建数据 - 提交

                /**
                 * 设置数据库配置
                 */
                if (isset($params['dbhost'])) $env['DB_HOST'] = $params['dbhost'];
                if (isset($params['dbport'])) $env['DB_PORT'] = $params['dbport'];
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
                $install['settings'][] = [
                    'key' => 'admin_login_entrance',
                    'value' => $this->getInstallFormDefaults()['admin_login_entrance'],
                ];

                /**
                 * 设置管理员信息
                 */
                $install['admin'] = [
                    'username' => $params['manager'],
                    'email' => $params['manager_email'],
                    'password' => $params['manager_pwd'],
                ];
                session()->put('install_form_defaults', [
                    'manager' => $params['manager'],
                    'manager_email' => $params['manager_email'],
                    'manager_pwd' => $params['manager_pwd'],
                    'admin_login_entrance' => $this->getInstallFormDefaults()['admin_login_entrance'],
                ]);

                /**
                 * SQL文件处理
                 */
                $dbPort = (int) ($params['dbport'] ?? env('DB_PORT', 3306));
                $con = mysqli_connect($params['dbhost'], $params['dbuser'], $params['dbpw'], null, $dbPort) or die('不能连接数据库 $DB_HOST');
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
            return DatabasePreflight::check($request->all());
        } else {
            return ['msg' => '非法请求方式!', 'status' => 40000];
        }
    }

    public function checkRewrite(Request $request) {
        $requestUri = (string) $request->server('REQUEST_URI', '');
        $scriptName = (string) $request->server('SCRIPT_NAME', '');
        $path = trim((string) $request->path(), '/');
        $containsIndexPhp = stripos($requestUri, 'index.php') !== false;
        $supported = $path === 'install/rewrite-check' && !$containsIndexPhp;

        return response()->json([
            'status' => $supported ? 200 : 40000,
            'msg' => $supported
                ? '已通过干净路由访问到安装探测接口，rewrite 规则生效。'
                : '当前请求仍带有 index.php 或未通过干净路由进入，rewrite 可能未开启或规则未生效。',
            'data' => [
                'supported' => $supported,
                'request_uri' => $requestUri,
                'path' => $path,
                'script_name' => $scriptName,
            ],
        ]);
    }

    //保存数据库信息
    public function saveDBInfo(Request $request) {
        $all = $request->all();
        $env = [
            'DB_HOST' => $all['dbhost'],
            'DB_PORT' => $all['dbport'] ?? env('DB_PORT', 3306),
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
            InstallLogger::log('install_start_blocked', [
                'reason' => 'already_installed',
            ]);
            return ['msg' => "CMS已经安装过，如需重新安装，请删除Public目录下install.lock文件。", 'status' => 40000];
        }

        try {
            InstallLogger::log('install_start', [
                'db_host' => env('DB_HOST'),
                'db_database' => env('DB_DATABASE'),
            ]);
            $migrate = Artisan::call('migrate', [
                '--path' => modules_relative_path('Install/Database/Migrations/install'),
                '--force' => 1,
            ]);
            Artisan::call('db:seed', [
                '--class' => "Modules\Install\Database\Seeders\DatabaseSeeder",
                '--force' => 1,
            ]);
            $installError = session('install.error');
            InstallLogger::log('install_success', [
                'install_error' => $installError,
            ]);
            return ['status' => 200, 'msg' => "安装完成!", 'data' => ['done' => 1, 'error' => $installError]];

        } catch (\Exception $exception) {
            InstallLogger::log('install_fail', [
                'error' => $exception->getMessage(),
            ]);
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
            $add['password'] = $this->hashInstallAdminPassword((string) $admin['password']);
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
        $limit_unique = ['DB_PREFIX', 'DB_DATABASE', 'DB_USERNAME', 'DB_PASSWORD', 'DB_PORT'];
        $update_env = [];
        foreach ($params as $key => $value) {
            if (in_array($key, $limit_unique)) $update_env[$key] = $value;
        }
        modifyEnv($update_env);//更新ENV配置文件中的数据库配置项
    }

    private function buildInstallerCheckSummary(array $checks): array
    {
        $summary = [
            'total' => 0,
            'passed' => 0,
            'failed_required' => 0,
            'failed_optional' => 0,
            'failed_items' => [],
        ];

        foreach ($checks as $group => $items) {
            foreach (($items ?: []) as $item) {
                $summary['total']++;
                if (($item['status'] ?? '') === 'pass') {
                    $summary['passed']++;
                    continue;
                }

                if (($item['required'] ?? true)) {
                    $summary['failed_required']++;
                } else {
                    $summary['failed_optional']++;
                }

                $summary['failed_items'][] = [
                    'group' => $group,
                    'title' => $item['title'] ?? $item['key'] ?? 'unknown',
                    'message' => $item['message'] ?? '不通过',
                    'required' => $item['required'] ?? true,
                ];
            }
        }

        return $summary;
    }

    private function getInstallFormDefaults(): array
    {
        $defaults = session('install_form_defaults') ?: [];
        if (empty($defaults['manager_pwd'])) {
            $defaults['manager_pwd'] = $this->generateRandomPassword();
        }

        if (empty($defaults['manager'])) {
            $defaults['manager'] = 'admin';
        }

        if (!array_key_exists('manager_email', $defaults)) {
            $defaults['manager_email'] = '';
        }

        if (empty($defaults['admin_login_entrance'])) {
            $defaults['admin_login_entrance'] = strtolower($this->generateRandomPassword(10));
        }

        session()->put('install_form_defaults', $defaults);

        return $defaults;
    }

    private function generateRandomPassword(int $length = 12): string
    {
        $raw = Str::random($length + 4);
        $password = preg_replace('/[^A-Za-z0-9]/', '', $raw) ?: Str::random($length);

        return substr($password, 0, $length);
    }

    private function hashInstallAdminPassword(string $password): string
    {
        $passwordKey = $this->getInstallPasswordKey();

        return md5($passwordKey . md5($password));
    }

    private function getInstallPasswordKey(): string
    {
        $passwordKey = (string) Setting::query()
            ->where('module', 'Main')
            ->where('key', 'password_key')
            ->value('value');

        return $passwordKey !== '' ? $passwordKey : 'mxz_';
    }
}
