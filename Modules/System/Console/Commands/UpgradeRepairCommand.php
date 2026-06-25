<?php

namespace Modules\System\Console\Commands;

use Illuminate\Console\Command;
use ZipArchive;

class UpgradeRepairCommand extends Command
{
    protected $signature = 'mxz:upgrade:repair
                            {--from= : 旧站点根目录的备份路径（用于回填配置）}
                            {--snapshot= : 升级前配置快照 zip 文件路径（会自动解压后回填）}
                            {--dry-run : 仅输出将执行的动作，不写入文件}
                            {--merge-root-config : 允许合并根目录 config/*.php（仅对纯数组配置生效）}';

    protected $description = '旧版手动覆盖升级到新版后：修复目录大小写并回填关键配置';

    public function handle(): int
    {
        $basePath = base_path();
        $dryRun = (bool) $this->option('dry-run');
        $from = trim((string) $this->option('from'));
        $snapshot = trim((string) $this->option('snapshot'));
        $mergeRootConfig = (bool) $this->option('merge-root-config');
        $tempExtractDir = '';

        $this->line('Base: ' . $basePath);
        if ($dryRun) {
            $this->warn('Dry-run 模式：不会写入文件');
        }

        $this->info('1) 归一化顶层目录：Modules/Plugins -> modules/plugins');
        $this->normalizeLegacyRoot($basePath, 'Modules', 'modules', $dryRun);
        $this->normalizeLegacyRoot($basePath, 'Plugins', 'plugins', $dryRun);

        if ($from === '' && $snapshot !== '') {
            $snapshotAbs = $this->toAbsolutePath($snapshot, $basePath);
            if (!is_file($snapshotAbs)) {
                $this->error('--snapshot 文件不存在：' . $snapshotAbs);
                return self::FAILURE;
            }
            if (!class_exists(ZipArchive::class)) {
                $this->error('ZipArchive 扩展未启用，无法解压快照');
                return self::FAILURE;
            }
            if ($dryRun) {
                $this->line('2) 跳过配置回填（dry-run 模式不会解压 --snapshot）');
            } else {
                $tempExtractDir = $this->extractSnapshotZip($snapshotAbs);
                if ($tempExtractDir === '') {
                    return self::FAILURE;
                }
                $from = $tempExtractDir;
            }
        }

        if ($from !== '') {
            $fromAbs = $this->toAbsolutePath($from, $basePath);
            if (!is_dir($fromAbs)) {
                $this->error('--from 目录不存在：' . $fromAbs);
                return self::FAILURE;
            }

            $this->info('2) 回填配置：从 ' . $fromAbs);
            $this->mergePluginConfigs($basePath, $fromAbs, $dryRun);
            $this->mergeModuleConfigs($basePath, $fromAbs, $dryRun);
            $this->mergeThemeConfigs($basePath, $fromAbs, $dryRun);
            if ($mergeRootConfig) {
                $this->mergeRootConfigFiles($basePath, $fromAbs, $dryRun);
            } else {
                $this->line('- 跳过根目录 config/*.php 合并（可用 --merge-root-config 启用）');
            }
        } else {
            $this->line('2) 跳过配置回填（未提供 --from）');
        }

        if ($tempExtractDir !== '' && is_dir($tempExtractDir)) {
            $this->line('3) 清理临时目录：' . $tempExtractDir);
            $this->removeDirectory($tempExtractDir);
        }

        $this->info('完成。建议执行：php artisan optimize:clear');
        return self::SUCCESS;
    }

    private function extractSnapshotZip(string $zipFile): string
    {
        $base = storage_path('upgrade-repair');
        if (!is_dir($base)) {
            @mkdir($base, 0755, true);
        }

        $runId = date('ymdHis') . '_' . substr(bin2hex(random_bytes(6)), 0, 12);
        $targetDir = $base . DIRECTORY_SEPARATOR . $runId;
        @mkdir($targetDir, 0755, true);

        $zip = new ZipArchive();
        $opened = $zip->open($zipFile);
        if ($opened !== true) {
            $this->removeDirectory($targetDir);
            $this->error('快照解压失败：无法打开 zip');
            return '';
        }
        if (!$zip->extractTo($targetDir)) {
            $zip->close();
            $this->removeDirectory($targetDir);
            $this->error('快照解压失败：无法解压 zip');
            return '';
        }
        $zip->close();

        return $targetDir;
    }

    private function toAbsolutePath(string $path, string $basePath): string
    {
        if (preg_match('/^[A-Za-z]:\\\\/', $path) || str_starts_with($path, '/') || str_starts_with($path, '\\\\')) {
            return $path;
        }

        return rtrim($basePath, '\\/') . DIRECTORY_SEPARATOR . ltrim($path, '\\/');
    }

    private function normalizeLegacyRoot(string $basePath, string $legacyName, string $targetName, bool $dryRun): void
    {
        $legacyRoot = rtrim($basePath, '\\/') . DIRECTORY_SEPARATOR . $legacyName;
        $targetRoot = rtrim($basePath, '\\/') . DIRECTORY_SEPARATOR . $targetName;
        if (!is_dir($legacyRoot)) {
            $this->line("- 不存在：{$legacyName}");
            return;
        }

        if (!is_dir($targetRoot)) {
            $this->line("- {$legacyName} -> {$targetName}（rename）");
            if (!$dryRun) {
                @rename($legacyRoot, $targetRoot);
            }
            return;
        }

        $this->line("- {$legacyName} 合并到 {$targetName}（merge）");
        if (!$dryRun) {
            $this->mergeDirectories($legacyRoot, $targetRoot);
            $this->removeDirectory($legacyRoot);
        }
    }

    private function mergeDirectories(string $source, string $target): void
    {
        if (!is_dir($source)) {
            return;
        }
        if (!is_dir($target)) {
            @mkdir($target, 0755, true);
        }
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
            if (!is_dir(dirname($targetPath))) {
                @mkdir(dirname($targetPath), 0755, true);
            }
            @copy($sourcePath, $targetPath);
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

    private function mergePluginConfigs(string $newRoot, string $oldRoot, bool $dryRun): void
    {
        $newPlugins = $this->firstExistingDir($newRoot, ['plugins', 'Plugins']);
        $oldPlugins = $this->firstExistingDir($oldRoot, ['plugins', 'Plugins']);
        if (!$newPlugins || !$oldPlugins) {
            $this->line('- 插件目录未找到，跳过');
            return;
        }

        $newPluginRoot = $newRoot . DIRECTORY_SEPARATOR . $newPlugins;
        $oldPluginRoot = $oldRoot . DIRECTORY_SEPARATOR . $oldPlugins;
        foreach (scandir($newPluginRoot) ?: [] as $pluginDir) {
            if ($pluginDir === '.' || $pluginDir === '..') {
                continue;
            }
            $newConfigFile = $newPluginRoot . DIRECTORY_SEPARATOR . $pluginDir . DIRECTORY_SEPARATOR . 'Config' . DIRECTORY_SEPARATOR . 'config.php';
            $oldConfigFile = $oldPluginRoot . DIRECTORY_SEPARATOR . $pluginDir . DIRECTORY_SEPARATOR . 'Config' . DIRECTORY_SEPARATOR . 'config.php';
            if (!is_file($newConfigFile) || !is_file($oldConfigFile)) {
                continue;
            }
            $newConfig = include $newConfigFile;
            $oldConfig = include $oldConfigFile;
            if (!is_array($newConfig) || !is_array($oldConfig)) {
                continue;
            }
            if (!isset($newConfig['config'], $oldConfig['config']) || !is_array($newConfig['config']) || !is_array($oldConfig['config'])) {
                continue;
            }

            $merged = $this->mergeConfigDefinitionValues($newConfig['config'], $oldConfig['config']);
            if ($merged === $newConfig['config']) {
                continue;
            }

            $this->line("- 回填插件配置：{$pluginDir}");
            if (!$dryRun) {
                $newConfig['config'] = $merged;
                file_put_contents($newConfigFile, '<?php  return ' . var_export($newConfig, true) . ';');
            }
        }
    }

    private function mergeModuleConfigs(string $newRoot, string $oldRoot, bool $dryRun): void
    {
        $newModules = $this->firstExistingDir($newRoot, ['modules', 'Modules']);
        $oldModules = $this->firstExistingDir($oldRoot, ['modules', 'Modules']);
        if (!$newModules || !$oldModules) {
            $this->line('- 模块目录未找到，跳过');
            return;
        }

        $newModuleRoot = $newRoot . DIRECTORY_SEPARATOR . $newModules;
        $oldModuleRoot = $oldRoot . DIRECTORY_SEPARATOR . $oldModules;
        foreach (scandir($newModuleRoot) ?: [] as $moduleDir) {
            if ($moduleDir === '.' || $moduleDir === '..') {
                continue;
            }
            $newConfigFile = $newModuleRoot . DIRECTORY_SEPARATOR . $moduleDir . DIRECTORY_SEPARATOR . 'Config' . DIRECTORY_SEPARATOR . 'config.php';
            $oldConfigFile = $oldModuleRoot . DIRECTORY_SEPARATOR . $moduleDir . DIRECTORY_SEPARATOR . 'Config' . DIRECTORY_SEPARATOR . 'config.php';
            if (!is_file($newConfigFile) || !is_file($oldConfigFile)) {
                continue;
            }

            $newContent = file_get_contents($newConfigFile);
            $oldContent = file_get_contents($oldConfigFile);
            if (!is_string($newContent) || !is_string($oldContent)) {
                continue;
            }

            $updated = $newContent;
            foreach (['domain', 'auth', 'addmodel'] as $key) {
                $oldValue = $this->extractPhpScalarConfigValue($oldContent, $key);
                $newValue = $this->extractPhpScalarConfigValue($newContent, $key);
                if ($oldValue === null || $newValue === null || $oldValue === $newValue) {
                    continue;
                }
                $updated = $this->replacePhpScalarConfigValue($updated, $key, $oldValue);
            }

            if ($updated === $newContent) {
                continue;
            }

            $this->line("- 回填模块关键配置：{$moduleDir}（domain/auth/addmodel）");
            if (!$dryRun) {
                file_put_contents($newConfigFile, $updated);
            }
        }
    }

    private function mergeThemeConfigs(string $newRoot, string $oldRoot, bool $dryRun): void
    {
        $newThemeRoot = $newRoot . DIRECTORY_SEPARATOR . 'public' . DIRECTORY_SEPARATOR . 'views' . DIRECTORY_SEPARATOR . 'themes';
        $oldThemeRoot = $oldRoot . DIRECTORY_SEPARATOR . 'public' . DIRECTORY_SEPARATOR . 'views' . DIRECTORY_SEPARATOR . 'themes';
        if (!is_dir($newThemeRoot) || !is_dir($oldThemeRoot)) {
            $this->line('- 主题目录未找到，跳过');
            return;
        }

        foreach (scandir($newThemeRoot) ?: [] as $themeDir) {
            if ($themeDir === '.' || $themeDir === '..') {
                continue;
            }
            $newConfigFile = $newThemeRoot . DIRECTORY_SEPARATOR . $themeDir . DIRECTORY_SEPARATOR . 'config.json';
            $oldConfigFile = $oldThemeRoot . DIRECTORY_SEPARATOR . $themeDir . DIRECTORY_SEPARATOR . 'config.json';
            if (!is_file($newConfigFile) || !is_file($oldConfigFile)) {
                continue;
            }

            $newConfig = json_decode((string) file_get_contents($newConfigFile), true);
            $oldConfig = json_decode((string) file_get_contents($oldConfigFile), true);
            if (!is_array($newConfig) || !is_array($oldConfig)) {
                continue;
            }

            $merged = $this->mergeThemeSettings($newConfig, $oldConfig);
            if ($merged === $newConfig) {
                continue;
            }

            $this->line("- 回填主题配置：{$themeDir}（非 manifest 字段）");
            if (!$dryRun) {
                file_put_contents($newConfigFile, json_encode($merged, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
            }
        }
    }

    private function mergeRootConfigFiles(string $newRoot, string $oldRoot, bool $dryRun): void
    {
        $newConfigDir = $newRoot . DIRECTORY_SEPARATOR . 'config';
        $oldConfigDir = $oldRoot . DIRECTORY_SEPARATOR . 'config';
        if (!is_dir($newConfigDir) || !is_dir($oldConfigDir)) {
            $this->line('- 根配置目录未找到，跳过');
            return;
        }

        foreach (scandir($newConfigDir) ?: [] as $file) {
            if ($file === '.' || $file === '..') {
                continue;
            }
            if (!str_ends_with($file, '.php')) {
                continue;
            }
            $newFile = $newConfigDir . DIRECTORY_SEPARATOR . $file;
            $oldFile = $oldConfigDir . DIRECTORY_SEPARATOR . $file;
            if (!is_file($newFile) || !is_file($oldFile)) {
                continue;
            }
            $newContent = file_get_contents($newFile);
            $oldContent = file_get_contents($oldFile);
            if (!is_string($newContent) || !is_string($oldContent) || !$this->isPlainPhpArrayFile($newContent) || !$this->isPlainPhpArrayFile($oldContent)) {
                continue;
            }

            $newArr = include $newFile;
            $oldArr = include $oldFile;
            if (!is_array($newArr) || !is_array($oldArr)) {
                continue;
            }
            $merged = $this->mergeArrayPreferOld($newArr, $oldArr);
            if ($merged === $newArr) {
                continue;
            }

            $this->line("- 合并根配置：config/{$file}（仅纯数组）");
            if (!$dryRun) {
                file_put_contents($newFile, '<?php return ' . var_export($merged, true) . ';');
            }
        }
    }

    private function firstExistingDir(string $root, array $candidates): ?string
    {
        foreach ($candidates as $candidate) {
            if (is_dir($root . DIRECTORY_SEPARATOR . $candidate)) {
                return $candidate;
            }
        }
        return null;
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
}

