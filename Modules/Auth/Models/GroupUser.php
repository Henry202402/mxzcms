<?php

namespace Modules\Auth\Models;

use Illuminate\Database\Eloquent\Model;


class GroupUser extends Model {
    //设置表名
    const TABLE_NAME = "module_auth_group_user";
    const primaryKey = "id";
    protected $table = self::TABLE_NAME;
    protected $primaryKey = self::primaryKey;//主键
    public $timestamps = false;

}