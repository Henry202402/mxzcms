<?php

namespace Modules\Main\Libs;

use App\Support\Async\AsyncArtisanDispatcher;
use App\Support\Telemetry\StatisticReporter;
use App\Support\Update\UpdateBackupService;
use App\Support\Update\UpdateDownloadService;
use App\Support\Update\UpdateLogger;
use App\Support\Update\UpdateMetaService;
use App\Support\Update\UpdatePreflightService;
use App\Support\Update\UpdateResponseFactory;
use App\Support\Update\UpdateSnapshotService;
use App\Support\Update\UpdateUnzipService;
use Illuminate\Support\Facades\DB;
use Modules\Main\Http\Controllers\Admin\FuncController;
use Modules\Main\Http\Controllers\Admin\IndexController;

class UPDATECMS
{
    private $cloud_host = "https://www.mxzcloud.com";
    public function __construct()
    {
        $this->request = request();
        $this->asyncDispatcher = app(AsyncArtisanDispatcher::class);
        $this->backupService = app(UpdateBackupService::class);
        $this->downloadService = app(UpdateDownloadService::class);
        $this->metaService = app(UpdateMetaService::class);
        $this->preflightService = app(UpdatePreflightService::class);
        $this->snapshotService = app(UpdateSnapshotService::class);
        $this->unzipService = app(UpdateUnzipService::class);
    }
    public function appAction($all){
        $res = [];
        switch ($all["action"]){
            case "check":
                $res = $this->respondApp($all, 'check', $this->checkapp($all));
                break;
            case "license-check":
                $license = $this->requestAppLicenseCheck($all);
                if (($license['status'] ?? 0) == 200) {
                    return $this->respondApp($all, 'license-check', UpdateResponseFactory::success($license['msg'] ?? '授权检查成功', $license['data'] ?? []));
                }
                return $this->respondApp($all, 'license-check', UpdateResponseFactory::error($license['msg'] ?? '授权检查失败', array_merge($license['data'] ?? [], [
                    'reason_code' => $license['reason_code'] ?? 'license_check_failed',
                ])));
            case "bind-license-site":
                $bind = $this->bindAppLicenseSite($all);
                if (($bind['status'] ?? 0) == 200) {
                    return $this->respondApp($all, 'bind-license-site', UpdateResponseFactory::success($bind['msg'] ?? '绑定成功', $bind['data'] ?? []));
                }
                return $this->respondApp($all, 'bind-license-site', UpdateResponseFactory::error($bind['msg'] ?? '绑定失败', array_merge($bind['data'] ?? [], [
                    'reason_code' => $bind['reason_code'] ?? 'license_bind_failed',
                ])));
            case "prepare-download":
                if ($licenseBlocked = $this->resolveAppLicenseBlockedResponse($all, 'prepare-download')) {
                    return $licenseBlocked;
                }
                if ($blocked = $this->metaService->resolveAppBlockReason($this->cloud_host, $all)) {
                    $this->logAppEvent('prepare_download_blocked', $all, [
                        'stage' => 'prepare-download',
                        'reason' => $blocked,
                        'reason_code' => 'blocked_by_limit',
                    ]);
                    StatisticReporter::reportBlocked('UpdateBlocked', $all["identification"], $all["cloudtype"], $blocked, [
                        'stage' => 'prepare-download',
                    ]);
                    return $this->respondApp($all, 'prepare-download', UpdateResponseFactory::error($blocked, [
                        'reason_code' => 'blocked_by_limit',
                    ]));
                }
                $preflight = $this->preflightService->inspectApp($all);
                if (!$preflight['passed']) {
                    $this->logAppEvent('preflight_failed', $all, [
                        'stage' => 'prepare-download',
                        'failed_count' => count($preflight['failed_checks'] ?? []),
                        'reason_code' => 'preflight_failed',
                    ]);
                    StatisticReporter::reportBlocked('UpdateBlocked', $all["identification"], $all["cloudtype"], 'upgrade_preflight_failed', [
                        'stage' => 'prepare-download',
                        'failed_count' => count($preflight['failed_checks'] ?? []),
                    ]);
                    return $this->respondApp($all, 'prepare-download', UpdateResponseFactory::error($preflight['msg'], [
                        'checks' => $preflight['checks'],
                        'failed_checks' => $preflight['failed_checks'],
                        'reason_code' => 'preflight_failed',
                    ]));
                }
                $package = $this->metaService->ensureAppMeta($this->cloud_host, $all);
                if (!$package) {
                    $this->logAppEvent('prepare_download_failed', $all, [
                        'stage' => 'prepare-download',
                        'reason' => 'missing_meta',
                        'reason_code' => 'missing_meta',
                    ]);
                    return $this->respondApp($all, 'prepare-download', UpdateResponseFactory::error('更新包信息不存在，请先检查版本', [
                        'reason_code' => 'missing_meta',
                    ]));
                }
                $res = $this->respondApp($all, 'prepare-download', $this->preparedownload($package['size'] ?? 0), [
                    'to_version' => $package['version'] ?? '',
                    'warning_checks' => $preflight['warning_checks'] ?? [],
                ]);
                $this->logAppEvent('prepare_download_ready', $all, [
                    'stage' => 'prepare-download',
                    'file_size' => $package['size'] ?? 0,
                    'to_version' => $package['version'] ?? '',
                    'preflight' => empty($preflight['warning_checks'] ?? []) ? 'passed' : 'warning',
                    'warning_count' => count($preflight['warning_checks'] ?? []),
                ]);
                break;
            case "start-download":
                if ($blocked = $this->metaService->resolveAppBlockReason($this->cloud_host, $all)) {
                    $this->logAppEvent('start_download_blocked', $all, [
                        'stage' => 'start-download',
                        'reason' => $blocked,
                        'reason_code' => 'blocked_by_limit',
                    ]);
                    StatisticReporter::reportBlocked('UpdateBlocked', $all["identification"], $all["cloudtype"], $blocked, [
                        'stage' => 'start-download',
                    ]);
                    return $this->respondApp($all, 'start-download', UpdateResponseFactory::error($blocked, [
                        'reason_code' => 'blocked_by_limit',
                    ]));
                }

                $package = $this->metaService->ensureAppMeta($this->cloud_host, $all);
                if (!$package) {
                    $this->logAppEvent('start_download_failed', $all, [
                        'stage' => 'start-download',
                        'reason' => 'missing_meta',
                        'reason_code' => 'missing_meta',
                    ]);
                    return $this->respondApp($all, 'start-download', UpdateResponseFactory::error('更新包信息不存在，请先检查版本', [
                        'reason_code' => 'missing_meta',
                    ]));
                }

                $downloadSource = $this->resolveAppRemoteDownloadSource($all, $package);
                if (($downloadSource['status'] ?? 0) != 200) {
                    return $this->respondApp($all, 'start-download', UpdateResponseFactory::error($downloadSource['msg'] ?? '更新包下载地址不存在，请先检查版本', array_merge($downloadSource['data'] ?? [], [
                        'reason_code' => $downloadSource['reason_code'] ?? 'missing_source',
                    ])));
                }
                $remoteFileUrl = trim((string) ($downloadSource['url'] ?? ''));
                if ($remoteFileUrl === '') {
                    $this->logAppEvent('start_download_failed', $all, [
                        'stage' => 'start-download',
                        'reason' => 'missing_source',
                        'to_version' => $package['version'] ?? '',
                        'reason_code' => 'missing_source',
                    ]);
                    return $this->respondApp($all, 'start-download', UpdateResponseFactory::error('更新包下载地址不存在，请先检查版本', [
                        'reason_code' => 'missing_source',
                    ]));
                }
                $remoteFileUrl = $this->appendClientInfoToRemoteUrl($remoteFileUrl, true);
                $localFilePath = storage_path('download/'.$all["cloudtype"].'/'.strtolower($all["identification"]).'-'.$package['version'].'.zip');
                $res = $this->respondApp($all, 'start-download', $this->startdownload($remoteFileUrl, $localFilePath), [
                    'to_version' => $package['version'] ?? '',
                    'local_file' => $localFilePath,
                ]);
                $this->logAppEvent($res['status'] == 200 ? 'start_download_success' : 'start_download_failed', $all, [
                    'stage' => 'start-download',
                    'status_code' => $res['status'] ?? 0,
                    'to_version' => $package['version'] ?? '',
                    'local_file' => $localFilePath,
                    'reason' => $res['msg'] ?? '',
                    'reason_code' => $res['reason_code'] ?? (($res['status'] ?? 0) == 200 ? 'download_success' : 'download_failed'),
                ]);
                break;
            case "unzip-file":
                $package = $this->metaService->ensureAppMeta($this->cloud_host, $all);
                if (!$package) {
                    $this->logAppEvent('unzip_failed', $all, [
                        'stage' => 'unzip-file',
                        'reason' => 'missing_meta',
                        'reason_code' => 'missing_meta',
                    ]);
                    return $this->respondApp($all, 'unzip-file', UpdateResponseFactory::error('更新包信息不存在，请先检查版本', [
                        'reason_code' => 'missing_meta',
                    ]));
                }

                $localFilePath = storage_path('download/'.$all["cloudtype"].'/'.strtolower($all["identification"]).'-'.$package['version'].'.zip');
                $packageContext = $this->buildPackagePathContext($all["identification"], $all["cloudtype"], $package['directory'] ?? '');
                switch ($all["cloudtype"]){
                    case "module":
                        $topath = modules_base_path();
                        break;
                    case "plugin":
                        $topath = plugins_base_path();
                        break;
                    case "theme":
                        $topath = base_path("public/views/themes/");
                        break;
                }

                $res = $this->unzipFile($localFilePath, $topath,"callback_pre_extract");
                //更新数据库
                if ($res['status'] == 200){
                    $postActions = [];
                    //更新版本号
                    $update = [
                        'updated_at' => date("Y-m-d H:i:s")
                    ];
                    if ($all["cloudtype"] == "theme"){
                        $check = DB::table("themes")
                            ->where("identification",$all["identification"])
                            ->first();
                        if($check){
                            DB::table("themes")
                                ->where("identification",$all["identification"])
                                ->update($update);
                            $this->metaService->clearVersionLimit($all);
                            $this->logAppEvent('unzip_success', $all, [
                                'stage' => 'unzip-file',
                                'to_version' => $package['version'] ?? '',
                                'mode' => 'update',
                                'package_path' => $packageContext,
                            ]);
                            StatisticReporter::reportSuccess('Update', $all["identification"], $all["cloudtype"], [
                                'to_version' => $package['version'] ?? '',
                            ]);
                            return $this->respondApp($all, 'unzip-file', UpdateResponseFactory::success($this->buildSuccessMessage('升级成功', $postActions), [
                                'post_actions' => $postActions,
                            ]), [
                                'to_version' => $package['version'] ?? '',
                                'mode' => 'update',
                                'package_path' => $packageContext,
                            ]);
                        }
                    }else{
                        $check = DB::table("modules")
                            ->where("identification",$all["identification"])
                            ->where("cloud_type",$all["cloudtype"])
                            ->first();
                        if($check){
                            DB::table("modules")
                                ->where("identification",$all["identification"])
                                ->where("cloud_type",$all["cloudtype"])
                                ->update($update);
                            if($all["cloudtype"]=="module"){
                                $postActions[] = $this->dispatchAsyncMigrateTask(
                                    $all["identification"],
                                    modules_relative_path($packageContext['directory'] . '/Database/Migrations/update')
                                );
                            }
                            $this->metaService->clearVersionLimit($all);
                            $this->logAppEvent('unzip_success', $all, [
                                'stage' => 'unzip-file',
                                'to_version' => $package['version'] ?? '',
                                'mode' => 'update',
                                'post_actions' => $postActions,
                                'package_path' => $packageContext,
                            ]);
                            StatisticReporter::reportSuccess('Update', $all["identification"], $all["cloudtype"], [
                                'to_version' => $package['version'] ?? '',
                            ]);
                            return $this->respondApp($all, 'unzip-file', UpdateResponseFactory::success($this->buildSuccessMessage('升级成功', $postActions), [
                                'post_actions' => $postActions,
                            ]), [
                                'to_version' => $package['version'] ?? '',
                                'mode' => 'update',
                                'package_path' => $packageContext,
                            ]);
                        }
                    }
                    $request = \request();
                    $request->merge([
                        'm' => $all["identification"],
                        'form' => 'cloud',
                        'cloud_type' => $all["cloudtype"],
                        'return_type' => 'api'
                    ]);
                    $func = new FuncController();
                    $res = $func->install($request);
                    if ($res['status'] == 200) {
                        $this->metaService->clearVersionLimit($all);
                        $this->logAppEvent('unzip_success', $all, [
                            'stage' => 'unzip-file',
                            'to_version' => $package['version'] ?? '',
                            'mode' => 'install_after_unzip',
                            'package_path' => $packageContext,
                        ]);
                        StatisticReporter::reportSuccess('Update', $all["identification"], $all["cloudtype"], [
                            'to_version' => $package['version'] ?? '',
                            'mode' => 'install_after_unzip',
                        ]);
                        return $this->respondApp($all, 'unzip-file', UpdateResponseFactory::success('安装成功'), [
                            'to_version' => $package['version'] ?? '',
                            'mode' => 'install_after_unzip',
                            'package_path' => $packageContext,
                        ]);
                    } else {
                        $this->logAppEvent('unzip_failed', $all, [
                            'stage' => 'unzip-file',
                            'to_version' => $package['version'] ?? '',
                            'reason' => 'install_after_unzip_failed',
                            'reason_code' => 'install_after_unzip_failed',
                            'package_path' => $packageContext,
                        ]);
                        StatisticReporter::reportFailure('UpdateFailed', $all["identification"], $all["cloudtype"], 'install_after_unzip_failed');
                        return $this->respondApp($all, 'unzip-file', UpdateResponseFactory::error('安装失败，请手动安装', [
                            'reason_code' => 'install_after_unzip_failed',
                        ]));
                    }
                } else {
                    $this->logAppEvent('unzip_failed', $all, [
                        'stage' => 'unzip-file',
                        'status_code' => $res['status'] ?? 0,
                        'to_version' => $package['version'] ?? '',
                        'reason' => $res['msg'] ?? 'unzip_failed',
                        'reason_code' => $res['reason_code'] ?? 'unzip_failed',
                        'package_path' => $packageContext,
                    ]);
                    StatisticReporter::reportFailure('UpdateFailed', $all["identification"], $all["cloudtype"], $res['msg'] ?? 'unzip_failed');
                }
                $res = $this->respondApp($all, 'unzip-file', $res, [
                    'to_version' => $package['version'] ?? '',
                    'local_file' => $localFilePath,
                    'package_path' => $packageContext,
                ]);
                break;
            case "get-file-size":
                $package = $this->metaService->ensureAppMeta($this->cloud_host, $all);
                if (!$package) {
                    return $this->respondApp($all, 'get-file-size', UpdateResponseFactory::error('更新包信息不存在，请先检查版本', [
                        'reason_code' => 'missing_meta',
                    ]));
                }
                // 本地保存文件的路径
                $localFilePath = storage_path('download/'.$all["cloudtype"].'/'.strtolower($all["identification"]).'-'.$package['version'].'.zip');
                $res = $this->respondApp($all, 'get-file-size', $this->getFileSize($localFilePath), [
                    'to_version' => $package['version'] ?? '',
                    'local_file' => $localFilePath,
                ]);
                break;
            case "get-app-list":
                $res = $this->getapplist($all);
                break;
            default :
                return UpdateResponseFactory::unknownAction('app', $all['action'] ?? null, [
                    'identification' => $all['identification'] ?? '',
                    'cloudtype' => $all['cloudtype'] ?? '',
                ]);
        }
        return $res;
    }
    public function statistic($data)
    {
        try {
            $cloudHost = $this->resolveCloudHost();
            if ($cloudHost === '') {
                return;
            }

            $data["origin_host"] = url("/");
            $data['time'] = time();
            if (($data['identification'] ?? '') == "cms") {
                $data['version'] = config("app.app_version");
            }
            unset($data['moduleName']);
            ksort($data);
            $data['sign'] = md5(http_build_query($data) . "mxzcms" . $data['time']);

            // Cloud 统计不可达时只跳过上报，不能拖慢或打断 CMS 主流程。
            curl_request_ms($cloudHost . '/api/cloud/statistic', $data, 180);
        } catch (\Throwable $throwable) {
        }
    }
    public function getapplist($all){
        $res = curl_request($this->cloud_host.'/api/cloud/applist?'.http_build_query($all));
        return $res;
    }
    public function cmsAction($all)
    {
        switch ($all["action"]){
            case "check":
                $res = $this->respondCms('check', $this->checkcms());
                break;
            case "snapshot":
                $result = $this->snapshotService->createUpgradeSnapshot(true, false);
                $this->logCmsEvent(($result['status'] ?? 0) == 200 ? 'snapshot_success' : 'snapshot_failed', [
                    'reason' => $result['msg'] ?? '',
                    'snapshot_file' => $result['data']['file'] ?? '',
                ]);
                $res = $this->respondCms('snapshot', $result, [
                    'snapshot_file' => $result['data']['file'] ?? '',
                    'snapshot_size' => $result['data']['size'] ?? 0,
                ]);
                break;
            case "backup":
                $res = $this->respondCms('backup', $this->backupcms());
                break;
            case "prepare-download":
                if ($blocked = $this->metaService->resolveCmsBlockReason($this->cloud_host, (string) config("app.app_version"))) {
                    $this->logCmsEvent('prepare_download_blocked', [
                        'stage' => 'prepare-download',
                        'reason' => $blocked,
                        'reason_code' => 'blocked_by_limit',
                    ]);
                    StatisticReporter::reportBlocked('UpdateBlocked', 'cms', 'cms', $blocked, [
                        'stage' => 'prepare-download',
                    ]);
                    return $this->respondCms('prepare-download', UpdateResponseFactory::error($blocked, [
                        'reason_code' => 'blocked_by_limit',
                    ]));
                }
                $preflight = $this->preflightService->inspectCms();
                if (!$preflight['passed']) {
                    $this->logCmsEvent('preflight_failed', [
                        'stage' => 'prepare-download',
                        'failed_count' => count($preflight['failed_checks'] ?? []),
                        'reason_code' => 'preflight_failed',
                    ]);
                    StatisticReporter::reportBlocked('UpdateBlocked', 'cms', 'cms', 'upgrade_preflight_failed', [
                        'stage' => 'prepare-download',
                        'failed_count' => count($preflight['failed_checks'] ?? []),
                    ]);
                    return $this->respondCms('prepare-download', UpdateResponseFactory::error($preflight['msg'], [
                        'checks' => $preflight['checks'],
                        'failed_checks' => $preflight['failed_checks'],
                        'reason_code' => 'preflight_failed',
                    ]));
                }
                $package = $this->metaService->ensureCmsMeta($this->cloud_host, (string) config("app.app_version"));
                if (!$package) {
                    $this->logCmsEvent('prepare_download_failed', [
                        'stage' => 'prepare-download',
                        'reason' => 'missing_meta',
                        'reason_code' => 'missing_meta',
                    ]);
                    return $this->respondCms('prepare-download', UpdateResponseFactory::error('主程序更新包信息不存在，请先检查版本', [
                        'reason_code' => 'missing_meta',
                    ]));
                }
                $res = $this->respondCms('prepare-download', $this->preparedownload($package['size'] ?? 0), [
                    'to_version' => $package['version'] ?? '',
                    'warning_checks' => $preflight['warning_checks'] ?? [],
                ]);
                $this->logCmsEvent('prepare_download_ready', [
                    'stage' => 'prepare-download',
                    'file_size' => $package['size'] ?? 0,
                    'to_version' => $package['version'] ?? '',
                    'preflight' => empty($preflight['warning_checks'] ?? []) ? 'passed' : 'warning',
                    'warning_count' => count($preflight['warning_checks'] ?? []),
                ]);
                break;
            case "start-download":
                if ($blocked = $this->metaService->resolveCmsBlockReason($this->cloud_host, (string) config("app.app_version"))) {
                    $this->logCmsEvent('start_download_blocked', [
                        'stage' => 'start-download',
                        'reason' => $blocked,
                        'reason_code' => 'blocked_by_limit',
                    ]);
                    StatisticReporter::reportBlocked('UpdateBlocked', 'cms', 'cms', $blocked, [
                        'stage' => 'start-download',
                    ]);
                    return $this->respondCms('start-download', UpdateResponseFactory::error($blocked, [
                        'reason_code' => 'blocked_by_limit',
                    ]));
                }
                $package = $this->metaService->ensureCmsMeta($this->cloud_host, (string) config("app.app_version"));
                if (!$package) {
                    $this->logCmsEvent('start_download_failed', [
                        'stage' => 'start-download',
                        'reason' => 'missing_meta',
                        'reason_code' => 'missing_meta',
                    ]);
                    return $this->respondCms('start-download', UpdateResponseFactory::error('主程序更新包信息不存在，请先检查版本', [
                        'reason_code' => 'missing_meta',
                    ]));
                }
                $remoteFileUrl = $package['source'] ?? '';
                if (!$remoteFileUrl) {
                    $this->logCmsEvent('start_download_failed', [
                        'stage' => 'start-download',
                        'reason' => 'missing_source',
                        'to_version' => $package['version'] ?? '',
                        'reason_code' => 'missing_source',
                    ]);
                    return $this->respondCms('start-download', UpdateResponseFactory::error('主程序更新包下载地址不存在，请先检查版本', [
                        'reason_code' => 'missing_source',
                    ]));
                }
                $remoteFileUrl = $this->appendClientInfoToRemoteUrl($remoteFileUrl);
                $localFilePath = storage_path('download/cms/'.$package['version'].'.zip');

                $res = $this->respondCms('start-download', $this->startdownload($remoteFileUrl, $localFilePath), [
                    'to_version' => $package['version'] ?? '',
                    'local_file' => $localFilePath,
                ]);
                $this->logCmsEvent($res['status'] == 200 ? 'start_download_success' : 'start_download_failed', [
                    'stage' => 'start-download',
                    'status_code' => $res['status'] ?? 0,
                    'to_version' => $package['version'] ?? '',
                    'local_file' => $localFilePath,
                    'reason' => $res['msg'] ?? '',
                    'reason_code' => $res['reason_code'] ?? (($res['status'] ?? 0) == 200 ? 'download_success' : 'download_failed'),
                ]);
                break;
            case "unzip-file":
                if ($blocked = $this->metaService->resolveCmsBlockReason($this->cloud_host, (string) config("app.app_version"))) {
                    $this->logCmsEvent('unzip_blocked', [
                        'stage' => 'unzip-file',
                        'reason' => $blocked,
                        'reason_code' => 'blocked_by_limit',
                    ]);
                    StatisticReporter::reportBlocked('UpdateBlocked', 'cms', 'cms', $blocked, [
                        'stage' => 'unzip-file',
                    ]);
                    return $this->respondCms('unzip-file', UpdateResponseFactory::error($blocked, [
                        'reason_code' => 'blocked_by_limit',
                    ]));
                }
                $package = $this->metaService->ensureCmsMeta($this->cloud_host, (string) config("app.app_version"));
                if (!$package) {
                    $this->logCmsEvent('unzip_failed', [
                        'stage' => 'unzip-file',
                        'reason' => 'missing_meta',
                        'reason_code' => 'missing_meta',
                    ]);
                    return $this->respondCms('unzip-file', UpdateResponseFactory::error('主程序更新包信息不存在，请先检查版本', [
                        'reason_code' => 'missing_meta',
                    ]));
                }
                $localFilePath = storage_path('download/cms/'.$package['version'].'.zip');
                $res = $this->unzipFile($localFilePath, base_path(),"callback_pre_extract",true);
                //更新数据库
                if ($res['status'] == 200){
                    $postActions = is_array($res['post_actions'] ?? null) ? $res['post_actions'] : [];
                    //更新版本号
                    modifyEnv([
                        'APP_VERSION' => $package['version']
                    ]);
                    array_unshift($postActions, $this->dispatchAsyncMigrateTask(
                        'Main',
                        modules_relative_path('Main/Database/Migrations/update')
                    ));
                    $postActions[] = $this->clearCacheSafely();
                    $this->logCmsEvent('unzip_success', [
                        'stage' => 'unzip-file',
                        'to_version' => $package['version'] ?? '',
                        'post_actions' => $postActions,
                    ]);
                    StatisticReporter::reportSuccess('Update', 'cms', 'cms', [
                        'to_version' => $package['version'] ?? '',
                    ]);
                    return $this->respondCms('unzip-file', UpdateResponseFactory::success($this->buildSuccessMessage('升级成功', $postActions), [
                        'post_actions' => $postActions,
                        'scanned_packages' => is_array($res['scanned_packages'] ?? null) ? $res['scanned_packages'] : [],
                    ]), [
                        'to_version' => $package['version'] ?? '',
                    ]);
                }else{
                    $this->logCmsEvent('unzip_failed', [
                        'stage' => 'unzip-file',
                        'status_code' => $res['status'] ?? 0,
                        'to_version' => $package['version'] ?? '',
                        'reason' => $res['msg'] ?? 'cms_unzip_failed',
                        'reason_code' => $res['reason_code'] ?? 'unzip_failed',
                    ]);
                    StatisticReporter::reportFailure('UpdateFailed', 'cms', 'cms', $res['msg'] ?? 'cms_unzip_failed');
                    return $this->respondCms('unzip-file', $res, [
                        'to_version' => $package['version'] ?? '',
                        'local_file' => $localFilePath,
                    ]);
                }

                break;
            case "get-file-size":
                $package = $this->metaService->ensureCmsMeta($this->cloud_host, (string) config("app.app_version"));
                if (!$package) {
                    return $this->respondCms('get-file-size', UpdateResponseFactory::error('主程序更新包信息不存在，请先检查版本', [
                        'reason_code' => 'missing_meta',
                    ]));
                }
                // 本地保存文件的路径
                $localFilePath = storage_path('download/cms/'.$package['version'].'.zip');
                $res = $this->respondCms('get-file-size', $this->getFileSize($localFilePath), [
                    'to_version' => $package['version'] ?? '',
                    'local_file' => $localFilePath,
                ]);
                break;
            case "get-sdks":
                $res = $this->getSdks();
                break;
            default :
                return UpdateResponseFactory::unknownAction('cms', $all['action'] ?? null, [
                    'identification' => 'cms',
                    'cloudtype' => 'cms',
                ]);
        }
        return $res;
    }
    public function getSdks(){
        if(cache()->get('cms_sdks_list')){
            return cache()->get('cms_sdks_list');
        }
        $res = curl_request($this->cloud_host.'/api/list/sdks');
        cache()->put('cms_sdks_list',$res);
        return $res;
    }
    public function checkapp($all)
    {
        $return = $this->metaService->checkApp($this->cloud_host, $all);
        $this->logAppEvent('check', $all, [
            'has_update' => ($return['status'] ?? 0) == 200,
        ]);
        return $return;
    }
    public function checkcms()
    {
        $return = $this->metaService->checkCms($this->cloud_host, (string) config("app.app_version"));
        $this->logCmsEvent('check', [
            'has_update' => ($return['status'] ?? 0) == 200,
        ]);
        return $return;
    }

