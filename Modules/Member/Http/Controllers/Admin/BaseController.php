<?php

namespace Modules\Member\Http\Controllers\Admin;

use Modules\Member\Http\Controllers\Common\CommonController;
use Modules\Member\Models\BaseConfiguration;
use Illuminate\Http\Request;


class BaseController extends CommonController {
    public function __construct(Request $request) {
        parent::__construct($request);
    }

    public function baseConfig() {
        $pageData = [
            'title' => '系统管理',
            'subtitle' => '活动配置',
            'controller' => 'Setting',
            'action' => 'setting/baseConfig',
        ];
        $configKey = ['vipConfig','signInConfig'];
        $all = BaseConfiguration::query()->get()->toArray();
        $existKey = array_column($all, 'name');
        foreach ($configKey as $key) {
            if (!in_array($key, $existKey)) {
                $add = ['name' => $key, 'json_str' => '{}'];
                if (BaseConfiguration::add($add)) {
                    $all[] = $add;
                }
            }
        }
        foreach ($all as $a) {
            $key = strstr($a['name'], 'Config', true);
            $data[$key] = json_decode($a['json_str'], true);
        }

        return $this->adminView('base.baseConfig', [
            'pageData' => $pageData,
            'data' => $data,
        ]);
    }

    public function baseConfigSubmit() {
        $all = $this->request->all();
        $type = $all['type'];
        unset($all['_token'], $all['type']);
        switch ($type) {
            /*case 'task':
                return $this->taskConfig($all, 'taskConfig');
                break;*/
            default:
                return $this->commonConfig($all, "{$type}Config");
                break;
        }

    }

    //通用
    public function commonConfig($data, $filename) {
        $res = BaseConfiguration::whereUpdate([
            'name' => $filename
        ], [
            'json_str' => json_encode($data, JSON_UNESCAPED_UNICODE),
        ]);
        if ($res) {
            return ['status' => 200, 'msg' => '更新成功'];
        } else {
            return ['status' => 0, 'msg' => '更新失败'];
        }
    }

    public function taskConfig($data, $filename) {
        $res = BaseConfiguration::whereUpdate([
            'name' => $filename
        ], [
            'json_str' => json_encode($data, JSON_UNESCAPED_UNICODE),
        ]);
        if ($res) {
            return ['status' => 200, 'msg' => '更新成功'];
        } else {
            return ['status' => 0, 'msg' => '更新失败'];
        }
    }
}

