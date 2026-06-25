<!doctype html>
<html>
<head>
    @include("install.head")
    <style>
        .rewrite-correct, .rewrite-error, .rewrite-detail {
            display: none;
        }
    </style>
</head>
<body>
@include("install.header")
@php
    $summary = $installCheckSummary ?? [
        'total' => 0,
        'passed' => 0,
        'failed_required' => 0,
        'failed_optional' => 0,
        'failed_items' => [],
    ];
@endphp
<section class="container install-shell">
    <div class="install-step-nav">
        <div class="install-step-nav__item is-active">
            <div><span class="install-step-nav__step">1</span><span class="install-step-nav__label">环境检测</span></div>
            <div class="install-step-nav__desc">检查 PHP、扩展、上传能力与目录权限。</div>
        </div>
        <div class="install-step-nav__item">
            <div><span class="install-step-nav__step">2</span><span class="install-step-nav__label">创建数据</span></div>
            <div class="install-step-nav__desc">填写数据库、站点信息与创始人账号。</div>
        </div>
        <div class="install-step-nav__item">
            <div><span class="install-step-nav__step">3</span><span class="install-step-nav__label">完成安装</span></div>
            <div class="install-step-nav__desc">执行初始化并确认后台入口与管理员密码。</div>
        </div>
    </div>

    <div class="install-panel mt-4">
        <div class="install-panel__body">
            <div class="install-kpi-grid mb-4">
                <div class="install-kpi">
                    <div class="install-kpi__label">总检测项</div>
                    <div class="install-kpi__value">{{$summary['total']}}</div>
                    <div class="install-kpi__hint">安装前基础预检总数</div>
                </div>
                <div class="install-kpi">
                    <div class="install-kpi__label">已通过</div>
                    <div class="install-kpi__value">{{$summary['passed']}}</div>
                    <div class="install-kpi__hint">满足当前安装条件的检测项</div>
                </div>
                <div class="install-kpi">
                    <div class="install-kpi__label">阻塞项</div>
                    <div class="install-kpi__value">{{$summary['failed_required']}}</div>
                    <div class="install-kpi__hint">必须先修复后才能继续安装</div>
                </div>
                <div class="install-kpi">
                    <div class="install-kpi__label">提醒项</div>
                    <div class="install-kpi__value">{{$summary['failed_optional']}}</div>
                    <div class="install-kpi__hint">建议处理，但不阻塞当前安装</div>
                </div>
            </div>

            <div class="alert install-alert {{$summary['failed_required'] ? 'alert-danger' : 'alert-success'}}">
                <div class="font-weight-bold">
                    {{$summary['failed_required'] ? '环境检测未通过，请先处理阻塞项。' : '环境检测通过，可以进入数据初始化阶段。'}}
                </div>
                <div class="small mt-2">
                    当前 PHP：<strong>{{$data['phpversion'] ?? phpversion()}}</strong>，
                    要求：<strong>{{$data['php_requirement'] ?? '^8.0.2'}}</strong>。
                    如遇扩展缺失或目录不可写，请先修复服务器环境再继续。
                </div>
            </div>

            @if(!empty($summary['failed_items']))
                <div class="install-info-card mt-4">
                    <h6>待处理失败项</h6>
                    <p class="install-section-desc">以下问题会影响安装成功率，建议先完成修复。</p>
                    <div class="row">
                        @foreach($summary['failed_items'] as $item)
                            <div class="col-md-6 mb-2">
                                <div class="border rounded p-3 h-100">
                                    <div class="font-weight-bold text-danger">
                                        <i class="fa fa-remove"></i> {{$item['title']}}
                                    </div>
                                    <div class="small mt-2 install-muted">
                                        类型：{{$item['required'] ? '必须项' : '可选项'}}<br>
                                        说明：{{$item['message']}}
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            <div class="install-summary-grid mt-4">
                <div class="install-summary-card">
                    <h6>运行环境</h6>
                    <ul class="install-meta-list">
                        <li>
                            <span class="install-meta-list__label">操作系统</span>
                            <span class="install-meta-list__value">{{$data['os']}}</span>
                        </li>
                        <li>
                            <span class="install-meta-list__label">PHP 版本要求</span>
                            <span class="install-meta-list__value">{{$data['php_requirement'] ?? '^8.0.2'}}</span>
                        </li>
                        <li>
                            <span class="install-meta-list__label">当前 PHP 状态</span>
                            <span class="install-meta-list__value">{!! $data['phpversion_msg'] !!}</span>
                        </li>
                        <li>
                            <span class="install-meta-list__label">上传能力</span>
                            <span class="install-meta-list__value">{!! $data['upload_size'] !!}</span>
                        </li>
                    </ul>
                </div>
                <div class="install-summary-card">
                    <h6>核心扩展</h6>
                    <ul class="install-meta-list">
                        <li><span class="install-meta-list__label">Session</span><span class="install-meta-list__value">{!! $data['session'] !!}</span></li>
                        <li><span class="install-meta-list__label">PDO</span><span class="install-meta-list__value">{!! $data['pdo'] !!}</span></li>
                        <li><span class="install-meta-list__label">PDO MySQL</span><span class="install-meta-list__value">{!! $data['pdo_mysql'] !!}</span></li>
                        <li><span class="install-meta-list__label">CURL</span><span class="install-meta-list__value">{!! $data['curl'] !!}</span></li>
                        <li><span class="install-meta-list__label">GD</span><span class="install-meta-list__value">{!! $data['gd'] !!}</span></li>
                        <li><span class="install-meta-list__label">MBstring</span><span class="install-meta-list__value">{!! $data['mbstring'] !!}</span></li>
                        <li><span class="install-meta-list__label">fileinfo</span><span class="install-meta-list__value">{!! $data['fileinfo'] !!}</span></li>
                    </ul>
                </div>
            </div>

            <div class="mt-4">
                <div class="install-section-title">详细检测清单</div>
                <p class="install-section-desc">这里展示当前安装器对环境、扩展、伪静态与上传限制的逐项检测结果。</p>
                <div class="table-responsive">
                    <table class="table table-bordered table-sm install-table">
                        <thead>
                        <tr>
                            <th>检测项</th>
                            <th>推荐配置</th>
                            <th>当前状态</th>
                            <th>最低要求</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td>操作系统</td>
                            <td>Linux / Windows Server</td>
                            <td><i class="fa fa-check correct"></i> {{$data['os']}}</td>
                            <td>不限制</td>
                        </tr>
                        <tr>
                            <td>PHP 版本</td>
                            <td>{{$data['php_requirement'] ?? '^8.0.2'}}</td>
                            <td>{!! $data['phpversion_msg'] !!}</td>
                            <td>{{$data['php_requirement'] ?? '^8.0.2'}}</td>
                        </tr>
                        <tr class="section-row">
                            <td colspan="4">扩展与函数检测</td>
                        </tr>
                        <tr>
                            <td>Session</td>
                            <td>开启</td>
                            <td>{!! $data['session'] !!}</td>
                            <td>开启</td>
                        </tr>
                        <tr>
                            <td>PDO</td>
                            <td>开启</td>
                            <td>{!! $data['pdo'] !!}</td>
                            <td>开启</td>
                        </tr>
                        <tr>
                            <td>PDO MySQL</td>
                            <td>开启</td>
                            <td>{!! $data['pdo_mysql'] !!}</td>
                            <td>开启</td>
                        </tr>
                        <tr>
                            <td>CURL</td>
                            <td>开启</td>
                            <td>{!! $data['curl'] !!}</td>
                            <td>开启</td>
                        </tr>
                        <tr>
                            <td>GD</td>
                            <td>开启</td>
                            <td>{!! $data['gd'] !!}</td>
                            <td>开启</td>
                        </tr>
                        <tr>
                            <td>MBstring</td>
                            <td>开启</td>
                            <td>{!! $data['mbstring'] !!}</td>
                            <td>开启</td>
                        </tr>
                        <tr>
                            <td>fileinfo</td>
                            <td>开启</td>
                            <td>{!! $data['fileinfo'] !!}</td>
                            <td>开启</td>
                        </tr>
                        <tr class="section-row">
                            <td colspan="4">可用性与能力检测</td>
                        </tr>
                        <tr>
                            <td>服务器 rewrite</td>
                            <td>开启</td>
                            <td>
                                <span class="rewrite-checking">正在检测...</span>
                                <span class="rewrite-correct"><i class="fa fa-check correct"></i> 支持</span>
                                <span class="rewrite-error"><i class="fa fa-remove error"></i> 不支持</span>
                                <div class="rewrite-detail small text-muted mt-2"></div>
                            </td>
                            <td>开启</td>
                        </tr>
                        <tr>
                            <td>附件上传</td>
                            <td>>= 2M</td>
                            <td>{!! $data['upload_size'] !!}</td>
                            <td>允许上传</td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="mt-4">
                <div class="install-section-title">目录权限检查</div>
                <p class="install-section-desc">安装器需要写入缓存、日志、上传目录与运行时文件，请确保以下目录可读且可写。</p>
                <div class="table-responsive">
                    <table class="table table-bordered table-sm install-table">
                        <thead>
                        <tr>
                            <th>目录 / 文件</th>
                            <th>写入</th>
                            <th>读取</th>
                        </tr>
                        </thead>
                        <tbody>
                        @if($data['folders'])
                            @foreach($data['folders'] as $dir => $vo)
                                <tr>
                                    <td>{{$dir}}</td>
                                    <td>
                                        @if($vo['w'])
                                            <i class="fa fa-check correct"></i> 可写
                                        @else
                                            <i class="fa fa-remove error"></i> 不可写
                                        @endif
                                    </td>
                                    <td>
                                        @if($vo['r'])
                                            <i class="fa fa-check correct"></i> 可读
                                        @else
                                            <i class="fa fa-remove error"></i> 不可读
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        @endif
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="install-bottom-actions">
                <a href="{{url('/install?install=2')}}" class="btn btn-outline-primary">重新检测</a>
                @if($summary['failed_required'] > 0)
                    <button type="button" class="btn btn-secondary" disabled>请先修复环境问题</button>
                @else
                    <a href="{{url('/install?install=3')}}" class="btn btn-primary">进入创建数据</a>
                @endif
            </div>
        </div>
    </div>
</section>

@include("install.footer")
<script>
    function updateRewriteView(result) {
        var supported = !!(result && result.data && result.data.supported);
        $('.rewrite-checking').hide();
        $('.rewrite-correct').toggle(supported);
        $('.rewrite-error').toggle(!supported);
        $('.rewrite-detail')
            .html(result && result.msg ? result.msg : 'rewrite 检测失败，请确认伪静态规则与 Web 服务器配置。')
            .show();
    }

    function detectRewriteSupport() {
        $.ajax({
            url: "{{url('install/rewrite-check')}}?t=" + Date.now(),
            type: 'GET',
            dataType: 'json',
            success: function (result) {
                updateRewriteView(result);
            },
            error: function () {
                updateRewriteView({
                    status: 40000,
                    msg: '未能通过干净路由访问到探测接口，rewrite 可能未开启，或当前站点仍依赖 index.php 访问。',
                    data: {
                        supported: false
                    }
                });
            }
        });
    }

    $(function () {
        detectRewriteSupport();
    });
</script>
</body>
</html>