    protected function resolveCloudHost(): string
    {
        $host = trim((string) $this->cloud_host);
        if ($host === '') {
            return '';
        }

        if (!preg_match('#^https?://#i', $host)) {
            $host = 'https://' . ltrim($host, '/');
        }

        $host = rtrim($host, '/');
        return filter_var($host, FILTER_VALIDATE_URL) ? $host : '';
    }

    protected function buildSiteFingerprint(): string
    {
        return md5((string) (config('app.key') ?: 'mxzcms') . '|' . base_path() . '|' . $this->request->getHost());
    }

    protected function buildAppLicensePayload(array $all, string $version = ''): array
    {
        return [
            'cloudtype' => $all['cloudtype'] ?? '',
            'identification' => $all['identification'] ?? '',
            'origin_host' => $this->request->getHost(),
            'site_host' => $this->request->getHost(),
            'site_fingerprint' => $this->buildSiteFingerprint(),
            'cms_version' => env("APP_VERSION") ?: config("app.app_version"),
            'version' => $version !== '' ? $version : (string) ($all['version'] ?? ''),
        ];
    }

    protected function requestCloudJson(string $path, array $payload): array
    {
        $host = $this->resolveCloudHost();
        if ($host === '') {
            return [
                'status' => 0,
                'msg' => 'Cloud 地址无效',
                'reason_code' => 'invalid_cloud_host',
                'data' => [],
            ];
        }

        try {
            $response = curl_request($host . $path . '?' . http_build_query($payload));
            $decoded = json_decode($response, true);
            if (is_array($decoded)) {
                return $decoded;
            }
        } catch (\Throwable $exception) {
            return [
                'status' => 0,
                'msg' => $exception->getMessage(),
                'reason_code' => 'cloud_request_exception',
                'data' => [],
            ];
        }

        return [
            'status' => 0,
            'msg' => 'Cloud 返回异常',
            'reason_code' => 'invalid_cloud_response',
            'data' => [],
        ];
    }

