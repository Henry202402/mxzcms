<?php

namespace App\Support\Telemetry;

class StatisticReporter
{
    public static function report(string $action, string $identification, string $type, array $extra = []): void
    {
        if (!function_exists('hook')) {
            return;
        }

        $payload = array_merge([
            'moduleName' => 'System',
            'action' => $action,
            'identification' => $identification,
            'type' => $type,
        ], $extra);

        try {
            hook('Statistic', $payload);
        } catch (\Throwable $throwable) {
        }
    }

    public static function reportBlocked(
        string $action,
        string $identification,
        string $type,
        string $reason,
        array $extra = []
    ): void {
        static::report($action, $identification, $type, array_merge([
            'result' => 'blocked',
            'reason' => $reason,
        ], $extra));
    }

    public static function reportSuccess(string $action, string $identification, string $type, array $extra = []): void
    {
        static::report($action, $identification, $type, array_merge([
            'result' => 'success',
        ], $extra));
    }

    public static function reportFailure(string $action, string $identification, string $type, string $reason, array $extra = []): void
    {
        static::report($action, $identification, $type, array_merge([
            'result' => 'failure',
            'reason' => $reason,
        ], $extra));
    }
}
