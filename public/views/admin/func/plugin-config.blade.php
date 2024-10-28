@include("admin.public.header")
<body class="horizontal">
@include("admin.public.topbar")
@include("admin.public.nav")
<style type="text/css">
    .display-none {
        display: none;
    }
    .onlineModuleList {
        cursor: pointer;
    }
    .cursor{
        cursor: pointer;
    }
    .mokuai .row div.col-md-3 {
        height: 340px;
        margin: 2rem 0;
    }
    .box {
        box-shadow: 0 0 8px #ccc;
        border-radius: 15px;
    }
    .box img {
        width: 101%;
        height: 215px;
    }
    .clearfix:after {
        visibility: hidden;
        clear: both;
        display: block;
        content: ".";
        height: 0;
    }
    .clearfix {
        *zoom: 1;
    }

</style>
<div class="row page-header">
    <div class="col-lg-6 align-self-center ">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{url('admin/index')}}">{{getTranslateByKey("common_home_page")}}</a></li>
            <li class="breadcrumb-item"><a href="{{url('admin/plugin')}}">插件管理</a></li>
            <li class="breadcrumb-item active">插件配置</li>
        </ol>
    </div>
</div>

<section class="container-fluid pl-5 pr-5 ">

    <div class="row pb-5">
        <div class="col-12 bg-light" style="min-height: calc(100vh - 250px)">
            <div class="">
                <div class="card-header card-default">
                    <div class="row">
                        <div class="col-md-12 col-12">
                            {{$pageData['subtitle']}}
                        </div>
                    </div>
                </div>
                <div class="card-body">

                    <form class="w-100" action="{{$pageData['formaction']}}"
                          method="{{$pageData['method']}}"
                          id="{{$pageData['formid']}}"
                          enctype="multipart/form-data">
                        @foreach($pageData['fields'] as $f)
                            @include(moduleAdminTemplate('formtools')."formtooltemplates.".$f['formtype'],compact( 'f'))
                        @endforeach
                        <div class="form-group">
                            <label class="col-lg-1 control-label"></label>
                            <div class="col-lg-11">
                                <button type="submit" class="btn btn-sm btn-info" id="post_button" style="cursor: pointer;">
                                    {{$pageData['actionName']}}
                                </button>
                                <a href="{{url("admin/plugin")}}" type="button" class="btn btn-sm btn-danger" >
                                    {{$pageData['backName']}}
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @include('admin.public.footer')

</section>

@include('admin.public.js',['load'=> ["custom"]])

</body>
</html>