    protected function requestAppLicenseCheck(array $all, string $version = ''): array
    {
        return $this->requestCloudJson('/api/cloud/license/check', $this->buildAppLicensePayload($all, $version));
    }

    protected function bindAppLicenseSite(array $all, string $version = ''): array
    {
        return $this->requestCloudJson('/api/cloud/license/bindSite', $this->buildAppLicensePayload($all, $version));
    }

    protected function requestAppDownloadToken(array $all, string $version = ''): array
    {
        return $this->requestCloudJson('/api/cloud/license/getDownloadToken', $this->buildAppLicensePayload($all, $version));
    }

    protected function resolveAppLicenseBlockedResponse(array $all, string $stage): ?array
    {
        $license = $this->requestAppLicenseCheck($all);
        if (($license['status'] ?? 0) != 200) {
            return $this->respondApp($all, $stage, UpdateResponseFactory::error($license['msg'] ?? '授权检查失败', array_merge($license['data'] ?? [], [
                'reason_code' => $license['reason_code'] ?? 'license_check_failed',
            ])));
        }

        $data = $license['data'] ?? [];
        $saleType = trim((string) ($data['sale_type'] ?? 'free'));
        $licenseStatus = trim((string) ($data['license_status'] ?? 'free_download'));
        if ($saleType === 'free' || $licenseStatus === 'paid_authorized') {
            return null;
        }

        $msg = $license['msg'] ?? '授权检查失败';
        $reasonCode = 'license_check_failed';
        switch ($licenseStatus) {
            case 'private_contact':
                $msg = '当前资源为私有化部署，请联系平台或开发者处理';
                $reasonCode = 'license_private_contact';
                break;
            case 'paid_unordered':
                $msg = '当前资源为收费资源，请先完成购买后再下载';
                $reasonCode = 'license_payment_required';
                break;
            case 'paid_unpaid':
                $msg = '当前订单尚未支付完成，请先完成支付后再下载';
                $reasonCode = 'license_payment_pending';
                break;
            case 'paid_unbound':
                $msg = '当前资源已支付，但还未绑定当前站点';
                $reasonCode = 'license_bind_required';
                break;
        }

        return $this->respondApp($all, $stage, UpdateResponseFactory::error($msg, array_merge($data, [
            'reason_code' => $reasonCode,
        ])));
    }

