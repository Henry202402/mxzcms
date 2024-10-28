<?php

namespace Modules\Auth\Models;

use Illuminate\Database\Eloquent\Model;


class Group extends Model {
    //设置表名
    const TABLE_NAME = "module_auth_group";
    const primaryKey = "group_id";
    protected $table = self::TABLE_NAME;
    protected $primaryKey = self::primaryKey;//主键
    public $timestamps = false;

}