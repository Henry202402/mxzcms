<?php

namespace Modules\Formtools\Helper;


trait FormFunc {

    //表单类型
    public static function formtype() {
        return [
            'text' => '单行文本',
            'textarea' => '多行文本',
            'editor' => '编辑器',
            'password' => '密码输入',
            'radio' => '单项选框',
            'checkbox' => '复项选框',
            'select' => '下拉列表',
            'upload' => '上传文件【同步】',
            'uploadAjax' => '上传文件【异步】',
            'image' => '上传图片【同步】',
            'imageAjax' => '上传图片【异步】',
            'date' => '日期选择',
            'time' => '时间选择',
            'datetime' => '日期时间',
            'hidden' => '隐藏域',
            'readonly' => '只读文本',
            'disabled' => '禁用文本',
        ];

    }

    //字段类型
    public static function fieldtype() {
        return [
            'string' => 'varchar(0-65525)',
            'char' => 'char(0-225)',
            'integer' => 'int(11)',
            'tinyInteger' => 'tinyint(4)',
            'text' => 'text(unlimit)',
            'tinyText' => 'tinytext(225)',
            'mediumText' => 'mediumtext(16777215)',
            'longText' => 'longtext(4294967295)',
            'float' => 'float(8,2)',
            'double' => 'double(16,2)',
            'decimal' => 'decimal(10,2)',
            'boolean' => 'boolean(0-1)',
            'enum' => 'enum(0-255)',
            'json' => 'json(unlimit)',
            'jsonb' => 'jsonb(unlimit)',
            'year' => 'year(YYYY)',
            'date' => 'date(YYYY-MM-DD)',
            'dateTime' => 'dateTime(YYYY-MM-DD HH:MM:SS)',
            'timestamp' => 'timestamp(YYYY-MM-DD HH:MM:SS)',
            'time' => 'time(HH:MM:SS)',
            'binary' => 'binary(0-255)',
            'varbinary' => 'varbinary(0-65535)',
            'bit' => 'bit(0-64)',
            'ipAddress' => 'ipAddress(0-255)',
            'macAddress' => 'macAddress(0-255)',
            'blob' => 'blob(unlimit)',
            'tinyBlob' => 'tinyblob(255)',
            'mediumBlob' => 'mediumblob(16777215)',
            'longBlob' => 'longblob(4294967295)',
        ];
    }

    //是否是索引
    public static function isindex() {
        return [
            'NOINDEX' => '不设索引',
            'INDEX' => '普通索引',
            'UNIQUE' => '唯一索引',
            'FULLTEXT' => '全文索引',
        ];
    }

    //字段规则
    public static function rule() {
        return [
            'unlimited' => '不限制',
            'string' => '字符类型（不限制输入）',
            'unique' => '唯一型（不可重复，比对数据库）',
            'email' => '邮箱类型',
            'phone' => '手机号码',
            'number' => '数字类型',
            'date' => '日期类型',
            'time' => '时间类型',
            'scope' => '范围类型（时间范围，前后不能矛盾）',
            'file' => '文件类型（文件上传，PDF、MP3、MP4、word文档、txt）',
            'image' => '图片类型（图片上传，png、jpeg、gif、ico）',
            'url' => '链接类型',
            'ip' => 'IP类型',
            'idcard' => '身份证类型',
            'zip' => '邮编类型',
            'regex' => '正则表达式',
        ];
    }

    //列表模板
    public static function listTemplate() {
        return [
            'list' => '纯文字列表模板',
            'titleContentImage' => '图文列表模板（标题+内容+图片）',
            'titleImage' => '图文列表模板（标题+图片）',
            'contacts' => '联系我们模板（支持QQ、邮箱、手机和二维码）',
            'about' => '关于我们模板（富文本显示）',
            'milestone' => '发展历程模板',
            'feedback' => '在线留言模板（在线表单提交）',
            'carousel' => "轮播图（图片+url）",
            'photoList' => '相册模板，点击轮播（标题+图片）',
            'videoList' => '视频列表模板（标题+视频封面+点击播放）',
        ];
    }

    //详情模板
    public static function detailTemplate() {
        return [
            'detail' => '单详情模板',
            'detailLeftList' => '详情+左侧栏模板',
            'detailRightList' => '详情+右侧栏模板',
            'detailBottomList' => '详情+底部栏模板',

        ];
    }
}