    private function appendClientInfoToRemoteUrl(string $remoteFileUrl, bool $includeCmsVersion = false): string
    {
        $query = [
            'origin_host' => $this->request->getHost(),
            'user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? '',
            'site_fingerprint' => $this->buildSiteFingerprint(),
        ];

        if ($includeCmsVersion) {
            $query['cmsversion'] = env("APP_VERSION") ?: config("app.app_version");
        }

        $parts = parse_url($remoteFileUrl);
        $existing = [];
        if (!empty($parts['query'])) {
            parse_str($parts['query'], $existing);
        }
        $merged = array_merge($query, $existing);
        $rebuilt = ($parts['scheme'] ?? 'https') . '://' . ($parts['host'] ?? '') . ($parts['path'] ?? '');
        if (!empty($parts['port'])) {
            $rebuilt = ($parts['scheme'] ?? 'https') . '://' . $parts['host'] . ':' . $parts['port'] . ($parts['path'] ?? '');
        }

        return $rebuilt . '?' . http_build_query($merged);
    }

    private function resolveAppRemoteDownloadSource(array $all, array $package): array
    {
        $license = $this->requestAppLicenseCheck($all, (string) ($package['version'] ?? ''));
        if (($license['status'] ?? 0) != 200) {
            return [
                'status' => 0,
                'msg' => $license['msg'] ?? '授权检查失败',
                'reason_code' => $license['reason_code'] ?? 'license_check_failed',
                'data' => $license['data'] ?? [],
            ];
        }

        $data = $license['data'] ?? [];
        $saleType = trim((string) ($data['sale_type'] ?? 'free'));
        $licenseStatus = trim((string) ($data['license_status'] ?? 'free_download'));

        if ($saleType === 'private') {
            return [
                'status' => 0,
                'msg' => '当前资源为私有化部署，请联系平台或开发者处理',
                'reason_code' => 'license_private_contact',
                'data' => $data,
            ];
        }

        if ($saleType === 'paid') {
            if ($licenseStatus !== 'paid_authorized') {
                return [
                    'status' => 0,
                    'msg' => $license['msg'] ?? '当前站点未完成授权',
                    'reason_code' => 'license_not_authorized',
                    'data' => $data,
                ];
            }

            $token = $this->requestAppDownloadToken($all, (string) ($package['version'] ?? ''));
            if (($token['status'] ?? 0) != 200) {
                return [
                    'status' => 0,
                    'msg' => $token['msg'] ?? '获取下载授权失败',
                    'reason_code' => $token['reason_code'] ?? 'download_token_failed',
                    'data' => $token['data'] ?? [],
                ];
            }

            return [
                'status' => 200,
                'url' => (string) (($token['data']['download_url'] ?? '')),
            ];
        }

        return [
            'status' => 200,
            'url' => (string) ($package['source'] ?? ''),
        ];
    }

