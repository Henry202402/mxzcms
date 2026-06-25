<?php

namespace Modules\Formtools\Models;

use Illuminate\Database\Eloquent\Model;

class FormModel extends Model {
    //设置表名
    const TABLE_NAME = 'module_formtools_models';
    const RESERVED_IDENTIFICATIONS = [
        'models',
        'pages',
        'page_categories',
    ];

    protected $table = self::TABLE_NAME;
    protected $primaryKey = 'id';
    public $timestamps = false;

    public static function reservedIdentifications(): array
    {
        return self::RESERVED_IDENTIFICATIONS;
    }

    public static function isReservedIdentification(?string $identification): bool
    {
        return in_array(strtolower(trim((string) $identification)), self::RESERVED_IDENTIFICATIONS, true);
    }

    public static function isReservedTableName(?string $tableName): bool
    {
        $normalized = trim((string) $tableName);
        if ($normalized === '') {
            return false;
        }

        if (str_starts_with($normalized, env('DB_PREFIX', ''))) {
            $normalized = substr($normalized, strlen(env('DB_PREFIX', '')));
        }

        if ($normalized === self::TABLE_NAME) {
            return true;
        }

        if (!str_starts_with($normalized, 'module_formtools_')) {
            return false;
        }

        return self::isReservedIdentification(substr($normalized, strlen('module_formtools_')));
    }

    public function scopeWithoutReserved($query)
    {
        return $query->whereNotIn('identification', self::reservedIdentifications());
    }
}
