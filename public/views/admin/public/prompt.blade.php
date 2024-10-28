@include("admin.public.header")

<body class="horizontal">

<div class="row page-header center">
    <div class="col-lg-12 ">
        <div class="wrapper-page">
            <div class="panel panel-color {{ $data['status']?'panel-inverse':'panel-danger' }}">

                <div class="panel-heading">
                    <h3 class="text-center m-t-10">{{ $data['message'] }}</h3>
                </div>

                <div class="panel-body">
                    <div class="text-center">
                        <div class="alert {{ $data['status']?'alert-info':'alert-danger' }} alert-dismissable">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                            浏览器页面将在<b id="loginTime">{{ $data['jumpTime'] }}</b>秒后跳转......
                        </div>
                        <div class="form-group m-b-0">
                            <div class="input-group">
                                {{--<input type="password" class="form-control" placeholder="Enter Email">--}}
                                <span class="input-group-btn"> <button type="button" class="btn h-btn {{ $data['status']?'btn-success':'btn-danger' }}">点击立即跳转</button> </span>

                                <span class="input-group-btn" style="margin-left: 30px"> <a href="{{url('admin/logout')}}"><button type="button" class="btn btn-danger">退出</button></a> </span>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>

</div>

<!-- Common Plugins -->
@include('admin.public.js',['load'=> [""]])


</body>
</html>


<script type="text/javascript">
    $(function(){
        //循环倒计时，并跳转
        var url = "{{ $data['url'] }}";
        var loginTime = parseInt($('#loginTime').text());
        console.log(loginTime);
        var time = setInterval(function(){
            loginTime = loginTime-1;
            $('#loginTime').text(loginTime);
            if(loginTime==0){
                clearInterval(time);
                window.location.href=url;
            }
        },1000);
    })
    //点击跳转
    $('.h-btnw').click(function () {
         url = "{{ $data['url'] }}";
        window.location.href=url;
    })
</script>