    public function backupcms()
    {
        $snapshot = $this->snapshotService->createUpgradeSnapshot(true, false);
        $backup_dirs = [
            'app',
            'config',
            modules_relative_path('Auth'),
            modules_relative_path('Main'),
            modules_relative_path('System'),
            modules_relative_path('Formtools'),
            modules_relative_path('Member'),
            'public/views/admin',
            'public/views/themes/default',
        ];
        //备份文件
        $backup_files = [
            modules_relative_path('ModulesController.php'),
            plugins_relative_path('PluginsController.php'),
            '.env',
            'composer.json'
        ];

        $result = $this->backupService->backupCms($backup_dirs, $backup_files);
        if (($snapshot['status'] ?? 0) == 200) {
            $result['data'] = array_merge($result['data'] ?? [], [
                'snapshot_file' => $snapshot['data']['file'] ?? '',
                'snapshot_size' => $snapshot['data']['size'] ?? 0,
            ]);
        } else {
            $result['data'] = array_merge($result['data'] ?? [], [
                'snapshot_error' => $snapshot['msg'] ?? 'snapshot_failed',
            ]);
        }
        $this->logCmsEvent($result['status'] == 200 ? 'backup_success' : 'backup_failed', [
            'reason' => $result['msg'] ?? '',
            'backup_file' => $result['data']['file'] ?? '',
            'snapshot_file' => $result['data']['snapshot_file'] ?? '',
        ]);

        return $result;
    }

