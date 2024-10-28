<?php

namespace Modules\System\Http\Controllers\Common;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Intervention\Image\Facades\Image;

class CommonController extends Controller {
    public $moduleName, $request;

    public function __construct(Request $request) {
        $this->moduleName = 'System';
        $this->request = $request;
    }

    public function homeView($path, $data) {
        $dir = 'home';//获取不同主题目录
        return view(strtolower($this->moduleName) . '::' . $dir . '.' . $path, $data);
    }

    public function adminView($path, $data) {
        if (!$data['pageData']['moduleName']) $data['pageData']['moduleName'] = $this->moduleName;
        return view(strtolower($this->moduleName) . '::admin.' . $path, $data);
    }

    /**
     * 压缩图片
     * img_url  图片路径
     * max_size 这个大小就压缩，单位KB
     * width    宽
     * height   高
     */
    public function resizeImg($img_url, $max_size, $width, $height) {
        $img_url = str_replace(url('uploads') . '/', '', $img_url);
        $url = public_path('uploads/' . $img_url);
        $size = filesize($url) / 1024;
        if ($size > $max_size) {
            Image::make($url)->resize($width, $height)->save($url);
        }
    }
}
