<?php

namespace Modules\System\Http\Controllers\Common;

use Mxzcms\Modules\session\SessionKey as SessionKeyParent;

class SessionKey extends SessionKeyParent {
    const CurrentUserPermissionGroupInfo = 'CurrentPermissionGroupInfo';
    const NoAuthNum = 'NoAuthNum';

    public static function getAllSession() {
        $list = [
            self::CurrentUserPermissionGroupInfo => '后台储存用户权限组信息',
            self::NoAuthNum => '后台储存用户访问没有权限的次数',
        ];
        return array_merge(self::allSession, $list);
    }
}
