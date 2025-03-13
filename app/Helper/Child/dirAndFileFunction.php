<?php

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Modules\System\Models\Attachments;
use Modules\System\Services\ServiceModel;

/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2023/5/16
 * Time: 17:50
 */

function module_path($name, $path = '',$type="Modules") {
    return base_path($type."/{$name}/{$path}");
}

//file文件
function modifyEnv(array $data) {
    $envPath = base_path() . DIRECTORY_SEPARATOR . '.env';

    $contentArray = collect(file($envPath, FILE_IGNORE_NEW_LINES));

    $contentArray->transform(function ($item) use ($data) {
        foreach ($data as $key => $value) {
            if (str_contains($item, $key)) {
                return $key . '=' . $value;
            }
        }

        return $item;
    });

    $content = implode("\n", $contentArray->toArray());

    File::put($envPath, $content);
}


//上传单文件
function UploadFile($request,
                    $field,
                    $filename,
                    $allowExt = ALLOWEXT,
                    $drive = "local",
                    $preview = null,
                    $preview_w = 0,
                    $preview_h = 0,
                    $watermark_type = null,
                    $watermark_text = "",
                    $preview_watermark = null,
                    $watermark = null
) {

    //判断是否开启文件上传功能
    if (__E("upload_status") == 0) {
        throw new Exception("系统没有开启上传功能！", 40000);
    }

    //判断文件大小
    if ($_FILES[$field]['size'] <= 0) {

        throw new Exception("文件没有选择！", 40000);

    } else {

        //判断文件大小
        if ($_FILES[$field]['size'] / 1024 > __E("upload_limit")) {
            throw new Exception("超出文件设置大小！", 40000);
        }

        //判断文件格式
        $file = $request->file($field);
        //获取文件的扩展名
        $ext = $file->getClientOriginalExtension();
        $ext = strtolower($ext);
        if (!$ext){
            $mimeType = $file->getClientMimeType();
            $mimeType = explode("/",$mimeType);
            $ext = $mimeType[1];
        }
        //过滤文件格式
        if (!in_array($ext, explode(",", $allowExt))) {
            throw new Exception("文件格式不允许！", 40000);
        }
    }

    switch (__E("upload_driver")) {

        case "local":

            return _upload($request, $field, $filename, $allowExt, $drive, true, $preview, $preview_w, $preview_h, $watermark_type, $watermark_text, $preview_watermark, $watermark);

            break;


        default :

            //判断插件是否存在
            if (is_dir(PLUGIN_PATH . __E("upload_driver"))) {

                //触发上传驱动事件

                $filename = hook("Upload",[
                    "request"=>$request,
                    "field"=>$field,
                    "filename"=>$filename,
                    "allowExt"=>$allowExt,
                    "drive"=>__E("upload_driver"),
                    "moduleName"=>__E("upload_driver"),
                    "cloudType"=>"plugin"
                ]);

                //event(new \App\Events\UploadDriver($request, $field, $filename, $allowExt, $drive, "put"));

                return $filename[0];

            } else {

                throw new Exception("Upload driver does not exist！", 40000);

            }

            break;


    }


}

