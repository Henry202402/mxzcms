<?php

namespace App\Support\PackageManifest;

use Modules\Main\Models\Modules;

class PackageManifest
{
    public const PACKAGE_MODULE = 'module';
    public const PACKAGE_PLUGIN = 'plugin';
    public const PACKAGE_THEME = 'theme';

    public const LEVEL_CORE = 'core';
    public const LEVEL_BASE = 'base';
    public const LEVEL_OPTIONAL = 'optional';
    public const LEVEL_BUSINESS = 'business';
    public const LEVEL_EXTENSION = 'extension';

    /**
     * Load and normalize a package manifest from disk.
     */
    public static function load(string $identification, string $packageType): ?array
    {
        $file = static::configPath($identification, $packageType);
        if (!$file || !file_exists($file)) {
            return null;
        }

        if ($packageType === self::PACKAGE_THEME) {
            $raw = json_decode(file_get_contents($file), true);
        } else {
            $raw = include $file;
        }

        if (!is_array($raw)) {
            return null;
        }

        return static::normalize($raw, $packageType, $identification);
    }

    /**
     * Normalize legacy config.php/config.json fields into a stable structure.
     */
    public static function normalize(array $raw, string $packageType, string $fallbackIdentification = ''): array
    {
        $identification = $raw['identification'] ?? $fallbackIdentification;
        $identification = $identification ?: $fallbackIdentification;
        $level = static::resolveLevel($identification, $packageType, $raw['level'] ?? null);
        $defaults = static::defaultsForLevel($level, $packageType);

        $dependencies = [];
        if (!empty($raw['dependencies']) && is_array($raw['dependencies'])) {
            $dependencies = array_values($raw['dependencies']);
        }

        if (!empty($raw['pluginlimit']) && $packageType === self::PACKAGE_MODULE) {
            $legacyDependency = static::parseLegacyPluginLimit($raw['pluginlimit']);
            if ($legacyDependency) {
                $dependencies[] = $legacyDependency;
            }
        }

        $compatibility = [
            'cms' => static::resolveCompatibilityValue($raw, 'cms', $raw['cmslimit'] ?? '*'),
            'php' => static::resolveCompatibilityValue($raw, 'php', '*'),
            'laravel' => static::resolveCompatibilityValue($raw, 'laravel', '*'),
        ];

        return array_merge($raw, [
            'name' => $raw['name'] ?? $identification,
            'identification' => $identification,
            'directory' => $raw['directory'] ?? strtolower($identification),
            'package_type' => $raw['package_type'] ?? $packageType,
            'level' => $level,
            'author' => $raw['author'] ?? '',
            'version' => $raw['version'] ?? '',
            'description' => $raw['description'] ?? '',
            'installable' => $raw['installable'] ?? $defaults['installable'],
            'disableable' => $raw['disableable'] ?? $defaults['disableable'],
            'uninstallable' => $raw['uninstallable'] ?? $defaults['uninstallable'],
            'deletable' => $raw['deletable'] ?? $defaults['deletable'],
            'compatibility' => $compatibility,
            'dependencies' => $dependencies,
            'conflicts' => $raw['conflicts'] ?? [],
            'data_policy' => $raw['data_policy'] ?? $defaults['data_policy'],
            'telemetry' => $raw['telemetry'] ?? $defaults['telemetry'],
            'protection' => $raw['protection'] ?? $defaults['protection'],
            'ui' => [
                'domain' => $raw['ui']['domain'] ?? ($raw['domain'] ?? 'n'),
                'auth' => $raw['ui']['auth'] ?? ($raw['auth'] ?? 'n'),
                'addmodel' => $raw['ui']['addmodel'] ?? ($raw['addmodel'] ?? 'n'),
                'links' => $raw['ui']['links'] ?? ($raw['links'] ?? []),
            ],
            'legacy' => [
                'cmslimit' => $raw['cmslimit'] ?? '',
                'pluginlimit' => $raw['pluginlimit'] ?? '',
            ],
        ]);
    }

    public static function configPath(string $identification, string $packageType): ?string
    {
        if ($packageType === self::PACKAGE_MODULE) {
            return module_path($identification, 'Config/config.php');
        }

        if ($packageType === self::PACKAGE_PLUGIN) {
            return module_path($identification, 'Config/config.php', 'plugin');
        }

        if ($packageType === self::PACKAGE_THEME) {
            return THEME_PATH . $identification . '/config.json';
        }

        return null;
    }

    private static function resolveCompatibilityValue(array $raw, string $key, string $default): string
    {
        $value = $raw['compatibility'][$key] ?? $default;
        return $value === null || $value === '' ? '*' : $value;
    }

    private static function resolveLevel(string $identification, string $packageType, ?string $customLevel): string
    {
        if ($customLevel) {
            return $customLevel;
        }

        if ($packageType === self::PACKAGE_THEME || $packageType === self::PACKAGE_PLUGIN) {
            return self::LEVEL_EXTENSION;
        }

        $coreModules = ['Main', 'Install', 'System'];
        if (in_array($identification, $coreModules, true)) {
            return self::LEVEL_CORE;
        }

        $baseModules = ['Auth', 'Formtools', 'Member'];
        if (in_array($identification, $baseModules, true)) {
            return self::LEVEL_BASE;
        }

        $optionalModules = ['Files', 'Editor', 'Log', 'Devtools'];
        if (in_array($identification, $optionalModules, true)) {
            return self::LEVEL_OPTIONAL;
        }

        return self::LEVEL_BUSINESS;
    }

    private static function defaultsForLevel(string $level, string $packageType): array
    {
        $defaults = [
            'installable' => true,
            'disableable' => true,
            'uninstallable' => true,
            'deletable' => true,
            'data_policy' => [
                'on_disable' => 'keep',
                'on_uninstall' => 'backup',
                'on_delete' => 'manual',
            ],
            'telemetry' => [
                'install' => true,
                'uninstall' => true,
                'enable' => true,
                'disable' => true,
                'upgrade' => true,
                'usage' => true,
            ],
            'protection' => [
                'mode' => 'none',
                'encrypted_allowed' => false,
                'protected_paths' => [],
            ],
        ];

        if ($level === self::LEVEL_CORE || $level === self::LEVEL_BASE) {
            $defaults['disableable'] = false;
            $defaults['uninstallable'] = false;
            $defaults['deletable'] = false;
        }

        if ($packageType === self::PACKAGE_THEME) {
            $defaults['level'] = self::LEVEL_EXTENSION;
        }

        return $defaults;
    }

    private static function parseLegacyPluginLimit(string $pluginLimit): ?array
    {
        $pluginLimit = trim($pluginLimit);
        if ($pluginLimit === '') {
            return null;
        }

        $parts = explode('-', $pluginLimit, 2);
        $name = trim($parts[0] ?? '');
        $version = trim($parts[1] ?? '');
        if ($name === '') {
            return null;
        }

        return [
            'type' => Modules::Plugin,
            'name' => $name,
            'version' => $version !== '' ? '>=' . $version : '*',
            'source' => 'pluginlimit',
        ];
    }
}
