<?php

namespace App\Support\PackageManifest;

use Modules\Main\Models\Modules;

class CompatibilityChecker
{
    public static function checkInstallable(array $manifest): ?string
    {
        if (!($manifest['installable'] ?? true)) {
            return '当前包不允许安装';
        }

        return static::checkCmsVersion($manifest)
            ?: static::checkPhpVersion($manifest)
            ?: static::checkLaravelVersion($manifest)
            ?: static::checkDependencies($manifest)
            ?: static::checkConflicts($manifest);
    }

    public static function checkCmsVersion(array $manifest, ?string $currentVersion = null): ?string
    {
        $currentVersion = $currentVersion ?: (string) env('APP_VERSION');
        $constraint = $manifest['compatibility']['cms'] ?? '*';
        if ($constraint === '' || $constraint === '*' || $currentVersion === '') {
            return null;
        }

        if (!static::matchesConstraint($currentVersion, $constraint)) {
            return '主程序版本太低，请先升级!';
        }

        return null;
    }

    public static function checkPhpVersion(array $manifest, ?string $currentVersion = null): ?string
    {
        $currentVersion = $currentVersion ?: PHP_VERSION;
        $constraint = $manifest['compatibility']['php'] ?? '*';
        if ($constraint === '' || $constraint === '*' || $currentVersion === '') {
            return null;
        }

        if (!static::matchesConstraint($currentVersion, $constraint)) {
            return 'PHP 版本不满足要求：' . $constraint;
        }

        return null;
    }

    public static function checkLaravelVersion(array $manifest, ?string $currentVersion = null): ?string
    {
        $currentVersion = $currentVersion ?: app()->version();
        $constraint = $manifest['compatibility']['laravel'] ?? '*';
        if ($constraint === '' || $constraint === '*' || $currentVersion === '') {
            return null;
        }

        if (!static::matchesConstraint($currentVersion, $constraint)) {
            return 'Laravel 版本不满足要求：' . $constraint;
        }

        return null;
    }

    public static function checkDependencies(array $manifest): ?string
    {
        foreach ($manifest['dependencies'] ?? [] as $dependency) {
            $type = $dependency['type'] ?? '';
            $name = $dependency['name'] ?? '';
            if ($type === '' || $name === '') {
                continue;
            }

            $installed = Modules::query()
                ->where('cloud_type', $type)
                ->whereRaw('LOWER(identification) = ?', [strtolower($name)])
                ->first();

            if (!$installed) {
                return sprintf(
                    '请先安装依赖%s：%s!',
                    $type === Modules::Plugin ? '插件' : '模块',
                    $name
                );
            }

            $requiredVersion = static::normalizeRequiredVersion($dependency['version'] ?? '*');
            if ($requiredVersion === '*') {
                continue;
            }

            $dependencyManifest = PackageManifest::load($installed->identification, $type);
            $currentVersion = $dependencyManifest['version'] ?? '';
            if ($currentVersion !== '' && version_compare($requiredVersion, $currentVersion) > 0) {
                return sprintf(
                    '依赖%s版本太低，请先升级：%s!',
                    $type === Modules::Plugin ? '插件' : '模块',
                    $name
                );
            }
        }

        return null;
    }

    public static function findDependents(array $manifest): array
    {
        $packageType = $manifest['package_type'] ?? '';
        $identification = $manifest['identification'] ?? '';
        if ($packageType === '' || $identification === '') {
            return [];
        }

        $dependents = [];
        $installedPackages = Modules::query()
            ->whereIn('cloud_type', [Modules::Module, Modules::Plugin])
            ->get();

        foreach ($installedPackages as $installedPackage) {
            if (
                strtolower($installedPackage->identification) === strtolower($identification)
                && $installedPackage->cloud_type === $packageType
            ) {
                continue;
            }

            $installedManifest = PackageManifest::load($installedPackage->identification, $installedPackage->cloud_type);
            if (!$installedManifest) {
                continue;
            }

            foreach ($installedManifest['dependencies'] ?? [] as $dependency) {
                $dependencyType = $dependency['type'] ?? '';
                $dependencyName = $dependency['name'] ?? '';
                if (
                    $dependencyType === $packageType
                    && strtolower($dependencyName) === strtolower($identification)
                ) {
                    $dependents[] = $installedManifest['name'] ?? $installedManifest['identification'];
                    break;
                }
            }
        }

        return array_values(array_unique($dependents));
    }

