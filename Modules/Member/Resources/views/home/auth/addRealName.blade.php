@include("member::home.public.head")
<style>
    .front .img, .behind .img {
        width: 100%;
        height: 100%;
        display: flex;
        justify-content: center;
        align-items: center;
        cursor: pointer;
    }

    .front .img img, .behind .img img {
        width: 250px;
        height: 158px;
    }

    .hide {
        display: none !important
    }

    .show {
        display: block !important
    }

    .center {
        text-align: center !important
    }
</style>
<body data-layout="detached" data-topbar="colored">

<!-- <body data-layout="horizontal" data-topbar="dark"> -->

<div class="container-fluid">
    <!-- Begin page -->
    <div id="layout-wrapper">

    @include("member::home.public.header")

    <!-- ========== Left Sidebar Start ========== -->
    @include("member::home.public.leftnav")
    <!-- Left Sidebar End -->

        <!-- ============================================================== -->
        <!-- Start right Content here -->
        <!-- ============================================================== -->
        <div class="main-content">

            <div class="page-content">

                <!-- start page title -->
            @include("member::home.public.topnav")
            <!-- start page title -->


                <div class="checkout-tabs">
                    <div class="row">
                        <div class="col-lg-2">
                            <div class="nav flex-column nav-pills" id="v-pills-tab" role="tablist"
                                 aria-orientation="vertical">
                                @if($_GET['type']==1)
                                    <a class="nav-link active" id="v-pills-gen-ques-tab" data-bs-toggle="pill"
                                       href="#v-pills-gen-ques" role="tab" aria-controls="v-pills-gen-ques"
                                       aria-selected="true">
                                        <i class="fas fa-user d-block check-nav-icon mt-4 mb-2"></i>
                                        <p class="fw-bold mb-4">个人认证</p>
                                    </a>
                                @elseif($_GET['type']==2)
                                    <a class="nav-link active" id="v-pills-privacy-tab" data-bs-toggle="pill"
                                       href="#v-pills-privacy" role="tab" aria-controls="v-pills-privacy"
                                       aria-selected="false">
                                        <i class="fas fa-users d-block check-nav-icon mt-4 mb-2"></i>
                                        <p class="fw-bold mb-4">企业认证</p>
                                    </a>
                                @endif
                            </div>
                        </div>
                        <div class="col-lg-10">
                            <div class="card">
                                <div class="card-body">
                                    <div class="tab-content" id="v-pills-tabContent">
                                        <form id="myForm" enctype="multipart/form-data" method="post">
                                            @if($_GET['type']==1)
                                                <div class="tab-pane fade show active" id="v-pills-gen-ques"
                                                     role="tabpanel"
                                                     aria-labelledby="v-pills-gen-ques-tab">
                                                    <input type="hidden" name="type" value="1">
                                                    <div class="mb-3">
                                                        <label class="form-label">真实姓名</label>
                                                        <input type="text" class="form-control" name="real_name"
                                                               placeholder="真实姓名">
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label">身份证号码</label>
                                                        <input type="text" class="form-control" name="id_card"
                                                               placeholder="身份证号码">
                                                    </div>

                                                    <div class="row mt-5">
                                                        <div class="col-md-6">

                                                            <div class="front" onclick="selectFile('file1')">
                                                                <input type="file" class="hide" id="file1"
                                                                       name="id_card_positive_img"
                                                                       onchange="showFile('file1','file1Src')">
                                                                <div class="img">
                                                                    <img id="file1Src"
                                                                         src="{{moduleHomeResource($moduleName,'home/assets/img/idcard-front.png')}}"
                                                                         alt="">
                                                                </div>
                                                                <p class="center">拍摄人像面</p>
                                                            </div>

                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="behind" onclick="selectFile('file2')">
                                                                <input type="file" class="hide" id="file2"
                                                                       name="id_card_back_img"
                                                                       onchange="showFile('file2','file2Src')">
                                                                <div class="img">
                                                                    <img id="file2Src"
                                                                         src="{{moduleHomeResource($moduleName,'home/assets/img/idcard-behind.png')}}"
                                                                         alt="">
                                                                </div>
                                                                <p class="center">拍摄国徽面</p>
                                                            </div>
                                                        </div>
                                                    </div>

                                                </div>

                                            @elseif($_GET['type']==2)
                                                <div class="tab-pane fade show active" id="v-pills-privacy"
                                                     role="tabpanel"
                                                     aria-labelledby="v-pills-privacy-tab">
                                                    <input type="hidden" name="type" value="2">

                                                    <div class="mb-3">
                                                        <label class="form-label">企业名称</label>
                                                        <input type="text" class="form-control" name="company_name"
                                                               value="{{$auth['company_name']?:''}}"
                                                               placeholder="企业名称">
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label">统一社会信用代码</label>
                                                        <input type="text" class="form-control" name="unified_social_credit_code"
                                                               value="{{$auth['unified_social_credit_code']?:''}}"
                                                               placeholder="统一社会信用代码">
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label">法人名称</label>
                                                        <input type="text" class="form-control" name="legal_person"
                                                               value="{{$auth['legal_person']?:''}}"
                                                               placeholder="法人名称">
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label">法人名称身份证号</label>
                                                        <input type="text" class="form-control" name="legal_id_card"
                                                               value="{{$auth['legal_id_card']?:''}}"
                                                               placeholder="法人名称身份证号">
                                                    </div>

                                                    <div class="row mt-5">
                                                        <div class="col-md-12">

                                                            <div class="front" onclick="selectFile('file1')">
                                                                <input type="file" class="hide" id="file1"
                                                                       name="business_license_img"
                                                                       onchange="showFile('file1','file1Src')">
                                                                <div class="img">
                                                                    <img id="file1Src"
                                                                         src="{{moduleHomeResource($moduleName,'home/assets/img/idcard-behind.png')}}"
                                                                         alt="">
                                                                </div>
                                                                <p class="center">拍摄营业执照</p>
                                                            </div>

                                                        </div>

                                                    </div>
                                                </div>
                                            @endif

                                            <div class="mb-3 mt-5 center">
                                                {{csrf_field()}}
                                                <button type="button"
                                                        class="btn btn-info col-md-2 h-sub">
                                                    提交
                                                </button>
                                                <button type="button"
                                                        class="btn btn-danger col-md-2"
                                                        onclick="history.go(-1)">返回
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- end row -->

            </div>
            <!-- End Page-content -->

            @include("member::home.public.footer")
        </div>
        <!-- end main content-->

    </div>
    <!-- END layout-wrapper -->

