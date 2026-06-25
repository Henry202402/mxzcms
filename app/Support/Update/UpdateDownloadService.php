<?php

namespace App\Support\Update;

class UpdateDownloadService
{
    public function download(string $remoteFileUrl, string $localFilePath, int $chunkSize = 3145728): array
    {
        ignore_user_abort();
        set_time_limit(0);
        ini_set('memory_limit', '2560M');

        if (file_exists($localFilePath)) {
            @unlink($localFilePath);
        }

        mk_dir(dirname($localFilePath));
        if (!is_writable(dirname($localFilePath))) {
            chmod($localFilePath, 0777);
            if (!is_writable(dirname($localFilePath))) {
                return [
                    "status" => 500,
                    "msg" => "下载目录不可写",
                    "data" => []
                ];
            }
        }

        file_put_contents($localFilePath, "");
        chmod($localFilePath, 0777);
        $context = stream_context_create([
            'ssl' => [
                'verify_peer' => false,
                'verify_peer_name' => false
            ],
        ]);

        $remoteFile = fopen($remoteFileUrl, 'rb', false, $context);
        $localFile = fopen($localFilePath, 'r+wb');
        if (!$remoteFile) {
            return [
                "status" => 500,
                "msg" => "远程文件获取失败",
                "data" => []
            ];
        }
        if (!$localFile) {
            return [
                "status" => 500,
                "msg" => "本地文件获取失败",
                "data" => []
            ];
        }

        while (!feof($remoteFile)) {
            $chunk = fread($remoteFile, $chunkSize);
            fwrite($localFile, $chunk);
            flush();
        }
        fclose($remoteFile);
        fclose($localFile);

        return [
            "status" => 200,
            "msg" => "下载完成",
            "data" => []
        ];
    }

    public function fileSize(string $localFilePath): array
    {
        if (!file_exists($localFilePath)) {
            return UpdateResponseFactory::error('下载文件不存在', [
                'size' => 0,
            ], 500);
        }

        return UpdateResponseFactory::downloadedFileSize((int) filesize($localFilePath));
    }
}
