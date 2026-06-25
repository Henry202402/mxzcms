<?php

namespace App\Support\Installer;

class ComposerAutoloadInspector
{
    public static function inspectStructured(): array
    {
        $composer = static::readComposerJson();
        $psr4 = $composer['autoload']['psr-4'] ?? [];
        if (!is_array($psr4)) {
            $psr4 = [];
        }

        return [
            static::buildCheck(
                'module_psr4_root',
                'Composer 模块自动加载目录',
                'Modules\\',
                (string) ($psr4['Modules\\'] ?? ''),
                package_root_relative('module')
            ),
            static::buildCheck(
                'plugin_psr4_root',
                'Composer 插件自动加载目录',
                'Plugins\\',
                (string) ($psr4['Plugins\\'] ?? ''),
                package_root_relative('plugin')
            ),
        ];
    }

    private static function buildCheck(
        string $key,
        string $title,
        string $namespace,
        string $currentMapping,
        string $expectedRoot
    ): array {
        $normalizedCurrent = static::normalizePath($currentMapping);
        $normalizedExpected = static::normalizePath($expectedRoot);
        $passed = $normalizedCurrent !== '' && strcasecmp($normalizedCurrent, $normalizedExpected) === 0;

        $currentDisplay = $currentMapping !== '' ? static::normalizeDisplayPath($currentMapping) : '(missing)';
        $expectedDisplay = static::normalizeDisplayPath($expectedRoot . '/');
        $label = $passed
            ? sprintf('%s 已匹配当前目录：%s => %s', $title, $namespace, $currentDisplay)
            : sprintf('%s 与当前目录不一致：%s => %s，当前目录为 %s', $title, $namespace, $currentDisplay, $expectedDisplay);

        return [
            'key' => $key,
            'title' => $title,
            'label' => $label,
            'current' => $currentDisplay,
            'expected' => $expectedDisplay,
            'required' => false,
            'status' => $passed ? 'pass' : 'fail',
            'message' => $passed ? '通过' : '存在兼容性风险',
            'risk_level' => $passed ? 'low' : 'medium',
            'namespace' => $namespace,
        ];
    }

    private static function readComposerJson(): array
    {
        $file = base_path('composer.json');
        if (!is_file($file)) {
            return [];
        }

        $content = file_get_contents($file);
        if (!is_string($content) || trim($content) === '') {
            return [];
        }

        $decoded = json_decode($content, true);
        return is_array($decoded) ? $decoded : [];
    }

    private static function normalizePath(string $path): string
    {
        return trim(str_replace('\\', '/', $path), '/');
    }

    private static function normalizeDisplayPath(string $path): string
    {
        $normalized = static::normalizePath($path);
        return $normalized === '' ? '' : $normalized . '/';
    }
}
