<?php

namespace Modules\Formtools\Models;

use Illuminate\Database\Eloquent\Model;

class FormModel extends Model {
    //设置表名
    const TABLE_NAME = 'module_formtools_models';
    protected $table = self::TABLE_NAME;
    protected $primaryKey = 'id';
    public $timestamps = false;

}
