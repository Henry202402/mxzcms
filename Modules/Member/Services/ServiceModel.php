<?php

namespace Modules\Member\Services;

use Illuminate\Support\Facades\DB;
use Modules\Auth\Models\Group;
use Modules\Auth\Models\GroupUser;
use Modules\Main\Models\Common;
use Modules\Main\Models\Member;
use Modules\Member\Models\Auth;
use Modules\Member\Models\AuthRecord;

class ServiceModel {
    /********************************* Member ************************************/
    //用户列表
    public static function getAdminUserList($all, $type = []) {
        return Member::query()
            ->from(Member::TABLE_NAME . ' as user')
            ->where(function ($q) use ($all) {
                if ($all['username']) $q->where('user.username', "LIKE", "{$all['username']}%")
                    ->orWhere('user.phone', "LIKE", "{$all['username']}%")
                    ->orWhere('user.nickname', "LIKE", "{$all['username']}%");
            })
            ->where(function ($q) use ($all) {
                if (isset($all['status'])) $q->where('user.status', $all['status']);
                if ($all['uid'] > 0) $q->where('user.uid', $all['uid']);
                if ($all['timeRang'] && count($all['timeRang']) > 0) $q->whereBetween('user.created_at', $all['timeRang']);
            })
            ->where(function ($q) use ($all) {
//                $q->where('group.type', null)->orWhere('group.type', '<>', 'admin');
            })
            ->leftJoin(Member::TABLE_NAME . ' as puser', 'puser.uid', '=', 'user.pid')
            ->leftJoin(GroupUser::TABLE_NAME . ' as role', function ($q) use ($all) {
                $q->on('user.uid', '=', 'role.uid');
            })
            ->leftJoin(Group::TABLE_NAME . ' as group', 'group.group_id', '=', 'role.group_id')
            ->orderByDesc('user.created_at')
            ->select([
                'user.*',
                'puser.username as pid_name',
                'group.group_id',
                'group.type',
                'group.group_name',
            ])
            ->paginate(__E('admin_page_count'));
    }


    public static function getUserAuthList($all = []) {
        return Auth::query()
            ->where(function ($q) use ($all) {
                if ($all['uid']) $q->where('uid', $all['uid']);
                if ($all['real_name']) $q->where('real_name', 'LIKE', $all['real_name'] . '%');
                if ($all['company_name']) $q->where('company_name', 'LIKE', $all['company_name'] . '%');
                if ($all['id_card']) $q->where('id_card', 'LIKE', $all['id_card'] . '%');
                if ($all['credit_code']) $q->where('unified_social_credit_code', 'LIKE', $all['credit_code'] . '%');
                if (isset($all['status'])) $q->where('status', $all['status']);
                if ($all['rang_arr']) $q->whereBetween('created_at', $all['rang_arr']);
            })
            ->with(['user_data'])
            ->latest()
            ->paginate(getLen($all));
    }

    public static function getUserAuthRecordList($all = []) {
        return AuthRecord::query()
            ->where(function ($q) use ($all) {
                if ($all['uid']) $q->where('uid', $all['uid']);
                if ($all['real_name']) $q->where('real_name', 'LIKE', $all['real_name'] . '%');
                if ($all['company_name']) $q->where('company_name', 'LIKE', $all['company_name'] . '%');
                if ($all['id_card']) $q->where('id_card', 'LIKE', $all['id_card'] . '%');
                if ($all['credit_code']) $q->where('unified_social_credit_code', 'LIKE', $all['credit_code'] . '%');
                if (isset($all['status'])) $q->where('status', $all['status']);
                if ($all['type']) $q->where('type', $all['type']);
                if ($all['rang_arr']) $q->whereBetween('created_at', $all['rang_arr']);
            })
            ->with(['user_data'])
            ->latest()
            ->paginate(getLen($all));
    }
}