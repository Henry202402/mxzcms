@include("admin.public.header")
<body class="horizontal">

<div class="main-horizontal-nav">
    <nav>
        <!-- Menu Toggle btn-->
        <div class="menu-toggle">
            <h3>{{getTranslateByKey("menu_navigation")}}</h3>
            <button type="button" id="menu-btn">
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
        </div>

        <ul id="respMenu" class="ace-responsive-menu" data-menu-style="horizontal">
            <li>
                <a href="{{url("admin/theme/setting?m=".$_GET['m'])}}"> <i class="fa fa-arrow-circle-left"></i>返回主题预览</a>
            </li>
        </ul>
    </nav>
</div>

<div class="row page-header" style="margin-bottom: 0px;">
    <div class="col-lg-6 align-self-center ">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{url("admin/index")}}">{{getTranslateByKey("common_home_page")}}</a></li>
            <li class="breadcrumb-item"><a href="{{url("admin/theme")}}">主题列表</a></li>
            <li class="breadcrumb-item active">页面配置</li>
        </ol>
    </div>
</div>

<section class="container-fluid pl-5 pr-5 ">

    <div class="row pb-5">
        <div class="col-12 bg-light p-3" style="min-height: calc(100vh - 125px)">
            <div class=" ">

                <form method="post" action="" id="diy_form" enctype="multipart/form-data">
                    {{csrf_field()}}
                    <div class="form-group">
                        <h4>logo 动画</h4>
                        <div class="fileinput-new" data-provides="fileinput">
                            <div class="fileinput-preview" data-trigger="fileinput" style="border: none;">
                                <img src="{{GetLocalFileByPath(cacheGlobalSettingsByKey('weblogo'))}}" style="width: auto; height:100px;">
                            </div>
                        </div>
                    </div>

                    <div class="form-group ">
                        <label>动画效果</label>

                        <div class="form-inline">
                            <div class="radio radio-inline radio-success">
                                <input id="logo_animated" type="radio" name="logo_animated" value="" @if(!cacheGlobalSettingsByKey("logo_animated")) checked @endif  >
                                <label for="logo_animated"> 无动画 </label>
                            </div>
                            <div class="radio radio-inline radio-success">
                                <input id="logo_animated2" type="radio" name="logo_animated" value="logo-animated" @if(cacheGlobalSettingsByKey("logo_animated")=="logo-animated") checked @endif  >
                                <label for="logo_animated2"> 360°旋转 </label>
                            </div>
                            <div class="radio radio-inline radio-success">
                                <input id="logo_animated3" type="radio" name="logo_animated" value="" disabled  >
                                <label for="logo_animated3"> 心跳旋律 </label>
                            </div>

                            <div class="radio radio-inline radio-success">
                                <input id="logo_animated4" type="radio" name="logo_animated" value="" disabled  >
                                <label for="logo_animated4"> 左右渐变 </label>
                            </div>

                        </div>
                    </div>

                    <div class="form-group ">
                        <div class="row">
                            <div class="col-md-6">
                                <label>全局背景色</label>
                                <div class="input-group m-b">
                                    <span class="input-group-addon" style="background: {{cacheGlobalSettingsByKey("global_bgcolor")}} ">
                                        &nbsp;&nbsp;&nbsp;
                                    </span>
                                    <input type="text" name="global_bgcolor" value="{{cacheGlobalSettingsByKey("global_bgcolor")}}" class="form-control">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label>全局字体</label>
                                <div class="input-group m-b">
                                    <input type="text" name="global_font" value="{{cacheGlobalSettingsByKey("global_font")}}" class="form-control">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-6 mt-20">
                                <label>
                                    顶部代码（代码会放在 head 标签内）
                                </label>
                                <textarea style="height: 150px;" placeholder="顶部代码" name="head_codes" class="form-control"
                                          rows="4">{{cacheGlobalSettingsByKey('head_codes')}}</textarea>
                            </div>

                            <div class="col-md-6 mt-20">
                                <label>
                                    底部代码（代码会放在 body 标签底部）
                                </label>
                                <textarea style="height: 150px;" placeholder="底部代码" name="foot_codes" class="form-control"
                                          rows="4">{{cacheGlobalSettingsByKey('foot_codes')}}</textarea>
                            </div>
                        </div>
                    </div>


                    <div class="form-group ">
                        <div class="row">
                            <div class="col-md-6">
                                <label>顶部导航条背景色</label>
                                <div class="input-group m-b">
                                    <span class="input-group-addon" style="background: {{cacheGlobalSettingsByKey("nav_bgcolor")}} ">
                                        &nbsp;&nbsp;&nbsp;
                                    </span>
                                    <input type="text" name="nav_bgcolor" value="{{cacheGlobalSettingsByKey("nav_bgcolor")}}" class="form-control">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label>顶部导航条文字颜色</label>
                                <div class="input-group m-b">
                                    <span class="input-group-addon" style="background: {{cacheGlobalSettingsByKey("nav_color")}} ">
                                        &nbsp;&nbsp;&nbsp;
                                    </span>
                                    <input type="text" name="nav_color" value="{{cacheGlobalSettingsByKey("nav_color")}}" class="form-control">
                                </div>
                            </div>
                        </div>

                    </div>

                    <div class="form-group ">
                        <label>顶部导航条效果</label>

                        <div class="form-inline">
                            <div class="radio radio-inline radio-success">
                                <input id="nav_position" type="radio" name="nav_position" value="static" @if(cacheGlobalSettingsByKey("nav_position")=="static") checked @endif >
                                <label for="nav_position"> 随页面滚动 </label>
                            </div>
                            <div class="radio radio-inline radio-success">
                                <input id="nav_position2" type="radio" name="nav_position" value="sticky"  @if(cacheGlobalSettingsByKey("nav_position")=="sticky") checked @endif >
                                <label for="nav_position2"> sticky在顶部 </label>
                            </div>


                        </div>
                    </div>

                    <div class="form-group ">
                        <label>页面加载动画</label>

                        <div class="form-inline">
                            <div class="radio radio-inline radio-success">
                                <input id="preloader" type="radio" name="preloader" value="off" @if(cacheGlobalSettingsByKey("preloader")=="off") checked @endif >
                                <label for="preloader"> 关闭 </label>
                            </div>
                            <div class="radio radio-inline radio-success">
                                <input id="preloader2" type="radio" name="preloader" value="on"  @if(cacheGlobalSettingsByKey("preloader")=="on") checked @endif >
                                <label for="preloader2"> 开启 </label>
                            </div>


                        </div>
                    </div>

                    <div class="form-group ">
                        <label>页面宽度</label>

                        <div class="form-inline">
                            <div class="radio radio-inline radio-success">
                                <input id="page_width" type="radio" name="page_width" value="container" @if(cacheGlobalSettingsByKey("page_width")=="container") checked @endif >
                                <label for="page_width"> 窄屏 </label>
                            </div>
                            <div class="radio radio-inline radio-success">
                                <input id="page_width2" type="radio" name="page_width" value="pull-container"  @if(cacheGlobalSettingsByKey("page_width")=="pull-container") checked @endif >
                                <label for="page_width2"> 全屏 </label>
                            </div>


                        </div>
                    </div>

                    <div class="form-group ">
                        <h4>首页大屏</h4>
                    </div>

                    <div class="form-group ">

                        <div class="form-inline">
                            <div class="radio radio-inline radio-success">
                                <input id="home_screen" type="radio" name="home_screen" value="off" @if(cacheGlobalSettingsByKey("home_screen")=="off") checked @endif >
                                <label for="home_screen"> 关闭 </label>
                            </div>
                            <div class="radio radio-inline radio-success">
                                <input id="home_screen2" type="radio" name="home_screen" value="on" @if(cacheGlobalSettingsByKey("home_screen")=="on") checked @endif  >
                                <label for="home_screen2"> 开启 </label>
                            </div>


                        </div>
                    </div>

                    <div class="form-group">
                        <label>大屏背景图片</label>
                        <div class="fileinput-new" data-provides="fileinput">
                            <div class="fileinput-preview" data-trigger="fileinput" style="width: 200px; height:200px;">
                                @if(cacheGlobalSettingsByKey('home_screen_image'))
                                    <img src="{{GetLocalFileByPath(cacheGlobalSettingsByKey('home_screen_image'))}}" style="width: 180px; height:180px;">
                                @endif
                            </div>
                            <span class="btn btn-success  btn-file">
                                                        <span class="fileinput-new">{{getTranslateByKey("common_select")}}</span>
                                                        <span class="fileinput-exists">{{getTranslateByKey("common_change")}}</span>
                                                        <input type="file" id="image" name="home_screen_image">
                                                    </span>
                            <a href="#" class="btn btn-danger fileinput-exists" data-dismiss="fileinput">{{getTranslateByKey("common_delete")}}</a>
                        </div>
                    </div>



                    <div class="form-group ">
                        <label>大屏区域源码(模板参考跳转入口)</label>
                        <textarea name="home_screen_code" style="height: 400px;" class="form-control ">{{cacheGlobalSettingsByKey('home_screen_code')}}</textarea>
                    </div>




                    <button type="button" id="diy_button" class="btn btn-primary margin-l-5 mx-sm-3">保存</button>
                    {{--<button type="button" id="ton" onclick="history.go(-1);" class="btn btn-default ">{{getTranslateByKey("common_back")}}</button>--}}

                </form>

            </div>
        </div>
    </div>
    @include('admin.public.footer')
</section>
@include('admin.public.js',['load'=> ["custom"]])
<script>

    $(function () {

        $("#diy_button").click(function () {

            popup({type:'load',msg:"正在请求",delay:800,callBack:function(){
                    $.ajax({
                        "method":"post",
                        "url":"{{url('admin/theme/diy?m=').$_GET['m']}}",
                        "data":  new FormData($('#diy_form')[0]),                                  //$("#userinfo_form").serialize(),
                        "dataType":'json',
                        "cache":false,
                        "processData": false,
                        "contentType": false,
                        "success":function (res) {
                            if(res.status==200){
                                popup({type:"success",msg:res.msg,delay:2000});
                                setTimeout(function () {
                                    location.reload()
                                },2000)
                            }else{
                                popup({type:"error",msg:res.msg,delay:2000});
                            }
                        },
                        "error":function (res) {
                            console.log(res);
                        }
                    })
                }});
        })

    })

</script>
</body>

</html>
