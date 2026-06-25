<?php

namespace App\Support\Update;

use App\Support\Async\AsyncArtisanDispatcher;

class UpdateUnzipService
{
    public function unzip(string $localFilePath, string $toPath, ?string $callbackPreExtract = null, bool $isMain = false): array
    {
        ignore_user_abort();
        set_time_limit(0);
        ini_set('memory_limit', '2560M');

        if ($isMain) {
            $this->normalizeLegacyPackageRoots($toPath);
        }

        $zip = new \PclZip($localFilePath);
        $packageMigrations = $isMain ? $this->detectPackageMigrations($zip) : [];
        $res = $zip->extract(
            PCLZIP_OPT_PATH,
            $toPath,
            PCLZIP_CB_PRE_EXTRACT,
            $callbackPreExtract,
            PCLZIP_OPT_SET_CHMOD,
            0755
        );
        @unlink($localFilePath);

        if ($res <= 0) {
            $errorInfo = method_exists($zip, 'errorInfo') ? $zip->errorInfo(true) : '';
            $this->cleanupBackupCopies(cache()->get("update_backup"));
            cache()->put("update_backup", null);
            return [
                "status" => 500,
                "msg" => "解压失败",
                "reason_code" => "unzip_failed",
                "error_detail" => is_string($errorInfo) ? trim($errorInfo) : '',
            ];
        }

        $temps = cache()->get("update_backup") ?: [];
        $this->restoreBackedUpConfigs($temps);
        $postActions = $isMain ? $this->dispatchPackageMigrations($packageMigrations) : [];
        $scannedPackages = $isMain ? $this->formatScannedPackages($packageMigrations) : [];

        cache()->put("update_backup", null);
        return [
            "status" => 200,
            "msg" => "解压成功",
            "post_actions" => $postActions,
            "scanned_packages" => $scannedPackages,
        ];
    }

    private function cleanupBackupCopies(?array $temps): void
    {
        foreach (($temps ?: []) as $temp) {
            @unlink(update_extract_backup_copy_path($temp));
        }
    }

    private function restoreBackedUpConfigs(array $temps): void
    {
        $moduleRoot = str_replace('\\', '/', base_path(package_root_relative('module'))) . '/';
        $pluginRoot = str_replace('\\', '/', base_path(package_root_relative('plugin'))) . '/';
        $configRoot = str_replace('\\', '/', base_path('config')) . '/';
        foreach ($temps as $temp) {
            $normalizedTemp = str_replace('\\', '/', $temp);
            if (strpos($normalizedTemp, $moduleRoot) === 0) {
                $this->restoreModuleConfigFile($temp);
                continue;
            }

            if (strpos($normalizedTemp, $pluginRoot) === 0) {
                $this->mergePluginConfigFile($temp);
                continue;
            }

            if (strpos($normalizedTemp, $configRoot) === 0) {
                $this->mergePlainPhpArrayFile($temp);
                continue;
            }

            if (preg_match('#/config\.json$#i', $normalizedTemp)) {
                $this->mergeThemeConfigFile($temp);
                continue;
            }

            @unlink(update_extract_backup_copy_path($temp));
        }
    }

    private function restoreModuleConfigFile(string $path): void
    {
        $copyPath = update_extract_backup_copy_path($path);
        $newContent = file_exists($path) ? file_get_contents($path) : false;
        $oldContent = file_exists($copyPath) ? file_get_contents($copyPath) : false;
        if (!is_string($newContent) || !is_string($oldContent) || $newContent === '' || $oldContent === '') {
            @unlink($copyPath);
            return;
        }

        $mergedContent = $newContent;
        foreach (['domain', 'auth', 'addmodel'] as $key) {
            $oldValue = $this->extractPhpScalarConfigValue($oldContent, $key);
            $newValue = $this->extractPhpScalarConfigValue($newContent, $key);
            if ($oldValue === null || $newValue === null || $oldValue === $newValue) {
                continue;
            }

            $mergedContent = $this->replacePhpScalarConfigValue($mergedContent, $key, $oldValue);
        }

        if ($mergedContent !== $newContent) {
            file_put_contents($path, $mergedContent);
        }

        @unlink($copyPath);
    }

