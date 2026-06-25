<?php

namespace App\Support\Async;

use App\Support\Update\UpdateLogger;

class AsyncArtisanDispatcher
{
    public function dispatch(string $actionName, array $arguments, string $moduleName, array $context = []): array
    {
        $taskId = $context['async_id'] ?? $this->generateTaskId($moduleName, $actionName);
        $requestId = $context['requestid'] ?? (request()->requestid ?? '');
        $payload = [
            'actionName' => trim($actionName),
            'arguments' => $arguments,
            'moduleName' => trim($moduleName),
            'requestid' => $requestId,
            'async_id' => $taskId,
            'context' => $context,
        ];

        try {
            curl_request_ms(url("api/asynCall"), $payload);
            UpdateLogger::log('async.dispatch_queued', [
                'async_id' => $taskId,
                'module' => $payload['moduleName'],
                'action' => $payload['actionName'],
                'arguments' => $arguments,
                'context' => $context,
                'requestid' => $requestId,
            ]);

            return [
                'status' => 'queued',
                'async_id' => $taskId,
                'requestid' => $requestId,
            ];
        } catch (\Throwable $exception) {
            UpdateLogger::log('async.dispatch_failed', [
                'async_id' => $taskId,
                'module' => $payload['moduleName'],
                'action' => $payload['actionName'],
                'arguments' => $arguments,
                'context' => $context,
                'requestid' => $requestId,
                'error' => $exception->getMessage(),
            ]);

            throw $exception;
        }
    }

    private function generateTaskId(string $moduleName, string $actionName): string
    {
        return strtolower(trim($moduleName)) . '_' . trim($actionName) . '_' . uniqid();
    }
}
