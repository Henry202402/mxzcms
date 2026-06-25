@include(moduleAdminTemplate($moduleName)."public.header")
<style>
    .cache-config-hero {
        margin-bottom: 20px;
        padding: 22px 24px;
        border: 1px solid #e5e7eb;
        border-radius: 16px;
        background: linear-gradient(180deg, #ffffff 0%, #f8fafc 100%);
        box-shadow: 0 16px 40px rgba(15, 23, 42, 0.06);
    }

    .cache-config-hero__title {
        margin: 0;
        font-size: 22px;
        font-weight: 700;
        color: #111827;
    }

    .cache-config-hero__desc {
        margin-top: 8px;
        max-width: 760px;
        color: #6b7280;
        line-height: 1.7;
    }

    .cache-config-overview {
        display: grid;
        grid-template-columns: repeat(4, minmax(0, 1fr));
        gap: 14px;
        margin-top: 18px;
    }

    .cache-overview-card,
    .cache-quick-card,
    .cache-config-section {
        border: 1px solid #e5e7eb;
        border-radius: 16px;
        background: #fff;
        box-shadow: 0 10px 32px rgba(15, 23, 42, 0.05);
    }

    .cache-overview-card {
        padding: 16px 18px;
    }

    .cache-overview-card__name {
        font-size: 13px;
        color: #6b7280;
    }

    .cache-overview-card__value {
        margin-top: 10px;
        font-size: 26px;
        line-height: 1.2;
        font-weight: 700;
        color: #111827;
        word-break: break-all;
    }

    .cache-overview-card__desc {
        margin-top: 10px;
        min-height: 42px;
        color: #6b7280;
        line-height: 1.6;
    }

    .cache-layout-grid {
        display: grid;
        grid-template-columns: minmax(0, 2fr) minmax(320px, 1fr);
        gap: 20px;
        align-items: start;
    }

    .cache-main-panel {
        padding: 20px;
    }

    .cache-panel-title {
        margin: 0 0 6px;
        font-size: 16px;
        font-weight: 700;
        color: #111827;
    }

    .cache-panel-desc {
        margin-bottom: 16px;
        color: #6b7280;
        line-height: 1.7;
    }

    .cache-driver-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 14px;
        margin-bottom: 20px;
    }

    .cache-driver-option {
        position: relative;
        display: block;
        padding: 18px;
        border: 1px solid #dbe3ee;
        border-radius: 14px;
        background: #fff;
        cursor: pointer;
        transition: all .2s ease;
    }

    .cache-driver-option input {
        position: absolute;
        opacity: 0;
        pointer-events: none;
    }

    .cache-driver-option.is-active {
        border-color: #bfdbfe;
        background: #eff6ff;
        box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.08);
    }

    .cache-driver-option__title {
        display: block;
        font-size: 15px;
        font-weight: 700;
        color: #111827;
    }

    .cache-driver-option__desc {
        display: block;
        margin-top: 8px;
        color: #6b7280;
        line-height: 1.7;
    }

    .cache-form-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 16px;
    }

    .cache-form-item--full {
        grid-column: 1 / -1;
    }

    .cache-form-item label {
        display: block;
        margin-bottom: 8px;
        font-size: 13px;
        font-weight: 600;
        color: #374151;
    }

    .cache-form-item input {
        width: 100%;
        height: 42px;
        padding: 8px 12px;
        border: 1px solid #d1d5db;
        border-radius: 10px;
    }

    .cache-section-stack {
        display: grid;
        gap: 18px;
    }

    .cache-config-section {
        padding: 18px;
    }

    .cache-config-section__title {
        margin: 0 0 6px;
        font-size: 15px;
        font-weight: 700;
        color: #111827;
    }

    .cache-config-section__desc {
        margin-bottom: 16px;
        color: #6b7280;
        line-height: 1.7;
    }

    .cache-quick-panel {
        display: grid;
        gap: 18px;
    }

    .cache-quick-card {
        padding: 18px;
    }

    .cache-quick-card__title {
        margin: 0 0 8px;
        font-size: 15px;
        font-weight: 700;
        color: #111827;
    }

    .cache-quick-card__desc {
        margin-bottom: 14px;
        color: #6b7280;
        line-height: 1.7;
    }

    .cache-driver-tip {
        padding: 12px 14px;
        border: 1px dashed #cbd5e1;
        border-radius: 12px;
        background: #f8fafc;
        color: #475569;
        line-height: 1.7;
    }

    .cache-form-actions {
        margin-top: 20px;
        display: flex;
        align-items: center;
        gap: 12px;
        flex-wrap: wrap;
    }

    @media (max-width: 1200px) {
        .cache-config-overview,
        .cache-driver-grid,
        .cache-form-grid,
        .cache-layout-grid {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }

        .cache-layout-grid {
            grid-template-columns: repeat(1, minmax(0, 1fr));
        }
    }

    @media (max-width: 768px) {
        .cache-config-overview,
        .cache-driver-grid,
        .cache-form-grid {
            grid-template-columns: repeat(1, minmax(0, 1fr));
        }
    }
