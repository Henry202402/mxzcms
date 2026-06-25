@include(moduleAdminTemplate($moduleName)."public.header")
@php($currentUploadDriver = $currentUploadDriver ?? (__E('upload_driver') ?: 'local'))
<style>
    .fileinput-preview {
        border: 1px #ccc solid;
        margin-bottom: .2rem;
    }

    .h-word-deal {
        position: relative;
        top: 10px;
        color: #64748b;
        line-height: 1.7;
    }

    legend{
        font-size: 18px;
    }

    .upload-config-hero {
        margin-bottom: 20px;
        padding: 22px 24px;
        border: 1px solid #e5e7eb;
        border-radius: 16px;
        background: linear-gradient(180deg, #ffffff 0%, #f8fafc 100%);
        box-shadow: 0 16px 40px rgba(15, 23, 42, 0.06);
    }

    .upload-config-hero__title {
        margin: 0;
        font-size: 22px;
        font-weight: 700;
        color: #0f172a;
    }

    .upload-config-hero__desc {
        margin: 10px 0 0;
        color: #64748b;
        line-height: 1.8;
    }

    .upload-config-overview {
        display: grid;
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: 14px;
        margin-top: 18px;
    }

    .upload-config-overview__item {
        padding: 16px 18px;
        border: 1px solid #e5e7eb;
        border-radius: 14px;
        background: rgba(255, 255, 255, 0.92);
    }

    .upload-config-overview__label {
        margin: 0 0 8px;
        color: #64748b;
        font-size: 12px;
    }

    .upload-config-overview__value {
        margin: 0;
        color: #0f172a;
        font-size: 20px;
        font-weight: 700;
        line-height: 1.3;
        word-break: break-word;
    }

    .upload-config-overview__desc {
        margin-top: 8px;
        color: #94a3b8;
        line-height: 1.7;
    }

    .upload-config-panel {
        border: 1px solid #e5e7eb;
        border-radius: 16px;
        box-shadow: 0 10px 32px rgba(15, 23, 42, 0.05);
        overflow: hidden;
    }

    .upload-driver-grid {
        display: grid;
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: 14px;
    }

    .upload-driver-option {
        position: relative;
        display: block;
        margin: 0;
        padding: 16px;
        border: 1px solid #e5e7eb;
        border-radius: 14px;
        background: #fff;
        cursor: pointer;
        transition: all .2s ease;
    }

    .upload-driver-option:hover {
        border-color: #bfdbfe;
        box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.08);
    }

    .upload-driver-option input {
        position: absolute;
        opacity: 0;
        pointer-events: none;
    }

    .upload-driver-option__title {
        display: block;
        font-size: 15px;
        font-weight: 700;
        color: #0f172a;
    }

    .upload-driver-option__desc {
        display: block;
        margin-top: 6px;
        color: #64748b;
        line-height: 1.7;
        min-height: 48px;
    }

    .upload-driver-option.is-active {
        border-color: #bfdbfe;
        background: #eff6ff;
        box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.08);
    }

    .upload-config-section {
        margin-top: 24px;
        padding-top: 8px;
        border-top: 1px dashed #e5e7eb;
    }

    .upload-config-section:first-of-type {
        margin-top: 0;
        padding-top: 0;
        border-top: 0;
    }

    @media (max-width: 991px) {
        .upload-config-overview,
        .upload-driver-grid {
            grid-template-columns: 1fr;
        }
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


    @include(moduleAdminTemplate($moduleName)."public.left")


    <!-- Main content -->
        <div class="content-wrapper">

            <!-- Page header -->
            <div class="page-header">
                @include(moduleAdminTemplate($moduleName)."public.page",
         ['breadcrumb'=>[$pageData['title'],$pageData['subtitle']]])
            </div>
            <!-- /page header -->


            <!-- Content area -->
            <div class="content" style="margin-top: 1rem;">

                <div class="upload-config-hero">
                    <h3 class="upload-config-hero__title">上传设置</h3>
                    <p class="upload-config-hero__desc">这里统一管理文件上传来源、大小限制、图片缩略图和水印策略。建议先确认上传驱动，再设置限制和图片处理规则。</p>
                    <div class="upload-config-overview">
                        @foreach($uploadOverview as $overview)
                            <div class="upload-config-overview__item">
                                <p class="upload-config-overview__label">{{$overview['name']}}</p>
                                <p class="upload-config-overview__value">{{$overview['value']}}</p>
                                <div class="upload-config-overview__desc">{{$overview['desc']}}</div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="panel panel-flat upload-config-panel">
                    <div class="panel-heading">

                        <form id="myForm" class="form-horizontal" method="post" enctype="multipart/form-data">
                            {{csrf_field()}}
                            <fieldset class="content-group">
                                <legend class="text-bold mt-20">上传配置</legend>
                                <div class="upload-config-section">
                                <div class="form-group">
                                    <label class="control-label col-lg-1">开启上传功能</label>
                                    <div class="col-lg-11">

                                        <label class="radio-inline">
                                            <input type="radio" name="upload_status" class="styled h-radio"
                                                   value="1"
                                                   @if(__E("upload_status")==1) checked @endif >
                                            <span class="h-span-val">开启</span>
                                        </label>

                                        <label class="radio-inline">
                                            <input type="radio" name="upload_status" class="styled h-radio"
                                                   value="0"
                                                   @if(!__E("upload_status") || __E("upload_status")==0) checked @endif >
                                            <span class="h-span-val">关闭</span>
                                        </label>
                                    </div>
                                </div>

                                <div class="form-group ">
                                    <label class="control-label col-lg-1">上传大小</label>
                                    <div class="col-lg-8">
                                        <input type="number" name="upload_limit" value="{{__E('upload_limit')}}"
                                               class="form-control">
                                    </div>
                                    <div class="col-lg-3 h-word-deal">
                                        单个文件允许上传的最大体积
                                    </div>
                                </div>
                                <div class="form-group ">
                                    <label class="control-label col-lg-1">上传格式</label>
                                    <div class="col-lg-8">
                                        <input type="text" name="upload_format" value="{{__E('upload_format')}}"
                                               class="form-control">
                                    </div>
                                    <div class="col-lg-3 h-word-deal">
                                        允许上传的格式，多个格式用英文逗号分隔
                                    </div>
                                </div>
                                </div>

                                <div class="upload-config-section">
                                <div class="form-group">
                                    <label class="control-label col-lg-1">上传驱动</label>
                                    <div class="col-lg-11">
                                        <div class="upload-driver-grid" id="uploadDriverGrid">
                                            <label class="upload-driver-option @if($currentUploadDriver === 'local') is-active @endif">
                                                <input type="radio" name="upload_driver"
                                                       value="local"
                                                       @if($currentUploadDriver === 'local') checked @endif >
                                                <span class="upload-driver-option__title">本地存储</span>
                                                <span class="upload-driver-option__desc">文件保存在当前站点服务器，适合默认部署和本地管理场景。</span>
                                            </label>

                                            @foreach($plugin_list as $plugin)
                                                <label class="upload-driver-option @if($currentUploadDriver === ($plugin['identification'] ?? '')) is-active @endif">
                                                    <input type="radio" name="upload_driver"
                                                           value="{{$plugin['identification']}}"
                                                           @if($currentUploadDriver === ($plugin['identification'] ?? '')) checked @endif >
                                                    <span class="upload-driver-option__title">{{$plugin['name']}}</span>
                                                    <span class="upload-driver-option__desc">已接入的上传插件来源，可用于对象存储、云端文件或其它外部上传服务。</span>
                                                </label>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                                </div>

                                <legend class="text-bold mt-20" style="text-transform: none;">图片设置(缩略图)</legend>
                                <div class="upload-config-section">
                                <div class="form-group">
                                    <label class="control-label col-lg-1">自动生成</label>
                                    <div class="col-lg-11">

                                        <label class="radio-inline">
                                            <input type="radio" name="thumb_auto" class="styled h-radio"
                                                   value="1"
                                                   @if(__E("thumb_auto")==1) checked @endif >
                                            <span class="h-span-val">开启</span>
                                        </label>

                                        <label class="radio-inline">
                                            <input type="radio" name="thumb_auto" class="styled h-radio"
                                                   value="0"
                                                   @if(!__E("thumb_auto") || __E("thumb_auto")==0) checked @endif >
                                            <span class="h-span-val">关闭</span>
                                        </label>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="control-label col-lg-1">生成方式</label>
                                    <div class="col-lg-11">

                                        <label class="radio-inline">
                                            <input type="radio" name="thumb_method" class="styled h-radio"
                                                   value="draw"
                                                   @if(__E("thumb_method")=="draw") checked @endif>
                                            <span class="h-span-val">拉伸</span>
                                        </label>

                                        <label class="radio-inline">
                                            <input type="radio" name="thumb_method" class="styled h-radio"
                                                   value="message"
                                                   @if(__E("thumb_method")=="message") checked @endif>
                                            <span class="h-span-val">留白</span>
                                        </label>

                                        <label class="radio-inline">
                                            <input type="radio" name="thumb_method" class="styled h-radio"
                                                   value="cut"
                                                   @if(__E("thumb_method")=="cut") checked @endif>
                                            <span class="h-span-val">裁减</span>
                                        </label>
                                    </div>
                                </div>
                                </div>

                                <legend class="text-bold mt-20" style="text-transform: none;">图片设置(水印)</legend>
                                <div class="upload-config-section">
                                <div class="form-group">
                                    <label class="control-label col-lg-1">水印类型</label>
                                    <div class="col-lg-11">

                                        <label class="radio-inline">
                                            <input type="radio" name="watermark_type" class="styled h-radio"
                                                   value="img"
                                                   @if(__E("watermark_type")=="img") checked @endif >
                                            <span class="h-span-val">图片</span>
                                        </label>

                                        <label class="radio-inline">
                                            <input type="radio" name="watermark_type" class="styled h-radio"
                                                   value="text"
                                                   @if(__E("watermark_type")=="text") checked @endif >
                                            <span class="h-span-val">文字</span>
                                        </label>
                                    </div>
                                </div>


                                <div class="form-group">
                                    <label class="control-label col-lg-1">水印位置</label>
                                    <div class="col-lg-11">
                                        <label class="radio-inline">
                                            <input type="radio" name="watermark_position" class="styled h-radio"
                                                   value="top-left"
                                                   @if(__E("watermark_position")=="top-left") checked @endif >
                                            <span class="h-span-val">左上</span>
                                        </label>

                                        <label class="radio-inline">
                                            <input type="radio" name="watermark_position" class="styled h-radio"
                                                   value="top"
                                                   @if(__E("watermark_position")=="top") checked @endif >
                                            <span class="h-span-val">中上</span>
                                        </label>

                                        <label class="radio-inline">
                                            <input type="radio" name="watermark_position" class="styled h-radio"
                                                   value="top-right"
                                                   @if(__E("watermark_position")=="top-right") checked @endif >
                                            <span class="h-span-val">右上</span>
                                        </label>
                                    </div>
                                    <label class="control-label col-lg-1"></label>
                                    <div class="col-lg-11">
                                        <label class="radio-inline">
                                            <input type="radio" name="watermark_position" class="styled h-radio"
                                                   value="left"
                                                   @if(__E("watermark_position")=="left") checked @endif >
                                            <span class="h-span-val">左中</span>
                                        </label>

                                        <label class="radio-inline">
                                            <input type="radio" name="watermark_position" class="styled h-radio"
                                                   value="center"
                                                   @if(__E("watermark_position")=="center") checked @endif >
                                            <span class="h-span-val">中间</span>
                                        </label>

                                        <label class="radio-inline">
                                            <input type="radio" name="watermark_position" class="styled h-radio"
                                                   value="right"
                                                   @if(__E("watermark_position")=="right") checked @endif >
                                            <span class="h-span-val">右中</span>
                                        </label>
                                    </div>

                                    <label class="control-label col-lg-1"></label>
                                    <div class="col-lg-11">
                                        <label class="radio-inline">
                                            <input type="radio" name="watermark_position" class="styled h-radio"
                                                   value="bottom-left"
                                                   @if(__E("watermark_position")=="bottom-left") checked @endif >
                                            <span class="h-span-val">左下</span>
                                        </label>

                                        <label class="radio-inline">
                                            <input type="radio" name="watermark_position" class="styled h-radio"
                                                   value="bottom"
                                                   @if(__E("watermark_position")=="bottom") checked @endif >
                                            <span class="h-span-val">下方</span>
                                        </label>

                                        <label class="radio-inline">
                                            <input type="radio" name="watermark_position" class="styled h-radio"
                                                   value="bottom-right"
                                                   @if(__E("watermark_position")=="bottom-right") checked @endif >
                                            <span class="h-span-val">右下</span>
                                        </label>
                                    </div>
                                </div>
                                <section id="watermark_text" @if(__E("watermark_type")=="img") style="display: none" @endif>
                                <div class="form-group ">
                                    <label class="control-label col-lg-1">水印文字</label>
                                    <div class="col-lg-8">
                                        <input type="text" name="watermark_text" value="{{__E('watermark_text')}}"
                                               class="form-control">
                                    </div>
                                    <div class="col-lg-3 h-word-deal">
                                        用于图片文字水印展示
                                    </div>
                                </div>


                                <div class="form-group ">
                                    <label class="control-label col-lg-1">水印文字大小</label>
                                    <div class="col-lg-8">
                                        <input type="number" name="watermark_text_size" value="{{__E('watermark_text_size')}}"
                                               class="form-control">
                                    </div>
                                    <div class="col-lg-3 h-word-deal">
                                        像素(px)
                                    </div>
                                </div>


                                <div class="form-group ">
                                    <label class="control-label col-lg-1">文字角度</label>
                                    <div class="col-lg-8">
                                        <input type="number" name="watermark_text_angle" value="{{__E('watermark_text_angle')}}"
                                               class="form-control">
                                    </div>
                                    <div class="col-lg-3 h-word-deal">
                                        水平为0
                                    </div>
                                </div>

                                <div class="form-group ">
                                    <label class="control-label col-lg-1">文字颜色</label>
                                    <div class="col-lg-8">
                                        <input type="text" name="watermark_text_color" value="{{__E('watermark_text_color')}}"
                                               class="form-control">
                                    </div>
                                    <div class="col-lg-3 h-word-deal">
                                        自定义颜色
                                    </div>
                                </div>


                                <div class="form-group ">
                                    <label class="control-label col-lg-1">上传格式</label>
                                    <div class="col-lg-8">
                                        <input type="text" name="watermark_upload_format" value="{{__E('watermark_upload_format')}}"
                                               class="form-control">
                                    </div>
                                    <div class="col-lg-3 h-word-deal">
                                        仅对这些图片格式启用水印处理，多个格式用英文逗号分隔
                                    </div>
                                </div>
                                </section>

                                <section id="watermark_img" @if(__E("watermark_type")!="img") style="display: none" @endif>
                                    <div class="form-group row">
                                        <label for="inputEmail3" class="col-sm-1 col-form-label">水印图片</label>
                                        <div class="col-sm-4">
                                            <div class="fileinput fileinput-new input-group col-md-12" data-provides="fileinput">
                                                <div class="form-control" data-trigger="fileinput"><span class="fileinput-filename"></span></div>
                                                <span class="input-group-addon btn btn-primary btn-file ">
                                                                                      <span class="fileinput-new">选择图片</span>
                                                                                      <span class="fileinput-exists">更换</span>
                                                                                      <input type="hidden"><input name="watermark_img" type="file">
                                                                                      </span>
                                                <a class="input-group-addon btn btn-danger  hover fileinput-exists" data-dismiss="fileinput">删除</a>
                                            </div>
                                        </div>
                                    </div>
                                </section>
                                </div>

                                <div class="form-group">
                                    <input type="hidden" name="form" value="upload">
                                    <label class="col-lg-1 control-label"></label>
                                    <div class="col-lg-11">
                                        <button type="button" class="btn btn-sm btn-info h-sub">
                                            提交
                                        </button>
                                    </div>
                                </div>
                            </fieldset>

                        </form>
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
    <!-- /page content -->

</div>

<!-- 						Content End		 						-->
<!-- ============================================================== -->
@include(moduleAdminTemplate($moduleName)."public.js")
<script>
    function toggleWatermarkPanels(type) {
        if (type === "img") {
            $("#watermark_img").show();
            $("#watermark_text").hide();
        } else {
            $("#watermark_img").hide();
            $("#watermark_text").show();
        }
    }

    function syncUploadDriverCards() {
        $('#uploadDriverGrid .upload-driver-option').removeClass('is-active');
        $('#uploadDriverGrid input[name="upload_driver"]:checked').closest('.upload-driver-option').addClass('is-active');
    }

    //水印类型选项卡
    $("input[name='watermark_type']").change(function () {
        toggleWatermarkPanels($(this).val());
    });

    $('#uploadDriverGrid input[name="upload_driver"]').change(function () {
        syncUploadDriverCards();
    });

    toggleWatermarkPanels($("input[name='watermark_type']:checked").val());
    syncUploadDriverCards();

    $('input[name=watermark_img]').change(function () {
       $('.fileinput-filename').text($(this).val());
    });
    $('.h-sub').click(function () {
        layer.load(1);
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': "{{ csrf_token() }}"
            },
            "method": "post",
            "url": "{{moduleAdminJump($moduleName,'secure/toolSubmit')}}",
            "data": new FormData($('#myForm')[0]),
            "dataType": 'json',
            "cache": false,
            "processData": false,
            "contentType": false,
            "success": function (res) {
                layer.closeAll();
                if (res.status == 200) {
                    layer.msg(res.msg, {icon: 1, time: 1000}, function () {
                        window.location.reload();
                    });
                } else {
                    layer.msg(res.msg, {icon: 2})
                }
            },
            "error": function (res) {
                layer.closeAll();
                layer.msg("系统错误，请稍后重试", {icon: 5})
            }
        });
    });
</script>
<script type="text/javascript"
        src="{{asset("assets/module")}}/js/plugins/forms/selects/select2.min.js"></script>
<script type="text/javascript" src="{{asset("assets/module")}}/js/pages/form_select2.js"></script>
</body>
</html>
