<?php
//发送邮件
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Modules\Install\Http\Controllers\InstallController;

//检测手机号
function checkPhone($phone) {
    $check = '/^(1([3456789][0-9]))\d{8}$/';
    if (preg_match($check, $phone)) {
        return true;
    } else {
        return false;
    }
}

function sendEmail($to_email = '', $content = '我是测试的内容！', $subject = '邮件测试') {
    checkEmail($to_email);

    // 模板文件
    /*Mail::send('globals.emails.test',['name'=>$name],function($message){
        $to = '282584778@qq.com';
        $message ->to($to)->subject('邮件测试');
    });*/

    try {
        //测试邮件
        Mail::raw($content, function ($message) use ($to_email, $subject) {
            $to = $to_email;
            $message->to($to)->subject($subject);
        });
        // 返回的一个错误数组，利用此可以判断是否发送成功
        return Mail::failures();

    } catch (Exception $exception) {
        throw new Exception($exception->getMessage(), 40000);
    }

}

//检测邮件格式
function checkEmail($email_adress) {

    $pattern = '/^[a-z0-9]+([._-][a-z0-9]+)*@([0-9a-z]+\.[a-z]{2,14}(\.[a-z]{2})?)$/i';

    if (!preg_match($pattern, $email_adress)) {
        throw new Exception("Incorrect email address！", 40000);
    }
}

//过滤值为空的数组
function filterEmptyArr($array) {

    if (!is_array($array)) return false;

    $return_arr = array();
    foreach ($array as $k => $v) {
        if (is_array($v)) {

            foreach ($v as $k1 => $v1) {
                if ($v1) {
                    $return_arr[$k] = $v;
                }
            }

        } else if ($v) {
            $return_arr[$k] = $v;
        }


    }

    return $return_arr;
}

//验证数组是否为空,除了指定的key
function CheckArrIsEmpty($arr, $except = []) {
    if (!is_array($arr)) return false;
    foreach ($arr as $k => $value) {
        if (is_array($value)) {
            CheckArrIsEmpty($value);
        } else if (!$value && $value != 0) {
            if (!in_array($k, $except)) {
                throw  new  Exception($k . " is empty.", 40000);
            }
        }
    }
}

//对象生成树形结构数据
function buildTree($pidArray, $parentId = 0, $pkey = "id", $pidkey = "pid", $chkey = "children") {
    $tree = array();
    $retuen = "obj";
    foreach ($pidArray as $item) {
        if (is_array($item)) {
            $item = (object)$item;
            $retuen = "arr";
        }
        if ($item->$pidkey == $parentId) {
            $children = buildTree($pidArray, $item->$pkey, $pkey, $pidkey, $chkey);
            if ($children) {
                $item->$chkey = $children;
            }
            if ($retuen == "arr") {
                $tree[] = (array)$item;
            } else {
                $tree[] = $item;
            }

        }
    }
    return $tree;
}

//属性递归生成缩进
function generateIndentedTree(&$tree, $depth = 0, $name = "name", $symbol = " |— ", $chkey = "children") {
    $retuen = "obj";
    foreach ($tree as $index => $node) {
        if (is_array($node)) {
            $retuen = "arr";
        }
        if ($depth > 0) {
            if ($retuen == "arr") {
                $tree[$index][$name] = str_repeat($symbol, $depth - 1) . $symbol . $node[$name];
            } else {
                $node->$name = str_repeat($symbol, $depth - 1) . $symbol . $node->$name;
            }
        }
        if ($retuen == "obj" && isset($node->$chkey)) {
            generateIndentedTree($node->$chkey, $depth + 1, $name, $symbol, $chkey);
        } elseif ($retuen == "arr" && isset($node[$chkey])) {
            generateIndentedTree($tree[$index][$chkey], $depth + 1, $name, $symbol, $chkey);
        }
    }
}

function getTreeoptions($selectDatas, &$datas = [], $key = "id", $name = "name", $chkey = "children", $getAllFile = false) {
    foreach ($selectDatas as $selectData) {
        if ($getAllFile) {
            $datas[] = $selectData;
        } else {
            $datas[] = [
                "name" => is_array($selectData) ? $selectData[$name] : $selectData->$name,
                "value" => is_array($selectData) ? $selectData[$key] : $selectData->$key
            ];
        }

        if (
            (is_array($selectData) && isset($selectData[$chkey])) || (!is_array($selectData) && isset($selectData->$chkey))
        ) {
            getTreeoptions(is_array($selectData) ? $selectData['children'] : $selectData->$chkey, $datas, $key, $name, $chkey, $getAllFile);
        }
    }
}

