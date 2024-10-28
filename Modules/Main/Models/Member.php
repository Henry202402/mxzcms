<?php

namespace Modules\Main\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class Member extends Model {
    //设置表名
    const TABLE_NAME = 'members',
        DEFAULT_PASS = '123456'; //登陆的 后台 SESSION 标识
    protected $table = self::TABLE_NAME;
    protected $primaryKey = 'uid';
    public $timestamps = false;
    protected $guarded = [];

}
