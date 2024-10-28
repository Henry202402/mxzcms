<?php

namespace Modules\System\Http\Controllers\Admin;

use Illuminate\Support\Facades\Cache;
use Modules\System\Http\Controllers\Common\CommonController;
use Illuminate\Http\Request;
use Modules\System\Listeners\RunCronJob;
use Modules\System\Models\Task;

class TaskController extends CommonController {

    public function __construct(Request $request) {
        parent::__construct($request);
    }

    //定时任务
    public function scheduledTasksList(Request $request) {
        $pageData = [
            'subtitle' => '安全与工具',
            'title' => '定时任务',
            'controller' => 'Secure',
            'action' => 'scheduledTasksList',
        ];
        $res = hook('GetCronJob', []);

        $route = getURIByRoute($this->request);
        $moduleName = $route['moduleName'];
        foreach ($res as $key => $m) {
            if ($m[0] == $moduleName) {
                $pageData['data'] = $m[1];
                unset($res[$key]);
                break;
            }
        }

        $addModuleList = [];
        foreach ($pageData['data'] as $d) {
            if ($d['module_class'] && $d['module_class_method']) $addModuleList[] = "{$d['module_class']}@{$d['module_class_method']}";
        }

        $noAddList = [];
        foreach ($res as $key => $m) {
            foreach ($m[1] as $mv) {
                if (!is_array($mv)) $mv = explode('@', $mv);
                if(!$mv[0] || !$mv[1]) continue;
                $str = "{$mv[0]}@{$mv[1]}";
                if (!in_array($str, $addModuleList)) {
                    $remark = urlencode($mv[2]);
                    $base = base64_encode("{$m[0]}@{$mv[0]}@{$mv[1]}@{$remark}");
                    $noAddList[] = [strtolower($m[0]), $mv[0], $mv[1], $mv[2], $base];
                }
            }
        }

        $pageData['noAddList'] = $noAddList;
        $moduleInfo = Cache::get(\Mxzcms\Modules\cache\CacheKey::ModulesActive);
        foreach ($moduleInfo as $m) {
            $pageData['moduleList'][strtolower($m['identification'])] = $m['name'];
        }

        return $this->adminView('task.scheduledTasksList', [
            'pageData' => $pageData,
        ]);
    }

    //添加
    public function scheduledTasksAdd(Request $request) {
        $all = $request->all();
        if ($request->ajax()) {
            $add['task_type'] = intval($all['task_type']);
            $add['name'] = trim($all['name']);
            $add['remark'] = trim($all['remark']);
            if ($all['module']) $add['module'] = strtolower($all['module']);
            $add['module_class'] = trim($all['module_class']);
            $add['module_class_method'] = trim($all['module_class_method']);
            $add['type'] = intval($all['type']);
            $add['day'] = $add['hour'] = 0;
            if (in_array($add['type'], [6])) {
                $add['day'] = intval($all['week']);
            }
            if (in_array($add['type'], [2, 7])) {
                $add['day'] = intval($all['day']);
            }
            if (in_array($add['type'], [1, 2, 4, 6, 7])) {
                $add['hour'] = intval($all['hour']);
            }
            $add['minute'] = intval($all['minute']);
            if ($add['type'] == 1 && $add['day'] == 0) $add['day'] = 1;
            if ($add['type'] == 5 && $add['minute'] == 0) $add['minute'] = 1;
            $add['content'] = $all['task_type'] == 2 ? trim($all['content2']) : trim($all['content1']);
            if (\Modules\System\Services\ServiceModel::addTask($add)) {
                return returnArr(200, '添加成功');
            } else {
                return returnArr(0, '添加失败');
            }
        }
        $pageData = [
            'subtitle' => '安全与工具',
            'title' => '添加定时任务',
            'controller' => 'Secure',
            'action' => 'secure/scheduledTasksList',
        ];
        if ($all['info']) {
            $pageData['moduleInfo'] = explode('@', base64_decode($all['info']));
            $pageData['moduleInfo'][3] = urldecode($pageData['moduleInfo'][3]);
        }
        return $this->adminView('task.scheduledTasksAdd', [
            'pageData' => $pageData,
        ]);
    }

