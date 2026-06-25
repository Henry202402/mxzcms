<?php

namespace Modules\Main\Libs;

use Illuminate\Support\Facades\Artisan;
use RuntimeException;

class CMSBOOSTRAP
{
    /**
     * Legacy entry kept for compatibility with existing calls.
     */
    public static function boostrap(): void
    {
        self::bootstrap();
    }

    public static function bootstrap(): void
    {
        $self = new self();
        $self->ensureSupportedFunctions();
        self::ensureDirectories();
        $self->ensureEnvFile();
        $self->configureErrorReporting();
    }

    public static function checkEnvValue(): void
    {
        self::ensureApplicationKey();
    }

    public static function checkDir(): void
    {
        self::ensureDirectories();
    }

    public static function ensureApplicationKey(): void
    {
        if (config('app.key')) {
            return;
        }

        $envPath = base_path('.env');
        if (!file_exists($envPath)) {
            return;
        }
        if (!is_writable($envPath)) {
            throw new RuntimeException('.env is not writable, unable to generate APP_KEY automatically');
        }

        try {
            Artisan::call('key:generate', ['--force' => true]);
        } catch (\Throwable $exception) {
            throw new RuntimeException('generate APP_KEY failed: ' . $exception->getMessage(), 0, $exception);
        }
    }

    public static function ensureDirectories(): void
    {
        foreach (self::directoryList() as $dir) {
            if (!is_dir($dir) && !mkdir($dir, 0777, true) && !is_dir($dir)) {
                throw new RuntimeException('create directory failed: ' . $dir);
            }
            if (!is_writable($dir)) {
                @chmod($dir, 0777);
            }
        }
    }

    private static function directoryList(): array
    {
        return [
            base_path('bootstrap/cache'),
            public_path('views/modules'),
            storage_path('logs'),
            storage_path('backup'),
            storage_path('download'),
            storage_path('download/cms'),
            storage_path('download/module'),
            storage_path('download/plugin'),
            storage_path('download/theme'),
            storage_path('framework'),
            storage_path('framework/sessions'),
            storage_path('framework/views'),
            storage_path('framework/cache'),
        ];
    }

    private function ensureEnvFile(): void
    {
        if (file_exists(base_path('.env'))) {
            return;
        }

        $envExamplePath = base_path('.env.example');
        if (!file_exists($envExamplePath)) {
            throw new RuntimeException('.env.example is not exists');
        }

        $env = file_get_contents($envExamplePath);
        if ($env === false) {
            throw new RuntimeException('read .env.example failed');
        }

        file_put_contents(base_path('.env'), $env);
    }

    private function ensureSupportedFunctions(): void
    {
        foreach (['file_get_contents', 'file_put_contents', 'getenv', 'fopen', 'chmod', 'unlink', 'symlink'] as $function) {
            if (!function_exists($function)) {
                throw new RuntimeException($function . ' function is not exists');
            }
        }
    }

    private function configureErrorReporting(): void
    {
        // Keep production-style suppression in normal runtime, but expose full errors in debug mode.
        $debug = config('app.debug');
        if (!is_bool($debug)) {
            $debug = filter_var($_ENV['APP_DEBUG'] ?? getenv('APP_DEBUG') ?: false, FILTER_VALIDATE_BOOLEAN);
        }
        error_reporting($debug ? E_ALL : E_ERROR);
    }
}
