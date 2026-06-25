<?php

namespace App\Support\Installer;

class DirectoryInspector
{
    public static function inspectLegacy(?array $folders = null): array
    {
        $folders = $folders ?: static::defaultFolders();
        $result = [];

        foreach ($folders as $dir) {
            $testPath = base_path($dir);
            static::ensureDirectoryOrFileParent($testPath);
            $isFile = is_file($testPath);

            $result[$dir] = [
                'w' => $isFile ? is_writable($testPath) : static::testWrite($testPath),
                'r' => is_readable($testPath),
            ];
        }

        return $result;
    }

    public static function inspectStructured(?array $folders = null): array
    {
        $folders = $folders ?: static::defaultFolders();
        $result = [];

        foreach ($folders as $dir) {
            $legacy = static::inspectLegacy([$dir])[$dir];
            $result[] = [
                'key' => $dir,
                'title' => $dir,
                'current' => [
                    'readable' => $legacy['r'],
                    'writable' => $legacy['w'],
                ],
                'expected' => [
                    'readable' => true,
                    'writable' => true,
                ],
                'required' => true,
                'status' => ($legacy['r'] && $legacy['w']) ? 'pass' : 'fail',
                'message' => ($legacy['r'] && $legacy['w']) ? '通过' : '不通过',
                'risk_level' => ($legacy['r'] && $legacy['w']) ? 'low' : 'high',
            ];
        }

        return $result;
    }

    public static function defaultFolders(): array
    {
        return [
            'app',
            'bootstrap/cache',
            'config',
            package_root_relative('module'),
            package_root_relative('plugin'),
            'public',
            'storage',
            'storage/logs',
            'storage/backup',
            'storage/download',
            'storage/download/cms',
            'storage/download/module',
            'storage/download/plugin',
            'storage/download/theme',
            'storage/framework',
            'storage/framework/cache',
            'storage/framework/sessions',
            'storage/framework/views',
            'vendor',
            '.env',
        ];
    }

    private static function ensureDirectoryOrFileParent(string $path): void
    {
        if (is_file($path) || is_dir($path)) {
            return;
        }

        $parent = dirname($path);
        if (!is_dir($parent)) {
            @mkdir($parent, 0777, true);
        }

        if (substr($path, -4) !== '.env' && strpos(basename($path), '.') === false) {
            @mkdir($path, 0777, true);
        }
    }

    private static function testWrite(string $directory): bool
    {
        if (!is_dir($directory)) {
            return false;
        }

        $testFile = $directory . DIRECTORY_SEPARATOR . '_installer_write_test.txt';
        $handler = @fopen($testFile, 'w');
        if (!$handler) {
            return false;
        }
        fclose($handler);

        return @unlink($testFile);
    }
}
