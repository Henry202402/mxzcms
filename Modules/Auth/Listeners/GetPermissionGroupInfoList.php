<?php

namespace Modules\Auth\Listeners;

use Illuminate\Support\Facades\Cache;
use Modules\Auth\Http\Controllers\Common\CacheKey;
use Modules\Auth\Models\Group;
use Modules\Auth\Models\GroupUser;
use Modules\System\Http\Controllers\Common\SessionKey;

class GetPermissionGroupInfoList {

    public function handle(\App\Events\GetPermissionGroupInfoList $event) {
        //事件逻辑 ...
        $pageData = $event->data;
        $group = [];//权限组
        if ($pageData['uid'] > 0) {
            $group = GroupUser::query()->where('uid', $pageData['uid'])->first();
            if (!$group) $group = Group::query()->where('type', 'member')->orderBy(Group::primaryKey)->first();
        }
        //获取缓存权限组信息
        $list = Cache::get(CacheKey::GroupListInfo) ?: [];
        //直接读取缓存返回 type = 1
        if ($pageData['type'] == 2 || !$list || count($list) <= 0) {
            //重新读取权限组缓存返回 type = 2
            $list = [];
            $data = Group::query()->get()->toArray();
            foreach ($data as $d) {
                $list[$d['group_id']]['type'] = $d['type'];
                $list[$d['group_id']]['group_name'] = $d['group_name'];
                $list[$d['group_id']]['role_array'] = json_decode($d['role_json'], true) ?: [];
            }
            Cache::put(CacheKey::GroupListInfo, $list);
        }

        $userInfo = session(SessionKey::AdminInfo);
        if ($group['group_id'] != $userInfo['group_id'] && $pageData['uid'] && $group['group_id']) {
            $roleArray = $list[$group['group_id']];
            session([SessionKey::CurrentUserPermissionGroupInfo => $roleArray]);
            $userInfo['type'] = $roleArray['type'];
            $userInfo['group_id'] = $group['group_id'];
            session([SessionKey::AdminInfo => $userInfo]);
        }
        return ['list' => $list, 'group' => $group, 'currentRoleArray' => $list[$group['group_id']] ?: []];
    }

}
