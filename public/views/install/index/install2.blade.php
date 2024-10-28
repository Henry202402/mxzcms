<!doctype html>
<html>
<head>
    @include("install.head")
    <style>
        .rewrite-correct, .rewrite-error {
            display: none;
        }
    </style>
</head>
<body>
@include("install.header")
<section class="container pb-5">
   <div class="row">
       <div class="col-md-12 col-sm-12">
           <div class="step mt-3">
               <nav aria-label="breadcrumb">
                   <ol class="breadcrumb">
                       <li class="breadcrumb-item active"><em>1</em> 检测环境</li>
                       <li class="breadcrumb-item"><em>2</em> 创建数据</li>
                       <li class="breadcrumb-item" aria-current="page"><em>3</em> 完成安装</li>
                   </ol>
               </nav>
           </div>
           <div class="server">
               <table class="table small">
                   <tr>
                       <td class="font-weight-bolder">环境检测</td>
                       <td class="font-weight-bolder">推荐配置</td>
                       <td class="font-weight-bolder">当前状态</td>
                       <td class="font-weight-bolder">最低要求</td>
                   </tr>
                   <tr>
                       <td>操作系统</td>
                       <td>类UNIX</td>
                       <td><i class="fa fa-check correct"></i> {{$data['os']}}</td>
                       <td>不限制</td>
                   </tr>
                   <tr>
                       <td>PHP版本</td>
                       <td>>=8.0.x</td>
                       <td>
                           {!! $data['phpversion_msg'] !!}
                       </td>
                       <td>8.0.x</td>
                   </tr>
                   <!-- 模块检测 -->
                   <tr>
                       <td class="td1" colspan="4">
                           模块检测
                       </td>
                   </tr>
                   <tr>
                       <td>session</td>
                       <td>开启</td>
                       <td>
                           {!! $data['session'] !!}
                       </td>
                       <td>开启</td>
                   </tr>
                   <tr>
                       <td>
                           PDO
                           <a href="https://www.baidu.com/s?wd=开启PDO,PDO_MYSQL扩展" target="_blank">
                               <i class="fa fa-question-circle question"></i>
                           </a>
                       </td>
                       <td>开启</td>
                       <td>
                           {!! $data['pdo'] !!}
                       </td>
                       <td>开启</td>
                   </tr>
                   <tr>
                       <td>
                           PDO_MySQL
                           <a href="https://www.baidu.com/s?wd=开启PDO,PDO_MYSQL扩展" target="_blank">
                               <i class="fa fa-question-circle question"></i>
                           </a>
                       </td>
                       <td>开启</td>
                       <td>
                           {!! $data['pdo_mysql'] !!}
                       </td>
                       <td>开启</td>
                   </tr>
                   <tr>
                       <td>
                           CURL
                           <a href="https://www.baidu.com/s?wd=开启PHP CURL扩展" target="_blank">
                               <i class="fa fa-question-circle question"></i>
                           </a>
                       </td>
                       <td>开启</td>
                       <td>
                           {!! $data['curl'] !!}
                       </td>
                       <td>开启</td>
                   </tr>
                   <tr>
                       <td>
                           GD
                           <a href="https://www.baidu.com/s?wd=开启PHP GD扩展" target="_blank">
                               <i class="fa fa-question-circle question"></i>
                           </a>
                       </td>
                       <td>开启</td>
                       <td>
                           {!! $data['gd'] !!}
                       </td>
                       <td>开启</td>
                   </tr>
                   <tr>
                       <td>
                           MBstring
                           <a href="https://www.baidu.com/s?wd=开启PHP MBstring扩展" target="_blank">
                               <i class="fa fa-question-circle question"></i>
                           </a>
                       </td>
                       <td>开启</td>
                       <td>
                           {!! $data['mbstring'] !!}
                       </td>
                       <td>开启</td>
                   </tr>
                   <tr>
                       <td>
                           fileinfo
                           <a href="https://www.baidu.com/s?wd=开启PHP fileinfo扩展" target="_blank">
                               <i class="fa fa-question-circle question"></i>
                           </a>
                       </td>
                       <td>开启</td>
                       <td>
                           {!! $data['fileinfo'] !!}
                       </td>
                       <td>开启</td>
                   </tr>


