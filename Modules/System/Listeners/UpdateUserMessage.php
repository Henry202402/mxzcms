<?php

namespace Modules\System\Listeners;

use Intervention\Image\Facades\Image;
use Modules\Main\Models\SystemMessage;
use Modules\Main\Services\ServiceModel;
use Modules\System\Http\Controllers\Common\SessionKey;
use Modules\Main\Models\Member;

class UpdateUserMessage {

    public function handle(\Modules\System\Events\UpdateUserMessage $event) {
        //事件逻辑 ...
        $all = $event->data;
        if ($all['uid'] <= 0) return returnArr(0, 'uid错误');
        if (in_array($all['operate_type'], [1, 3])) {
            $ids = is_array($all['ids']) ? $all['ids'] : explode(',', $all['ids']);
            $ids = array_unique(array_filter($ids));
            if (count($ids) <= 0) return returnArr(0, 'id错误');
        }

        switch ($all['operate_type']) {
            case 1://已读
                $res = SystemMessage::query()
                    ->where('receive_uid', $all['uid'])
                    ->where('status', 0)
                    ->whereIn(SystemMessage::primaryKey, $ids)
                    ->update(['status' => 1, 'updated_at' => getDay()]);
                break;
            case 2://全部已读
                $res = SystemMessage::query()
                    ->where('receive_uid', $all['uid'])
                    ->where('status', 0)
                    ->update(['status' => 1, 'updated_at' => getDay()]);
                break;
            case 3://删除
                $res = SystemMessage::query()
                    ->where('receive_uid', $all['uid'])
                    ->whereIn(SystemMessage::primaryKey, $ids)
                    ->delete();
                break;
            case 4://列表
                $res = SystemMessage::query()
                    ->where('receive_uid', $all['uid'])
                    ->latest(SystemMessage::primaryKey);
                if ($all['get_data_type'] == 'array') {
                    $res = $res->get()->toArray();
                } else {
                    $res = $res->simplePaginate(getLen($all));
                }
                break;
            case 5://详情
                $res = SystemMessage::query()
                    ->where('receive_uid', $all['uid'])
                    ->where(SystemMessage::primaryKey, $all['id'])
                    ->first();
                if ($res) {
                    SystemMessage::query()
                        ->where(SystemMessage::primaryKey, $all['id'])
                        ->update(['status' => 1, 'updated_at' => getDay()]);
                }
                break;
            case 6://添加
                if ($if = ifCondition([
                    'title' => '标题不能为空',
                    'content' => '内容不能为空',
                    'uid' => '接收者uid不能为空',
                ], $all)) return $if;
                $add = [
                    'module' => $all['module'] ?: 'Main',
                    'title' => $all['title'],
                    'content' => $all['content'],
                    'uid' => $all['send_uid'] ?: 1,
                    'receive_uid' => $all['uid'],
                    'created_at' => getDay(),
                    'updated_at' => getDay(),
                    'json_str' => is_array($all['json_str']) ? json_encode($all['json_str'], JSON_UNESCAPED_UNICODE) : $all['json_str'],
                ];
                $res = SystemMessage::query()->insertGetId($add);
                break;
            default:
                return returnArr(0, '类型错误');
        }
        if ($res) {
            return returnArr(200, '操作成功', $res);
        } else {
            return returnArr(0, '操作失败');
        }
    }

}