<?php

namespace App\Support\Installer;

use Illuminate\Support\Facades\DB;

class DatabasePreflight
{
    public static function check(array $dbConfig): array
    {
        $host = $dbConfig['hostname'] ?? $dbConfig['dbhost'] ?? '';
        $port = $dbConfig['hostport'] ?? $dbConfig['dbport'] ?? env('DB_PORT', 3306);
        $username = $dbConfig['username'] ?? $dbConfig['dbuser'] ?? '';
        $password = $dbConfig['password'] ?? $dbConfig['dbpw'] ?? '';
        $database = $dbConfig['database'] ?? $dbConfig['dbname'] ?? '';

        try {
            config(['database.connections.mysql.host' => $host]);
            config(['database.connections.mysql.port' => $port]);
            config(['database.connections.mysql.username' => $username]);
            config(['database.connections.mysql.password' => $password]);
            config(['database.connections.mysql.database' => '']);

            DB::purge('mysql');
            DB::connection('mysql')->getPdo();

            $message = '账号密码验证成功！';
            if ($database !== '') {
                try {
                    DB::select('use `' . str_replace('`', '', $database) . '`');
                    $result = [
                        'msg' => $message . '数据库已存在！',
                        'status' => 200,
                        'data' => [
                            'database_exists' => true,
                        ],
                    ];
                    InstallLogger::log('db_preflight_success', [
                        'host' => $host,
                        'port' => $port,
                        'database' => $database,
                        'database_exists' => true,
                    ]);
                    return $result;
                } catch (\Exception $exception) {
                    $result = [
                        'msg' => $message . '数据库不存在将自动创建！',
                        'status' => 40000,
                        'data' => [
                            'database_exists' => false,
                        ],
                    ];
                    InstallLogger::log('db_preflight_database_missing', [
                        'host' => $host,
                        'port' => $port,
                        'database' => $database,
                        'database_exists' => false,
                    ]);
                    return $result;
                }
            }

            $result = [
                'msg' => $message,
                'status' => 200,
                'data' => [
                    'database_exists' => null,
                ],
            ];
            InstallLogger::log('db_preflight_success', [
                'host' => $host,
                'port' => $port,
                'database' => $database,
                'database_exists' => null,
            ]);
            return $result;
        } catch (\Exception $exception) {
            $result = [
                'msg' => '数据库账号或密码不正确！' . $exception->getMessage(),
                'status' => 40000,
                'data' => [
                    'database_exists' => null,
                ],
            ];
            InstallLogger::log('db_preflight_fail', [
                'host' => $host,
                'port' => $port,
                'database' => $database,
                'error' => $exception->getMessage(),
            ]);
            return $result;
        }
    }
}