    //编辑
    public function scheduledTasksEdit(Request $request) {
        $all = $request->all();
        $data = \Modules\System\Services\ServiceModel::apiGetOneTask(['id' => $all['id']]);
        if ($request->ajax()) {
            if (!$data) return returnArr(0, '记录不存在');
            if ($all['update_type'] == 1) {
                $add['status'] = $all['status'] == 2 ? 2 : 1;
                $msg = ['操作成功', '操作失败'];
            } else {
                $add['task_type'] = intval($all['task_type']);
                $add['name'] = trim($all['name']);
                $add['remark'] = trim($all['remark']);
                $add['type'] = intval($all['type']);
                $add['day'] = $add['hour'] = 0;
                if (in_array($add['type'], [6])) {
                    $add['day'] = intval($all['week']);
                }
                if (in_array($add['type'], [2, 7])) {
                    $add['day'] = intval($all['day']);
                }
                if (in_array($add['type'], [1, 2, 4, 6, 7])) {
                    $add['hour'] = intval($all['hour']);
                }
                $add['minute'] = intval($all['minute']);
                if ($add['type'] == 1 && $add['day'] == 0) $add['day'] = 1;
                if ($add['type'] == 5 && $add['minute'] == 0) $add['minute'] = 1;
                $add['content'] = $all['task_type'] == 2 ? trim($all['content2']) : trim($all['content1']);

                $msg = ['更新成功', '更新失败'];
            }

            if (\Modules\System\Services\ServiceModel::whereUpdateTask(['id' => $data['id']], $add)) {
                return returnArr(200, $msg[0]);
            } else {
                return returnArr(0, $msg[1]);
            }
        }
        $pageData = [
            'subtitle' => '安全与工具',
            'title' => '编辑定时任务',
            'controller' => 'Secure',
            'action' => 'secure/scheduledTasksList',
        ];
        if (!$data) return backMsg('记录不存在');
        return $this->adminView('task.scheduledTasksEdit', [
            'pageData' => $pageData,
            'data' => $data,
        ]);
    }

    //删除
    public function scheduledTasksDelete(Request $request) {
        $all = $request->all();
        if ($all['id'] <= 0) return returnArr(0, '参数错误');
        $find = \Modules\System\Services\ServiceModel::apiGetOneTask(['id' => $all['id']]);
        if (!$find) return returnArr(0, '记录不存在');
        if (Task::destroy($find['id'])) {
            return returnArr(200, '删除成功');
        } else {
            return returnArr(0, '删除失败');
        }
    }

    //立即执行
    public function scheduledTasksExecute(Request $request) {
        $all = $request->all();
        if ($all['id'] <= 0) return returnArr(0, '参数错误');
        $find = \Modules\System\Services\ServiceModel::apiGetOneTask(['id' => $all['id']]);
        $api = new RunCronJob();
        return $api->executeTasks($find);
    }

    public function scheduledTasksLog(Request $request) {
        $all = $request->all();
        if ($all['id'] <= 0) return returnArr(0, '参数错误');
        $task = Task::query()->find($all['id']);
        $data = \Modules\Log\Services\ServiceModel::getTaskLogList(['module' => strtolower($task['module']), 'unique_id' => $all['id']]);
        foreach ($data as &$d) {
            $d['context'] = json_decode($d['context'], true);
            $d['context']['result'] = is_array($d['context']['result']) ? json_encode($d['context']['result'], JSON_UNESCAPED_UNICODE) : $d['context']['result'];
        }
        return $this->adminView('task.scheduledTasksLog', [
            'data' => $data,
        ]);
    }
}