//生成树形结构数据
function listToTree($list, $pk = 'id', $pid = 'pid', $child = '_child', $root = 0) {
    $tree = array();
    if (is_array($list)) {
        $refer = array();
        foreach ($list as $key => $data) {
            $refer[$data[$pk]] = &$list[$key];
        }

        foreach ($list as $key => $data) {
            // 判断是否存在parent
            $parentId = $data[$pid];

            if ($root == $parentId) {
                $tree[$data[$pk]] = &$list[$key];
            } else {
                if (isset($refer[$parentId])) {
                    $parent = &$refer[$parentId];
                    $parent[$child][$data[$pk]] = &$list[$key];
                }
            }
        }
    }

    return $tree;
}

//读取树形数据,树形数据$data，$pid 当前菜单的父ID
function getTreeData($data, $pid = 0) {

    foreach ($data as $key => $val) {

        if ($pid == $val["id"]) {
            $select = " selected ";
        } else {
            $select = "  ";
        }

        echo "<option value='" . $val["id"] . "' " . $select . " >" . cerate_xxx($val["level"]) . $val["name"] . "</option>";

        if (isset($val["sub"])) {
            getTreeData($val["sub"], $pid);
        }

    }

}

//读取树形数据,树形数据$data，$type 前台还是后台
function getTreeDataForTable($data, $type = "home") {

    foreach ($data as $key => $val) {

        $class = '';

        if ($val["level"] == 1) {
            $class = "style='font-weight: bold;font-size: 20px;'";
        }

        $stauts = $val["stauts"] == '1' ? '启用' : '禁用';

        echo "<tr><td>" . $val["id"] . "</td>" .

            "<td " . $class . ">" . cerate_xxx($val["level"]) . $val["name"] . "</td>" .

            "<td>" . $val["path"] . "</td>" .

            "<td>" . $val["pre_icon"] . "</td>" .

            "<td>" . $val["suf_icon"] . "</td>" .

            "<td>" . $val["order"] . "</td>" .

            "<td>" . $stauts . "</td>" .


            "<td>" .
            "<a class=\"\" href=\"" . url('admin/menu/' . $type . '/edit/' . $val["id"]) . "\">编辑</a> &nbsp;&nbsp;" .
            "<a class=\"\" href=" . "#" . ">高级选项</a> &nbsp;&nbsp;" .
            "<a class=\"\" href=\"javascript:;\" onclick=\"delData(" . $val["id"] . ")\" >删除</a></td>" .
            "</tr>";

        if (isset($val["sub"])) {
            getTreeDataForTable($val["sub"], $type);
        }

    }

}

//读取树形数据,树形数据$data
function getTreeDataForAuthTable($data) {

    foreach ($data as $key => $val) {

        $class = '';

        if ($val["level"] == 1) {
            $class = "style='font-weight: bold;font-size: 20px;'";
        }

        echo "<tr><td>" . $val["id"] . "</td>" .

            "<td " . $class . ">" . cerate_xxx($val["level"]) . $val["name"] . "</td>" .

            "<td>" . $val["path"] . "</td>" .

            "<td>" . $val["permissions"] . "</td>" .

            "<td>" .
            "<a class=\"\" href=\"" . url('admin/menu/auth/edit/' . $val["id"]) . "\">编辑</a> &nbsp;&nbsp;" .
            "<a class=\"\" href=\"javascript:;\" onclick=\"delData(" . $val["id"] . ")\" >删除</a></td>" .
            "</tr>";

        if (isset($val["sub"])) {
            getTreeDataForAuthTable($val["sub"]);
        }

    }

}

//创建下级标示
function cerate_xxx($index) {

    $str = "";

    if ($index != 1) {

        for ($i = 2; $i < $index; $i++) {
            $str .= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
        }
        $str .= "|—";

    }


    return $str;

}

function excelTime($date, $time = false) {
    if (function_exists('GregorianToJD')) {
        if (is_numeric($date)) {
            $jd = GregorianToJD(1, 1, 1970);
            $gregorian = JDToGregorian($jd + intval($date) - 25569);
            $date = explode('/', $gregorian);
            $date_str = str_pad($date [2], 4, '0', STR_PAD_LEFT)
                . "-" . str_pad($date [0], 2, '0', STR_PAD_LEFT)
                . "-" . str_pad($date [1], 2, '0', STR_PAD_LEFT)
                . ($time ? " 00:00:00" : '');
            return $date_str;
        }
    } else {
        $date = $date > 25568 ? $date + 1 : 25569;
        /*There was a bug if Converting date before 1-1-1970 (tstamp 0)*/
        $ofs = (70 * 365 + 17 + 2) * 86400;
        $date = date("Y-m-d", ($date * 86400) - $ofs) . ($time ? " 00:00:00" : '');
    }
    return $date;
}

