<!doctype html>
<html>
<head>
    @include("install.head")
</head>
<body>
<div class="container install-shell">
    @include("install.header")
    <div class="install-step-nav mt-3">
        <div class="install-step-nav__item is-active">
            <div><span class="install-step-nav__step">1</span><span class="install-step-nav__label">阅读协议</span></div>
            <div class="install-step-nav__desc">确认使用协议后继续环境检测。</div>
        </div>
        <div class="install-step-nav__item">
            <div><span class="install-step-nav__step">2</span><span class="install-step-nav__label">环境检测</span></div>
            <div class="install-step-nav__desc">检查服务器运行环境与权限。</div>
        </div>
        <div class="install-step-nav__item">
            <div><span class="install-step-nav__step">3</span><span class="install-step-nav__label">开始安装</span></div>
            <div class="install-step-nav__desc">填写数据库并初始化站点数据。</div>
        </div>
    </div>
    <div class="install-panel mt-4">
        <div class="install-panel__body">
            <div class="d-flex justify-content-between align-items-center flex-wrap mb-3">
                <div>
                    <div class="install-section-title mb-1">{{$cms_name}}《使用协议》</div>
                    <div class="install-muted small">建议完整阅读协议内容后再继续安装。</div>
                </div>
                <span class="install-badge"><i class="fa fa-file-text-o"></i> 协议确认</span>
            </div>
            <div class="install-info-card p-0">
                <div class="card-body p-3">
                    <div class="card-text overflow-auto text-secondary small" style="height: 60vh;overflow: auto">
                        {!! file_get_contents("https://www.mxzcloud.com/api/cloud/getAgrees")?:"<a href='https://www.mxzcloud.com/api/cloud/getAgrees'>在线查看使用协议</a>" !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="install-bottom-actions">
        <a href="{{url('/install?install=2')}}" class="btn btn-primary">同意并进入环境检测</a>
    </div>
</div>
@include("install.footer")
</body>
</html>
