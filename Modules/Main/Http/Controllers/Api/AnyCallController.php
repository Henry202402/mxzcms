<?php

namespace Modules\Main\Http\Controllers\Api;

use Illuminate\Routing\Controller as BaseController;

class AnyCallController extends BaseController {

    public function asynCall() {
        set_time_limit(0);
        ini_set('max_execution_time', 0);
        ini_set('memory_limit', '-1');

        $all = request()->all();

        if(!$all['className ']){
            return returnArr(0,'类库名称不能为空');
        }

        if (!class_exists($all['className '])) {
            return returnArr(0,'类库不存在');
        }

        if(!$all['actionName']){
            return returnArr(0,'方法名称不能为空');
        }

        if (!method_exists($all['className '],$all['actionName'])){
            return returnArr(0,'类方法不存在');
        }

        if(!$all['arguments']){
            $all['arguments'] = [];
        }

        $res = call_user_func([ new $all['className '](),$all['actionName']],$all['arguments']);

        //日志记录
        hook("Loger", [
            'module' => "Main",
            'type' => "access",
            'two_type' => "api",
            'params' => $all,
            'remark' => "万能接口：{$res}",
            'unique_id' => '',
            'requestid' => $all['requestid']
        ]);

        return $res;

    }

}
