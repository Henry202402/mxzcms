<?php

function curl_get($url, $time = 5) {
    $curl = curl_init();
    curl_setopt_array($curl, array(
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => $time,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'GET',
    ));

    $response = curl_exec($curl);
    if (curl_errno($curl)) {
        return json_encode(['status' => 0, 'msg' => '请求错误', 'error' => curl_error($curl)], JSON_UNESCAPED_UNICODE);
    }
    curl_close($curl);
    return $response;
}

function curl_post($url, $data) {
    $curl = curl_init();

    curl_setopt_array($curl, array(
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS => $data,
    ));

    $response = curl_exec($curl);

    curl_close($curl);
    return is_array($response) ?: json_decode($response, true);
}

//参数1：访问的URL，参数2：post数据(不填则为GET)，参数3：提交的$cookies,参数4：是否返回$cookies
function curl_request($url, $post = '', $cookie = '', $returnCookie = 0, $json = false, $header = array()) {
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_USERAGENT, 'Mozilla/5.0 (compatible; MSIE 10.0; Windows NT 6.1; Trident/6.0)');
    curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 0);
    curl_setopt($curl, CURLOPT_AUTOREFERER, 0);
    //curl_setopt($curl, CURLOPT_REFERER, "http://XXX");
    if ($post && !$json) {
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($post));
    }
    if ($json) {
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $post);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
    }
    if ($cookie) {
        curl_setopt($curl, CURLOPT_COOKIE, $cookie);
    }
    curl_setopt($curl, CURLOPT_HEADER, $returnCookie);
    curl_setopt($curl, CURLOPT_TIMEOUT, 60);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
    //不输出头信息
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    $data = curl_exec($curl);
    if (curl_errno($curl)) {
        return false;
        //throw new Exception("Error:" . curl_error($curl));
    }
    curl_close($curl);
    if ($returnCookie) {
        list($header, $body) = explode("\r\n\r\n", $data, 2);
        preg_match_all("/Set\-Cookie:([^;]*);/", $header, $matches);
        $info['cookie'] = substr($matches[1][0], 1);
        $info['content'] = $body;
        return $info;
    } else {
        return $data;
    }
}

//获取本地翻译语言
function getTranslateByKey($key, $type = "admin") {
    $temp['moduleName'] = explode('\\', \request()->route()->getAction()['namespace'])[1];
    $loader = App()->make("translation.loader");
    if ($loader->load($type, session("admin_current_language")["shortcode"], strtolower($temp["moduleName"]))) {
        return $loader->load($type, session("admin_current_language")["shortcode"], strtolower($temp["moduleName"]))[$key];
    }
    $temp["moduleName"] = "system";
    if ($loader->load($type, session("admin_current_language")["shortcode"], strtolower($temp["moduleName"]))) {
        return $loader->load($type, session("admin_current_language")["shortcode"], strtolower($temp["moduleName"]))[$key];
    }
    return $key;
}

function getHomeByKey($key) {
    return __(session("home_current_language")["shortcode"] . "." . $key);
}

//获取上一页的URL
function getPreUrl() {
    return url()->previous();
}

//API统一的数据返回格式
if (!function_exists('return_api_format')) {
    function return_api_format($return = []) {
        $return['data'] = !isset($return['data']) ? [] : $return['data'];
        $return['msg'] = !isset($return['msg']) ? '获取成功' : $return['msg'];
        $return['status'] = !isset($return['status']) ? (empty($return['data']) ? 40000 : 200) : $return['status'];
        return response()->json($return);
    }
}

//seo配置URL
function seourl($url) {
    //判断是否开启重写
    if (1) {
        return url($url);
    }
}