//上传操作
function _upload($request,
                 $field,
                 $filename,
                 $allowExt,
                 $drive,
                 $addAttachment = true,
                 $preview = null,
                 $preview_w = 0,
                 $preview_h = 0,
                 $watermark_type = null,
                 $watermark_text = "",
                 $preview_watermark = null,
                 $watermark = null
) {
    if ($_FILES[$field]['size'] <= 0) {
        return false;
    }
    $file = $request->file($field);
    if ($file->isValid()) { //括号里面的是必须加的哦
        //如果括号里面的不加上的话，下面的方法也无法调用的
        //获取文件的扩展名
        $ext = $file->getClientOriginalExtension();
        if (!$ext){
            $mimeType = $file->getClientMimeType();
            $mimeType = explode("/",$mimeType);
            $ext = $mimeType[1];
        }
        //获取文件的绝对路径
        $path = $file->getRealPath();

        //定义文件名
        $filename = $filename . '.' . $ext;

        //存储文件。disk里面的public。总的来说，就是调用disk模块里的public配置
        Storage::disk($drive)->put($filename, file_get_contents($path));

        if ($preview) {
            //定义预览名称,设置裁剪图片保存的名称
            $pre_filename = public_path() . "/" . 'uploads' . "/" . "preview" . "/" . $filename;
            if (!is_dir(dirname($pre_filename))) {
                mk_dir(dirname($pre_filename), 0777);
            }
            try {
                //缩略图
                $image = \Intervention\Image\Facades\Image::make(public_path() . "/" . 'uploads' . "/" . $filename);
                if ($preview_w && $preview_h) {
                    /*$image->height($preview_h);
                    $image->width($preview_w);*/
                    $image->resize($preview_w, $preview_h);
                } else if ($preview_w) {
                    $image->widen($preview_w);
                } else if ($preview_h) {
                    $image->heighten($preview_h);
                }
                if ($preview_watermark) {
                    if ($watermark_type == "img") {
                        $image->insert(GetUrlByPath(__E("watermark_img")), __E("watermark_position"), 10, 10);
                    } else if ($watermark_type == "text") {
                        $image->text($watermark_text, 20, 20, function ($font) {
                            $font->file(public_path() . '/xkb.ttf');
                            $font->size(__E("watermark_text_size"));
                            $font->color(__E("watermark_text_color"));
//                        $font->align('center');
                            $font->valign('top');
                            $font->angle(__E("watermark_text_angle"));
                        });
                    }
                }
                $image->save($pre_filename);

                //原图
                if ($watermark) {
                    $image2 = \Intervention\Image\Facades\Image::make(public_path() . "/" . 'uploads' . "/" . $filename);
                    if ($watermark_type == "img") {
                        $image2->insert(GetUrlByPath(__E("watermark_img")), __E("watermark_position"), 10, 10);
                    } else if ($watermark_type == "text") {
                        $image2->text($watermark_text, 20, 20, function ($font) {
                            $font->file(public_path() . '/xkb.ttf');
                            $font->size(__E("watermark_text_size"));
                            $font->color(__E("watermark_text_color"));
//                        $font->align('center');
                            $font->valign('top');
                            $font->angle(__E("watermark_text_angle"));
                        });
                    }
                    $image2->save(public_path() . "/" . 'uploads' . "/" . $filename);
                }

            } catch (Exception $exception) {
                throw new \Exception($exception->getMessage());
            }
        }

        //添加到附件表
        if ($addAttachment) {
            addAttachment($filename, $drive);
            if ($pre_filename) {
                addAttachment("preview" . "/" . $filename, $drive);
            }
        }

        return $filename;
    }
}


//添加附件表
function addAttachment($filename, $drive) {

    $arr["path"] = $filename;
    $arr["drive"] = $drive;

    $Attachment = new ServiceModel();

    return $Attachment->InsertArr($arr);


}

//删除附件
function delAttachment($filename) {

    $arr["path"] = $filename;

    $Attachment = new ServiceModel();

    return $Attachment->deleteByPathMD5($arr["path"]);

}

//获取本地存储的文件
function GetLocalFileByPath($path) {
    return asset('uploads') . '/' . $path;
}

//获取驱动的URL
function GetUrlByPath($path) {
    if (!$path) return $path;
    if (strpos($path, 'http') !== false) return $path;
    $data = ServiceModel::getByPath($path);
    if ($data) {
        switch ($data["drive"]) {
            case "local":
                return GetLocalFileByPath($path);
                break;
            default :
                return $path;
        }
    } else {
        return GetLocalFileByPath($path);
    }
}

if (!function_exists('write_lock_file')) {
    /**
     * 写入锁文件
     * @param $path
     */
    function write_lock_file($path, $content = '', $file_name = 'lock') {
        $lock_file = fopen($path . '/' . $file_name, 'w+');//创建 锁文件
        fwrite($lock_file, empty($content) ? date('Y-m-d H:i:s') : $content);//写入
    }
}

if (!function_exists('get_dir_files')) {
    // 列出指定目录下所有目录和文件
    function get_dir_files($dir, &$arr = []) {
        if (is_dir($dir)) {//如果是目录，则进行下一步操作
            $d = opendir($dir);//打开目录
            if ($d) {//目录打开正常
                while (($file = readdir($d)) !== false) {//循环读出目录下的文件，直到读不到为止
                    if ($file != '.' && $file != '..') {//排除一个点和两个点
                        if (is_dir($dir . '/' . $file)) {//如果当前是目录
                            get_dir_files($dir . '/' . $file, $arr);//进一步获取该目录里的文件
                        } else {
                            $arr[] = $dir . '/' . $file;//记录文件名
                        }
                    }
                }
            }
            closedir($d);//关闭句柄
        }
//        return $arr;
    }
}

