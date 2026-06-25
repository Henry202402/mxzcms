<?php

namespace Modules\System\Http\Controllers\Admin;

use Illuminate\Support\Str;
use Modules\Main\Models\Modules;
use Modules\System\Http\Controllers\Common\CommonController;
use Illuminate\Http\Request;
use Modules\System\Services\ServiceModel;
use Modules\System\Models\Setting;

class SecureController extends CommonController {

    public function __construct(Request $request) {
        parent::__construct($request);
    }

    //安全设置
    public function secureConfig(Request $request) {
        $pageData = [
            'subtitle' => '安全与工具',
            'title' => '安全设置',
            'controller' => 'Secure',
            'action' => 'secureConfig',
        ];


        return $this->adminView('secure.secureConfig', [
            'pageData' => $pageData,
        ]);
    }


    //上传设置
    public function uploadsConfig(Request $request) {
        $pageData = [
            'subtitle' => '安全与工具',
            'title' => '上传设置',
            'controller' => 'Secure',
            'action' => 'uploadsConfig',
        ];

        //获取当前所有的上传插件
        $plugin_upload_lists = hook("GetUploadPluginList");

        $local_start_plugin_list = [];
        foreach ($plugin_upload_lists as $plugin) {
            if ($plugin) {
                $local_start_plugin_list[] = $plugin;
            }
        }

        $currentUploadDriver = (string) __E('upload_driver');
        $currentUploadDriverLabel = '本地存储';
        if ($currentUploadDriver !== '' && $currentUploadDriver !== 'local') {
            foreach ($local_start_plugin_list as $plugin) {
                if (($plugin['identification'] ?? '') === $currentUploadDriver) {
                    $currentUploadDriverLabel = $plugin['name'] ?? $currentUploadDriver;
                    break;
                }
            }
        }

        $uploadOverview = [
            [
                'name' => '当前上传驱动',
                'value' => $currentUploadDriverLabel,
                'desc' => '当前文件上传优先使用的存储来源',
            ],
            [
                'name' => '可选上传来源',
                'value' => count($local_start_plugin_list) + 1,
                'desc' => '包含本地存储和已接入的上传插件',
            ],
            [
                'name' => '单文件大小限制',
                'value' => (__E('upload_limit') ?: '0') . ' KB',
                'desc' => '超过限制的文件将无法上传',
            ],
            [
                'name' => '缩略图',
                'value' => __E('thumb_auto') == 1 ? '已开启' : '未开启',
                'desc' => '图片上传后是否自动生成缩略图',
            ],
            [
                'name' => '水印类型',
                'value' => __E('watermark_type') == 'img' ? '图片水印' : '文字水印',
                'desc' => '当前图片水印的生成方式',
            ],
            [
                'name' => '上传状态',
                'value' => __E('upload_status') == 1 ? '已开启' : '已关闭',
                'desc' => '关闭后将停止文件上传能力',
            ],
        ];

        return $this->adminView('secure.uploadsConfig', [
            'pageData' => $pageData,
            'plugin_list' => $local_start_plugin_list,
            'uploadOverview' => $uploadOverview,
            'currentUploadDriver' => $currentUploadDriver ?: 'local',
        ]);
    }

    //缓存配置
    public function cacheConfig(Request $request) {
        $pageData = [
            'subtitle' => '安全与工具',
            'title' => '缓存配置',
            'controller' => 'Secure',
            'action' => 'cacheConfig',
        ];

        $cacheDriver = (string) env('CACHE_DRIVER', 'file');
        if ($cacheDriver === '') {
            $cacheDriver = 'file';
        }

        $driverOptions = [
            [
                'value' => 'file',
                'name' => 'File',
                'desc' => '默认本地文件缓存，部署简单，适合单机环境。',
            ],
            [
                'value' => 'redis',
                'name' => 'Redis',
                'desc' => '适合高并发和多节点场景，推荐生产环境优先使用。',
            ],
            [
                'value' => 'memcached',
                'name' => 'Memcached',
                'desc' => '轻量内存缓存，适合追求简单高性能的独立缓存服务。',
            ],
            [
                'value' => 'database',
                'name' => 'Database',
                'desc' => '使用数据库保存缓存，便于统一维护但性能较保守。',
            ],
            [
                'value' => 'apc',
                'name' => 'APC',
                'desc' => '依赖扩展环境，适用于已安装 APC 的特定服务器。',
            ],
            [
                'value' => 'array',
                'name' => 'Array',
                'desc' => '仅当前请求有效，通常用于调试或临时开发环境。',
            ],
        ];

        $driverMetaMap = [];
        foreach ($driverOptions as $option) {
            $driverMetaMap[$option['value']] = $option;
        }

        $currentDriverMeta = $driverMetaMap[$cacheDriver] ?? $driverMetaMap['file'];
        $connectionTarget = '当前驱动无需额外连接配置';
        if ($cacheDriver === 'redis') {
            $connectionTarget = (env('REDIS_HOST') ?: '127.0.0.1') . ':' . (env('REDIS_PORT') ?: '6379');
        } elseif ($cacheDriver === 'memcached') {
            $connectionTarget = (env('MEMCACHED_HOST') ?: '127.0.0.1') . ':' . (env('MEMCACHED_PORT') ?: '11211');
        }

        $cacheOverview = [
            [
                'name' => '当前缓存驱动',
                'value' => $currentDriverMeta['name'],
                'desc' => $currentDriverMeta['desc'],
            ],
            [
                'name' => '缓存前缀',
                'value' => env('CACHE_PREFIX') ?: '未设置',
                'desc' => '用于区分站点缓存键，避免与其它项目相互污染。',
            ],
            [
                'name' => '连接目标',
                'value' => $connectionTarget,
                'desc' => '仅在 Redis 或 Memcached 驱动下需要重点确认。',
            ],
            [
                'name' => '可选驱动',
                'value' => count($driverOptions),
                'desc' => '支持本地、数据库、内存服务等多种缓存方式。',
            ],
        ];

        $cacheQuickActions = [
            [
                'title' => '清理系统缓存',
                'desc' => '保存缓存配置后，可直接清理视图、配置、路由等缓存，确保新设置立即生效。',
                'action' => 'clear-cache',
                'button' => '立即清理',
            ],
            [
                'title' => '检查驱动连接',
                'desc' => '切换到 Redis 或 Memcached 时，请先确认服务地址、端口和认证信息是否正确。',
                'action' => 'check-driver',
                'button' => '查看当前连接',
            ],
        ];

        return $this->adminView('secure.cacheConfig', [
            'pageData' => $pageData,
            'cacheOverview' => $cacheOverview,
            'cacheDriverOptions' => $driverOptions,
            'currentCacheDriver' => $cacheDriver,
            'cacheQuickActions' => $cacheQuickActions,
            'currentCacheDriverMeta' => $currentDriverMeta,
            'cacheConnectionTarget' => $connectionTarget,
        ]);
    }

