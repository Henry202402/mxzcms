<?php

namespace Modules\System\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;


class Setting extends Model {
    //设置表名
    const TABLE_NAME = "module_system_settings";
    protected $table = self::TABLE_NAME;
    //protected $primaryKey="";
    public $timestamps = false;

}