if (!function_exists('get_file_filtering')) {
    /**
     * 获取指定格式的文件
     * @param array $array
     * @param array $format
     * @return array
     */
    function get_file_filtering($array = [], $format = []) {
        $return = [];
        if (empty($array) || empty($format)) return $return;
        foreach ($array as $key => $value) {
            $arr = pathinfo($value);
            if (!empty($arr['extension']) && in_array($arr['extension'], $format)) $return[] = $value;
        }
        return $return;
    }
}

if (!function_exists('write_lock_file')) {
    /**
     * 写入锁文件
     * @param $path
     */
    function write_lock_file($path, $content = '', $file_name = 'lock') {
        $lock_file = fopen($path . '/' . $file_name, 'w+');//创建 锁文件
        fwrite($lock_file, empty($content) ? date('Y-m-d H:i:s') : $content);//写入
    }
}

if (!function_exists('del_dir_files')) {
    /**
     * 删除文件夹与下方的所有文件
     * @param $dirName 文件夹名称
     * @param int $delete_dir 是否删除文件夹【1.删除；0.不删除】
     */
    function del_dir_files($dirName, $delete_dir = 1) {
        if ($handle = @opendir($dirName)) {
            while (false !== ($item = @readdir($handle))) {
                if ($item != '.' && $item != '..') {
                    if (is_dir($dirName . '/' . $item)) del_dir_files($dirName . '/' . $item);
                    else @unlink($dirName . '/' . $item);
                }
            }
            @closedir($handle);
        }
        if ($delete_dir == 1) @rmdir($dirName);
    }
}


if (!function_exists('put_file_to_zip')) {
    /**
     * 把指定文件目录下的所有文件，打包压缩至压缩包内
     * @param $path
     * @param $zip
     * @param $old_filename
     * @param $limit_dir 限制压缩的文件目录
     */
    function put_file_to_zip($path, $zip, $old_filename, $limit_dir = []) {
        $handler = opendir($path); //打开当前文件夹由$path指定。
        while (($filename = readdir($handler)) !== false) {
            if ($filename != "." && $filename != "..") {//文件夹文件名字为'.'和‘..’，不要对他们进行操作
                if (is_dir($path . "/" . $filename)) {// 如果读取的某个对象是文件夹，则递归
                    if (!empty($limit_dir) && !in_array($filename, $limit_dir)) continue;
                    $old_filename = (empty($old_filename) ? '' : ($old_filename . '/'));
                    $zip->addEmptyDir($old_filename . $filename);
                    put_file_to_zip($path . "/" . $filename, $zip, $old_filename . $filename);
                } else { //将文件加入zip对象
                    $zip->addFile($path . "/" . $filename, (empty($old_filename) ? '' : ($old_filename . '/')) . $filename);
                }
            }
        }
        @closedir($path);
    }
}

if (!function_exists('get_dir_size')) {
    /**
     * 获取指定文件夹的大小
     * @param $path
     * @param int $fileseze
     * @param array $limit_dir
     * @return int
     */
    function get_dir_size($path, $fileseze = 0, $limit_dir = []) {
        header("content-type:text/html;charset=utf-8");
        $handler = opendir($path); //打开当前文件夹由$path指定。
        while (($filename = readdir($handler)) !== false) {
            if ($filename != "." && $filename != "..") {//文件夹文件名字为'.'和‘..’，不要对他们进行操作
                if (is_dir($path . "/" . $filename)) {// 如果读取的某个对象是文件夹，则递归
                    if (!empty($limit_dir) && !in_array($filename, $limit_dir)) continue;
                    $fileseze = get_dir_size($path . "/" . $filename, $fileseze);
                } else $fileseze += filesize($path . "/" . $filename);//文件大小
            }
        }
        @closedir($path);
        return $fileseze;
    }
}

