<?php

namespace Modules\System\Services;


use Illuminate\Support\Facades\DB;
use Modules\Auth\Models\Group;
use Modules\Auth\Models\GroupUser;
use Modules\Main\Models\Common;
use Modules\Main\Models\Member;
use Modules\Main\Models\Modules;
use Modules\System\Models\Attachments;
use Modules\System\Models\ModuleBindDomain;
use Modules\System\Models\Setting;
use Modules\System\Models\Task;

class ServiceModel {
    /********************************* Attachments ************************************/
    function InsertArr($arr) {
        if (!is_array($arr)) return false;

        //查找
        $obj = Attachments::query()->where("path_md5", "=", md5($arr["path"]))->first();

        if ($obj) {
            $data = [
                'path' => $arr["path"],
                'path_md5' => md5($arr["path"]),
                'drive' => $arr["drive"],
                'update_at' => date("Y-m-d H:i:s")
            ];
            return Attachments::query()->where('path_md5', md5($arr["path"]))->update($data);
        } else {
            //新增
            $data = [
                'path' => $arr["path"],
                'path_md5' => md5($arr["path"]),
                'drive' => $arr["drive"],
                'update_at' => date("Y-m-d H:i:s"),
                'create_at' => date("Y-m-d H:i:s")
            ];
            return Attachments::query()->insertGetId($data);
        }
    }

    //获取所有
    static function getByPath($path) {

        $data = Attachments::query()
            ->where("path_md5", "=", md5($path))
            ->orwhere("path", "=", $path)
            ->first();
        if (!$data) {
            return null;
        }

        return $data->toArray();
    }

    //删除
    function deleteByPathMD5($path) {
        return Attachments::query()->where("path_md5", "=", md5($path))->delete();
    }

    /********************************* Setting ************************************/
    //批量更新
    public function updateBatch($multipleData = []) {

        $tableName = DB::getTablePrefix() . Setting::TABLE_NAME; // 表名

        $firstRow = current($multipleData);

        $updateColumn = array_keys($firstRow);
        // 默认以key为条件更新，如果没有ID则以第一个字段为条件
        $referenceColumn = isset($firstRow['key']) ? 'key' : current($updateColumn);
        unset($updateColumn[0]);
        // 拼接sql语句
        $updateSql = "UPDATE " . $tableName . " SET ";
        $sets = [];
        $bindings = [];
        foreach ($updateColumn as $uColumn) {
            $setSql = "`" . $uColumn . "` = CASE ";
            foreach ($multipleData as $data) {
                $setSql .= "WHEN `" . $referenceColumn . "` = ? THEN ? ";
                $bindings[] = $data[$referenceColumn];
                $bindings[] = $data[$uColumn];
            }
            $setSql .= "ELSE `" . $uColumn . "` END ";
            $sets[] = $setSql;
        }
        $updateSql .= implode(', ', $sets);
        $whereIn = collect($multipleData)->pluck($referenceColumn)->values()->all();
        $bindings = array_merge($bindings, $whereIn);
        $whereIn = rtrim(str_repeat('?,', count($whereIn)), ',');
        $updateSql = rtrim($updateSql, ", ") . " WHERE `" . $referenceColumn . "` IN (" . $whereIn . ")";
        // 传入预处理sql语句和对应绑定数据

        return DB::update($updateSql, $bindings);
    }


    /********************************* Setting ************************************/


    /********************************* Task ************************************/
    public static function task_type() {
        return [
            1 => '访问URL',
            2 => 'Shell脚本',
        ];
    }

    public static function type() {
        return [
            1 => '每天',
            2 => 'N天',
            3 => '每小时',
            4 => 'N小时',
            5 => 'N分钟',
            6 => '每星期',
            7 => '每月',
        ];
    }

    public static function type_msg($day, $hour, $min) {
        $week = \Modules\System\Services\ServiceModel::taskWeek()[$day];
        return [
            1 => "每天, {$hour}点{$min}分 执行",
            2 => "每隔{$day}天, {$hour}点{$min}分 执行",
            3 => "每小时, 第{$min}分钟 执行",
            4 => "每隔{$hour}小时, 第{$min}分钟 执行",
            5 => "每隔{$min}分钟执行",
            6 => "每{$week}, {$hour}点{$min}分执行",
            7 => "每月, {$day}号 {$hour}点{$min}分执行",
        ];
    }

    public static function taskWeek() {
        return [
            0 => '周日',
            1 => '周一',
            2 => '周二',
            3 => '周三',
            4 => '周四',
            5 => '周五',
            6 => '周六',
        ];
    }

    public static function taskStatus() {
        return [
            1 => '正常',
            2 => '停用',
        ];
    }

    public static function apiGetOneTask($w) {
        return Task::query()->where($w)->first();
    }

    public static function addTask($add) {
        $add['created_at'] = getDay();
        $add['updated_at'] = getDay();
        return Task::query()->insertGetId($add);
    }

    public static function whereUpdateTask($w, $add) {
        $add['updated_at'] = getDay();
        return Task::query()->where($w)->update($add);
    }

    public static function scheduledTasksList() {
        return Task::query()->latest()->get()->toArray();
    }

    public static function getTaskList() {
        return Task::query()
            ->where('status', 1)
            ->get()
            ->toArray();
    }


    /********************************* ModuleBindDomain ************************************/
    public static function apiGetOne($tableName, $w) {
        return Common::query()->from($tableName)->where($w)->first();
    }

    public static function add($tableName, $add) {
        return Common::query()->from($tableName)->insertGetId($add);
    }

    public static function whereUpdate($tableName, $w, $up) {
        return Common::query()->from($tableName)->where($w)->update($up);
    }

    public static function apiGetModule($w) {
        return ModuleBindDomain::query()
            ->where($w)
            ->with('module_data')
            ->first();
    }

    /********************************* ModuleBindDomain ************************************/
    //获取所有模块
    public static function getModuleList($w=[]) {
        return Modules::query()
            ->where($w)
            ->where('cloud_type', Modules::Module)
            ->with('domain')
            ->get()
            ->toArray();
    }

    //获取所有启用的记录
    public static function getDataBystatu($status=1){
        return Modules::query()
//            ->where('status', $status)
            ->orderBy('created_at','desc')
            ->orderBy('type','desc')
            ->get()
            ->toArray();
    }
}
