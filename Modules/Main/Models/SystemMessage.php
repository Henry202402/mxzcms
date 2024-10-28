<?php

namespace Modules\Main\Models;

use Illuminate\Database\Eloquent\Model;


class SystemMessage extends Model {
    //设置表名
    const TABLE_NAME = "system_message";
    const primaryKey = "id";
    protected $table = self::TABLE_NAME;
    protected $primaryKey = self::primaryKey;//主键
    public $timestamps = false;

}