</style>
<body>

@include(moduleAdminTemplate($moduleName)."public.nav")

<div class="page-container">
    <div class="page-content">
        @include(moduleAdminTemplate($moduleName)."public.left")

        <div class="content-wrapper">
            <div class="page-header">
                @include(moduleAdminTemplate($moduleName)."public.page", ['breadcrumb'=>[$pageData['title'],$pageData['subtitle']]])
            </div>

            <div class="content" style="margin-top: 1rem;">
                <div class="cache-config-hero">
                    <h2 class="cache-config-hero__title">缓存配置概览</h2>
                    <div class="cache-config-hero__desc">
                        管理当前站点的缓存驱动、连接参数和缓存前缀。切换驱动后建议立即清理系统缓存，避免旧配置继续生效。
                    </div>

                    <div class="cache-config-overview">
                        @foreach($cacheOverview as $overview)
                            <div class="cache-overview-card">
                                <div class="cache-overview-card__name">{{$overview['name']}}</div>
                                <div class="cache-overview-card__value">{{$overview['value']}}</div>
                                <div class="cache-overview-card__desc">{{$overview['desc']}}</div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="cache-layout-grid">
                    <div class="cache-overview-card cache-main-panel">
                        <form id="myForm" method="post" enctype="multipart/form-data">
                            {{csrf_field()}}
                            <input type="hidden" name="form" value="cache">

                            <h3 class="cache-panel-title">缓存驱动与连接</h3>
                            <div class="cache-panel-desc">
                                先选择缓存驱动，再补充对应服务的连接信息。非当前驱动的参数会保留，方便后续再次切换。
                            </div>

                            <div class="cache-driver-grid" id="cacheDriverGrid">
                                @foreach($cacheDriverOptions as $driver)
                                    <label class="cache-driver-option @if($currentCacheDriver === $driver['value']) is-active @endif">
                                        <input type="radio" name="CACHE_DRIVER"
                                               value="{{$driver['value']}}"
                                               @if($currentCacheDriver === $driver['value']) checked @endif>
                                        <span class="cache-driver-option__title">{{$driver['name']}}</span>
                                        <span class="cache-driver-option__desc">{{$driver['desc']}}</span>
                                    </label>
                                @endforeach
                            </div>

                            <div class="cache-section-stack">
                                <div class="cache-config-section">
                                    <h4 class="cache-config-section__title">基础设置</h4>
                                    <div class="cache-config-section__desc">缓存前缀用于区分不同项目或环境的缓存键，建议按站点标识设置。</div>
                                    <div class="cache-form-grid">
                                        <div class="cache-form-item cache-form-item--full">
                                            <label>缓存前缀</label>
                                            <input type="text" name="CACHE_PREFIX" value="{{env('CACHE_PREFIX')}}" placeholder="例如：mxzcms_cache">
                                        </div>
                                    </div>
                                </div>

                                <div class="cache-config-section" id="redisConfigPanel">
                                    <h4 class="cache-config-section__title">Redis 设置</h4>
                                    <div class="cache-config-section__desc">当缓存驱动为 Redis 时，请确认主机、端口和密码与服务器保持一致。</div>
                                    <div class="cache-form-grid">
                                        <div class="cache-form-item">
                                            <label>Redis 主机</label>
                                            <input type="text" name="REDIS_HOST" value="{{env('REDIS_HOST')}}" placeholder="127.0.0.1">
                                        </div>
                                        <div class="cache-form-item">
                                            <label>Redis 端口</label>
                                            <input type="text" name="REDIS_PORT" value="{{env('REDIS_PORT')}}" placeholder="6379">
                                        </div>
                                        <div class="cache-form-item cache-form-item--full">
                                            <label>Redis 密码</label>
                                            <input type="text" name="REDIS_PASSWORD" value="{{env('REDIS_PASSWORD')}}" placeholder="未设置可留空">
                                        </div>
                                    </div>
                                </div>

                                <div class="cache-config-section" id="memcachedConfigPanel">
                                    <h4 class="cache-config-section__title">Memcached 设置</h4>
                                    <div class="cache-config-section__desc">Memcached 适合简单轻量的内存缓存服务，使用前请确认主机和认证信息可用。</div>
                                    <div class="cache-form-grid">
                                        <div class="cache-form-item">
                                            <label>Memcached 主机</label>
                                            <input type="text" name="MEMCACHED_HOST" value="{{env('MEMCACHED_HOST')}}" placeholder="127.0.0.1">
                                        </div>
                                        <div class="cache-form-item">
                                            <label>Memcached 端口</label>
                                            <input type="text" name="MEMCACHED_PORT" value="{{env('MEMCACHED_PORT')}}" placeholder="11211">
                                        </div>
                                        <div class="cache-form-item">
                                            <label>Memcached 用户名</label>
                                            <input type="text" name="MEMCACHED_USERNAME" value="{{env('MEMCACHED_USERNAME')}}" placeholder="未启用认证可留空">
                                        </div>
                                        <div class="cache-form-item">
                                            <label>Memcached 密码</label>
                                            <input type="text" name="MEMCACHED_PASSWORD" value="{{env('MEMCACHED_PASSWORD')}}" placeholder="未启用认证可留空">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="cache-form-actions">
                                <button type="button" class="btn btn-info h-sub">保存配置</button>
                                <button type="button" class="btn btn-default" onclick="clearCache()">保存后清缓存</button>
                            </div>
                        </form>
                    </div>

                    <div class="cache-quick-panel">
                        @foreach($cacheQuickActions as $action)
                            <div class="cache-quick-card">
                                <h4 class="cache-quick-card__title">{{$action['title']}}</h4>
                                <div class="cache-quick-card__desc">{{$action['desc']}}</div>

                                @if($action['action'] === 'clear-cache')
                                    <button type="button" class="btn btn-info" onclick="clearCache()">{{$action['button']}}</button>
                                @elseif($action['action'] === 'check-driver')
                                    <button type="button" class="btn btn-default" onclick="showCacheDriverInfo()">{{$action['button']}}</button>
                                @endif
                            </div>
                        @endforeach

                        <div class="cache-quick-card">
                            <h4 class="cache-quick-card__title">当前驱动说明</h4>
                            <div class="cache-driver-tip">
                                当前使用 <strong>{{$currentCacheDriverMeta['name']}}</strong>。
                                {{$currentCacheDriverMeta['desc']}}
                            </div>
                        </div>

                        <div class="cache-quick-card">
                            <h4 class="cache-quick-card__title">连接目标</h4>
                            <div class="cache-driver-tip">{{$cacheConnectionTarget}}</div>
                        </div>
                    </div>
                </div>

                @include(moduleAdminTemplate($moduleName)."public.footer")
            </div>
        </div>
    </div>
