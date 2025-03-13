<?php

namespace Modules\Member\Library\Wechat;


use Illuminate\Support\Facades\Cache;

class WeChatApi {
    public $appid;
    public $appsecret;

    public function __construct($appid = '', $appsecret = '') {
        $this->appid = $appid;
        $this->appsecret = $appsecret;
    }

    public function getConfig() {
        $all = \Request()->all();
        if (!$all['url']) return returnArr(0, 'url不能为空');

        //获取access_token
        $access_token = $this->getToken();
        if (!$access_token) return returnArr(0, '获取access_token失败');

        //获取ticket
        $ticket = $this->getTicket($access_token);
        if (!$ticket) return returnArr(0, '获取ticket失败');

        $config = $this->getSign($ticket, $all['url']);
        return returnArr(200, '获取成功', $config);
    }

    public function getToken() {
        if (!$this->appid || !$this->appsecret) {
            $config = hook("Login", ['moduleName' => __E("login_driver"), 'cloudType' => "plugin", 'data' => [
                'request' => \Request(),
                'req_type' => 'getConfig',
                'login_method' => 'WeChat',
                'login_type' => 'public',
                'data' => [],
            ]])[0];
            $this->appid = $config['wx_PUBLIC_APPID'];
            $this->appsecret = $config['wx_PUBLIC_APPSECRET'];
        }
        $key = "WeChatAccessToken";
        $access_token = Cache::get($key);
        if (!$access_token) {
            $url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid={$this->appid}&secret={$this->appsecret}";
            $data = file_get_contents($url);
            if ($data) {
                $data = json_decode($data, true);
                Cache::put($key, $data['access_token'], 7100);
                return $data['access_token'];
            }
        } else {
            return $access_token;
        }

    }

    public function getTicket($access_token) {
        $key = "WeChatTicket";
        $ticket = Cache::get($key);
        if (!$ticket) {
            $url = "https://api.weixin.qq.com/cgi-bin/ticket/getticket?access_token=" . $access_token . "&type=jsapi";
            $data = file_get_contents($url);
            if ($data) {
                $data = json_decode($data, true);
                Cache::put($key, $data['ticket'], 7100);
                return $data['ticket'];
            }
        } else {
            return $ticket;
        }
    }

    public function getSign($jsapi_ticket, $url) {
        $arr['noncestr'] = md5(time());
        $arr['jsapi_ticket'] = $jsapi_ticket;
        $arr['timestamp'] = time();
        $arr["url"] = $url;
        ksort($arr);
        $str = urldecode(http_build_query($arr));
        $str = sha1($str);
        $config['appId'] = $this->appid;
        $config['timestamp'] = $arr['timestamp'];
        $config['nonceStr'] = $arr['noncestr'];
        $config['signature'] = $str;
        return $config;
    }
}