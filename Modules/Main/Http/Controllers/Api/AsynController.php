<?php

namespace Modules\Main\Http\Controllers\Api;

use App\Support\Update\UpdateLogger;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Routing\Controller as BaseController;
use Plugins\Logger\Lib\Logger;

class AsynController extends BaseController {

    public function asynCall() {
        ignore_user_abort(true);
        set_time_limit(0);
        ini_set('max_execution_time', 0);
        ini_set('memory_limit', '-1');

        $all = $this->normalizePayload(request()->all());
        if ($error = $this->validatePayload($all)) {
            $this->writeAsyncLog($all, $error['msg'], array_merge($all, [
                'status' => $error['status'],
                'reason_code' => $error['reason_code'],
            ]));
            return $error;
        }

        $startedAt = microtime(true);
        $this->writeAsyncLog($all, "{$all['moduleName']}、{$all['actionName']}开始异步记录", array_merge($all, [
            'status' => 102,
        ]));

        try {
            $exitCode = Artisan::call($all['actionName'], $all['arguments']);
            $output = $this->excerptOutput(Artisan::output());
            $durationMs = (int) ((microtime(true) - $startedAt) * 1000);
            $status = $exitCode === 0 ? 200 : 0;
            $msg = $exitCode === 0 ? '执行成功' : '执行失败';

            $payload = array_merge($all, [
                'status' => $status,
                'exit_code' => $exitCode,
                'duration_ms' => $durationMs,
                'output' => $output,
            ]);
            $this->writeAsyncLog($all, "{$all['moduleName']}、{$all['actionName']}异步记录结束,结果：{$msg}", $payload);
            UpdateLogger::log('async.task_finished', $payload);

            return [
                'status' => $status,
                'msg' => $msg,
                'async_id' => $all['async_id'],
                'duration_ms' => $durationMs,
                'exit_code' => $exitCode,
            ];
        } catch (\Throwable $exception) {
            $durationMs = (int) ((microtime(true) - $startedAt) * 1000);
            $payload = array_merge($all, [
                'status' => 0,
                'duration_ms' => $durationMs,
                'error' => $exception->getMessage(),
            ]);
            $this->writeAsyncLog($all, "{$all['moduleName']}、{$all['actionName']}异步记录结束,结果：执行异常", $payload);
            UpdateLogger::log('async.task_exception', $payload);

            return [
                'status' => 0,
                'msg' => $exception->getMessage() ?: '执行异常',
                'async_id' => $all['async_id'],
                'duration_ms' => $durationMs,
            ];
        }
    }

    private function normalizePayload(array $all): array
    {
        $all['actionName'] = trim((string) ($all['actionName'] ?? ''));
        $all['moduleName'] = trim((string) ($all['moduleName'] ?? ''));
        $all['arguments'] = isset($all['arguments']) && is_array($all['arguments']) ? $all['arguments'] : [];
        $all['requestid'] = trim((string) ($all['requestid'] ?? (request()->requestid ?? '')));
        $all['async_id'] = trim((string) ($all['async_id'] ?? ''));
        if ($all['async_id'] === '') {
            $all['async_id'] = strtolower($all['moduleName'] ?: 'app') . '_' . ($all['actionName'] ?: 'task') . '_' . uniqid();
        }
        $all['context'] = isset($all['context']) && is_array($all['context']) ? $all['context'] : [];
        return $all;
    }

    private function validatePayload(array $all): ?array
    {
        if ($all['actionName'] === '') {
            return [
                'status' => 400,
                'msg' => '命令行名称不能为空!',
                'reason_code' => 'missing_action_name',
            ];
        }

        if ($all['moduleName'] === '') {
            return [
                'status' => 400,
                'msg' => '模块名称不能为空!',
                'reason_code' => 'missing_module_name',
            ];
        }

        return null;
    }

    private function writeAsyncLog(array $all, string $remark, array $params): void
    {
        if (class_exists(Logger::class)) {
            $params = Logger::normalizeSystemContext('asyn', $params, [
                'module' => $all['moduleName'] ?: 'system',
                'requestid' => $all['requestid'] ?? '',
                'async_id' => $all['async_id'] ?? '',
            ]);
        } else {
            $params = array_merge($params, [
                'type' => 'system',
                'two_type' => 'asyn',
                'module' => $all['moduleName'] ?: 'system',
                'requestid' => $all['requestid'] ?? '',
                'async_id' => $all['async_id'] ?? '',
            ]);
        }
        hook("Loger", [
            'module' => $all['moduleName'] ?: 'system',
            'type' => "system",
            'two_type' => "asyn",
            'params' => $params,
            'remark' => $remark,
            'unique_id' => $all['async_id'] ?? '',
            'requestid' => $all['requestid'] ?? ''
        ]);
    }

    private function excerptOutput(string $output): string
    {
        $output = trim($output);
        if ($output === '') {
            return '';
        }

        return substr($output, 0, 2000);
    }

}
