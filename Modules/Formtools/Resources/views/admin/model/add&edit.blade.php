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
                <div class="panel panel-flat">
                    <div class="panel-heading">

                        <form class="form-horizontal" action="{{url("admin/".$moduleName."/model?"."action=Submit&moduleName={$pageData['moduleName']}&model=".$pageData['model']."&page=".$pageData['page'])}}" method="post" id="post_form" enctype="multipart/form-data">
                            {{csrf_field()}}
                            <fieldset class="content-group">
                                <legend class="text-bold">{{$pageData['title']}}</legend>
                                @foreach($pageData['fields'] as $f)
                                    @include(moduleAdminTemplate("formtools")."formtooltemplates.".$f['formtype'],compact( 'f'))
                                @endforeach

                                <div class="form-group">
                                    <label class="col-lg-1 control-label"></label>
                                    <div class="col-lg-11">
                                        <button type="submit" class="btn btn-sm btn-info" id="post_button">
                                            提交
                                        </button>
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
        src="{{moduleAdminResource($moduleName)}}/js/plugins/forms/selects/select2.min.js"></script>
<script type="text/javascript" src="{{moduleAdminResource($moduleName)}}/js/pages/form_select2.js"></script>
</body>
</html>