</div>

@include(moduleAdminTemplate($moduleName)."public.js")
<script>
    function syncCacheDriverPanels() {
        var currentDriver = $('#cacheDriverGrid input[name="CACHE_DRIVER"]:checked').val();
        $('#cacheDriverGrid .cache-driver-option').removeClass('is-active');
        $('#cacheDriverGrid input[name="CACHE_DRIVER"]:checked').closest('.cache-driver-option').addClass('is-active');

        if (currentDriver === 'redis') {
            $('#redisConfigPanel').show();
            $('#memcachedConfigPanel').hide();
        } else if (currentDriver === 'memcached') {
            $('#redisConfigPanel').hide();
            $('#memcachedConfigPanel').show();
        } else {
            $('#redisConfigPanel').hide();
            $('#memcachedConfigPanel').hide();
        }
    }

    function showCacheDriverInfo() {
        layer.alert('当前缓存驱动：{{$currentCacheDriverMeta['name']}}<br>连接目标：{{$cacheConnectionTarget}}', {
            title: '当前连接信息'
        });
    }

    function clearCache() {
        layer.load(1);
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': "{{ csrf_token() }}"
            },
            method: 'post',
            url: "{{ url('admin/clear') }}",
            dataType: 'json',
            success: function (res) {
                layer.closeAll();
                if (res.status == 200) {
                    layer.msg(res.msg, {icon: 1, time: 1200});
                } else {
                    layer.msg(res.msg || '清理失败', {icon: 2});
                }
            },
            error: function () {
                layer.closeAll();
                layer.msg('系统错误，请稍后重试', {icon: 5});
            }
        });
    }

    $('#cacheDriverGrid input[name="CACHE_DRIVER"]').change(function () {
        syncCacheDriverPanels();
    });

    $('.h-sub').click(function () {
        layer.load(1);
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': "{{ csrf_token() }}"
            },
            method: 'post',
            url: "{{moduleAdminJump($moduleName,'secure/toolSubmit')}}",
            data: new FormData($('#myForm')[0]),
            dataType: 'json',
            cache: false,
            processData: false,
            contentType: false,
            success: function (res) {
                layer.closeAll();
                if (res.status == 200) {
                    layer.msg(res.msg, {icon: 1, time: 1000}, function () {
                        window.location.reload();
                    });
                } else {
                    layer.msg(res.msg, {icon: 2});
                }
            },
            error: function () {
                layer.closeAll();
                layer.msg('系统错误，请稍后重试', {icon: 5});
            }
        });
    });

    syncCacheDriverPanels();
</script>
</body>
</html>
