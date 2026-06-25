<?php

namespace App\Support\Update;

class UpdateLogger
{
    public static function log(string $event, array $payload = []): void
    {
        $directory = storage_path('logs');
        if (!is_dir($directory)) {
            @mkdir($directory, 0777, true);
        }

        $record = [
            'time' => date('Y-m-d H:i:s'),
            'event' => $event,
            'request_id' => request()->requestid ?? null,
            'payload' => $payload,
        ];

        @file_put_contents(
            $directory . DIRECTORY_SEPARATOR . 'update.log',
            json_encode($record, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) . PHP_EOL,
            FILE_APPEND
        );
    }
}
