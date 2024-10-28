<?php

namespace Modules\System\Models;

use Illuminate\Database\Eloquent\Model;

class Attachments extends Model {
    //设置表名
    const TABLE_NAME = "module_system_attachments";
    protected $table = self::TABLE_NAME;
    //protected $primaryKey = "id";
    public $timestamps = false;


}
