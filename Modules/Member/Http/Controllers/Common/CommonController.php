<?php

namespace Modules\Member\Http\Controllers\Common;

use Illuminate\Http\Request;
use Intervention\Image\Facades\Image;
use Tymon\JWTAuth\Facades\JWTAuth;
use Modules\ModulesController;

class CommonController extends ModulesController {
    public $moduleName, $request;

    public function __construct(Request $request) {
        $this->moduleName = 'Member';
        $this->request = $request;
    }

    public function homeView($path, $data) {
        $dir = 'home';//获取不同主题目录
        if (!$data['moduleName']) $data['moduleName'] = $this->moduleName;
        return view(strtolower($this->moduleName) . '::' . $dir . '.' . $path, $data);
    }

    public function adminView($path, $data) {
        if (!$data['pageData']['moduleName']) $data['pageData']['moduleName'] = $this->moduleName;
        return view(strtolower($this->moduleName) . '::admin.' . $path, $data);
    }

    //不用验证登录的key
    public function getNoLoginStr() {
        return md5($this->moduleName . md5($this->moduleName));
    }

    //获取用户信息/验证用户
    public function current_user() {
        $request = \Request();
        if ($request->isMethod('OPTIONS')) exit();
        if ($request->no_login_string != $this->getNoLoginStr()) {
            try {
                $user = JWTAuth::toUser(JWTAuth::getToken());//获取用户名
                if (!$user) exit(returnJson(0, '获取用户信息错误1'));
            } catch (\Exception $exception) {
                exit(returnJson(0, '获取用户信息错误2'));
            }
            return $user;
        } else {
            return $request;
        }
    }

    public function current_user_or() {
        $request = \Request();
        if ($request->isMethod('OPTIONS')) exit();
        if ($request->admin_str != $this->getNoLoginStr()) {
            if ($_SERVER['HTTP_AUTHORIZATION'] || $_GET['token']) {
                try {
                    $user = JWTAuth::toUser(JWTAuth::getToken());//获取用户名
                    if (!$user) exit(returnJson(0, '获取用户信息错误1'));
                } catch (\Exception $exception) {
                    exit(returnJson(0, '获取用户信息错误2'));
                }
            } else {
                $user = [];
            }
            return $user;
        } else {
            return $request;
        }
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
