<?php

namespace Modules\Main\Models;

use Illuminate\Database\Eloquent\Model;
use Modules\System\Models\ModuleBindDomain;

class Modules extends Model {
    //设置表名
    const TABLE_NAME = "modules";
    const Module = "module";
    const Plugin = "plugin";
    const Theme = "theme";
    const CLOUD_TYPE = [
        self::Module => '功能模块',
        self::Plugin => '插件',
        self::Theme => '主题',
    ];
    protected $table = self::TABLE_NAME;
    protected $primaryKey = "id";
    public $timestamps = false;

    public function domain() {
        return $this->hasOne(ModuleBindDomain::class,'module','identification');
    }
}
