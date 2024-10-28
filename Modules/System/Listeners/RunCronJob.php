<?php

namespace Modules\System\Listeners;

use Illuminate\Http\Request;

class RunCronJob {

    public function handle(\App\Events\RunCronJob $event) {
        $req = new Request();
        $schedule = $event->data['schedule'];
        $list = \Modules\System\Services\ServiceModel::getTaskList();
        foreach ($list as $item) {
            //获取定时周期格式
            $cron = $this->cron_time($item['day'], $item['hour'], $item['minute'])[$item['type']];
            //模块内 直接调用
            if ($item['module_class'] && $item['module_class_method']) {
                $schedule->call(function () use ($item, $req) {
                    $res = call_user_func([new $item['module_class']($req), $item['module_class_method']]);
                    \Modules\System\Services\ServiceModel::whereUpdateTask(['id' => $item['id']], ['last_execution_time' => getDay()]);
                    hook("Loger",[
                        'module' => $item['module'],
                        'type' => 5,
                        'two_type' => 2,
                        'params' => [
                            'time' => getDay(),
                            'result' => $res,
                        ],
                        'remark' => "定时任务执行结果",
                        'unique_id' => $item['id'],
                    ]);

                })->cron($cron);
            } else {
                //访问URL 或者 命令行
                $schedule->call(function () use ($item) {
                    $res = $this->executeTasks($item);
                })->cron($cron);
            }
        }

    }

    //获取定时周期格式
    public function cron_time($day, $hour, $min) {
        $day = intval($day);
        $hour = intval($hour);
        $min = intval($min);
        $list = [
            1 => "{$min} {$hour} * * * ",
            2 => "{$min} {$hour} */{$day} * * ",
            3 => "{$min} * * * * ",
            4 => "{$min} */{$hour} * * * ",
            5 => "*/{$min} * * * * ",
            6 => "{$min} {$hour} * * {$day}",
            7 => "{$min} {$hour} {$day} * *",
        ];
        if ($day == 0) $list[2] = "{$min} {$hour} * * * ";
        if ($hour == 0) $list[4] = "{$min} * * * * ";
        if ($min == 0) $list[5] = "* * * * * ";
        return $list;
    }

    //执行任务
    public function executeTasks($find) {
        hook("Loger",[
            'module' => $find['module'],
            'type' => 5,
            'two_type' => 1,
            'params' => [
                'time' => getDay(),
                'content' => $find['content'],
            ],
            'remark' => "定时任务执行参数",
            'unique_id' => $find['id'],
        ]);

        try {
            if ($find['module_class'] && $find['module_class_method']) {
                $res = call_user_func([new $find['module_class'](new Request()), $find['module_class_method']]);
            } elseif ($find['task_type'] == 1) {
                $res = curl_get($find['content']);
            } else {
                $res = shell_exec($find['content']);
            }
        } catch (\Exception $exception) {
            $res = false;
        }
        \Modules\System\Services\ServiceModel::whereUpdateTask(['id' => $find['id']], ['last_execution_time' => getDay()]);
        hook("Loger",[
            'module' => $find['module'],
            'type' => 5,
            'two_type' => 2,
            'params' => [
                'time' => getDay(),
                'content' => $find['content'],
                'result' => $res,
            ],
            'remark' => "定时任务执行结果",
            'unique_id' => $find['id'],
        ]);

        if ($res) {
            return returnArr(200, '执行成功');
        } else {
            return returnArr(0, '执行失败');
        }
    }

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

}
