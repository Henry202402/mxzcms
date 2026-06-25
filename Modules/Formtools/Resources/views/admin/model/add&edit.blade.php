@include(moduleAdminTemplate($moduleName)."public.header")
<!-- ============================================================== -->
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

            <!-- Content area -->
            <div class="content">
                @include(moduleAdminTemplate($moduleName)."public.crumb",['breadcrumb'=>[$pageData['title'],$pageData['subtitle']]])
                <div class="alert alert-info alert-styled-left">
                    <span>当前内容会按模型字段渲染，详情 SEO 会优先使用这里填写的内容级 SEO；若留空，则回退到模型级 SEO 与默认字段。</span>
                    @if(!empty($pageData['access_identification']))
                        <br>
                        <span>前台列表：</span><code>{{url("list/".$pageData['access_identification'])}}</code>
                        <span style="margin-left: 12px;">前台详情：</span><code>{{url("detail/".$pageData['access_identification']."/{id}")}}</code>
                    @endif
                </div>
                <div class="panel panel-flat">
                    <div class="panel-heading">

                        <form class="form-horizontal" action="{{url("admin/".$moduleName."/model?"."action=Submit&moduleName={$pageData['moduleName']}&model=".$pageData['model']."&page=".$pageData['page'])}}" method="post" id="post_form" enctype="multipart/form-data">
                            {{csrf_field()}}
                            <fieldset class="content-group">
                                <legend class="text-bold">{{$pageData['title']}}</legend>
                                @foreach($pageData['fields'] as $f)
                                    @include(moduleAdminTemplate("formtools")."formtooltemplates.".$f['formtype'],compact( 'f'))
                                @endforeach

                                <div class="form-group row">
                                    <label class="col-lg-1 control-label">
                                        审核状态
                                    </label>
                                    <div class="col-lg-11">
                                        <label class="radio-inline">
                                            <input type="radio" class="styled"
                                                   name="status"
                                                   value=1
                                                   @if($pageData['status']==1) checked @endif
                                            > 通过
                                        </label>
                                        <label class="radio-inline">
                                            <input type="radio" class="styled"
                                                   name="status"
                                                   value=0
                                                   @if($pageData['status']==0) checked @endif
                                            > 不通过
                                        </label>
                                        <label class="radio-inline">
                                            <input type="radio" class="styled"
                                                   name="status"
                                                   value=2
                                                   @if($pageData['status']==2) checked @endif
                                            > 下架
                                        </label>
                                        <span class="help-block">审核状态</span>
                                    </div>

                                </div>

                                <div class="form-group row">
                                    <label class="col-lg-1 control-label">
                                        审核备注
                                    </label>
                                    <div class="col-lg-11">
                                        <input type="text" id="remark"
                                               name="remark"
                                               class="form-control"
                                               placeholder="请输入审核备注"
                                               value="{{$pageData['remark']}}"
                                        >
                                        <span class="help-block">审核备注</span>
                                    </div>

                                </div>

                                <div class="form-group row">
                                    <label class="col-lg-1 control-label">
                                        SEO标题
                                    </label>
                                    <div class="col-lg-11">
                                        <input type="text" id="seo_title"
                                               name="seo_title"
                                               class="form-control"
                                               placeholder="请输入SEO标题"
                                               value="{{$pageData['seo_title']}}"
                                        >

                                        <span class="help-block">不填写将自动使用 name 或者 title</span>

                                    </div>

                                </div>

                                <div class="form-group row">
                                    <label class="col-lg-1 control-label">
                                        SEO关键词
                                    </label>
                                    <div class="col-lg-11">
                                        <input type="text" id="seo_keywords"
                                               name="seo_keywords"
                                               class="form-control"
                                               placeholder="请输入SEO关键词"
                                               value="{{$pageData['seo_keywords']}}"
                                        >

                                        <span class="help-block">不填写将自动使用通配SEO</span>

                                    </div>

                                </div>

                                <div class="form-group row">
                                    <label class="col-lg-1 control-label">
                                        SEO描述
                                    </label>
                                    <div class="col-lg-11">
                                        <textarea name="seo_description"
                                                  id="seo_description" cols="30" rows="4"
                                                  placeholder="请输入SEO描述"
                                                  class="form-control">{{$pageData['seo_description']}}</textarea>
                                        <span class="help-block">不填写将自动使用通配SEO</span>
                                    </div>

                                </div>

                                <div class="form-group">
                                    <label class="col-lg-1 control-label"></label>
                                    <div class="col-lg-11">
                                        <button type="submit" class="btn btn-sm btn-info" id="post_button">
                                            提交
                                        </button>
                                        @if(!empty($pageData['access_identification']))
                                            <a href="{{url("list/".$pageData['access_identification'])}}" target="_blank" type="button" class="btn btn-sm btn-default">
                                                前台预览
                                            </a>
                                        @endif
                                        <a href="{{url("admin/".$moduleName."/model?moduleName={$pageData['moduleName']}&action=List&model=".$pageData['model']."&page=".$pageData['page'])}}" type="button" class="btn btn-sm btn-danger" >
                                            返回
                                        </a>
                                    </div>
                                </div>
                            </fieldset>

                        </form>
                    </div>
                </div>


                @include(moduleAdminTemplate($moduleName)."public.footer")


            </div>
            <!-- /content area -->

        </div>
        <!-- /main content -->

    </div>
    <!-- /page content -->

</div>
<!-- /page container -->

<!-- 						Content End		 						-->
<!-- ============================================================== -->
@include(moduleAdminTemplate($moduleName)."public.js")

{{--必须放在最后--}}
<script type="text/javascript"
        src="{{asset("assets/module")}}/js/plugins/forms/selects/select2.min.js"></script>
<script type="text/javascript" src="{{asset("assets/module")}}/js/pages/form_select2.js"></script>
</body>
</html>
