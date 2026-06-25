<?php

namespace App\Support\Update;

class UpdateResponseFactory
{
    public static function success(string $msg, array $extra = [], int $status = 200): array
    {
        return array_merge([
            'status' => $status,
            'msg' => $msg,
        ], $extra);
    }

    public static function error(string $msg, array $extra = [], int $status = 0): array
    {
        return static::success($msg, $extra, $status);
    }

    public static function preparedDownload(int $fileSize = 0): array
    {
        return static::success('文件总大小', [
            'file_size' => $fileSize,
        ]);
    }

    public static function downloadedFileSize(int $size): array
    {
        return static::success('已下载文件大小', [
            'size' => $size,
        ]);
    }

    public static function contextual(array $response, string $target, string $stage, array $context = []): array
    {
        return array_merge([
            'target' => $target,
            'stage' => $stage,
        ], $context, $response);
    }

    public static function unknownAction(string $target, ?string $action = null, array $context = []): array
    {
        return static::contextual(static::error('不支持的更新操作', [
            'action' => $action,
            'reason_code' => 'unknown_action',
        ], 400), $target, 'dispatch', $context);
    }
}
