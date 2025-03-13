<?php

namespace Modules\System\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PayController extends Controller {

    public function getData($array = null) {
        if ($array) return is_array($array) ? $array : json_decode($array, true);
        header("Content-type:text/html;charset=utf-8");
        if ($GLOBALS['HTTP_RAW_POST_DATA']) {
            $data = $GLOBALS['HTTP_RAW_POST_DATA'];
        } else if ($_POST['HTTP_RAW_POST_DATA']) {
            $data = $_POST['HTTP_RAW_POST_DATA'];
        } else if ($_GET['HTTP_RAW_POST_DATA']) {
            $data = $_GET['HTTP_RAW_POST_DATA'];
        } elseif (file_get_contents("php://input")) {
            $data = file_get_contents("php://input");
        } else{
            $data = \Request()->all();
        }
        return $data;
    }

    //支付回调
    public function callback(Request $request, $pay_method) {
        $data = $this->getData();
        file_put_contents('./callback.txt', var_export($data, true) . PHP_EOL, FILE_APPEND);
        hook("Pay", ['moduleName' => __E("pay_driver"), 'cloudType' => "plugin", 'data' => [
            'request' => \Request(),
            'module' => '',
            'action' => '',
            'req_type' => 'callback',
            'pay_method' => $pay_method,
            'pay_type' => '',
            'outTradeNo' => '',
            'totalFee' => 0,
            'openid' => '',
            'callback_data' => $data,
        ]])[0];
    }
}
