<?php

namespace Modules\System\Models;

use Illuminate\Database\Eloquent\Model;


class Task extends Model {
    //设置表名
    const TABLE_NAME = "module_system_scheduled_tasks";
    protected $table = self::TABLE_NAME;
    protected $primaryKey = "id";
    public $timestamps = false;

}
