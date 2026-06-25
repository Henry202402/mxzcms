<?php

namespace App\Support\Update;

use ZipArchive;

class UpdateBackupService
{
    public function backupCms(array $backupDirs, array $backupFiles): array
    {
        $zip = new ZipArchive();
        $filename = storage_path("backup/".date("ymdHis").'-bak.zip');
        @unlink($filename);

        if (!is_writable(storage_path("backup"))) {
            mkdir(storage_path("backup"), 0777, true);
            chmod(storage_path("backup"), 0777);
            chmod($filename, 0777);
            if (!is_writable($filename)) {
                return [
                    "status" => 500,
                    "msg" => "备份文件".$filename."不可写"
                ];
            }
        }

        file_put_contents($filename, "");
        try {
            $zip->open($filename, ZipArchive::CREATE);
            foreach ($backupDirs as $dir) {
                $this->addPath($dir, $zip);
            }
            foreach ($backupFiles as $file) {
                $this->addPath($file, $zip);
            }
            $zip->close();

            return [
                "status" => 200,
                "msg" => "备份成功",
                "data" => [
                    "file" => $filename
                ]
            ];
        } catch (\Exception $exception) {
            $zip->close();

            return [
                "status" => 500,
                "msg" => $exception->getMessage(),
                "data" => [
                    "file" => $filename
                ]
            ];
        }
    }

    private function addPath(string $path, ZipArchive $zip): void
    {
        if (is_dir(base_path($path))) {
            $zip->addEmptyDir($path);
            $files = scandir(base_path($path));
            foreach ($files as $file) {
                if ($file === "." || $file === "..") {
                    continue;
                }
                $fullPath = $path."/".$file;
                if (is_dir(base_path($fullPath))) {
                    $this->addPath($fullPath, $zip);
                    continue;
                }
                $zip->addFile(base_path($fullPath), $fullPath);
            }
            return;
        }

        $zip->addFile(base_path($path), $path);
    }
}
