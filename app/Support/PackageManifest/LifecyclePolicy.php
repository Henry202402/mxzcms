<?php

namespace App\Support\PackageManifest;

class LifecyclePolicy
{
    public static function canUninstall(array $manifest): array
    {
        if (!($manifest['uninstallable'] ?? true)) {
            return [
                'allowed' => false,
                'message' => static::uninstallDeniedMessage($manifest),
            ];
        }

        return [
            'allowed' => true,
            'message' => '',
        ];
    }

    public static function canDisable(array $manifest): array
    {
        if (!($manifest['disableable'] ?? true)) {
            return [
                'allowed' => false,
                'message' => '当前包不允许禁用',
            ];
        }

        return [
            'allowed' => true,
            'message' => '',
        ];
    }

    private static function uninstallDeniedMessage(array $manifest): string
    {
        $name = $manifest['name'] ?? ($manifest['identification'] ?? '当前模块');
        $level = $manifest['level'] ?? '';

        if ($level === PackageManifest::LEVEL_CORE) {
            return $name . '属于核心模块，不能卸载';
        }

        if ($level === PackageManifest::LEVEL_BASE) {
            return $name . '属于平台基础模块，暂不允许卸载';
        }

        return $name . '当前不允许卸载';
    }
}
