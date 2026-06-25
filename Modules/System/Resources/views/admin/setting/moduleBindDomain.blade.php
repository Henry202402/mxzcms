@include(moduleAdminTemplate($moduleName)."public.header")
<style>
    .domain-bind-hero {
        margin-bottom: 20px;
        padding: 22px 24px;
        border: 1px solid #e5e7eb;
        border-radius: 16px;
        background: linear-gradient(180deg, #ffffff 0%, #f8fafc 100%);
        box-shadow: 0 16px 40px rgba(15, 23, 42, 0.06);
    }

    .domain-bind-hero__title {
        margin: 0;
        font-size: 22px;
        font-weight: 700;
        color: #0f172a;
    }

    .domain-bind-hero__desc {
        margin: 10px 0 0;
        color: #64748b;
        line-height: 1.8;
    }

    .domain-bind-list {
        border: 1px solid #e5e7eb;
        border-radius: 16px;
        box-shadow: 0 10px 32px rgba(15, 23, 42, 0.05);
        overflow: hidden;
    }

    .domain-bind-list .panel-heading {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
        padding: 16px 20px;
    }

    .domain-bind-summary {
        display: inline-flex;
        align-items: center;
        padding: 4px 10px;
        border-radius: 999px;
        background: #eff6ff;
        color: #1d4ed8;
        font-size: 12px;
        font-weight: 600;
    }

    .domain-bind-table td {
        vertical-align: top !important;
    }

    .domain-bind-domains {
        max-width: 420px;
        line-height: 1.8;
        white-space: normal;
        word-break: break-all;
        color: #475569;
    }

    .domain-bind-empty {
        color: #94a3b8;
    }

    .domain-bind-dialog {
        display: none;
        padding: 22px;
    }

    .domain-bind-dialog__desc {
        margin: 0 0 16px;
        color: #64748b;
        line-height: 1.8;
    }
</style>
<body>

<!--                        Topbar End                              -->
<!-- ============================================================== -->


<!-- ============================================================== -->
<!-- 						Navigation Start 						-->
<!-- ============================================================== -->

@include(moduleAdminTemplate($moduleName)."public.nav")
<!-- ============================================================== -->
<!-- 						Navigation End	 						-->
<!-- ============================================================== -->

<!-- Page container -->
<div class="page-container">

    <!-- Page content -->
    <div class="page-content">

        <!-- Main sidebar -->
        <div class="sidebar sidebar-main">
            <div class="sidebar-content">
                @include(moduleAdminTemplate($moduleName)."public.left")
            </div>
        </div>
        <!-- /main sidebar -->


        <!-- Main content -->
        <div class="content-wrapper">

            <!-- Page header -->
            <div class="page-header">

            @include(moduleAdminTemplate($moduleName)."public.page",
            ['breadcrumb'=>[$pageData['title'],$pageData['subtitle']]])

            <!-- Content area -->
                <div class="content" style="margin-top: 1rem;">
                    <div class="domain-bind-hero">
                        <h3 class="domain-bind-hero__title">模块绑定域名</h3>
                        <p class="domain-bind-hero__desc">这里维护的是模块首页域名绑定。当前前台只在访问站点首页时按 Host 命中模块首页，多个域名可一行一个填写，保存时会自动清洗、去重并统计数量。</p>
                    </div>

                    <div class="table-responsive panel panel-default domain-bind-list">
                        <div class="panel-heading">
                            <span>模块域名列表</span>
                            <span class="domain-bind-summary">共 {{count($data)}} 个模块</span>
                        </div>
                        <table class="table table-bordered triptable-sed domain-bind-table">
                            <thead>
                            <tr>
                                <th>#ID</th>
                                <th>模块名称</th>
                                <th>域名</th>
                                <th>域名数量</th>
                                <th>操作</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($data as $d)
                                @php($domainValue = !empty($d['domain']['domain']) ? str_replace(',', "\n", $d['domain']['domain']) : '')
                                <tr>
                                    <td>{{$d['id']}}</td>
                                    <td>{{$d['name']}}</td>
                                    <td>
                                        <div class="domain-bind-domains">
                                            @if(!empty($d['domain']['domain']))
                                                {!! nl2br(e(str_replace(',', "\n", $d['domain']['domain']))) !!}
                                            @else
                                                <span class="domain-bind-empty">暂未绑定域名</span>
                                            @endif
                                        </div>
                                    </td>
                                    <td>{{$d['domain']['num'] ?: 0}}</td>
                                    <td>
                                        <button type="button"
                                                class="h-button-edit btn btn-info btn-xs {{permissions('novel/sequenceEdit')}}"
                                                data-id="{{$d['id']}}"
                                                data-name="{{e($d['name'])}}"
                                                data-domain="{{e($domainValue)}}"
                                                onclick="openDomainDialog(this)">
                                            编辑
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="20">
                                        暂无数据
                                    </td>
                                </tr>
                            @endforelse


                            </tbody>
                        </table>

                    </div>

                    <div id="domainBindDialog" class="domain-bind-dialog">
                        <p class="domain-bind-dialog__desc">每行填写一个域名。支持直接粘贴域名或带协议地址，保存时会自动提取 Host、转小写并去重。</p>
                        <div class="form-group">
                            <label class="control-label">模块名称</label>
                            <input type="text" id="domainBindName" class="form-control" disabled>
                            <input type="hidden" id="domainBindModuleId">
                        </div>
                        <div class="form-group">
                            <label class="control-label">域名列表</label>
                            <textarea id="domainBindValue" class="form-control" rows="14" placeholder="例如：&#10;news.example.com&#10;https://m.news.example.com/"></textarea>
                        </div>
                    </div>
                    <!-- Footer -->
                @include(moduleAdminTemplate($moduleName)."public.footer")
                <!-- /footer -->

                </div>
                <!-- /content area -->

            </div>
            <!-- /main content -->

        </div>
    </div>

    <!-- 						Content End		 						-->
    <!-- ============================================================== -->
    @include(moduleAdminTemplate($moduleName)."public.js")
    <script>
        function openDomainDialog(trigger) {
            var $trigger = $(trigger);
            $('#domainBindModuleId').val($trigger.data('id'));
            $('#domainBindName').val($trigger.data('name') || '');
            $('#domainBindValue').val($trigger.data('domain') || '');

            layer.open({
                type: 1,
                title: '编辑绑定域名',
                skin: 'layui-layer-rim',
                area: ['620px', '520px'],
                content: $('#domainBindDialog'),
                btn: ['保存', '取消'],
                yes: function (index) {
                    submitDomainDialog(index);
                }
            });
        }

        function submitDomainDialog(index) {
            var module_id = $('#domainBindModuleId').val();
            var domain = $('#domainBindValue').val();
            if (!module_id) return layer.msg('模块不能为空', {icon: 2});

            $.post('{{moduleAdminJump($moduleName,'setting/moduleBindDomainSubmit')}}',
                {
                    _method: 'PUT',
                    _token: '{{csrf_token()}}',
                    module_id: module_id,
                    domain: domain,
                },
                function (data) {
                    if (data.status == 200) {
                        layer.msg(data.msg, {icon: 1, time: 1000}, function () {
                            layer.close(index);
                            window.location.reload()
                        });
                    } else {
                        layer.msg(data.msg, {icon: 2});
                    }
                });
        }
    </script>
</body>
</html>