//是否为手机号码
if (!function_exists('is_mobile')) {
    function is_mobile($text) {
        $search = '/^0?1[3|4|5|6|7|8][0-9]\d{8}$/';
        if (preg_match($search, $text)) return true;
        else return false;
    }
}

//手机号码 中间4位加密
if (!function_exists('get_encryption_mobile')) {
    function get_encryption_mobile($tel) {
        $new_tel = preg_replace('/(\d{3})\d{4}(\d{4})/', '$1****$2', $tel);
        return $new_tel;
    }
}

//随机验证码
if (!function_exists('random_verification_code')) {
    function random_verification_code($length = 6) {
        $code = '';
        for ($i = 0; $i < $length; $i++) $code .= mt_rand(0, 9);
        return $code;
    }
}

if (!function_exists('member_encryption_mode')) {
    function member_encryption_mode($password) {
        return md5("union_" . md5($password));
    }
}

/**
 * 获取客户端浏览器信息
 * @param null
 * @return  string
 * @author  huang
 */
function get_broswer() {
    $sys = $_SERVER['HTTP_USER_AGENT'];  //获取用户代理字符串
    if (stripos($sys, "Firefox/") > 0) {
        preg_match("/Firefox\/([^;)]+)+/i", $sys, $b);
        $exp[0] = "Firefox";
        $exp[1] = $b[1];    //获取火狐浏览器的版本号
    } elseif (stripos($sys, "Maxthon") > 0) {
        preg_match("/Maxthon\/([\d\.]+)/", $sys, $aoyou);
        $exp[0] = "傲游";
        $exp[1] = $aoyou[1];
    } elseif (stripos($sys, "MSIE") > 0) {
        preg_match("/MSIE\s+([^;)]+)+/i", $sys, $ie);
        $exp[0] = "IE";
        $exp[1] = $ie[1];  //获取IE的版本号
    } elseif (stripos($sys, "OPR") > 0) {
        preg_match("/OPR\/([\d\.]+)/", $sys, $opera);
        $exp[0] = "Opera";
        $exp[1] = $opera[1];
    } elseif (stripos($sys, "Edge") > 0) {
        //win10 Edge浏览器 添加了chrome内核标记 在判断Chrome之前匹配
        preg_match("/Edge\/([\d\.]+)/", $sys, $Edge);
        $exp[0] = "Edge";
        $exp[1] = $Edge[1];
    } elseif (stripos($sys, "Chrome") > 0) {
        preg_match("/Chrome\/([\d\.]+)/", $sys, $google);
        $exp[0] = "Chrome";
        $exp[1] = $google[1];  //获取google chrome的版本号
    } elseif (stripos($sys, 'rv:') > 0 && stripos($sys, 'Gecko') > 0) {
        preg_match("/rv:([\d\.]+)/", $sys, $IE);
        $exp[0] = "IE";
        $exp[1] = $IE[1];
    } else {
        $exp[0] = "未知浏览器";
        $exp[1] = "";
    }
    return $exp[0] . '(' . $exp[1] . ')';
}

/**
 * 获取客户端操作系统信息,包括win10
 * @param null
 * @return  string
 * @author  huang
 */