    public function preparedownload($filesize= 0)
    {
        return UpdateResponseFactory::preparedDownload((int) $filesize);
    }

    public function startdownload($remoteFileUrl, $localFilePath,$chunkSize = 3*1024*1024)
    {
        return $this->downloadService->download($remoteFileUrl, $localFilePath, $chunkSize);
    }

    public function getFileSize($localFilePath)
    {
        return $this->downloadService->fileSize($localFilePath);
    }

    public function unzipFile($localFilePath, $toPath,$callback_pre_extract = null,$ismain=false)
    {
        return $this->unzipService->unzip($localFilePath, $toPath, $callback_pre_extract, $ismain);
    }

    private function logAppEvent(string $event, array $all, array $extra = []): void
    {
        UpdateLogger::log('app.' . $event, array_merge([
            'identification' => $all['identification'] ?? '',
            'cloudtype' => $all['cloudtype'] ?? '',
            'version' => $all['version'] ?? '',
            'package_path' => $this->buildPackagePathContext($all['identification'] ?? '', $all['cloudtype'] ?? ''),
        ], $extra));
    }

    private function logCmsEvent(string $event, array $extra = []): void
    {
        UpdateLogger::log('cms.' . $event, array_merge([
            'identification' => 'cms',
            'cloudtype' => 'cms',
            'version' => (string) config('app.app_version'),
        ], $extra));
    }

