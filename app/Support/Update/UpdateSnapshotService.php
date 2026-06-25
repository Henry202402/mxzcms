<?php

namespace App\Support\Update;

use ZipArchive;

class UpdateSnapshotService
{
    public function createUpgradeSnapshot(bool $includeEnv = true, bool $includeRootConfig = false): array
    {
        if (!class_exists(ZipArchive::class)) {
            return UpdateResponseFactory::error('快照创建失败：ZipArchive 扩展未启用', [
                'reason_code' => 'ziparchive_missing',
            ], 500);
        }

        $filename = 'upgrade-snapshot-' . date('ymdHis') . '.zip';
        $backupDir = storage_path('backup');
        if (!is_dir($backupDir)) {
            @mkdir($backupDir, 0755, true);
        }
        $zipPath = $backupDir . DIRECTORY_SEPARATOR . $filename;
        @unlink($zipPath);

        $zip = new ZipArchive();
        $opened = $zip->open($zipPath, ZipArchive::CREATE);
        if ($opened !== true) {
            return UpdateResponseFactory::error('快照创建失败：无法创建压缩文件', [
                'reason_code' => 'zip_create_failed',
            ], 500);
        }

        $basePath = base_path();
        $addedCount = 0;

        $meta = [
            'type' => 'upgrade_snapshot',
            'created_at' => date('c'),
            'include_env' => $includeEnv,
            'include_root_config' => $includeRootConfig,
        ];

        if ($includeEnv) {
            $envFile = $basePath . DIRECTORY_SEPARATOR . '.env';
            if (is_file($envFile)) {
                $zip->addFile($envFile, '.env');
                $addedCount++;
            }
        }

        $moduleRoot = function_exists('package_root_relative')
            ? base_path(package_root_relative('module'))
            : (is_dir(base_path('modules')) ? base_path('modules') : base_path('Modules'));
        $pluginRoot = function_exists('package_root_relative')
            ? base_path(package_root_relative('plugin'))
            : (is_dir(base_path('plugins')) ? base_path('plugins') : base_path('Plugins'));

        foreach ([$moduleRoot, $pluginRoot] as $root) {
            if (!is_dir($root)) {
                continue;
            }
            foreach (scandir($root) ?: [] as $dir) {
                if ($dir === '.' || $dir === '..') {
                    continue;
                }
                $configFile = $root . DIRECTORY_SEPARATOR . $dir . DIRECTORY_SEPARATOR . 'Config' . DIRECTORY_SEPARATOR . 'config.php';
                if (!is_file($configFile)) {
                    continue;
                }
                $relative = ltrim(str_replace('\\', '/', str_replace($basePath, '', $configFile)), '/');
                $zip->addFile($configFile, $relative);
                $addedCount++;
            }
        }

        $themeRoot = public_path('views/themes');
        if (is_dir($themeRoot)) {
            foreach (scandir($themeRoot) ?: [] as $dir) {
                if ($dir === '.' || $dir === '..') {
                    continue;
                }
                $configFile = $themeRoot . DIRECTORY_SEPARATOR . $dir . DIRECTORY_SEPARATOR . 'config.json';
                if (!is_file($configFile)) {
                    continue;
                }
                $relative = ltrim(str_replace('\\', '/', str_replace($basePath, '', $configFile)), '/');
                $zip->addFile($configFile, $relative);
                $addedCount++;
            }
        }

        if ($includeRootConfig) {
            $configRoot = base_path('config');
            if (is_dir($configRoot)) {
                foreach (scandir($configRoot) ?: [] as $file) {
                    if ($file === '.' || $file === '..') {
                        continue;
                    }
                    if (!str_ends_with($file, '.php')) {
                        continue;
                    }
                    $configFile = $configRoot . DIRECTORY_SEPARATOR . $file;
                    if (!is_file($configFile)) {
                        continue;
                    }
                    $zip->addFile($configFile, 'config/' . $file);
                    $addedCount++;
                }
            }
        }

        $zip->addFromString('meta.json', json_encode($meta, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
        $zip->close();

        if ($addedCount <= 0) {
            @unlink($zipPath);
            return UpdateResponseFactory::error('快照为空：未找到可备份的配置文件', [
                'reason_code' => 'snapshot_empty',
            ], 400);
        }

        return UpdateResponseFactory::success('升级快照已生成', [
            'file' => $filename,
            'path' => $zipPath,
            'size' => @filesize($zipPath) ?: 0,
        ]);
    }
}