function get_os() {

    $agent = $_SERVER['HTTP_USER_AGENT'];
    $os = false;

    if (preg_match('/win/i', $agent) && strpos($agent, '95')) {
        $os = 'Windows 95';
    } else if (preg_match('/win 9x/i', $agent) && strpos($agent, '4.90')) {
        $os = 'Windows ME';
    } else if (preg_match('/win/i', $agent) && preg_match('/98/i', $agent)) {
        $os = 'Windows 98';
    } else if (preg_match('/win/i', $agent) && preg_match('/nt 6.0/i', $agent)) {
        $os = 'Windows Vista';
    } else if (preg_match('/win/i', $agent) && preg_match('/nt 6.1/i', $agent)) {
        $os = 'Windows 7';
    } else if (preg_match('/win/i', $agent) && preg_match('/nt 6.2/i', $agent)) {
        $os = 'Windows 8';
    } else if (preg_match('/win/i', $agent) && preg_match('/nt 10.0/i', $agent)) {
        $os = 'Windows 10';#添加win10判断
    } else if (preg_match('/win/i', $agent) && preg_match('/nt 5.1/i', $agent)) {
        $os = 'Windows XP';
    } else if (preg_match('/win/i', $agent) && preg_match('/nt 5/i', $agent)) {
        $os = 'Windows 2000';
    } else if (preg_match('/win/i', $agent) && preg_match('/nt/i', $agent)) {
        $os = 'Windows NT';
    } else if (preg_match('/win/i', $agent) && preg_match('/32/i', $agent)) {
        $os = 'Windows 32';
    } else if (preg_match('/linux/i', $agent)) {
        $os = 'Linux';
    } else if (preg_match('/unix/i', $agent)) {
        $os = 'Unix';
    } else if (preg_match('/sun/i', $agent) && preg_match('/os/i', $agent)) {
        $os = 'SunOS';
    } else if (preg_match('/ibm/i', $agent) && preg_match('/os/i', $agent)) {
        $os = 'IBM OS/2';
    } else if (preg_match('/Mac/i', $agent) && preg_match('/PC/i', $agent)) {
        $os = 'Macintosh';
    } else if (preg_match('/PowerPC/i', $agent)) {
        $os = 'PowerPC';
    } else if (preg_match('/AIX/i', $agent)) {
        $os = 'AIX';
    } else if (preg_match('/HPUX/i', $agent)) {
        $os = 'HPUX';
    } else if (preg_match('/NetBSD/i', $agent)) {
        $os = 'NetBSD';
    } else if (preg_match('/BSD/i', $agent)) {
        $os = 'BSD';
    } else if (preg_match('/OSF1/i', $agent)) {
        $os = 'OSF1';
    } else if (preg_match('/IRIX/i', $agent)) {
        $os = 'IRIX';
    } else if (preg_match('/FreeBSD/i', $agent)) {
        $os = 'FreeBSD';
    } else if (preg_match('/teleport/i', $agent)) {
        $os = 'teleport';
    } else if (preg_match('/flashget/i', $agent)) {
        $os = 'flashget';
    } else if (preg_match('/webzip/i', $agent)) {
        $os = 'webzip';
    } else if (preg_match('/offline/i', $agent)) {
        $os = 'offline';
    } else {
        $os = '未知操作系统';
    }
    return $os;
}

if (!function_exists('get_client_info')) {
    /**
     * 获取IP与浏览器信息、语言
     */
    function get_client_info() {
        if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $XFF = $_SERVER['HTTP_X_FORWARDED_FOR'];
            $client_pos = strpos($XFF, ', ');
            $client_ip = false !== $client_pos ? substr($XFF, 0, $client_pos) : $XFF;
            unset($XFF, $client_pos);
        } else $client_ip = $_SERVER['HTTP_CLIENT_IP'] ?: $_SERVER['REMOTE_ADDR'] ?: $_SERVER['LOCAL_ADDR'] ?: '0.0.0.0';
        $client_lang = isset($_SERVER['HTTP_ACCEPT_LANGUAGE']) ? substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 5) : '';
        $client_agent = $_SERVER['HTTP_USER_AGENT'] ?: '';
        return ['ip' => &$client_ip, 'lang' => &$client_lang, 'agent' => &$client_agent];
    }
}

if (!function_exists('get_ip')) {
    function get_ip() {
        $data = get_client_info();
        return empty($data['ip']) ? '' : $data['ip'];
    }
}

