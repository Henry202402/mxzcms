<?php

namespace Modules\Member\Models;

use Illuminate\Database\Eloquent\Model;

class BaseConfiguration extends Model {
    //设置表名
    const TABLE_NAME = "module_member_base_configuration";
    public $table = self::TABLE_NAME;
    public $primaryKey = "id";
    public $timestamps = false;

    public static function add($w) {
        $w['updated_at'] = getDay();
        return self::query()->insertGetId($w);
    }

    public static function getOne($w) {
        return self::query()->where($w)->first();
    }

    public static function whereUpdate($w, $up) {
        $up['updated_at'] = getDay();
        return self::query()->where($w)->update($up);
    }
}