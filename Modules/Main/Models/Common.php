<?php

namespace Modules\Main\Models;

use Illuminate\Database\Eloquent\Model;

class Common extends Model {
    //设置表名
    const TABLE_NAME = '';
    public $table = self::TABLE_NAME;
    public $primaryKey = '';
    public $timestamps = false;
    public $guarded = [];

}
