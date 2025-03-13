<?php

namespace Modules\Member\Http\Controllers\Api;

use Illuminate\Http\Request;
use Modules\Main\Services\ServiceModel;
use Modules\Member\Http\Controllers\Common\CommonController;
use Modules\Member\Library\Wechat\WeChatApi;
use Modules\Member\Models\ThreeLogin;
use Tymon\JWTAuth\Facades\JWTAuth;

class LoginController extends CommonController {

    //跳转公众号获取code
    public function getPublicCode(Request $request) {
        $all = $request->all();

        $parsedUrl = parse_url($all['url']);
        if (!in_array($parsedUrl['host'], ['www.wm5v.com', 'm.wm5v.com'])) return returnArr(0, '未授权');

        $all['login_method'] = $all['login_method'] ?: 'WeChat';
        $all['login_type'] = $all['login_type'] ?: 'public';
        $url = hook("Login", ['moduleName' => __E("login_driver"), 'cloudType' => "plugin", 'data' => [
            'request' => $request,
            'req_type' => 'getCode',
            'login_method' => $all['login_method'],
            'login_type' => $all['login_type'],
            'data' => ['callback' => url("api/" . strtolower($this->moduleName) . "/login/returnCode/" . base64_encode(base64_encode($all['url'])))],
        ]])[0];
        return redirect($url);
    }

    //code获取openid和用户信息
    public function returnCode(Request $request, $param) {
        $all = $request->all();
        $all['login_method'] = $all['login_method'] ?: 'WeChat';
        $all['login_type'] = $all['login_type'] ?: 'public';
        $jump_url = base64_decode(base64_decode($param));
        $res = hook("Login", ['moduleName' => __E("login_driver"), 'cloudType' => "plugin", 'data' => [
            'request' => $request,
            'req_type' => 'codeGetInfo',
            'login_method' => $all['login_method'],
            'login_type' => $all['login_type'],
            'data' => ['code' => $all['code']],
        ]])[0];
        $tmpArray = array_filter(explode('?', $jump_url));
        $and = $tmpArray[1] ? '&' : '?';

        $str = "openid={$res['openid']}";
        $info = ThreeLogin::openidGetUser(['wx_public_openid' => $res['openid']]);
        if ($info['user']) {
            $token = JWTAuth::fromUser($info['user']);
            $str .= "&token={$token}";
        } else {
            $row = hook("Login", ['moduleName' => __E("login_driver"), 'cloudType' => "plugin", 'data' => [
                'request' => $request,
                'req_type' => 'publicTokenGetWeChatUserInfo',
                'login_method' => $all['login_method'],
                'login_type' => $all['login_type'],
                'data' => ['access_token' => $res['access_token'], 'openid' => $res['openid']],
            ]])[0];
            $str .= "&nickname={$row['nickname']}";
            $str .= "&avatar={$row['headimgurl']}";
        }

        return redirect("{$jump_url}{$and}{$str}");
    }

    //公众号支付和分享，获取sign config
    public function publicGetSignConfig(Request $request) {
        return (new WeChatApi())->getConfig();
    }
}
