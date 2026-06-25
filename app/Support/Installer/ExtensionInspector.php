<?php

namespace App\Support\Installer;

class ExtensionInspector
{
    public static function inspectLegacy(): array
    {
        $gdInfo = function_exists('gd_info') ? gd_info() : [];

        return [
            'pdo' => static::htmlStatus(class_exists('pdo'), class_exists('pdo') ? '已开启' : '未开启'),
            'pdo_mysql' => static::htmlStatus(extension_loaded('pdo_mysql'), extension_loaded('pdo_mysql') ? '已开启' : '未开启'),
            'curl' => static::htmlStatus(extension_loaded('curl'), extension_loaded('curl') ? '已开启' : '未开启'),
            'gd' => static::buildGdStatus($gdInfo),
            'mbstring' => static::htmlStatus(extension_loaded('mbstring'), extension_loaded('mbstring') ? '已开启' : '未开启'),
            'fileinfo' => static::htmlStatus(extension_loaded('fileinfo'), extension_loaded('fileinfo') ? '已开启' : '未开启'),
        ];
    }

    public static function inspectStructured(): array
    {
        return [
            static::buildCheck('pdo', 'PDO', class_exists('pdo'), true, class_exists('pdo')),
            static::buildCheck('pdo_mysql', 'PDO MySQL', extension_loaded('pdo_mysql'), true, extension_loaded('pdo_mysql')),
            static::buildCheck('curl', 'CURL', extension_loaded('curl'), true, extension_loaded('curl')),
            static::buildCheck('gd', 'GD', extension_loaded('gd'), true, extension_loaded('gd')),
            static::buildCheck('mbstring', 'MBstring', extension_loaded('mbstring'), true, extension_loaded('mbstring')),
            static::buildCheck('fileinfo', 'fileinfo', extension_loaded('fileinfo'), true, extension_loaded('fileinfo')),
            static::buildCheck('openssl', 'OpenSSL', extension_loaded('openssl'), true, extension_loaded('openssl')),
            static::buildCheck('json', 'JSON', extension_loaded('json'), true, extension_loaded('json')),
        ];
    }

    private static function buildGdStatus(array $gdInfo): string
    {
        if (!extension_loaded('gd')) {
            $message = '未开启';
            if (function_exists('imagettftext')) {
                $message .= '<br><i class="fa fa-remove error"></i> FreeType Support未开启';
            }

            return static::htmlStatus(false, $message, true);
        }

        $version = $gdInfo['GD Version'] ?? '已开启';
        return static::htmlStatus(true, $version);
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

    private static function htmlStatus(bool $passed, string $message, bool $messageContainsIcon = false): string
    {
        $class = $passed ? 'check correct' : 'remove error';
        $icon = '<i class="fa fa-' . $class . '"></i> ';

        return $messageContainsIcon ? $icon . $message : $icon . e($message);
    }
}
