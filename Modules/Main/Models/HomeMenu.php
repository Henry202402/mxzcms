<?php

namespace Modules\Main\Models;

use Illuminate\Database\Eloquent\Model;


class HomeMenu extends Model {
    //设置表名
    const TABLE_NAME = "home_menu";
    const primaryKey = "id";
    protected $table = self::TABLE_NAME;
    protected $primaryKey = self::primaryKey;//主键
    public $timestamps = false;

    public function child() {
        return $this->hasMany(self::class, 'pid', 'id');
    }
}