<?php

namespace Modules\System\Models;

use Illuminate\Database\Eloquent\Model;
use Modules\Main\Models\Modules;

class ModuleBindDomain extends Model {

    const TABLE_NAME = "module_system_domain_bind_module";
    public $table = self::TABLE_NAME;
    public $primaryKey = "id";
    public $timestamps = false;

    public function module_data() {
        return $this->hasOne(Modules::class, 'id', 'module_id');
    }
}