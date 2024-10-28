<!-- Page header -->
<div class="breadcrumb-line">
    <ul class="breadcrumb">
        <li>
            <a href="{{moduleAdminJump($moduleName,'index')}}">
                <i class="icon-home2 position-left"></i>
                首页
            </a>
        </li>
        @foreach($breadcrumb as $b)
            <li>{{$b}}</li>
        @endforeach
    </ul>
</div>
<!-- /page header -->