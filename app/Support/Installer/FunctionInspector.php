<?php

namespace App\Support\Installer;

class FunctionInspector
{
    public static function inspectLegacy(): array
    {
        return [
            'session' => static::htmlStatus(function_exists('session_start'), function_exists('session_start') ? '支持' : '不支持'),
        ];
    }

    public static function inspectStructured(): array
    {
        return [
            static::buildCheck('file_get_contents', 'file_get_contents', function_exists('file_get_contents'), true, function_exists('file_get_contents')),
            static::buildCheck('file_put_contents', 'file_put_contents', function_exists('file_put_contents'), true, function_exists('file_put_contents')),
            static::buildCheck('fopen', 'fopen', function_exists('fopen'), true, function_exists('fopen')),
            static::buildCheck('chmod', 'chmod', function_exists('chmod'), true, function_exists('chmod')),
            static::buildCheck('unlink', 'unlink', function_exists('unlink'), true, function_exists('unlink')),
            static::buildCheck('mkdir', 'mkdir', function_exists('mkdir'), true, function_exists('mkdir')),
            static::buildCheck('scandir', 'scandir', function_exists('scandir'), true, function_exists('scandir')),
            static::buildCheck('is_writable', 'is_writable', function_exists('is_writable'), true, function_exists('is_writable')),
            static::buildCheck('symlink', 'symlink', function_exists('symlink'), false, function_exists('symlink'), false),
        ];
    }

    private static function buildCheck(string $key, string $title, $current, $expected, bool $passed, bool $required = true): array
    {
        return [
            'key' => $key,
            'title' => $title,
            'current' => $current,
            'expected' => $expected,
            'required' => $required,
            'status' => $passed ? 'pass' : 'fail',
            'message' => $passed ? '通过' : '不通过',
            'risk_level' => $passed ? 'low' : ($required ? 'high' : 'medium'),
        ];
    }

    private static function htmlStatus(bool $passed, string $message): string
    {
        $class = $passed ? 'check correct' : 'remove error';
        return '<i class="fa fa-' . $class . '"></i> ' . e($message);
    }
}