    private function respondApp(array $all, string $stage, array $response, array $extra = []): array
    {
        return UpdateResponseFactory::contextual($response, 'app', $stage, array_merge([
            'identification' => $all['identification'] ?? '',
            'cloudtype' => $all['cloudtype'] ?? '',
        ], $extra));
    }

    private function respondCms(string $stage, array $response, array $extra = []): array
    {
        return UpdateResponseFactory::contextual($response, 'cms', $stage, array_merge([
            'identification' => 'cms',
            'cloudtype' => 'cms',
        ], $extra));
    }

    private function dispatchAsyncMigrateTask(string $moduleName, string $path): array
    {
        try {
            $task = $this->asyncDispatcher->dispatch('migrate', [
                '--path' => $path,
                '--force' => 1,
            ], $moduleName, [
                'source' => 'update_flow',
                'stage' => 'migration',
                'path' => $path,
            ]);

            return [
                'name' => 'migrate',
                'status' => 'queued',
                'async_id' => $task['async_id'] ?? '',
                'msg' => '已提交数据迁移任务',
            ];
        } catch (\Throwable $exception) {
            UpdateLogger::log('update.post_action_failed', [
                'name' => 'migrate',
                'module' => $moduleName,
                'path' => $path,
                'error' => $exception->getMessage(),
            ]);

            return [
                'name' => 'migrate',
                'status' => 'failed',
                'msg' => '数据迁移任务提交失败，请查看日志',
            ];
        }
    }

