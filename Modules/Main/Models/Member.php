<?php

namespace Modules\Main\Models;

use Illuminate\Foundation\Auth\User;
use Modules\Member\Models\Wallet;
use Tymon\JWTAuth\Contracts\JWTSubject;

class Member extends User implements JWTSubject {
    //设置表名
    const TABLE_NAME = 'members',
        DEFAULT_PASS = '123456'; //登陆的 后台 SESSION 标识
    protected $table = self::TABLE_NAME;
    protected $primaryKey = 'uid';
    public $timestamps = false;
    protected $guarded = [];


    public function getAvatarAttribute($value) {
        return $value ? GetUrlByPath($value) : null;
    }

    public function wallet() {
        return $this->hasOne(Wallet::class,'uid','uid');
    }





















    /*********************************** 放底部，不能删除 ***********************************/
    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier() {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims() {
        return [];
    }
}