//获取ip
function getIP($type = 0) {
    $type = $type ? 1 : 0;
    static $ip = NULL;
    if ($ip !== NULL)
        return $ip[$type];
    if (isset($_SERVER['HTTP_CLIENT_IP'])) {//客户端的ip
        $ip = $_SERVER['HTTP_CLIENT_IP'];
    } elseif (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {//浏览当前页面的用户计算机的网关
        $arr = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
        $pos = array_search('unknown', $arr);
        if (false !== $pos)
            unset($arr[$pos]);
        $ip = trim($arr[0]);
    } elseif (isset($_SERVER['REMOTE_ADDR'])) {
        $ip = $_SERVER['REMOTE_ADDR']; //浏览当前页面的用户计算机的ip地址
    } else {
        $ip = $_SERVER['REMOTE_ADDR'];
    }
    // IP地址合法验证
    $long = sprintf("%u", ip2long($ip));
    $ip = $long ? array($ip, $long) : array('0.0.0.0', 0);
    $str = $ip[$type];
    if ($str == '0.0.0.0') return getIP2();
    return $str;
}

function getIP2() {
    if (getenv("HTTP_CLIENT_IP")) {
        $ip = getenv("HTTP_CLIENT_IP");
    } else if (getenv("HTTP_X_FORWARDED_FOR")) {
        $ip = getenv("HTTP_X_FORWARDED_FOR");
    } else if (getenv("HTTP_X_FORWARDED")) {
        $ip = getenv("HTTP_X_FORWARDED");
    } else if (getenv("HTTP_FORWARDED_FOR")) {
        $ip = getenv("HTTP_FORWARDED_FOR");
    } else if (getenv("HTTP_FORWARDED")) {
        $ip = getenv("HTTP_FORWARDED");
    } else {
        $ip = $_SERVER["REMOTE_ADDR"];
    }
    return $ip ?: '0.0.0.0';
}

function get_tree_list(array $array, $id = 0, $level = 0, $parent_id = 'pid') {
    $list = array();
    foreach ($array as $k => $v) {
        if ($v[$parent_id] == $id) {
            $v['level'] = $level;
            $v['_child'] = get_tree_list($array, $v['id'], $level + 1);
            $list[] = $v;
        }
    }
    return $list;
}

if (!function_exists('get_uuid')) {
    function get_uuid($string = '') {
        $string = '' === $string ? uniqid(mt_rand(), true) : (0 === (int)preg_match('/[A-Z]/', $string) ? $string : mb_strtolower($string, 'UTF-8'));
        $code = hash('sha1', $string . ':UUID');
        $uuid = substr($code, 0, 10);
        $uuid .= substr($code, 10, 4);
        $uuid .= substr($code, 16, 4);
        $uuid .= substr($code, 22, 4);
        $uuid .= substr($code, 28, 12);
        $uuid = strtoupper($uuid);
        unset($string, $code);
        return $uuid;
    }
}

function is_install() {
    return InstallController::checkInstall();
}

//生成邀请码函数
// 注意这里生成的邀请码 不会超过 13位数 超过13 位函数递归
//后期业务量大可以重新改进
//参数id 为数据库自增id
//by andy update 2019-12-16
//数据库自增id 大于100 万请重新改进和使用其他方法
function createOnlyId($id) {

    //打乱字符串种子
    $code = str_shuffle('ABCDEFGHIJKLMNOPQRSTUVWXYZksdjfksdjwieujqoznnqweurjajdjskjdkfjdsfkslcxvio');

    $rand = $code[rand(0, 25)]
        . strtoupper(dechex(date('m')))
        . date('d')
        . substr(time(), -5)
        . substr(microtime(), 2, 5)
        . sprintf('%02d', rand(0, 99));
    for (
        $a = md5($rand, true),
        $s = str_shuffle('0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZaloqweoernxzmvnxmvmxcskafhksddqellmkjajdffd'),
        $d = '',
        $f = 0;
        $f < 4;
        $g = ord($a[$f]),
        $d .= $s[($g ^ ord($a[$f + 8])) - $g & 0x1F],
        $f++
    ) ;

    //将id 放入数组 这样可以放头部跟尾部，不一样
    $arr = [0 => rand(0, 10), 1 => $id, 2 => date('s'), 3 => date('i')];

    //将id 唯一值 $arr[1] 加入进去
    $re_str = $arr[rand(0, 3)] . $d . $arr[1];
    //位数大于13 重新生成 函数递归
    if (strlen($re_str) > 13) {
        return createOnlyId($id);
    } else {
        return $re_str;
    }

}

//判断是否是微信浏览器
function is_weixin() {

    if (strpos($_SERVER['HTTP_USER_AGENT'],
            'MicroMessenger') !== false) {
        return true;
    }
    return false;

}

/**
 * 判断是否支付宝内置浏览器访问
 * @return bool
 */
function isAliClient() {
    return strpos($_SERVER['HTTP_USER_AGENT'], 'Alipay') !== false;
}

/**
 * 是否移动端访问访问
 *
 * @return bool
 */
function isMobileClient() {
    // 如果有HTTP_X_WAP_PROFILE则一定是移动设备
    if (isset($_SERVER['HTTP_X_WAP_PROFILE'])) {
        return true;
    }

//如果via信息含有wap则一定是移动设备,部分服务商会屏蔽该信息
    if (isset($_SERVER['HTTP_VIA'])) {
        //找不到为flase,否则为true
        return stristr($_SERVER['HTTP_VIA'], "wap") ? true : false;
    }

//判断手机发送的客户端标志
    if (isset($_SERVER['HTTP_USER_AGENT'])) {
        $clientkeywords = [
            'nokia', 'sony', 'ericsson', 'mot', 'samsung', 'htc', 'sgh', 'lg', 'sharp',
            'sie-', 'philips', 'panasonic', 'alcatel', 'lenovo', 'iphone', 'ipod', 'blackberry', 'meizu',
            'android', 'netfront', 'symbian', 'ucweb', 'windowsce', 'palm', 'operamini', 'operamobi',
            'openwave', 'nexusone', 'cldc', 'midp', 'wap', 'mobile', 'alipay'
        ];

// 从HTTP_USER_AGENT中查找手机浏览器的关键字
        if (preg_match("/(" . implode('|', $clientkeywords) . ")/i", strtolower($_SERVER['HTTP_USER_AGENT']))) {
            return true;
        }
    }

//协议法，因为有可能不准确，放到最后判断
    if (isset($_SERVER['HTTP_ACCEPT'])) {
        // 如果只支持wml并且不支持html那一定是移动设备
        // 如果支持wml和html但是wml在html之前则是移动设备
        if ((strpos($_SERVER['HTTP_ACCEPT'], 'vnd.wap.wml') !== false) && (strpos($_SERVER['HTTP_ACCEPT'], 'text/html') === false || (strpos($_SERVER['HTTP_ACCEPT'], 'vnd.wap.wml') < strpos($_SERVER['HTTP_ACCEPT'], 'text/html')))) {
            return true;
        }
    }

    return false;
}

if (!function_exists('get_month_days')) {
    /**
     * 获取某月份的所有日期列表
     * @param string $time
     * @param string $format
     * @return array
     */
    function get_month_days($time = '', $format = 'Y-m-d') {
        $time = $time != '' ? $time : time();
        //获取当前周几
        $week = date('d', $time);
        $date = [];
        for ($i = 1; $i <= date('t', $time); $i++) {
            $date[$i] = date($format, strtotime('+' . $i - $week . ' days', $time));
        }
        return $date;
    }
}

if (!function_exists('set_month_format')) {
    /**
     * 设置 月份 的格式统一
     * @param $month
     * @return string
     */
    function set_month_format($month) {
        return (string)(strlen($month) == 1 ? '0' . $month : $month);
    }
}


function getPhoneCode() {
    return [
        86 => '中国大陆',
        852 => '中国香港',
        853 => '中国澳门',
        886 => '中国台湾',
    ];
}

function userType() {
    return [];
}

function dealErrorExceptionInfo($exception) {
    $tmp = (array)$exception;
    $array = [];
    $in = ['statusCode', 'code', 'message', "file", "line"];
    foreach ($tmp as $k => $t) {
        $str = str_replace("\x00", '\\', $k);
        $keyArr = explode('\\', $str);
        $key = $keyArr[count($keyArr) - 1];
        if (in_array($key, $in)) $array[$key] = $t;
    }
    if (empty($array['statusCode'])) {
        $array['statusCode'] = 500;
    }
    if (!in_array($array['code'], [500, 404]) && !in_array($array['statusCode'], [500, 404])) {
        $array['view'] = "error.500";
    } else {
        $array['view'] = "error." . $array['statusCode'];
    }
    return $array;
}

function toArray($d) {
    if (!$d) return [];
    if (is_array($d)) return $d;
    $arr = get_object_vars($d);
    if ($arr['exists']) $arr = $d->toArray();
    return $arr;
}

//判断参数
function ifCondition($arr, $all = []) {
    $all = count($all) > 0 ? $all : \Request()->all();
    foreach ($arr as $key => $val) {
        $req = 'required';
        $field[$key] = $req;
        $msg[$key . '.' . $req] = $val;
    }
    $validator = Validator::make($all, $field, $msg);
    if ($validator->fails()) return ['status' => 0, 'msg' => $validator->errors()->first()];
    return false;
}

//获取签名
function getSign($params, $key) {
    if (!$params || !$key) return false;
    $no_in = ['sign'];
    ksort($params);
    $str = '';
    foreach ($params as $k => $param) {
        if (in_array($k, $no_in)) continue;
        $str .= "$k=$param&";
    }
    $str .= "key=$key";
    return md5($str);
}