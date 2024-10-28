<?php

namespace Modules\System\Listeners;

use Illuminate\Http\Request;

class ExceptionHandler
{

    public function handle( \App\Events\ExceptionHandler $event) {

        //事件逻辑 ...
        $exception = $event->data['exception'];//获取事件数据
        if(\request()->route()){
            $module=explode('\\',\request()->route()->getAction()['namespace'])[1];
        }
        $array = dealErrorExceptionInfo($exception);
        hook("Loger",[
            'module' => $module?:"Main",
            'type' => 4,
            'two_type' => 1,
            'params' => $array,
            'remark' => "",
            'unique_id' => '',
        ]);

        if ($array['code'] == 40000) {
            //阻止父方法执行
            exit(json_encode(["status" => 40000, "msg" => $array['message']], JSON_UNESCAPED_UNICODE));
        } elseif (in_array($array['statusCode'], [400, 401])) {
            exit(json_encode(["status" => 40001, "msg" => $array['message']], JSON_UNESCAPED_UNICODE));
        } elseif (in_array($array['statusCode'], [404])) {
            die(view('error.404'));
        } elseif (in_array($array['statusCode'], [500])) {
            try {
                die(view('error.500', [
                    'msg' => $array['message'],
                    "file" => $array['file'],
                    "line" => $array['line']
                ]));
            }catch (\Exception $e){
                exit(json_encode(["status" => 500, "msg" => $array['message']], JSON_UNESCAPED_UNICODE));
            }
        }else{
            try {
                die(view('error.500', [
                    'msg' => $array['message'],
                    "file" => $array['file'],
                    "line" => $array['line']
                ]));
            }catch (\Exception $e){
                exit(json_encode(["status" => 500, "msg" => $array['message']], JSON_UNESCAPED_UNICODE));
            }
        }
    }

}