</div>
<!-- end container-fluid -->

<!-- Right bar overlay-->
<div class="rightbar-overlay"></div>
<div id="qrcode" class="h-package-qrcode" style="padding: 20px;display: none"></div>
@include("member::home.public.js")
<script src="{{moduleHomeResource($moduleName,'home/assets/js/user.js')}}"></script>
<script src="{{moduleHomeResource($moduleName,'home/assets/js/jquery.qrcode.min.js')}}"></script>
<script>
    //显示图片
    function showFile(file_id, img_id) {
        var file = document.getElementById(file_id).files[0];
        if (file) {
            var reader = new FileReader();
            reader.onload = function (event) {
                var src_text = event.target.result;
                document.getElementById(img_id).src = src_text;
            };
        } else {
            document.getElementById(img_id).src = '';
        }
        reader.readAsDataURL(file);
    }

    function selectFile(file_id) {
        document.getElementById(file_id).click();
    }

    $('.h-sub').click(function () {
        layer.load(1);
        $.ajax({
            "type": "POST",
            "method": "POST",
            "url": "{{url('member/addRealName')}}",
            "data": new FormData($('#myForm')[0]),
            "dataType": 'json',
            "cache": false,
            "processData": false,
            "contentType": false,
            "success": function (res) {
                layer.closeAll();
                if (res.status == 200) {
                    layer.msg(res.msg, {icon: 1, time: 500}, function () {
                        window.location.href = "{{url('member/myRealName')}}";
                    });
                } else {
                    layer.msg(res.msg, {icon: 2, time: 2000});
                }
            },
            "error": function (res) {
                layer.closeAll();
                console.log(res);
            }
        })
    });
</script>
</body>

</html>