    private function mergePluginConfigFile(string $path): void
    {
        $copyPath = update_extract_backup_copy_path($path);
        $newConfig = file_exists($path) ? include $path : [];
        $oldConfig = file_exists($copyPath) ? include $copyPath : [];
        if (!is_array($newConfig) || !is_array($oldConfig)) {
            @unlink($copyPath);
            return;
        }

        if (isset($newConfig['config'], $oldConfig['config']) && is_array($newConfig['config']) && is_array($oldConfig['config'])) {
            $newConfig['config'] = $this->mergeConfigDefinitionValues($newConfig['config'], $oldConfig['config']);
        }

        file_put_contents($path, '<?php  return ' . var_export($newConfig, true) . ';');
        @unlink($copyPath);
    }

    private function mergeThemeConfigFile(string $path): void
    {
        $copyPath = update_extract_backup_copy_path($path);
        $newConfig = file_exists($path) ? json_decode((string) file_get_contents($path), true) : [];
        $oldConfig = file_exists($copyPath) ? json_decode((string) file_get_contents($copyPath), true) : [];
        if (!is_array($newConfig) || !is_array($oldConfig)) {
            @unlink($copyPath);
            return;
        }

        $merged = $this->mergeThemeSettings($newConfig, $oldConfig);
        file_put_contents($path, json_encode($merged, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
        @unlink($copyPath);
    }

    private function mergePlainPhpArrayFile(string $path): void
    {
        $copyPath = update_extract_backup_copy_path($path);
        $newContent = file_exists($path) ? file_get_contents($path) : false;
        $oldContent = file_exists($copyPath) ? file_get_contents($copyPath) : false;
        if (!is_string($newContent) || !is_string($oldContent) || !$this->isPlainPhpArrayFile($newContent) || !$this->isPlainPhpArrayFile($oldContent)) {
            @unlink($copyPath);
            return;
        }

        $newConfig = include $path;
        $oldConfig = include $copyPath;
        if (is_array($newConfig) && is_array($oldConfig)) {
            $merged = $this->mergeArrayPreferOld($newConfig, $oldConfig);
            file_put_contents($path, '<?php return ' . var_export($merged, true) . ';');
        }

        @unlink($copyPath);
    }

    private function mergeConfigDefinitionValues(array $newConfig, array $oldConfig): array
    {
        foreach ($oldConfig as $key => $oldItem) {
            if (!array_key_exists($key, $newConfig)) {
                $newConfig[$key] = $oldItem;
                continue;
            }

            if (!is_array($oldItem) || !is_array($newConfig[$key])) {
                $newConfig[$key] = $oldItem;
                continue;
            }

            if (array_key_exists('value', $oldItem)) {
                $newConfig[$key]['value'] = $oldItem['value'];
            } else {
                $newConfig[$key] = $this->mergeArrayPreferOld($newConfig[$key], $oldItem);
            }
        }

        return $newConfig;
    }

    private function mergeArrayPreferOld(array $newValue, array $oldValue): array
    {
        foreach ($oldValue as $key => $value) {
            if (array_key_exists($key, $newValue) && is_array($newValue[$key]) && is_array($value)) {
                $newValue[$key] = $this->mergeArrayPreferOld($newValue[$key], $value);
                continue;
            }

            $newValue[$key] = $value;
        }

        return $newValue;
    }

    private function mergeThemeSettings(array $newConfig, array $oldConfig): array
    {
        $manifestKeys = [
            'name',
            'author',
            'version',
            'description',
            'identification',
            'preview',
            'cmslimit',
            'directory',
            'level',
            'dependencies',
            'conflicts',
            'compatibility',
            'package_type',
            'installable',
            'disableable',
            'uninstallable',
            'deletable',
            'data_policy',
            'telemetry',
            'protection',
            'ui',
            'legacy',
        ];
        foreach ($oldConfig as $key => $value) {
            if (in_array($key, $manifestKeys, true)) {
                continue;
            }

            if (isset($newConfig[$key]) && is_array($newConfig[$key]) && is_array($value)) {
                $newConfig[$key] = $this->mergeArrayPreferOld($newConfig[$key], $value);
                continue;
            }

            $newConfig[$key] = $value;
        }

        return $newConfig;
    }

    private function extractPhpScalarConfigValue(string $content, string $key): ?string
    {
        $pattern = '/([\'"])' . preg_quote($key, '/') . '\1\s*=>\s*([\'"])(.*?)\2/s';
        if (!preg_match($pattern, $content, $matches)) {
            return null;
        }

        return $matches[3];
    }

    private function replacePhpScalarConfigValue(string $content, string $key, string $value): string
    {
        $pattern = '/(([\'"])' . preg_quote($key, '/') . '\2\s*=>\s*)([\'"]).*?\3/s';
        return (string) preg_replace_callback($pattern, static function (array $matches) use ($value) {
            return $matches[1] . var_export($value, true);
        }, $content, 1);
    }

    private function isPlainPhpArrayFile(string $content): bool
    {
        foreach (['env(', 'function', '::class', 'base_path(', 'app_path(', 'storage_path(', 'public_path(', 'url('] as $token) {
            if (stripos($content, $token) !== false) {
                return false;
            }
        }

        return stripos($content, 'return') !== false;
    }

    private function detectPackageMigrations(\PclZip $zip): array
    {
        $list = $zip->listContent();
        if (!is_array($list)) {
            return [];
        }

        $packages = [];
        foreach ($list as $entry) {
            $filename = str_replace('\\', '/', trim((string) ($entry['filename'] ?? ''), '/'));
            if ($filename === '') {
                continue;
            }

            $segments = array_values(array_filter(explode('/', $filename), static function ($segment) {
                return $segment !== '';
            }));
            if (count($segments) < 5) {
                continue;
            }

            $root = strtolower((string) $segments[0]);
            if (!in_array($root, ['modules', 'plugins'], true)) {
                continue;
            }

            if (
                strtolower((string) $segments[2]) !== 'database'
                || strtolower((string) $segments[3]) !== 'migrations'
                || strtolower((string) $segments[4]) !== 'update'
            ) {
                continue;
            }

            $type = $root === 'plugins' ? 'plugin' : 'module';
            $name = trim((string) $segments[1]);
            if ($name === '' || in_array($name, ['Main', 'Install'], true)) {
                continue;
            }

            $key = $type . ':' . strtolower($name);
            if (!isset($packages[$key])) {
                $packages[$key] = [
                    'type' => $type,
                    'name' => $name,
                ];
            }
        }

        return array_values($packages);
    }

    private function dispatchPackageMigrations(array $packages): array
    {
        $postActions = [];
        foreach ($packages as $package) {
            $type = trim((string) ($package['type'] ?? ''));
            $name = trim((string) ($package['name'] ?? ''));
            if ($type === '' || $name === '') {
                continue;
            }
            $postActions[] = $this->dispatchPackageMigration($type, $name);
        }

        return array_values(array_filter($postActions, static function ($item) {
            return is_array($item) && !empty($item);
        }));
    }

    private function formatScannedPackages(array $packages): array
    {
        $items = [];
        foreach ($packages as $package) {
            $type = trim((string) ($package['type'] ?? ''));
            $name = trim((string) ($package['name'] ?? ''));
            if ($type === '' || $name === '') {
                continue;
            }
            $items[] = [
                'type' => $type,
                'label' => ($type === 'plugin' ? '插件' : '模块') . ' ' . $name,
                'name' => $name,
            ];
        }

        return $items;
    }

    private function dispatchPackageMigration(string $type, string $name): array
    {
        $directory = package_directory_name($name, $type);
        $path = $type === 'plugin'
            ? plugins_relative_path($directory . '/Database/Migrations/update')
            : modules_relative_path($directory . '/Database/Migrations/update');
        if (!is_dir(base_path($path))) {
            return [];
        }

        try {
            $task = app(AsyncArtisanDispatcher::class)->dispatch('migrate', [
                '--path' => $path,
                '--force' => 1,
            ], (string) $name, [
                'source' => 'cms_main_unzip',
                'stage' => 'package_update_migration',
                'cloud_type' => $type,
                'path' => $path,
            ]);

            return [
                'name' => 'migrate',
                'status' => 'queued',
                'async_id' => $task['async_id'] ?? '',
                'msg' => ($type === 'plugin' ? '插件' : '模块') . ' ' . $name . ' 数据迁移任务已提交',
            ];
        } catch (\Throwable $exception) {
            UpdateLogger::log('cms.package_migrate_dispatch_failed', [
                'module' => $name,
                'cloud_type' => $type,
                'path' => $path,
                'error' => $exception->getMessage(),
            ]);

            return [
                'name' => 'migrate',
                'status' => 'failed',
                'msg' => ($type === 'plugin' ? '插件' : '模块') . ' ' . $name . ' 数据迁移任务提交失败，请查看日志',
            ];
        }
    }

    private function normalizeLegacyPackageRoots(string $basePath): void
    {
        foreach (['Modules' => 'modules', 'Plugins' => 'plugins'] as $legacy => $target) {
            $this->normalizeLegacyPackageRoot($basePath, $legacy, $target);
        }
    }

    private function normalizeLegacyPackageRoot(string $basePath, string $legacyName, string $targetName): void
    {
        $legacyRoot = rtrim($basePath, '\\/') . DIRECTORY_SEPARATOR . $legacyName;
        $targetRoot = rtrim($basePath, '\\/') . DIRECTORY_SEPARATOR . $targetName;
        if (!is_dir($legacyRoot)) {
            return;
        }

        $legacyRealPath = realpath($legacyRoot);
        $targetRealPath = realpath($targetRoot);
        if ($legacyRealPath && $targetRealPath && strcasecmp($legacyRealPath, $targetRealPath) === 0) {
            return;
        }

        if (!is_dir($targetRoot) && @rename($legacyRoot, $targetRoot)) {
            UpdateLogger::log('cms.package_root_normalized', [
                'legacy_root' => $legacyRoot,
                'target_root' => $targetRoot,
                'mode' => 'rename',
            ]);
            return;
        }

        $this->mergeDirectories($legacyRoot, $targetRoot);
        $this->removeDirectory($legacyRoot);
        UpdateLogger::log('cms.package_root_normalized', [
            'legacy_root' => $legacyRoot,
            'target_root' => $targetRoot,
            'mode' => 'merge',
        ]);
    }

    private function mergeDirectories(string $source, string $target): void
    {
        if (!is_dir($source)) {
            return;
        }

        mk_dir($target);
        foreach (scandir($source) ?: [] as $entry) {
            if ($entry === '.' || $entry === '..') {
                continue;
            }

            $sourcePath = $source . DIRECTORY_SEPARATOR . $entry;
            $targetPath = $target . DIRECTORY_SEPARATOR . $entry;
            if (is_dir($sourcePath)) {
                $this->mergeDirectories($sourcePath, $targetPath);
                continue;
            }

            if (file_exists($targetPath)) {
                @chmod($targetPath, 0777);
                @unlink($targetPath);
            }
            mk_dir(dirname($targetPath));
            @copy($sourcePath, $targetPath);
            @chmod($targetPath, 0755);
        }
    }

    private function removeDirectory(string $path): void
    {
        if (!is_dir($path)) {
            return;
        }

        foreach (scandir($path) ?: [] as $entry) {
            if ($entry === '.' || $entry === '..') {
                continue;
            }

            $childPath = $path . DIRECTORY_SEPARATOR . $entry;
            if (is_dir($childPath)) {
                $this->removeDirectory($childPath);
                continue;
            }

            @chmod($childPath, 0777);
            @unlink($childPath);
        }

        @rmdir($path);
    }
}
