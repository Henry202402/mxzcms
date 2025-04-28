<?php

namespace Modules\Main\Http\Controllers\Api;

use Illuminate\Support\Facades\Artisan;
use Illuminate\Routing\Controller as BaseController;

class AsynController extends BaseController {

    public function asynCall() {
        ignore_user_abort(true);
        set_time_limit(0);
        ini_set('max_execution_time', 0);
        ini_set('memory_limit', '-1');

        $all = request()->all();

        //actionName ，命令行名称等耗时操作 update download send email
        //arguments  ，命令行参数
        //Artisan::call('migrate', [
        //   '--path' => "Modules/Main/Database/Migrations/update",
        //   '--force' => 1,
        //]);
        //moduleName 模块名称
        //调用方法
//        curl_request_ms(url("api/asynCall"),[
//            'actionName'=>"migrate",
//            'arguments'=>[
//                '--path' => "Modules/Main/Database/Migrations/update",
//                '--force' => 1,
//            ],
//            'moduleName'=>"Main"
//        ]);

        if(!$all['actionName']){
            exit('命令行名称不能为空!');
        }

        if(!$all['moduleName']){
            exit('模块名称不能为空!');
        }

        if(!$all['arguments']){
            $all['arguments'] = [];
        }

        // 执行一些任务或等待新数据
        hook("Loger", [
            'module' => $all['moduleName'],
            'type' => 4,
            'two_type' => 3,
            'params' => $all,
            'remark' => "开始异步记录",
            'unique_id' => '',
            'requestid' => $all['requestid']
        ]);
        $res = Artisan::call($all['actionName'], $all['arguments']);
        if($res){
            $res = "执行失败";
        }else{
            $res = "执行成功";
        }
        //日志记录
        hook("Loger", [
            'module' => $all['moduleName'],
            'type' => 4,
            'two_type' => 3,
            'params' => $all,
            'remark' => "异步记录结束,结果：{$res}",
            'unique_id' => '',
            'requestid' => $all['requestid']
        ]);

    }

}
