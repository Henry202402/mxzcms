<?php

namespace Modules\Formtools\Models;

use Illuminate\Database\Eloquent\Model;

class FormPageCategory extends Model
{
    const TABLE_NAME = 'module_formtools_page_categories';

    protected $table = self::TABLE_NAME;
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $casts = [
        'sort' => 'integer',
        'status' => 'integer',
    ];
}
