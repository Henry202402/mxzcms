<?php

namespace Modules\Auth\Services;


use Modules\Auth\Models\Group;
use Modules\Auth\Models\GroupUser;
use Modules\Main\Models\Common;
use Modules\Main\Models\Member;

class ServiceModel {

    public static function apiGetOne($tableName, $w) {
        return Common::query()->from($tableName)->where($w)->first();
    }

    public static function add($tableName,$add) {
        $add['created_at'] = getDay();
        $add['updated_at'] = getDay();
        return Common::query()->from($tableName)->insertGetId($add);
    }

    public static function groupGetOne($w, $nod_id = 0) {
        return Group::query()
            ->where($w)
            ->where(function ($q) use ($nod_id) {
                if ($nod_id > 0) $q->where(Group::primaryKey, '<>', $nod_id);
            })
            ->first();
    }


    public static function groupUser($all) {
        $keyword = trim((string) ($all['keyword'] ?? ''));
        return GroupUser::query()
            ->from(GroupUser::TABLE_NAME . ' as role')
            ->where('role.group_id', $all['group_id'])
            ->leftJoin(Group::TABLE_NAME . ' as group', 'group.group_id', '=', 'role.group_id')
            ->leftJoin(Member::TABLE_NAME . ' as user', 'user.uid', '=', 'role.uid')
            ->when($keyword !== '', function ($query) use ($keyword) {
                $query->where(function ($subQuery) use ($keyword) {
                    $subQuery->where('user.username', 'like', '%' . $keyword . '%')
                        ->orWhere('user.phone', 'like', '%' . $keyword . '%');
                    if (ctype_digit($keyword)) {
                        $subQuery->orWhere('user.uid', (int) $keyword);
                    }
                });
            })
            ->select([
                'role.id',
                'role.uid',
                'role.created_at',
                'group.type',
                'group.group_name',
                'user.username',
                'user.phone',
            ])
            ->paginate(getLen());
    }
}