    private function clearCacheSafely(): array
    {
        try {
            $result = call_user_func([new IndexController(), "clearCache"]);
            if (($result['status'] ?? 0) == 200) {
                return [
                    'name' => 'clear_cache',
                    'status' => 'success',
                    'msg' => '缓存已清理',
                ];
            }

            UpdateLogger::log('update.post_action_failed', [
                'name' => 'clear_cache',
                'error' => $result['msg'] ?? 'clear_cache_failed',
            ]);

            return [
                'name' => 'clear_cache',
                'status' => 'failed',
                'msg' => '缓存清理失败，请手动执行清缓存',
            ];
        } catch (\Throwable $exception) {
            UpdateLogger::log('update.post_action_failed', [
                'name' => 'clear_cache',
                'error' => $exception->getMessage(),
            ]);

            return [
                'name' => 'clear_cache',
                'status' => 'failed',
                'msg' => '缓存清理失败，请手动执行清缓存',
            ];
        }
    }

    private function buildSuccessMessage(string $message, array $postActions = []): string
    {
        $tips = [];
        foreach ($postActions as $postAction) {
            if (!empty($postAction['msg'])) {
                $tips[] = $postAction['msg'];
            }
        }

        if (!$tips) {
            return $message;
        }

        return $message . '；' . implode('；', $tips);
    }

    private function buildPackagePathContext(string $identification, string $cloudtype, string $preferredDirectory = ''): array
    {
        if ($identification === '' || $cloudtype === '') {
            return [];
        }

        if ($cloudtype === 'theme') {
            return [
                'root' => base_path('public/views/themes'),
                'directory' => $identification,
                'resolved_path' => base_path('public/views/themes/' . $identification),
            ];
        }

        $directory = trim($preferredDirectory);
        if ($directory === '') {
            $directory = package_directory_name($identification, $cloudtype);
        }
        return [
            'root' => package_root_path($cloudtype),
            'directory' => $directory,
            'resolved_path' => package_root_path($cloudtype, $directory),
        ];
    }
}
