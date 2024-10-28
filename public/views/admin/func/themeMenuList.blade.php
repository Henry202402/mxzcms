@include("admin.public.header")
<link rel="stylesheet" href="{{url('assets/layui/css/layui.css')}}">
<body class="horizontal">

@include("admin.public.themeMenuNav")

<div class="row page-header" style="margin-bottom: -15px;">
    <div class="col-lg-6 align-self-center ">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{url("admin/index")}}">{{getTranslateByKey("common_home_page")}}</a></li>
            <li class="breadcrumb-item"><a href="{{url("admin/theme")}}">主题列表</a></li>
            <li class="breadcrumb-item active">菜单管理</li>
        </ol>
    </div>
</div>

<section class="main-content mt-20" style="padding-bottom: 0px">
    <div class="row pb-5">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header card-default">
                    <div class="btn-group float-left">导航菜单</div>
                    <div class="btn-group float-right">
                        <a href="{{url('admin/theme/themeMenuAdd?m='.$_GET['m'])}}" class="btn btn-default btn-sm">
                            <em class="fa fa-plus"></em>
                        </a>
                    </div>
                </div>
                <div class="card-body" style="min-height: calc(100vh - 230px)">
                    <table id="auth-table" class="layui-table" lay-filter="auth-table"></table>
                </div>

            </div>
        </div>
    </div>
    @include('admin.public.footer')
</section>

@include('admin.public.js',['load'=> ["custom"]])
<script src="{{url('assets/layui/layui.js')}}"></script>
<script>
    layui.config({
        base: '../../assets/layui/module/'
    }).extend({
        treetable: 'treetable-lay/treetable'
    }).use(['table', 'treetable'], function () {
        var $ = layui.jquery;
        var treetable = layui.treetable;

        // 渲染表格
        layer.load(2);
        treetable.render({
            treeColIndex: 1,
            treeSpid: 0,
            treeIdName: 'id',
            treePidName: 'pid',
            elem: '#auth-table',
            url: '{{url('admin/theme/themeMenuList')}}',
            cols: [[
                {field: 'id', width: 100, title: 'ID'},
                {field: 'name', minWidth: 200, title: '菜单名称'},
                {field: 'url', title: '菜单URL'},
                {
                    field: 'url', title: '跳转URL', templet: function (d) {
                        if (d.url && d.url != '#' && d.url.indexOf('http')=='-1') {
                            return '{{url('')}}/' + d.url;
                        } else {
                            return d.url;
                        }
                    }
                },
                {
                    field: 'position', width: 120, title: '位置', templet: function (d) {
                        if (d.position == 'top') {
                            return '<span class="btn btn-xs btn-success">头部</span>';
                        }if (d.position == 'bottom') {
                            return '<span class="btn btn-xs btn-primary">底部</span>';
                        }  else {
                            return '<span class="btn btn-xs btn-info">页脚</span>';
                        }
                    }
                },
                {field: 'icon', title: 'icon'},
                {field: 'icon_character', title: 'icon文字'},
                {field: 'sort', title: '排序'},
                {
                    field: 'status', width: 120, title: '状态', templet: function (d) {
                        if (d.status == 1) {
                            return `<span class="btn btn-xs btn-success" onclick="changeStatus(${d.id},2)">启用</span>`;
                        } else {
                            return `<span class="btn btn-xs btn-danger" onclick="changeStatus(${d.id},1)">禁用</span>`;
                        }
                    }
                },
                {
                    width: 250, align: 'center', title: '操作', templet: function (d) {
                        var edit = `<a class="btn btn-xs btn-teal mr-2" href="{{url('admin/theme/themeMenuEdit?m='.$_GET['m'])}}&id=${d.id}">编辑</a>`;
                        var del = `<a class="btn btn-xs btn-danger" href="javascript:;" onclick="delData(${d.id})">删除</a>`;
                        return `${edit}${del}`;
                    }
                }
            ]],
            done: function () {
                layer.closeAll('loading');
            }
        });

        $('#btn-expand').click(function () {
            treetable.expandAll('#auth-table');
        });

        $('#btn-fold').click(function () {
            treetable.foldAll('#auth-table');
        });
    });

    function delData(id) {
        $.confirm({
            title: '{{getTranslateByKey("common_tip")}}',
            content: '{{getTranslateByKey("common_sure_to_delete")}}',
            type: 'default',
            buttons: {
                ok: {
                    text: "{{getTranslateByKey('common_ensure')}}",
                    btnClass: 'btn-primary',
                    keys: ['enter'],
                    action: function () {
                        location.href = "{{url('admin/theme/themeMenuDelete?id=')}}" + id
                    }
                },
                cancel: {
                    text: "{{getTranslateByKey('common_cancel')}}"
                }
            }
        });
    }

    function changeStatus(id, status) {
        var content = status == 1 ? '你确定要启用吗？' : '你确定要禁用吗？';
        $.confirm({
            title: '{{getTranslateByKey("common_tip")}}',
            content: content,
            type: 'default',
            buttons: {
                ok: {
                    text: "{{getTranslateByKey('common_ensure')}}",
                    btnClass: 'btn-primary',
                    keys: ['enter'],
                    action: function () {
                        location.href = "{{url('admin/theme/themeMenuChangeStatus?id=')}}" + id + '&status=' + status;
                    }
                },
                cancel: {
                    text: "{{getTranslateByKey('common_cancel')}}"
                }
            }
        });
    }
</script>
</body>
</html>