    //提交处理
    public function toolSubmit() {

        if ($this->request->ismethod('post')) {

            $all = $this->request->all();

            switch ($all['form']) {
                case "safe":

                    $sessionCookie = trim((string) ($all['SESSION_COOKIE'] ?? ''));
                    if ($sessionCookie === '') {
                        $sessionCookie = Str::slug(env('APP_NAME', 'laravel'), '_').'_session';
                    }

                    $env['SESSION_COOKIE'] = $sessionCookie;
                    // Keep the legacy key in sync so old deployments do not drift.
                    $env['COOKIE_NAME'] = $sessionCookie;
                    $env['SESSION_DOMAIN'] = $all['SESSION_DOMAIN'];

                    if (array_key_exists('SESSION_DRIVER', $all)) {
                        $env['SESSION_DRIVER'] = $all['SESSION_DRIVER'];
                    }

                    if (array_key_exists('SESSION_LIFETIME', $all)) {
                        $env['SESSION_LIFETIME'] = $all['SESSION_LIFETIME'];
                    }

                    if (array_key_exists('SESSION_ENCRYPT', $all)) {
                        $env['SESSION_ENCRYPT'] = $all['SESSION_ENCRYPT'];
                    }


                    $settings = [];
                    $in_database = [
                        'limit_count',
                        'limit_time',
                        'filter_strings',
                        'blacklist_ip',
                        'admin_login_entrance',
                        'password_key',
                        'admin_login_code',
                        'home_submit_code'
                    ];
                    foreach ($all as $key => $value) {
                        if (in_array($key, $in_database)) {
                            array_push($settings, ['key' => $key, 'value' => $value]);
                        }
                    }

                    modifyEnv($env);
                    $setting = new ServiceModel;
                    $setting->updateBatch($settings);
                    //更新缓存
                    cacheGlobalSettings(2);
                    break;

                case "cache":

                    $env['CACHE_PREFIX'] = $all['CACHE_PREFIX'];

                    $env['CACHE_DRIVER'] = $all['CACHE_DRIVER'];

                    $env['REDIS_HOST'] = $all['REDIS_HOST'];

                    $env['REDIS_PASSWORD'] = $all['REDIS_PASSWORD'];

                    $env['REDIS_PORT'] = $all['REDIS_PORT'];

                    $env['MEMCACHED_HOST'] = $all['MEMCACHED_HOST'];

                    $env['MEMCACHED_USERNAME'] = $all['MEMCACHED_USERNAME'];

                    $env['MEMCACHED_PASSWORD'] = $all['MEMCACHED_PASSWORD'];

                    $env['MEMCACHED_PORT'] = $all['MEMCACHED_PORT'];

                    modifyEnv($env);


                    break;

                case "upload":

                    $settings = [];
                    $in_database = [
                        'upload_status',
                        'upload_limit',
                        'upload_format',
                        'upload_driver',
                        'thumb_auto',
                        'thumb_method',
                        'watermark_type',
                        'watermark_position',
                        'watermark_text',
                        'watermark_text_size',
                        'watermark_text_angle',
                        'watermark_text_color',
                        'watermark_upload_format',
                        //'watermark_img',等等图片完成后开放

                    ];
                    foreach ($all as $key => $value) {
                        if (in_array($key, $in_database)) {
                            array_push($settings, ['key' => $key, 'value' => $value]);
                        }
                    }

                    $setting = new ServiceModel;
                    $setting->updateBatch($settings);

                    //更新缓存
                    cacheGlobalSettings(2);

                    break;

                default :

                    return ["status" => 40000, "msg" => "Method does not exist"];
            }


            return ["status" => 200, "msg" => "保存成功"];

        } else {
            return ["status" => 40000, "msg" => "method error,must post method"];
        }
    }



}
