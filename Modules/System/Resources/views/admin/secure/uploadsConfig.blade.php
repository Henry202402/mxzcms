@include(moduleAdminTemplate($moduleName)."public.header")
<style>
    .fileinput-preview {
        border: 1px #ccc solid;
        margin-bottom: .2rem;
    }

    .h-word-deal {
        position: relative;
        top: 10px;
    }
    legend{
        font-size: 18px;
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


                <div class="panel panel-flat">
                    <div class="panel-heading">

                        <form id="myForm" class="form-horizontal" method="post" enctype="multipart/form-data">
                            {{csrf_field()}}
                            <fieldset class="content-group">
                                <legend class="text-bold mt-20">上传配置</legend>
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
                                        上传文件大小(KB)
                                    </div>
                                </div>
                                <div class="form-group ">
                                    <label class="control-label col-lg-1">上传格式</label>
                                    <div class="col-lg-8">
                                        <input type="text" name="upload_format" value="{{__E('upload_format')}}"
                                               class="form-control">
                                    </div>
                                    <div class="col-lg-3 h-word-deal">
                                        上传文件格式(gif、png等等，用,隔开)
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="control-label col-lg-1">上传驱动</label>
                                    <div class="col-lg-11">

                                        <label class="radio-inline">
                                            <input type="radio" name="upload_driver" class="styled h-radio"
                                                   value="local"
                                                   @if(__E("upload_driver")=="local") checked @endif >
                                            <span class="h-span-val">本地</span>
                                        </label>

                                        @if($plugin_list)
                                            @foreach($plugin_list as $key => $plugin)
                                                @if($plugin)
                                                    <label class="radio-inline">
                                                        <input type="radio" name="upload_driver" class="styled h-radio"
                                                               value="{{$plugin['identification']}}"
                                                               @if(!empty(__E("upload_driver")) && __E("upload_driver")==$plugin['identification']) checked @endif >
                                                        <span class="h-span-val"> {{$plugin['name']}} </span>
                                                    </label>
                                                @endif
                                            @endforeach
                                        @endif
                                    </div>
                                </div>
                                {{--<div class="h-clear-both"></div>--}}
                                {{--<br>--}}

                                <legend class="text-bold mt-20" style="text-transform: none;">图片设置(缩略图)</legend>
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

                                <legend class="text-bold mt-20" style="text-transform: none;">图片设置(水印)</legend>
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
                                            <span class="h-span-val">中上</span>
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
                                                   value="top"
                                                   @if(__E("watermark_position")=="bottom") checked @endif >
                                            <span class="h-span-val">低下</span>
                                        </label>

                                        <label class="radio-inline">
                                            <input type="radio" name="watermark_position" class="styled h-radio"
                                                   value="bottom-right"
                                                   @if(__E("watermark_position")=="bottom-right") checked @endif >
                                            <span class="h-span-val">右下</span>
                                        </label>
                                    </div>
                                </div>


                                <div class="form-group ">
                                    <label class="control-label col-lg-1">水印文字</label>
                                    <div class="col-lg-8">
                                        <input type="text" name="watermark_text" value="{{__E('watermark_text')}}"
                                               class="form-control">
                                    </div>
                                    <div class="col-lg-3 h-word-deal">
                                        上传文件大小(KB)
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
                                        上传文件格式(gif、png等等，用,隔开)
                                    </div>
                                </div>

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

    //水印类型选项卡
    $("input[name='watermark_type']").change(function () {

        if($(this).val()=="img"){
            $("#watermark_img").show();
            $("#watermark_text").hide();

        }else if($(this).val()=="text"){
            $("#watermark_img").hide();
            $("#watermark_text").show();
        }

    })

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
        src="{{moduleAdminResource($moduleName)}}/js/plugins/forms/selects/select2.min.js"></script>
<script type="text/javascript" src="{{moduleAdminResource($moduleName)}}/js/pages/form_select2.js"></script>
</body>
</html>
