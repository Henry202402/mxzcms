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
            'data' => [],
            'noAddList' => [],
            'moduleList' => [],
        ];
        $existingTasks = \Modules\System\Services\ServiceModel::scheduledTasksList();
        $res = hook('GetCronJob', []);
        if (!is_array($res)) {
            $res = [];
        }

        $route = getURIByRoute($this->request);
        $moduleName = $route['moduleName'] ?? '';
        $pageData['data'] = is_array($existingTasks) ? $existingTasks : [];

        $addModuleList = [];
        foreach ($pageData['data'] as $d) {
            if (!empty($d['module_class']) && !empty($d['module_class_method'])) {
                $addModuleList[] = "{$d['module_class']}@{$d['module_class_method']}";
            }
        }

        $noAddList = [];
        $noAddMap = [];
        foreach ($res as $key => $m) {
            if (!is_array($m) || empty($m[0]) || empty($m[1]) || !is_array($m[1])) {
                continue;
            }
            foreach ($m[1] as $mv) {
                if (!is_array($mv)) {
                    $mv = explode('@', $mv);
                }
                if (empty($mv[0]) || empty($mv[1])) {
                    continue;
                }
                $str = "{$mv[0]}@{$mv[1]}";
                $uniqueKey = strtolower($m[0]) . '@' . $str;
                if (!in_array($str, $addModuleList) && !isset($noAddMap[$uniqueKey])) {
                    $remark = urlencode($mv[2] ?? '');
                    $base = base64_encode("{$m[0]}@{$mv[0]}@{$mv[1]}@{$remark}");
                    $noAddList[] = [strtolower($m[0]), $mv[0], $mv[1], $mv[2], $base];
                    $noAddMap[$uniqueKey] = true;
                }
            }
        }

        $pageData['noAddList'] = $noAddList;
        $moduleInfo = Cache::get(\Mxzcms\Modules\cache\CacheKey::ModulesActive);
        if (is_array($moduleInfo)) {
            foreach ($moduleInfo as $m) {
                if (!is_array($m) || empty($m['identification'])) {
                    continue;
                }
                $pageData['moduleList'][strtolower($m['identification'])] = $m['name'] ?? $m['identification'];
            }
        }

        $moduleFilterOptions = [];
        foreach ($pageData['moduleList'] as $moduleKey => $moduleLabel) {
            $moduleFilterOptions[] = [
                'value' => $moduleKey,
                'label' => $moduleLabel,
            ];
        }

        usort($moduleFilterOptions, function ($a, $b) {
            return strcmp($a['label'], $b['label']);
        });

        $statusMap = \Modules\System\Services\ServiceModel::taskStatus();
        $filters = [
            'module' => strtolower(trim((string) $request->input('module', ''))),
            'status' => trim((string) $request->input('status', '')),
            'keyword' => trim((string) $request->input('keyword', '')),
        ];

        $taskList = [];
        foreach ($pageData['data'] as $d) {
            $cycleOptions = \Modules\System\Services\ServiceModel::type_msg($d['day'], $d['hour'], $d['minute']);
            $taskList[] = [
                'id' => $d['id'],
                'module' => $d['module'],
                'module_label' => $pageData['moduleList'][$d['module']] ?? strtoupper($d['module']),
                'name' => $d['name'],
                'status' => (int) $d['status'],
                'status_label' => $statusMap[$d['status']] ?? '未知',
                'cycle_label' => $cycleOptions[$d['type']] ?? '未配置',
                'last_execution_time' => $d['last_execution_time'],
                'last_execution_display' => !empty($d['last_execution_time']) && $d['last_execution_time'] !== '0000-00-00 00:00:00' ? $d['last_execution_time'] : '未执行',
                'remark' => $d['remark'] ?: '暂无备注',
                'target' => !empty($d['module_class']) && !empty($d['module_class_method']) ? "{$d['module_class']}@{$d['module_class_method']}" : '-',
                'raw' => $d,
            ];
        }

        $filteredTaskList = array_values(array_filter($taskList, function ($task) use ($filters) {
            if ($filters['module'] !== '' && strtolower((string) $task['module']) !== $filters['module']) {
                return false;
            }
            if ($filters['status'] !== '' && (string) $task['status'] !== $filters['status']) {
                return false;
            }
            if ($filters['keyword'] !== '') {
                $searchText = implode(' ', [
                    $task['name'],
                    $task['remark'],
                    $task['target'],
                    $task['module_label'],
                    $task['cycle_label'],
                ]);
                if (stripos($searchText, $filters['keyword']) === false) {
                    return false;
                }
            }
            return true;
        }));

        $pendingTaskList = [];
        foreach ($pageData['noAddList'] as $d) {
            $pendingTaskList[] = [
                'module' => $d[0],
                'module_label' => $pageData['moduleList'][$d[0]] ?? strtoupper($d[0]),
                'target' => $d[1] . '@' . $d[2],
                'remark' => $d[3] ?: '暂无说明',
                'info' => $d[4],
            ];
        }

        $filteredPendingTaskList = array_values(array_filter($pendingTaskList, function ($task) use ($filters) {
            if ($filters['module'] !== '' && strtolower((string) $task['module']) !== $filters['module']) {
                return false;
            }
            if ($filters['keyword'] !== '') {
                $searchText = implode(' ', [
                    $task['module_label'],
                    $task['target'],
                    $task['remark'],
                ]);
                if (stripos($searchText, $filters['keyword']) === false) {
                    return false;
                }
            }
            return true;
        }));

        $taskOverview = [
            [
                'name' => '已添加任务',
                'value' => count($taskList),
                'desc' => '当前模块已经接入数据库管理的定时任务数量',
            ],
            [
                'name' => '启用中',
                'value' => count(array_filter($taskList, function ($task) {
                    return $task['status'] === 1;
                })),
                'desc' => '状态为正常的任务会按计划周期参与执行',
            ],
            [
                'name' => '未执行过',
                'value' => count(array_filter($taskList, function ($task) {
                    return $task['last_execution_display'] === '未执行';
                })),
                'desc' => '用于快速发现新建但尚未跑过的任务',
            ],
            [
                'name' => '待添加任务',
                'value' => count($pendingTaskList),
                'desc' => 'Hook 已提供但尚未加入管理列表的方法数量',
            ],
        ];

        return $this->adminView('task.scheduledTasksList', [
            'pageData' => $pageData,
            'taskOverview' => $taskOverview,
            'taskFilters' => $filters,
            'taskModuleOptions' => $moduleFilterOptions,
            'taskList' => $filteredTaskList,
            'pendingTaskList' => $filteredPendingTaskList,
            'taskStatusMap' => $statusMap,
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