{{--                   @if(!empty($data['show_always_populate_raw_post_data_tip']))--}}
{{--                       <tr>--}}
{{--                           <td>--}}
{{--                               $HTTP_RAW_POST_DATA关闭检测--}}
{{--                               <a href="https://www.baidu.com/s?wd=开启PHP fileinfo扩展" target="_blank">--}}
{{--                                   <i class="fa fa-question-circle question"></i>--}}
{{--                               </a>--}}
{{--                           </td>--}}
{{--                           <td>关闭</td>--}}
{{--                           <td>--}}
{{--                               {!! $data['always_populate_raw_post_data'] !!}--}}
{{--                           </td>--}}
{{--                           <td>关闭</td>--}}
{{--                       </tr>--}}
{{--                       <tr>--}}
{{--                           <td>$HTTP_RAW_POST_DATA未关闭解决</td>--}}
{{--                           <td colspan="3" class="text-left">--}}
{{--                               ;php.ini 找到 always_populate_raw_post_data设置如下:<br />--}}
{{--                               always_populate_raw_post_data = -1--}}
{{--                           </td>--}}
{{--                       </tr>--}}
{{--               @endif--}}
               <!-- rewrite检测 -->
                   <tr>
                       <td class="font-weight-bolder" colspan="4">
                           rewrite检测（开启rewrite更利于网站SEO优化）
                       </td>
                   </tr>
                   <tr>
                       <td>
                           服务器rewrite
                           <a href="#" target="_blank">
                               <i class="fa fa-question-circle question"></i>
                           </a>
                       </td>
                       <td>开启</td>
                       <td>
                           <span class="rewrite-checking">正在检测...</span>
                           <span class="rewrite-correct"><i class="fa fa-check correct"></i> 支持</span>
                           <span class="rewrite-error"><i class="fa fa-remove error"></i> 不支持</span>
                       </td>
                       <td>开启</td>
                   </tr>
                   <!-- 大小限制检测 -->
                   <tr>
                       <td class="td1" colspan="4">
                           大小限制检测
                       </td>
                   </tr>
                   <tr>
                       <td>附件上传</td>
                       <td>>2M</td>
                       <td>
                           {!! $data['upload_size'] !!}
                       </td>
                       <td>不限制</td>
                   </tr>
               </table>
               <table class="table small">
                   <tr>
                       <td class="font-weight-bolder">目录、文件权限检查</td>
                       <td class="font-weight-bolder">写入</td>
                       <td class="font-weight-bolder">读取</td>
                   </tr>
                   @if($data['folders'])
                       @foreach($data['folders'] as $dir=>$vo)
                           <tr>
                               <td>
                                   {{$dir}}
                               </td>
                               <td>
                                   @if($vo['w'])
                                       <i class="fa fa-check correct"></i> 可写
                                   @else
                                       <i class="fa fa-remove error"></i> 不可写
                                   @endif
                               </td>
                               <td>
                                   @if($vo['r'])
                                       <i class="fa fa-check correct"></i> 可读
                                   @else
                                       <i class="fa fa-remove error"></i> 不可读
                                   @endif
                               </td>
                           </tr>
                       @endforeach
                   @endif
               </table>
           </div>
           <div class="bottom text-center">
               <a href="{{url('/install?install=2')}}" class="btn btn-primary">重新检测</a>
               <a href="{{url('/install?install=3')}}" class="btn btn-primary">下一步</a>
           </div>
       </div>
   </div>
</section>

@include("install.footer")
<script>
    $('.rewrite-correct').show();
    $('.rewrite-checking').hide();
</script>
</body>
</html>