if (!function_exists('check_http_file_exists')) {
    //判断远程文件是否存在
    function check_http_file_exists($url) {
        $curl = curl_init($url);
        // 不取回数据
        curl_setopt($curl, CURLOPT_NOBODY, true);
        // 发送请求
        $result = curl_exec($curl);
        $found = false;
        // 如果请求没有发送失败
        if ($result !== false) {
            $statusCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
            if ($statusCode == 200) $found = true;
        }
        curl_close($curl);
        return $found;
    }
}

if (!function_exists('auto_incluede_directory_files')) {
    /**
     * 自动引入指定文件夹下方所有的文件
     * @param $path
     */
    function auto_incluede_directory_files($path) {
        $handler = opendir($path); //打开当前文件夹由$path指定。
        while (($filename = readdir($handler)) !== false) {
            if ($filename != '.' && $filename != '..') {//文件夹文件名字为'.'和‘..’，不要对他们进行操作
                // 如果读取的某个对象是文件夹，则递归
                if (is_dir($dir_path = $path . '/' . $filename)) auto_incluede_directory_files($dir_path);
                else if (is_file($file_path = $path . '/' . $filename)) include_once $file_path;
            }
        }
        @closedir($path);
    }
}

/**
 * 切分SQL文件成多个可以单独执行的sql语句
 * @param        $file            string sql文件路径
 * @param        $tablePre        string 表前缀
 * @param string $charset 字符集
 * @param string $defaultTablePre 默认表前缀
 * @param string $defaultCharset 默认字符集
 * @return array
 */
function get_split_sql($file, $tablePre, $charset = 'utf8mb4', $defaultTablePre = '', $defaultCharset = 'utf8mb4') {
    if (file_exists($file)) {
        //读取SQL文件
        $sql = file_get_contents($file);
        $sql = str_replace("\r", "\n", $sql);
        $sql = str_replace("BEGIN;\n", '', $sql);//兼容 navicat 导出的 insert 语句
        $sql = str_replace("COMMIT;\n", '', $sql);//兼容 navicat 导出的 insert 语句
        $sql = str_replace("↵↵", '', $sql);//兼容 navicat 导出的 insert 语句
        $sql = str_replace("\n  ", '', $sql);//兼容 navicat 导出的 insert 语句
        // $sql = str_replace(" ", '', $sql);//兼容 navicat 导出的 insert 语句
        if ($defaultCharset == $charset) {
            $sql = str_replace($defaultCharset, $charset, $sql);
        }
        $sql = trim($sql);
        //替换表前缀
        $sql = str_replace(" `{$defaultTablePre}", " `{$tablePre}", $sql);
        // $sqls = explode("-- ----------------------------", $sql);
        $sqls = explode(";\n", $sql);
        return $sqls;
    }
    return [];
}

//dir文件夹
//获取某目录下所有子文件和子目录,可以过滤
function getDirContent($path, array $filter = [], $onlydir = true) {
    if (!is_dir($path)) {
        return false;
    }
    //readdir方法
    /* $dir = opendir($path);
    $arr = array();
    while($content = readdir($dir)){
        if($content != '.' && $content != '..'){
            $arr[] = $content;
        }
    }
    closedir($dir); */

    //scandir方法
    $arr = array();
    $data = scandir($path);
    foreach ($data as $value) {
        if ($value != '.' && $value != '..' && $value != ".DS_Store" && !in_array($value, $filter)) {
            if ($onlydir) {
                if (is_dir($path . "/" . $value)) {
                    $arr[] = $value;
                } else {
                    continue;
                }
            } else {
                $arr[] = $value;
            }

        }
    }
    return $arr;
}

// 循环创建目录
function mk_dir($dir, $mode = 0755) {
    if (is_dir($dir) || @mkdir($dir, $mode)) return true;
    if (!mk_dir(dirname($dir), $mode)) return false;
    return @mkdir($dir, $mode);
}

function callback_pre_extract($p_event,$p_header)
{
    // 获取将要解压缩的文件名
    $filename = $p_header['filename'];
    // 获取文件的修改时间
    $fileMtime = $p_header['mtime'];
    if($p_header['folder']){
        return 1;
    }
    // 检查文件是否存在
    if (file_exists($filename)) {
        // 获取当前文件的修改时间
        $currentMtime = filemtime($filename);
        // 比对修改时间
        if ($fileMtime != $currentMtime) {
            chmod($filename,0777);
            @unlink($filename);
        }
    }
    return 1;
}

