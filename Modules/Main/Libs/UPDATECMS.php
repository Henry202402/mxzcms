<?php

namespace Modules\Main\Libs;

use GuzzleHttp\Client;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Modules\Main\Http\Controllers\Admin\FuncController;
use Modules\Main\Http\Controllers\Admin\IndexController;

class UPDATECMS
{
    private $cloud_host = "https://www.mxzcloud.com";
    public function __construct()
    {
        $this->request = request();
    }
    public function appAction($all){
        $res = [];
        switch ($all["action"]){
            case "check":
                $res = $this->checkapp($all);
                break;
            case "prepare-download":
                $res = $this->preparedownload(cache()->get('app_update_'.$all["cloudtype"].'_'.$all["identification"])['size']);
                break;
            case "start-download":

                $remoteFileUrl = cache()->get('app_update_'.$all["cloudtype"].'_'.$all["identification"])['source'];
                if(!$remoteFileUrl){
                    $this->checkapp($all);
                }
                if (strpos($remoteFileUrl, "?") !== false) {
                    $remoteFileUrl = $remoteFileUrl . "&origin_host=" . $this->request->getHost()."&user_agent=".urlencode($_SERVER['HTTP_USER_AGENT']);
                }else{
                    $remoteFileUrl = $remoteFileUrl . "?origin_host=" . $this->request->getHost()."&user_agent=".urlencode($_SERVER['HTTP_USER_AGENT']);
                }
                $localFilePath = storage_path('download/'.$all["cloudtype"].'/'.strtolower($all["identification"]).'-'.cache()->get('app_update_'.$all["cloudtype"].'_'.$all["identification"])['version'].'.zip');
                $res = $this->startdownload($remoteFileUrl, $localFilePath);
                break;
            case "unzip-file":

                $localFilePath = storage_path('download/'.$all["cloudtype"].'/'.strtolower($all["identification"]).'-'.cache()->get('app_update_'.$all["cloudtype"].'_'.$all["identification"])['version'].'.zip');
                switch ($all["cloudtype"]){
                    case "module":
                        $topath = base_path("Modules/");
                        break;
                    case "plugin":
                        $topath = base_path("Plugins/");
                        break;
                    case "theme":
                        $topath = base_path("public/views/themes/");
                        break;
                }

                $res = $this->unzipFile($localFilePath, $topath,"callback_pre_extract");
                //更新数据库
                if ($res['status'] == 200){
                    //更新版本号
                    $update = [
//                        'version' => cache()->get('app_update_'.$all["cloudtype"].'_'.$all["identification"])['version'],
                        'updated_at' => date("Y-m-d H:i:s")
                    ];
                    if ($all["cloudtype"] == "theme"){
                        $check = DB::table("themes")
                            ->where("identification",$all["identification"])
                            ->first();
                        if($check){
                            DB::table("themes")
                                ->where("identification",$all["identification"])
                                ->update($update);
                            hook("Statistic",['moduleName'=>"System","action"=>"Update","identification"=>$all["identification"],"type"=>$all["cloudtype"]]);
                            return [
                                "status" => 200,
                                "msg" => "升级成功"
                            ];
                        }
                    }else{
                        $check = DB::table("modules")
                            ->where("identification",$all["identification"])
                            ->where("cloud_type",$all["cloudtype"])
                            ->first();
                        if($check){
                            DB::table("modules")
                                ->where("identification",$all["identification"])
                                ->where("cloud_type",$all["cloudtype"])
                                ->update($update);
                            if($all["cloudtype"]=="module"){
                                try {
                                    Artisan::call('migrate', [
                                        '--path' => "Modules/{$all["identification"]}/Database/Migrations/update",
                                        '--force' => 1,
                                    ]);
                                }catch (\Exception $exception){}
                            }
                            hook("Statistic",['moduleName'=>"System","action"=>"Update","identification"=>$all["identification"],"type"=>$all["cloudtype"]]);
                            return [
                                "status" => 200,
                                "msg" => "升级成功"
                            ];
                        }
                    }
                    $request = \request();
                    $request->merge([
                        'm' => $all["identification"],
                        'form' => 'cloud',
                        'cloud_type' => $all["cloudtype"],
                        'return_type' => 'api'
                    ]);
                    $func = new FuncController();
                    $res = $func->install($request);
                    if ($res['status'] == 200) {
                        return ['msg' => '安装成功', 'status' => 200];
                    } else {
                        return ['msg' => '安装失败，请手动安装', 'status' => 0];
                    }
                }
                break;
            case "get-file-size":
                // 本地保存文件的路径
                $localFilePath = storage_path('download/'.$all["cloudtype"].'/'.strtolower($all["identification"]).'-'.cache()->get('app_update_'.$all["cloudtype"].'_'.$all["identification"])['version'].'.zip');
                $res = $this->getFileSize($localFilePath);
                break;
            case "get-app-list":
                $res = $this->getapplist($all);
                break;
            default :
                return [];
        }
        return $res;
    }
    public function statistic($data)
    {
        $data["origin_host"]= url("/");
        $data['time'] = time();
        unset($data['moduleName']);
        ksort($data);
        $data['sign'] =md5(http_build_query($data)."mxzcms".$data['time']);
        // 初始化 cURL
        $ch = curl_init();
        // 设置 cURL 选项
        curl_setopt($ch, CURLOPT_URL, $this->cloud_host.'/api/cloud/statistic?'.http_build_query($data));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch,CURLOPT_NOSIGNAL,true);
//        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT_MS, 300);
        curl_setopt($ch, CURLOPT_TIMEOUT_MS, 500);
        // 发起请求
        curl_exec($ch);
        // 关闭 cURL 资源
        curl_close($ch);
    }
    public function getapplist($all){
        $res = curl_request($this->cloud_host.'/api/cloud/applist?'.http_build_query($all));
        return $res;
    }
    public function cmsAction($all)
    {
        switch ($all["action"]){
            case "check":
                $res = $this->checkcms();
                break;
            case "backup":
                $res = $this->backupcms();
                break;
            case "prepare-download":
                $res = $this->preparedownload(cache()->get('cms_update_version')['size']);
                break;
            case "start-download":
                $remoteFileUrl = cache()->get('cms_update_version')['source'];
                if (strpos($remoteFileUrl, "?") !== false) {
                    $remoteFileUrl = $remoteFileUrl . "&origin_host=" . $this->request->getHost()."&user_agent=".urlencode($_SERVER['HTTP_USER_AGENT']);
                }else{
                    $remoteFileUrl = $remoteFileUrl . "?origin_host=" . $this->request->getHost()."&user_agent=".urlencode($_SERVER['HTTP_USER_AGENT']);
                }
                $localFilePath = storage_path('download/cms/'.cache()->get("cms_update_version")['version'].'.zip');

                $res = $this->startdownload($remoteFileUrl, $localFilePath);
                break;
            case "unzip-file":
                $localFilePath = storage_path('download/cms/'.cache()->get("cms_update_version")['version'].'.zip');
                $res = $this->unzipFile($localFilePath, base_path(),"callback_pre_extract");
                //更新数据库
                if ($res['status'] == 200){
                    //更新版本号
                    modifyEnv([
                        'APP_VERSION' => cache()->get("cms_update_version")['version']
                    ]);
                    try {
                        Artisan::call('migrate', [
                            '--path' => "Modules/Main/Database/Migrations/update",
                            '--force' => 1,
                        ]);
                    }catch (\Exception $exception){}
                    call_user_func([new IndexController(),"clearCache"]);
                    return [
                        "status" => 200,
                        "msg" => "升级成功"
                    ];
                }else{
                    return $res;
                }

                break;
            case "get-file-size":
                // 本地保存文件的路径
                $localFilePath = storage_path('download/cms/'.cache()->get("cms_update_version")['version'].'.zip');
                $res = $this->getFileSize($localFilePath);
                break;
            case "get-sdks":
                $res = $this->getSdks();
                break;
            default :
                return [];
        }
        return $res;
    }
    public function getSdks(){
        if(cache()->get('cms_sdks_list')){
            return cache()->get('cms_sdks_list');
        }
        $res = curl_request($this->cloud_host.'/api/list/sdks');
        cache()->put('cms_sdks_list',$res);
        return $res;
    }
    public function checkapp($all)
    {
        $res = curl_request($this->cloud_host.'/api/cloud/checkapp?'.http_build_query($all));
        $res = json_decode($res, true);
        if ($res['status'] == 200 && $res['data']['version']) {
            if ($res['data']['version'] != $all['version']){
                $return = [
                    "status" => 200,
                    "msg" => "有新版本"
                ];
                //保存缓存
                cache()->put('app_update_'.$all["cloudtype"].'_'.$all["identification"], $res['data'], 60 * 60 * 12);
            }

        }

        return $return;
    }
    public function checkcms()
    {
        $return = [];
        if (cache()->get('cms_update_version')['version'] == config("app.app_version")){
            return $return;
        }
        $res = curl_request($this->cloud_host.'/api/cloud/getversion?bycmsupdate=1');
        $res = json_decode($res, true);
        if ($res['code'] == 200 && $res['data']['version']) {
            if ($res['data']['version'] != config("app.app_version")){
                $return = [
                    "status" => 200,
                    "msg" => "有新版本"
                ];
                //保存缓存
                cache()->put('cms_update_version', $res['data'], 60 * 60 * 12);
            }
        }

        return $return;
    }

    public function backupcms()
    {
        //备份目录
        $backup_dirs = [
            'app',
            'config',
            'Modules/Auth',
            'Modules/Main',
            'Modules/System',
            'Modules/Formtools',
            'public/views/admin',
            'public/views/themes/default',
        ];
        //备份文件
        $backup_files = [
            'Modules/ModulesController.php',
            'Plugins/PluginsController.php',
            '.env',
            'composer.json'
        ];
        //添加备份文件
        $zip = new \ZipArchive();
        $filename = storage_path("backup/".date("ymdHis").'-bak.zip');
        @unlink($filename);
        if(!is_writable(storage_path("backup"))){
            mkdir(storage_path("backup"),0777,true);
            chmod(storage_path("backup"),0777);
            chmod($filename,0777);
            if (!is_writable($filename)){
                return [
                    "status" => 500,
                    "msg" => "备份文件".$filename."不可写"
                ];
            }
        }

        file_put_contents($filename, "");
        try {
            $zip->open($filename, \ZipArchive::CREATE);
            foreach ($backup_dirs as $dir) {
                $this->foraddfile($dir,$zip);
            }
            foreach ($backup_files as $file) {
                $this->foraddfile($file,$zip);
            }
            $zip->close();
            return [
                "status" => 200,
                "msg" => "备份成功",
                "data" => [
                    "file" => $filename
                ]
            ];
        }catch (\Exception $exception){
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

    private function foraddfile($dir,$zip){
        if(is_dir(base_path($dir))){
            $zip->addEmptyDir($dir);
            $files = scandir(base_path($dir));
            foreach ($files as $file){
                if ($file!="."&&$file!=".." && is_dir(base_path($dir."/".$file))){
                    $this->foraddfile($dir."/".$file,$zip);
                }else{
                    if ($file!="."&&$file!=".."){
                        $zip->addFile(base_path($dir."/".$file),$dir."/".$file);
                    }
                }
            }
        }else{
            $zip->addFile(base_path($dir),$dir);
        }
    }

    public function preparedownload($filesize= 0)
    {
        return [
            "status" => 200,
            "msg" => "文件总大小",
            "file_size" => $filesize
        ];
    }

    public function startdownload($remoteFileUrl, $localFilePath,$chunkSize = 3*1024*1024)
    {
        ignore_user_abort();
        set_time_limit(0);
        ini_set('memory_limit', '2560M');
        if (file_exists($localFilePath)) {
            @unlink($localFilePath);
        }

        mk_dir(dirname($localFilePath));
        if (!is_writable(dirname($localFilePath))) {
            chmod($localFilePath,0777);
            if (!is_writable(dirname($localFilePath))) {
                return [
                    "status" => 500,
                    "msg" => "下载目录不可写",
                    "data" => []
                ];
            }
        }
        file_put_contents($localFilePath, "");
        chmod($localFilePath,0777);
        $context = stream_context_create([
            'ssl' => [
                'verify_peer' => false,
                'verify_peer_name' => false
            ],
        ]);

        $remoteFile = fopen($remoteFileUrl, 'rb', false, $context);
        $localFile = fopen($localFilePath, 'r+wb');
        if(!$remoteFile){
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

    public function getFileSize($localFilePath)
    {
        $fileSize = filesize($localFilePath);
        return [
            "status" => 200,
            "msg" => "已下载文件大小",
            "size" => $fileSize
        ];
    }

    public function unzipFile($localFilePath, $toPath,$callback_pre_extract = null)
    {
        ignore_user_abort();
        set_time_limit(0);
        ini_set('memory_limit', '2560M');
        $zip = new \PclZip($localFilePath);
        $res = $zip->extract(
            PCLZIP_OPT_PATH, $toPath,
            PCLZIP_CB_PRE_EXTRACT, $callback_pre_extract,
            PCLZIP_OPT_SET_CHMOD, 0755
        );
        @unlink($localFilePath);
        if ($res <= 0) {
            return [
                "status" => 500,
                "msg" => "解压失败"
            ];
        }
        return [
            "status" => 200,
            "msg" => "解压成功"
        ];
    }
}
