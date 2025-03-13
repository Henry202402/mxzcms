@include(moduleAdminTemplate($moduleName)."public.header")
<style>
    #birthday {
        cursor: pointer;
        background-color: #fff;
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
         ['breadcrumb'=>['用户管理','用户详情']])
            </div>
            <!-- /page header -->


            <!-- Content area -->
            <div class="content" style="margin-top: 1rem;">


                <div class="panel panel-flat">
                    <div class="panel-heading">

                        <form class="form-horizontal" method="post" enctype="multipart/form-data">
                            {{csrf_field()}}
                            <fieldset class="content-group">
                                <legend class="text-bold">列表</legend>
                                <div class="form-group ">
                                    <label class="control-label col-lg-1">区号</label>
                                    <div class="col-lg-11">
                                        <select name="c_code" class="form-control">
                                            @foreach(getPhoneCode() as $key=>$c)
                                                <option value="{{$key}}" @if($key==$data['c_code']) selected @endif>{{$c}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-lg-1 control-label">
                                        手机号
                                    </label>
                                    <div class="col-lg-11">
                                        <input type="text" class="form-control" name="phone"
                                               placeholder="手机号" value="{{$data['phone']}}">
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-lg-1 control-label">
                                        用户名
                                    </label>
                                    <div class="col-lg-11">
                                        <input type="text" id="username" name="username" class="form-control"
                                               placeholder="用户名" value="{{$data['username']}}" required>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-lg-1 control-label">
                                        昵称
                                    </label>
                                    <div class="col-lg-11">
                                        <input type="text" id="nickname" name="nickname" class="form-control"
                                               placeholder="昵称" value="{{$data['nickname']}}">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-lg-1 control-label">
                                        密码
                                    </label>
                                    <div class="col-lg-11">
                                        <input type="text" name="password" class="form-control"
                                               placeholder="不填则不修改">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-lg-1">性别</label>
                                    <div class="col-lg-11">

                                        <label class="radio-inline">
                                            <input type="radio" name="male" class="styled h-radio" value="男"
                                                   @if($data['male']=='男' || !$data['male']) checked @endif>
                                            <span class="h-span-val">男</span>
                                        </label>

                                        <label class="radio-inline">
                                            <input type="radio" name="male" class="styled h-radio" value="女"
                                                   @if($data['male']=='女') checked @endif>
                                            <span class="h-span-val">女</span>
                                        </label>

                                        <label class="radio-inline">
                                            <input type="radio" name="male" class="styled h-radio" value="保密"
                                                   @if($data['male']=='保密') checked @endif>
                                            <span class="h-span-val">保密</span>
                                        </label>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-lg-1 control-label">
                                        生日
                                    </label>
                                    <div class="col-lg-11">
                                        <input type="text" id="birthday" name="birthday" class="form-control"
                                               placeholder="生日" value="{{$data['birthday']}}" readonly>
                                    </div>
                                </div>
                                <div class="form-group local_url">
                                    <label class="control-label col-lg-1">头像</label>
                                    <div class="fileinput-new-div col-lg-11" data-provides="fileinput">
                                        <div class="fileinput-preview" data-trigger="fileinput"
                                             style="width: 100px; height:100px;">
                                            <img id="addImg" class="img-fluid " style="height: 95px;"
                                                 src="{{GetUrlByPath($data['avatar'])}}"/>
                                        </div>
                                        <span class="btn btn-primary  btn-file">
                                    <span class="fileinput-new">选择</span>
                                    <span class="fileinput-exists">更换</span>
                                    <input type="file" id="images" name="avatar"
                                           onchange="showFile('images','addImg')"></span>
                                        <a href="#" onclick="deleteImg('addImg')"
                                           class="btn btn-danger fileinput-exists"
                                           data-dismiss="fileinput">删除</a>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-lg-1">状态</label>
                                    <div class="col-lg-11">

                                        <label class="radio-inline">
                                            <input type="radio" name="status" class="styled h-radio" value="1"
                                                   @if($data['status']==1) checked @endif>
                                            <span class="h-span-val">启用</span>
                                        </label>

                                        <label class="radio-inline">
                                            <input type="radio" name="status" class="styled h-radio" value="0"
                                                   @if($data['status']==0) checked @endif>
                                            <span class="h-span-val">禁用</span>
                                        </label>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <input type="hidden" name="uid" value="{{$data['uid']}}">
                                    <label class="col-lg-1 control-label"></label>
                                    <div class="col-lg-11">
                                        <button type="submit" class="btn btn-sm btn-info">
                                            提交
                                        </button>
                                        <button type="button" class="btn btn-sm btn-danger"
                                                onclick="history.go(-1)">
                                            返回
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
<script type="text/javascript" src="{{asset('assets/laydate/laydate.js')}}"></script>
<script>
    //常规用法
    laydate.render({
        elem: '#birthday',
        trigger: 'click'
    });
</script>
</body>
</html>