    public static function checkConflicts(array $manifest): ?string
    {
        foreach ($manifest['conflicts'] ?? [] as $conflict) {
            $type = $conflict['type'] ?? '';
            $name = $conflict['name'] ?? '';
            if ($type === '' || $name === '') {
                continue;
            }

            $installed = Modules::query()
                ->where('cloud_type', $type)
                ->whereRaw('LOWER(identification) = ?', [strtolower($name)])
                ->first();

            if (!$installed) {
                continue;
            }

            $requiredVersion = $conflict['version'] ?? '*';
            if ($requiredVersion === '' || $requiredVersion === '*') {
                return sprintf(
                    '当前包与已安装%s冲突：%s!',
                    $type === Modules::Plugin ? '插件' : '模块',
                    $name
                );
            }

            $installedManifest = PackageManifest::load($installed->identification, $type);
            $currentVersion = $installedManifest['version'] ?? '';
            if ($currentVersion === '' || static::matchesConstraint($currentVersion, $requiredVersion)) {
                return sprintf(
                    '当前包与已安装%s冲突：%s %s!',
                    $type === Modules::Plugin ? '插件' : '模块',
                    $name,
                    $requiredVersion
                );
            }
        }

        return null;
    }

    private static function normalizeRequiredVersion(string $version): string
    {
        $version = trim($version);
        if ($version === '' || $version === '*') {
            return '*';
        }

        foreach (['>=', '<=', '>', '<', '^', '~'] as $prefix) {
            if (strpos($version, $prefix) === 0) {
                return trim(substr($version, strlen($prefix)));
            }
        }

        return $version;
    }

    private static function matchesConstraint(string $currentVersion, string $constraint): bool
    {
        $currentVersion = static::extractVersionNumber($currentVersion);
        $constraint = trim($constraint);
        if ($constraint === '' || $constraint === '*' || $currentVersion === '') {
            return true;
        }

        foreach (preg_split('/\s*\|\|\s*/', $constraint) as $orGroup) {
            $orGroup = trim($orGroup);
            if ($orGroup === '') {
                continue;
            }

            $andMatched = true;
            foreach (preg_split('/[\s,]+/', $orGroup) as $singleConstraint) {
                $singleConstraint = trim($singleConstraint);
                if ($singleConstraint === '') {
                    continue;
                }

                if (!static::matchesSingleConstraint($currentVersion, $singleConstraint)) {
                    $andMatched = false;
                    break;
                }
            }

            if ($andMatched) {
                return true;
            }
        }

        return false;
    }

    private static function matchesSingleConstraint(string $currentVersion, string $constraint): bool
    {
        if ($constraint === '*' || $constraint === '') {
            return true;
        }

        if (preg_match('/^(>=|<=|>|<|=|\^|~)?\s*(.+)$/', $constraint, $matches)) {
            $operator = $matches[1] ?: '=';
            $expected = static::extractVersionNumber($matches[2]);
            if ($expected === '') {
                return true;
            }

            if ($operator === '^' || $operator === '~') {
                return static::matchesRangeConstraint($currentVersion, $expected, $operator);
            }

            return version_compare($currentVersion, $expected, $operator);
        }

        return version_compare($currentVersion, static::extractVersionNumber($constraint), '=');
    }

    private static function matchesRangeConstraint(string $currentVersion, string $expectedVersion, string $operator): bool
    {
        $segments = explode('.', $expectedVersion);
        $major = (int) ($segments[0] ?? 0);
        $minor = (int) ($segments[1] ?? 0);

        if (version_compare($currentVersion, $expectedVersion, '<')) {
            return false;
        }

        if ($operator === '^') {
            $upperBound = ($major + 1) . '.0.0';
            return version_compare($currentVersion, $upperBound, '<');
        }

        $upperBound = $major . '.' . ($minor + 1) . '.0';
        return version_compare($currentVersion, $upperBound, '<');
    }

    private static function extractVersionNumber(string $version): string
    {
        if (preg_match('/\d+(?:\.\d+){0,3}/', $version, $matches)) {
            return $matches[0];
        }

        return trim($version);
    }
}
