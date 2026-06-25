<?php

namespace App\Support\Update;

use App\Support\Installer\ComposerAutoloadInspector;
use App\Support\Installer\DirectoryInspector;
use App\Support\Installer\InstallerInspector;

class UpdatePreflightService
{
    public function inspectApp(array $all): array
    {
        $checks = InstallerInspector::inspectStructuredChecks();
        $checks['directories'] = DirectoryInspector::inspectStructured(
            $this->appDirectories($all['cloudtype'] ?? '')
        );
        $checks['autoload'] = ComposerAutoloadInspector::inspectStructured();

        return $this->buildResult($checks);
    }

    public function inspectCms(): array
    {
        $checks = InstallerInspector::inspectStructuredChecks();
        $checks['directories'] = DirectoryInspector::inspectStructured($this->cmsDirectories());
        $checks['autoload'] = ComposerAutoloadInspector::inspectStructured();

        return $this->buildResult($checks);
    }

    private function buildResult(array $checks): array
    {
        $failedChecks = [];
        $warningChecks = [];
        foreach ($checks as $group => $items) {
            foreach (($items ?: []) as $item) {
                if (($item['status'] ?? 'fail') === 'pass') {
                    continue;
                }

                $withGroup = array_merge($item, [
                    'group' => $group,
                ]);

                if ($item['required'] ?? false) {
                    $failedChecks[] = $withGroup;
                    continue;
                }

                $warningChecks[] = $withGroup;
            }
        }

        return [
            'status' => empty($failedChecks) ? 200 : 0,
            'passed' => empty($failedChecks),
            'msg' => empty($failedChecks) ? '升级环境检测通过' : '升级环境检测未通过，请先处理失败项',
            'checks' => $checks,
            'failed_checks' => $failedChecks,
            'warning_checks' => $warningChecks,
        ];
    }

    private function appDirectories(string $cloudType): array
    {
        $directories = [
            'storage',
            'storage/logs',
            'storage/backup',
            'storage/download',
            'bootstrap/cache',
        ];

        switch ($cloudType) {
            case 'module':
                $directories[] = package_root_relative('module');
                $directories[] = 'storage/download/module';
                break;
            case 'plugin':
                $directories[] = package_root_relative('plugin');
                $directories[] = 'storage/download/plugin';
                break;
            case 'theme':
                $directories[] = 'public/views/themes';
                $directories[] = 'storage/download/theme';
                break;
            default:
                $directories[] = 'storage/download';
                break;
        }

        return $directories;
    }

    private function cmsDirectories(): array
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
            '.env',
            'composer.json',
        ];
    }
}
