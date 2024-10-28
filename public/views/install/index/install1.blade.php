<!doctype html>
<html>
<head>
    @include("install.head")
</head>
<body>
<div class="container">
    @include("install.header")
    <div class="row">
        <div class="col-md-12 col-sm-12 m-auto">
            <div class="card mt-1">
                <div class="card-header">
                    <h6 class="text-center">{{$cms_name}}《使用协议》</h6>
                </div>
                <div class="card-body p-2">
                    <div class="card-text overflow-auto text-secondary small" style="height: 65vh;overflow: auto">
                        {!! file_get_contents("https://www.mxzcloud.com/api/cloud/getAgrees")?:"<a href='https://www.mxzcloud.com/api/cloud/getAgrees'>在线查看使用协议</a>" !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class=" col-12 text-center mt-5">
            <a href="{{url('/install?install=2')}}" class="btn btn-primary">同意接受本协议</a>
        </div>
    </div>
</div>
@include("install.footer")
</body>
</html>
