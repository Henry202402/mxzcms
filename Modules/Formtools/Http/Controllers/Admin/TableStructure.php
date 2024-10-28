<?php

namespace Modules\Formtools\Http\Controllers\Admin;


use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class TableStructure {


    public function createTable($tableName, $data) {
        Schema::create($tableName, function (Blueprint $table) use ($data) {
            $table->engine = 'InnoDB';
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_general_ci';
            $table->comment($data['remark']);
            $table->increments('id')->comment('主键');
            $table->timestamps(); //添加 created_at 和 updated_at列
        });
    }

    public function editTable() {

    }

    public function deleteTable($tableName) {
        if (Schema::hasTable($tableName)) {
            Schema::dropIfExists($tableName);
        }
    }

    public function createColumn($tableName, $data) {
        Schema::table($tableName, function (Blueprint $table) use ($data) {
            if ($data['fieldtype'] == "text") {
                $table = $table->text($data['identification']);
            } elseif ($data['fieldtype'] == "string") {
                $table = $table->string($data['identification'], $data['maxlength']);
            } elseif ($data['fieldtype'] == "integer") {
                $table = $table->integer($data['identification']);  //整型        $table->integer('votes');
            } elseif ($data['fieldtype'] == "mediumInteger") {
                $table = $table->mediumInteger($data['identification']);  //中整型        $table->mediumInteger('numbers');
            } elseif ($data['fieldtype'] == "bigInteger") {
                $table = $table->bigInteger($data['identification']);  //大整型        $table->bigInteger('numbers');
            } elseif ($data['fieldtype'] == "boolean") {
                $table = $table->boolean($data['identification']);  //布尔型        $table->boolean('confirmed');
            } elseif ($data['fieldtype'] == "decimal") {
                $table = $table->decimal($data['identification'], $data['maxlength'], 2);  //十进制        $table->decimal('amount', 5, 2);
            } elseif ($data['fieldtype'] == "double") {
                $table = $table->double($data['identification'], $data['maxlength'], 2);  //双精度浮点数        $table->double('column', 15, 8);
            } elseif ($data['fieldtype'] == "float") {
                $table = $table->float($data['identification'], $data['maxlength'], 2);  //浮点数        $table->float('amount');
            } elseif ($data['fieldtype'] == "json") {
                $table = $table->json($data['identification']);  //JSON        $table->json('options');
            } elseif ($data['fieldtype'] == "jsonb") {
                $table = $table->jsonb($data['identification']);  //JSONB        $table->jsonb('options');
            } elseif ($data['fieldtype'] == "longText") {
                $table = $table->longText($data['identification']);  //长文本        $table->longText('description');
            } elseif ($data['fieldtype'] == "mediumText") {
                $table = $table->mediumText($data['identification']);  //中等长度文本        $table->mediumText('description');
            } elseif ($data['fieldtype'] == "smallInteger") {
                $table = $table->smallInteger($data['identification']);  //小整型        $table->smallInteger('numbers');
            } elseif ($data['fieldtype'] == "tinyInteger") {
                $table = $table->tinyInteger($data['identification']);  //小整型        $table->tinyInteger('numbers');
            } elseif ($data['fieldtype'] == "unsignedBigInteger") {
                $table = $table->unsignedBigInteger($data['identification']);  //无符号大整型        $table->unsignedBigInteger('votes');
            } elseif ($data['fieldtype'] == "unsignedInteger") {
                $table = $table->unsignedInteger($data['identification']);  //无符号整型        $table->unsignedInteger('votes');
            } elseif ($data['fieldtype'] == "unsignedMediumInteger") {
                $table = $table->unsignedMediumInteger($data['identification']);  //无符号中整型        $table->unsignedMediumInteger('votes');
            } elseif ($data['fieldtype'] == "unsignedSmallInteger") {
                $table = $table->unsignedSmallInteger($data['identification']);  //无符号小整型        $table->unsignedSmallInteger('votes');
            } elseif ($data['fieldtype'] == "unsignedTinyInteger") {
                $table = $table->unsignedTinyInteger($data['identification']);  //无符号微整型        $table->unsignedTinyInteger('votes');
            } elseif ($data['fieldtype'] == "uuid") {
                $table = $table->uuid($data['identification']);  //UUID        $
            } elseif ($data['fieldtype'] == "enum") {
                $table = $table->enum($data['identification'],[]);  //枚举        $table->enum('level', ['easy', 'hard']);
            } elseif ($data['fieldtype'] == "date") {
                $table = $table->date($data['identification']);  //日期        $table->date('created_at');
            } elseif ($data['fieldtype'] == "dateTime") {
                $table = $table->dateTime($data['identification']);  //日期时间        $table->dateTime('created_at', 0);
            } elseif ($data['fieldtype'] == "dateTimeTz") {
                $table = $table->dateTimeTz($data['identification']);  //带时区的日期时间        $table->dateTimeTz('created_at', 0);
            } elseif ($data['fieldtype'] == "time") {
                $table = $table->time($data['identification']);  //时间        $table->time('sunrise', 0
            } elseif ($data['fieldtype'] == "timeTz") {
                $table = $table->timeTz($data['identification']);  //带时区的时间        $table->timeTz('sunrise', 0);
            } elseif ($data['fieldtype'] == "timestamp") {
                $table = $table->timestamp($data['identification']);  //时间戳        $table->timestamp('added_on');
            } elseif ($data['fieldtype'] == "timestampTz") {
                $table = $table->timestampTz($data['identification']);  //带时区的时间戳        $table->timestampTz('added_on');
            } elseif ($data['fieldtype'] == "year") {
                $table = $table->year($data['identification']);  //年份        $table->year('birth_year');
            } elseif ($data['fieldtype'] == "binary") {
                $table = $table->binary($data['identification']);  //二进制        $table->binary('data');
            } elseif ($data['fieldtype'] == "char") {
                $table = $table->char($data['identification'], $data['maxlength']);//字符型        $table->char('name', 4);
            } elseif ($data['fieldtype'] == "ipAddress") {
                $table = $table->ipAddress($data['identification']);//IP地址        $table->ipAddress('visitor');
            } elseif ($data['fieldtype'] == "macAddress") {
                $table = $table->macAddress($data['identification']);//MAC地址        $table->macAddress('device');
            }

            $table->comment($data['remark'])
                ->nullable()
                ->after($data['after_field'] ?: 'id');
        });
    }

    public function getColumns($tableName) {
        $columns = Schema::getColumnListing($tableName);
        return $columns;
    }

    public function editColumn($tableName, $oldData, $updateData2) {
        //修改字段
        if ($oldData['maxlength'] != $updateData2['maxlength'] || $oldData['fieldtype'] != $updateData2['fieldtype'] || $oldData['remark'] != $updateData2['remark']) {
            $sql = 'ALTER TABLE ' . env('DB_PREFIX') . $tableName . ' MODIFY COLUMN ' .
                $updateData2['identification'] . ' ' .
                $this->changeTomysql($updateData2['fieldtype']) .
                '(' . $updateData2['maxlength'] . ') COMMENT "' . $updateData2['remark'] . '"';
            DB::statement($sql);
        }
    }

    public function deleteColumn($tableName, $identification) {
        Schema::table($tableName, function (Blueprint $table) use ($identification) {
            $table->dropColumn($identification);
        });
    }

    public function moveColumn($tableName, $data) {
        $field = $data['identification'];
        $after = $data['after_field'];
        $comment = $data['remark'] ?: NULL;

        $prefix = env('DB_PREFIX');
        $type = '';
        foreach (DB::select("show columns from {$prefix}{$tableName}") as $val) {
            if ($val->Field == $field) {
                $type = $val->Type;
                break;
            }
        }
        $sql = "ALTER TABLE `{$prefix}{$tableName}` CHANGE `{$field}` `{$field}` {$type} NULL DEFAULT NULL COMMENT '{$comment}' AFTER `{$after}`";
        DB::statement($sql);
    }

    public function createIndex($tableName, $updateData2) {
        $indextype = [
            'INDEX' => '',
            'UNIQUE' => 'UNIQUE',
            'FULLTEXT' => 'FULLTEXT'
        ];
        $sql = 'ALTER TABLE ' . env('DB_PREFIX') . $tableName .
            ' ADD ' . $indextype[$updateData2['isindex']] .
            ' INDEX ' . $updateData2['identification'] .
            ' (' . $updateData2['identification'] . ')';
        DB::statement($sql);
    }

    public function editIndex($tableName, $oldData, $updateData2) {
        //检查索引
        $sm = Schema::getConnection()->getDoctrineSchemaManager();
        $indexesFound = $sm->listTableIndexes(env('db_prefix') . $tableName);
        $indextype = [
            "NOINDEX" => " INDEX ",
            'INDEX' => ' INDEX ',
            'UNIQUE' => ' UNIQUE ',
            'FULLTEXT' => ' FULLTEXT '
        ];
        //修改索引
        if ($oldData['isindex'] != $updateData2['isindex']) {
            if ($updateData2['isindex'] != 'NOINDEX') {
                if ($indexesFound[$updateData2['identification']]) {
                    $sql = 'ALTER TABLE ' . env('DB_PREFIX') . $tableName . ' DROP ' . $indextype['INDEX'] . ' ' . $updateData2['identification'];
                    DB::statement($sql);
                }
                $sql = 'ALTER TABLE ' . env('DB_PREFIX') . $tableName . ' ADD ' . $indextype[$updateData2['isindex']] . ' ' . $updateData2['identification'] . ' (' . $updateData2['identification'] . ')';
                DB::statement($sql);
            } elseif ($updateData2['isindex'] == 'NOINDEX') {
                if ($indexesFound[$updateData2['identification']]) {
                    $sql = 'ALTER TABLE ' . env('DB_PREFIX') . $tableName . ' DROP ' . $indextype['INDEX'] . ' ' . $updateData2['identification'];
                    DB::statement($sql);
                }
            }
        } elseif ($updateData2['isindex'] == "NOINDEX") {
            if ($indexesFound[$updateData2['identification']]) {
                $sql = 'ALTER TABLE ' . env('DB_PREFIX') . $tableName . ' DROP ' . $indextype['INDEX'] . ' ' . $updateData2['identification'];
                DB::statement($sql);
            }
        }
    }

    public function deleteIndex() {

    }

    public function createForeignKey() {

    }

    public function editForeignKey() {

    }

    public function deleteForeignKey() {

    }

    public function createTrigger() {

    }

    public function editTrigger() {

    }

    public function deleteTrigger() {

    }

    public function createView() {

    }

    public function editView() {

    }

    public function deleteView() {

    }

    public function createProcedure() {

    }

    public function editProcedure() {

    }

    public function deleteProcedure() {

    }

    public function createFunction() {

    }

    public function editFunction() {

    }

    public function deleteFunction() {

    }

    public function createEvent() {

    }

    public function editEvent() {

    }

    public function deleteEvent() {

    }

    public function createTablespace() {

    }

    public function editTablespace() {

    }

    public function deleteTablespace() {

    }

    public function createPartition() {

    }

    public function editPartition() {

    }

    public function deletePartition() {

    }

    public function createSequence() {

    }

    public function editSequence() {

    }

    public function deleteSequence() {

    }

    public function changeTomysql($string) {
        if (in_array($string, ["string", "Integer", "tinyInteger"])) {
            return [
                "string" => "varchar",
                "Integer" => "int",
                "tinyInteger" => "tinyint"
            ][$string];
        }
        return $string;
    }


}
