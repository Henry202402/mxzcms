@include(moduleAdminTemplate($moduleName)."public.header")
<style>
    input[type="radio"] {
        margin: 8px 10px 0px;
    }


    .h-span-val {
        font-size: 20px;
    }

    .h-clear-both {
        clear: both;
    }

    .h-mb-x {
        margin-bottom: 80px;
    }

    .h-red {
        color: red;
        font-size: 17px;
    }

    .h-mb-20 {
        margin-bottom: 20px;
    }

    .h-del-btn {
        color: red;
        margin-top: 11px;
        cursor: pointer;
    }
</style>
<body>
@include(moduleAdminTemplate($moduleName)."public.nav")

<div class="page-container">

    <!-- Page content -->
    <div class="page-content">

    @include(moduleAdminTemplate($moduleName)."public.left")

    <!-- Main content -->
        <div class="content-wrapper">

            <!-- Page header -->
            <div class="page-header">
                @include(moduleAdminTemplate($moduleName)."public.page", ['breadcrumb'=>['系统设置','基本配置']])
            </div>
            <!-- /page header -->


            <!-- Content area -->
            <div class="content" style="margin-top: 1rem;">


                <div class="panel panel-flat">
                    <div class="panel-heading">


                        {{csrf_field()}}
                        <fieldset class="content-group">
                            <ul class="nav nav-tabs">
                                <li @if($_GET['type']==0) class="active" @endif >
                                    <a href data-toggle="tab" data-target="#web">
                                        系统配置
                                    </a>
                                </li>
                                <li @if($_GET['type']==1) class="active" @endif >
                                    <a href data-toggle="tab" data-target="#captcha">
                                        提交验证码
                                    </a>
                                </li>
                                <li @if($_GET['type']==2) class="active" @endif >
                                    <a href data-toggle="tab" data-target="#email">
                                        SMTP邮箱设置
                                    </a>
                                </li>
                                <li @if($_GET['type']==3) class="active" @endif >
                                    <a href data-toggle="tab" data-target="#sms">
                                        SMS短信配置
                                    </a>
                                </li>
                                {{--                                <li @if($_GET['type']==4) class="active" @endif >--}}
                                {{--                                    <a href data-toggle="tab" data-target="#login">--}}
                                {{--                                        第三方登录--}}
                                {{--                                    </a>--}}
                                {{--                                </li>--}}
                                {{--                                <li @if($_GET['type']==5) class="active" @endif >--}}
                                {{--                                    <a href data-toggle="tab" data-target="#pay">--}}
                                {{--                                        第三方支付--}}
                                {{--                                    </a>--}}
                                {{--                                </li>--}}
                                <li @if($_GET['type']==6) class="active" @endif >
                                    <a href data-toggle="tab" data-target="#editor">
                                        富文本编辑器
                                    </a>
                                </li>
                                {{--                                <li @if($_GET['type']==6) class="active" @endif >--}}
                                {{--                                    <a href data-toggle="tab" data-target="#push">--}}
                                {{--                                        消息推送配置--}}
                                {{--                                    </a>--}}
                                {{--                                </li>--}}
                            </ul>
                            <div class="tab-content">
                                <div class="tab-pane @if($_GET['type']==0) active @endif " id="web">
                                    <form id="web_form" class="h-mt20" role="form" method="post">
                                        {{csrf_field()}}
                                        <legend class="text-bold mt-20">网站基本配置</legend>

                                        <div class="col-md-12">
                                            <label>
                                                网站名称
                                            </label>
                                            <input type="text" class="form-control" name="website_name"
                                                   value="{{cacheGlobalSettingsByKey('website_name')}}">
                                        </div>
                                        <div class="col-md-6 mt-20">
                                            <label>
                                                网站logo
                                            </label>
                                            <div>
                                                <div class="fileinput-new-div col-lg-11" data-provides="fileinput"
                                                     style="padding-left: 0">
                                                    <div class="fileinput-preview" data-trigger="fileinput"
                                                         style="width: 240px;height: 50px">
                                                        @if(cacheGlobalSettingsByKey('weblogo'))
                                                            <img id="addImg" class="img-fluid "
                                                                 style="width: 240px;height: 50px"
                                                                 src="{{GetLocalFileByPath(cacheGlobalSettingsByKey('weblogo'))}}"
                                                                 alt=""/>
                                                        @endif
                                                    </div>
                                                    <span class="btn btn-primary  btn-file">
                                                    <span class="fileinput-new">选择</span>
                                                    <span class="fileinput-exists">更换</span>
                                                        <input type="file" id="images" name="weblogo"
                                                               onchange="showFile('images','addImg')"></span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6 mt-20">
                                            <label>
                                                地址栏ico
                                            </label>
                                            <div>
                                                <div class="fileinput-new-div col-lg-6" data-provides="fileinput"
                                                     style="padding-left: 0">
                                                    <div class="fileinput-preview" data-trigger="fileinput"
                                                         style="width: 35px;height: 35px">
                                                        @if(cacheGlobalSettingsByKey('webicon'))
                                                            <img id="addIco" class="img-fluid "
                                                                 style="width: 32px;height: 32px"
                                                                 src="{{GetLocalFileByPath(cacheGlobalSettingsByKey('webicon'))}}"
                                                                 alt=""/>
                                                        @endif
                                                    </div>
                                                    <span class="btn btn-primary  btn-file">
                                                    <span class="fileinput-new">选择</span>
                                                    <span class="fileinput-exists">更换</span>
                                                        <input type="file" id="ico" name="webicon"
                                                               onchange="showFile('ico','addIco')"></span>
                                                </div>
                                                <div class="fileinput-new-div col-lg-6">
                                                    <p class="text-muted">
                                                        <br>
                                                        建议尺寸 64 * 64 (像素)的.ico文件。 <br>
                                                    </p>
                                                </div>

                                            </div>
                                        </div>
                                        <div class="col-md-12 mt-20">
                                            <label>
                                                关键字
                                            </label>
                                            <input type="text" class="form-control" name="website_keys"
                                                   value="{{cacheGlobalSettingsByKey('website_keys')}}"
                                                   placeholder="关键词，不要超过100个字符！">
                                        </div>
                                        <div class="col-md-12 mt-20">
                                            <label>
                                                网站描述
                                            </label>
                                            <textarea class="form-control" placeholder="请填写描述，不要超过200个字符！"
                                                      name="website_desc" class="form-text" rows="4"
                                                      style="height: 100px;">{{cacheGlobalSettingsByKey('website_desc')}}</textarea>
                                        </div>
                                        <div class="col-md-6 mt-20">
                                            <label>
                                                备案号
                                            </label>
                                            <input type="text" class="form-control" name="website_icp"
                                                   value="{{cacheGlobalSettingsByKey('website_icp')}}">
                                        </div>
                                        <div class="col-md-6 mt-20">
                                            <label>
                                                版权声明
                                            </label>
                                            <input type="text" class="form-control" name="website_copyright"
                                                   value="{{cacheGlobalSettingsByKey('website_copyright')}}">
                                        </div>
                                        <div class="h-clear-both"></div>
                                        <legend class="text-bold mt-20">前台登录/注册</legend>
                                        <div class="col-md-12">
                                            <label>
                                                会员登录
                                            </label>
                                            <br>
                                            <label class="radio-inline">
                                                <input type="radio" class="styled h-radio"
                                                       value="1" name="website_open_login"
                                                       @if(cacheGlobalSettingsByKey('website_open_login')==1) checked @endif >
                                                <span class="h-span-val">开启</span>
                                            </label>

                                            <label class="radio-inline">
                                                <input type="radio" class="styled h-radio"
                                                       value="0" name="website_open_login"
                                                       @if(cacheGlobalSettingsByKey('website_open_login')==0) checked @endif >
                                                <span class="h-span-val">关闭</span>
                                            </label>

                                        </div>

                                        <div class="col-md-12 mt-20">
                                            <label>
                                                会员注册
                                            </label>
                                            <br>
                                            <label class="radio-inline">
                                                <input type="radio" class="styled h-radio"
                                                       value="1" name="website_open_reg"
                                                       @if(cacheGlobalSettingsByKey('website_open_reg')==1) checked @endif >
                                                <span class="h-span-val">开启</span>
                                            </label>

                                            <label class="radio-inline">
                                                <input type="radio" class="styled h-radio"
                                                       value="0" name="website_open_reg"
                                                       @if(cacheGlobalSettingsByKey('website_open_reg')==0) checked @endif >
                                                <span class="h-span-val">关闭</span>
                                            </label>

                                        </div>

                                        <div class="col-md-12 mt-20">
                                            <label>
                                                注册验证
                                            </label>
                                            <br>
                                            <label class="checkbox-inline">
                                                <input type="checkbox" class="styled h-radio"
                                                       value="phone" name="website_reg_rqstd[]"
                                                       @if(in_array('phone',explode(",", cacheGlobalSettingsByKey('website_reg_rqstd')))) checked @endif>
                                                <span class="h-span-val">手机号码</span>
                                            </label>

                                            <label class="checkbox-inline">
                                                <input type="checkbox" class="styled h-radio"
                                                       value="email" name="website_reg_rqstd[]"
                                                       @if(in_array('email',explode(",", cacheGlobalSettingsByKey('website_reg_rqstd')))) checked @endif >
                                                <span class="h-span-val">电子邮件</span>
                                            </label>
                                            <span class="help-block">验证字段，都会发送对应的验证码，请确认配置是否完全</span>
                                        </div>

                                        <div class="col-md-12 mt-20">
                                            <label>
                                                注册协议【<a target="_blank" href="{{moduleAdminJump('Formtools','model?moduleName=Formtools&action=List&model=agreement')}}">协议列表</a>】
                                            </label>
                                            <br>
                                            @foreach($agreementList as $agree)
                                                <label class="checkbox-inline">
                                                    <input type="checkbox" class="styled h-radio"
                                                           value="{{$agree['id']}}" name="website_reg_agreement[]"
                                                           @if(in_array($agree['id'],explode(",", cacheGlobalSettingsByKey('website_reg_agreement')))) checked @endif>
                                                    <span class="h-span-val">{{$agree['name']}}</span>
                                                </label>
                                            @endforeach
                                            <span class="help-block">用户注册，是否需要显示协议，请勾选相关协议</span>
                                        </div>

                                        <div class="col-md-12 mt-20">
                                            <label>
                                                提交验证码
                                            </label>
                                            <br>
                                            <label class="radio-inline">
                                                <input type="radio" class="styled h-radio"
                                                       value="1" name="open_captcha"
                                                       @if(cacheGlobalSettingsByKey('open_captcha')==1) checked @endif >
                                                <span class="h-span-val">开启</span>
                                            </label>

                                            <label class="radio-inline">
                                                <input type="radio" class="styled h-radio"
                                                       value="0" name="open_captcha"
                                                       @if(cacheGlobalSettingsByKey('open_captcha')==0) checked @endif >
                                                <span class="h-span-val">关闭</span>
                                            </label>
                                            <span class="help-block">验证码开启需要安装对应的验证码插件</span>
                                        </div>

                                        <div class="h-clear-both"></div>
                                        <legend class="text-bold mt-20">其他</legend>
                                        {{--<div class="col-md-12 mt-20">
                                            <label>
                                                开启多货币
                                            </label>
                                            <br>
                                            <label class="radio-inline">
                                                <input type="radio" class="styled h-radio"
                                                       value="1" name="multi_currency"
                                                       @if(cacheGlobalSettingsByKey('multi_currency')==1) checked @endif >
                                                <span class="h-span-val">开启</span>
                                            </label>

                                            <label class="radio-inline">
                                                <input type="radio" class="styled h-radio"
                                                       value="0" name="multi_currency"
                                                       @if(cacheGlobalSettingsByKey('multi_currency')==0) checked @endif >
                                                <span class="h-span-val">关闭</span>
                                            </label>

                                        </div>


                                        <div class="col-md-12 mt-20">
                                            <label>
                                                默认货币
                                            </label>

                                            <select name="default_currency" class="form-control">
                                                @foreach($currencys as $currency)
                                                    <option value="{{$currency['id']}}"
                                                            @if(cacheGlobalSettingsByKey('default_currency') ==$currency['id']) selected @endif>{{$currency["name"]}}</option>
                                                @endforeach
                                            </select>
                                        </div>--}}

                                        <div class="col-md-12">
                                            <label>
                                                开启多语言
                                            </label>
                                            <br>
                                            <label class="radio-inline">
                                                <input type="radio" class="styled h-radio"
                                                       value="1" name="multilingual"
                                                       @if(cacheGlobalSettingsByKey('multilingual')==1) checked @endif >
                                                <span class="h-span-val">开启</span>
                                            </label>

                                            <label class="radio-inline">
                                                <input type="radio" class="styled h-radio"
                                                       value="0" name="multilingual"
                                                       @if(cacheGlobalSettingsByKey('multilingual')==0) checked @endif >
                                                <span class="h-span-val">关闭</span>
                                            </label>

                                        </div>

                                        <div class="col-md-12 mt-20">
                                            <label>
                                                默认语言
                                            </label>

                                            <select name="default_language" class="form-control">
                                                @foreach(\Modules\Main\Services\ServiceModel::getLangList() as $kl=>$vl)
                                                    <option value="{{$kl}}"
                                                            @if(session("admin_current_language")['shortcode'] == $kl)
                                                                selected >{{$vl}}</option>
                                                            @elseif(cacheGlobalSettingsByKey('default_language') == $kl)
                                                                selected >{{$vl}}</option>
                                                            @else
                                                                         >{{$vl}}</option>
                                                            @endif
                                                @endforeach
                                            </select>
                                        </div>


                                        <div class="col-md-12 mt-20">
                                            <label>网站维护</label><br>
                                            <label class="radio-inline">
                                                <input type="radio" class="styled h-radio"
                                                       value="1" name="website_status"
                                                       @if(cacheGlobalSettingsByKey('website_status')==1) checked @endif >
                                                <span class="h-span-val">正常</span>
                                            </label>

                                            <label class="radio-inline">
                                                <input type="radio" class="styled h-radio"
                                                       value="0" name="website_status"
                                                       @if(cacheGlobalSettingsByKey('website_status')==0) checked @endif >
                                                <span class="h-span-val">维护中</span>
                                            </label>

                                        </div>


                                        <div class="col-md-12 mt-20">
                                            <label>
                                                维护中显示的文字
                                            </label>
                                            <textarea class="form-control" name="website_status_when" rows="4"
                                                      style="height: 100px;">{{cacheGlobalSettingsByKey('website_status_when')}}</textarea>
                                        </div>


                                        <div class="h-clear-both"></div>
                                        <br>
                                        <legend class="text-bold mt-20">后台相关</legend>

                                        <div class="col-md-12">
                                            <label>是否开启云平台</label><br>
                                            <label class="radio-inline">
                                                <input type="radio" class="styled h-radio"
                                                       value="1" name="use_of_cloud"
                                                       @if(cacheGlobalSettingsByKey('use_of_cloud')==1) checked @endif >
                                                <span class="h-span-val">开启</span>
                                            </label>

                                            <label class="radio-inline">
                                                <input type="radio" class="styled h-radio"
                                                       value="0" name="use_of_cloud"
                                                       @if(cacheGlobalSettingsByKey('use_of_cloud')==0) checked @endif >
                                                <span class="h-span-val">关闭</span>
                                            </label>
                                        </div>

                                        <div class="col-md-12 mt-20">
                                            <label>调式模式</label><br>
                                            <label class="radio-inline">
                                                <input type="radio" class="styled h-radio"
                                                       value="true" name="website_debug"
                                                       @if(env('APP_DEBUG') == true) checked @endif >
                                                <span class="h-span-val">开启</span>
                                            </label>

                                            <label class="radio-inline">
                                                <input type="radio" class="styled h-radio"
                                                       value="false" name="website_debug"
                                                       @if(env('APP_DEBUG') == false) checked @endif >
                                                <span class="h-span-val">关闭</span>
                                            </label>
                                            <div class="col-sm-12">
                                                <p style="padding-top: 10px;">
                                                    将显示错误信息到html上
                                                </p>
                                            </div>
                                        </div>


                                        <div class="col-md-12 mt-20">
                                            <label>日志模式</label><br>
                                            <label class="radio-inline">
                                                <input type="radio" class="styled h-radio"
                                                       name="APP_LOG" value="single"
                                                       @if(env('APP_LOG') =='single') checked @endif >
                                                <span class="h-span-val">single(单文件)</span>
                                            </label>

                                            <label class="radio-inline">
                                                <input type="radio" class="styled h-radio"
                                                       name="APP_LOG" value="daily"
                                                       @if(env('APP_LOG') =='daily') checked @endif >
                                                <span class="h-span-val">daily(日期模式)</span>
                                            </label>

                                            <label class="radio-inline">
                                                <input type="radio" class="styled h-radio"
                                                       name="APP_LOG" value="syslog"
                                                       @if(env('APP_LOG') =='syslog') checked @endif >
                                                <span class="h-span-val">syslog(记录到syslog中)</span>
                                            </label>

                                            <label class="radio-inline">
                                                <input type="radio" class="styled h-radio"
                                                       name="APP_LOG" value="errorlog"
                                                       @if(env('APP_LOG') =='errorlog') checked @endif >
                                                <span class="h-span-val">errorlog(记录到PHP的error_log中)</span>
                                            </label>

                                        </div>


                                        <div class="col-md-12 mt-20">
                                            <label>日志级别</label><br>
                                            <label class="radio-inline">
                                                <input type="radio" class="styled h-radio"
                                                       name="LOG_LEVEL" value="debug"
                                                       @if(env('LOG_LEVEL') =='debug') checked @endif >
                                                <span class="h-span-val">debug</span>
                                            </label>

                                            <label class="radio-inline">
                                                <input type="radio" class="styled h-radio"
                                                       name="LOG_LEVEL" value="info"
                                                       @if(env('LOG_LEVEL') =='info') checked @endif >
                                                <span class="h-span-val">info</span>
                                            </label>

                                            <label class="radio-inline">
                                                <input type="radio" class="styled h-radio"
                                                       name="LOG_LEVEL" value="notice"
                                                       @if(env('LOG_LEVEL') =='notice') checked @endif >
                                                <span class="h-span-val">notice</span>
                                            </label>

                                            <label class="radio-inline">
                                                <input type="radio" class="styled h-radio"
                                                       name="LOG_LEVEL" value="warning"
                                                       @if(env('LOG_LEVEL') =='warning') checked @endif >
                                                <span class="h-span-val">warning</span>
                                            </label>

                                            <label class="radio-inline">
                                                <input type="radio" class="styled h-radio"
                                                       name="LOG_LEVEL" value="error"
                                                       @if(env('LOG_LEVEL') =='error') checked @endif >
                                                <span class="h-span-val">error</span>
                                            </label>

                                            <label class="radio-inline">
                                                <input type="radio" class="styled h-radio"
                                                       name="LOG_LEVEL" value="critical"
                                                       @if(env('LOG_LEVEL') =='critical') checked @endif >
                                                <span class="h-span-val">critical</span>
                                            </label>

                                            <label class="radio-inline">
                                                <input type="radio" class="styled h-radio"
                                                       name="LOG_LEVEL" value="alert"
                                                       @if(env('LOG_LEVEL') =='alert') checked @endif >
                                                <span class="h-span-val">alert</span>
                                            </label>

                                            <label class="radio-inline">
                                                <input type="radio" class="styled h-radio"
                                                       name="LOG_LEVEL" value="emergency"
                                                       @if(env('LOG_LEVEL') =='emergency') checked @endif >
                                                <span class="h-span-val">emergency</span>
                                            </label>

                                        </div>

                                        <div class="col-md-12 mt-20">
                                            <label>
                                                后台每页显示的数量
                                            </label>
                                            <input type="number" class="form-control"
                                                   name="admin_page_count" placeholder="后台每页显示的数量"
                                                   value="{{cacheGlobalSettingsByKey('admin_page_count')}}">
                                        </div>


                                        {{--<div class="col-md-6 mt-20">
                                            <label>是否开启后台验证码</label>

                                            <div>
                                                <label class="radio-inline">
                                                    <input type="radio" name="is_open_admin_verification_code"
                                                           class="styled h-radio" value="1"
                                                           @if($data['base']['is_open_admin_verification_code']=='1') checked @endif>
                                                    <span class="h-span-val">开启</span>
                                                </label>

                                                <label class="radio-inline">
                                                    <input type="radio" name="is_open_admin_verification_code"
                                                           class="styled h-radio" value="2"
                                                           @if($data['base']['is_open_admin_verification_code']=='2') checked @endif>
                                                    <span class="h-span-val">关闭</span>
                                                </label>
                                            </div>
                                        </div>--}}

                                        <div class="h-clear-both mt-20"></div>
                                        <div class="form-group col-md-10 mt-20">
                                            <button type="button" onclick="formSub('web_form','website',0)"
                                                    class="btn btn-primary {{permissions('base/baseConfigSubmit')}}">
                                                提交
                                            </button>
                                        </div>


                                    </form>
                                </div>
                                <div class="tab-pane @if($_GET['type']==1) active @endif " id="captcha">
                                    <form id="captcha_form" class="h-mt20" role="form" method="post">
                                        {{csrf_field()}}
                                        <div class="col-md-12 mt-20">
                                            <label>提交验证码</label>
                                            <br>
                                            @if($plugin_captcha_list)
                                                @foreach($plugin_captcha_list as $key => $captcha)
                                                    @if($captcha)
                                                        <label class="radio-inline">
                                                            <input type="radio" class="styled h-radio"
                                                                   value="{{$captcha['identification']}}"
                                                                   name="captcha_driver"
                                                                   @if(!empty(__E("captcha_driver")) && __E("captcha_driver")==$captcha['identification']) checked @endif >
                                                            <span class="h-span-val">{{$captcha['name']}}</span>
                                                        </label>
                                                    @endif
                                                @endforeach
                                            @endif

                                        </div>
                                        <div class="h-clear-both"></div>

                                        <div class="h-clear-both mt-20"></div>
                                        <div class="form-group col-md-10 mt-20">
                                            <button type="button" onclick="formSub('captcha_form','captcha',1)"
                                                    class="btn btn-primary {{permissions('base/baseConfigSubmit')}}">
                                                提交
                                            </button>
                                        </div>


                                    </form>

                                </div>
                                <div class="tab-pane @if($_GET['type']==2) active @endif " id="email">
                                    <form id="email_form" class="h-mt20" role="form" method="post">
                                        {{csrf_field()}}
                                        <div class="col-md-12 mt-20">
                                            <label>
                                                发件人（所显示的发件人姓名）
                                            </label>
                                            <input type="text" class="form-control" name="MAIL_FROM_NAME"
                                                   value="{{env('MAIL_FROM_NAME')}}" placeholder="发件人">
                                        </div>

                                        <div class="col-md-12 mt-20">
                                            <label>
                                                邮箱账号（用于发送邮件的邮箱账号）
                                            </label>
                                            <input type="text" class="form-control" name="MAIL_FROM_ADDRESS"
                                                   value="{{env('MAIL_FROM_ADDRESS')}}" placeholder="邮箱账号">
                                        </div>

                                        <div class="col-md-12 mt-20">
                                            <label>
                                                邮箱密码（用于发送邮件的邮箱密码）
                                            </label>
                                            <input type="text" class="form-control" name="MAIL_PASSWORD"
                                                   value="{{env('MAIL_PASSWORD')}}" placeholder="邮箱密码">
                                        </div>

                                        <div class="col-md-12 mt-20">
                                            <label>
                                                SMTP（如QQ邮箱为smtp.qq.com）
                                            </label>
                                            <input type="text" class="form-control" name="MAIL_HOST"
                                                   value="{{env('MAIL_HOST')}}" placeholder="SMTP">
                                        </div>

                                        <div class="col-md-12 mt-20">
                                            <label>
                                                发送端口（用于邮件发送端口（TLS一般为25，SSL一般为465））
                                            </label>
                                            <input type="text" class="form-control" name="MAIL_PORT"
                                                   value="{{env('MAIL_PORT')}}" placeholder="发送端口">
                                        </div>
                                        <div class="col-md-12 mt-20">
                                            <label>
                                                发送方式（默认邮箱服务方式为TLS；如果使用TLS方式25端口无法发送，请尝试使用SSL方式465端口发件）
                                            </label>
                                            <br>
                                            <label class="radio-inline">
                                                <input type="radio" class="styled h-radio"
                                                       value="ssl" name="MAIL_ENCRYPTION"
                                                       @if(env('MAIL_ENCRYPTION')=='ssl') checked @endif >
                                                <span class="h-span-val">SSL服务方式</span>
                                            </label>

                                            <label class="radio-inline">
                                                <input type="radio" class="styled h-radio"
                                                       value="tls" name="MAIL_ENCRYPTION"
                                                       @if(env('MAIL_ENCRYPTION')=='tls') checked @endif >
                                                <span class="h-span-val">TLS服务方式</span>
                                            </label>

                                        </div>
                                        <div class="col-md-12 mt-20">
                                            <label>
                                                发送测试
                                            </label>
                                            <br>
                                            <div class="col-md-6">
                                                <input placeholder="测试邮箱地址" id="email_adress" class="form-control"
                                                       type="text">
                                            </div>
                                            <div class="col-md-2">
                                                <input class="form-control" type="button" id="email_test_button"
                                                       value="发送">
                                            </div>
                                        </div>

                                        <div class="h-clear-both mt-20"></div>
                                        <div class="form-group col-md-10 mt-20">
                                            <button type="button" onclick="formSub('email_form','email',2)"
                                                    class="btn btn-primary {{permissions('base/baseConfigSubmit')}}">
                                                提交
                                            </button>
                                        </div>


                                    </form>

                                </div>
                                <div class="tab-pane @if($_GET['type']==3) active @endif " id="sms">
                                    <form id="sms_form" class="h-mt20" role="form" method="post">
                                        {{csrf_field()}}
                                        <div class="col-md-12 mt-20">
                                            <label>SMS服务商</label>
                                            <br>
                                            @if($plugin_list)
                                                @foreach($plugin_list as $key => $plugin)
                                                    @if($plugin)
                                                        <label class="radio-inline">
                                                            <input type="radio" class="styled h-radio"
                                                                   value="{{$plugin['identification']}}"
                                                                   name="sms_driver"
                                                                   @if(!empty(__E("sms_driver")) && __E("sms_driver")==$plugin['identification']) checked @endif >
                                                            <span class="h-span-val">{{$plugin['name']}}</span>
                                                        </label>
                                                    @endif
                                                @endforeach
                                            @endif

                                        </div>
                                        <div class="h-clear-both"></div>

                                        <div class="h-clear-both mt-20"></div>
                                        <div class="form-group col-md-10 mt-20">
                                            <button type="button" onclick="formSub('sms_form','sms',3)"
                                                    class="btn btn-primary {{permissions('base/baseConfigSubmit')}}">
                                                提交
                                            </button>
                                        </div>


                                    </form>

                                </div>
                                <div class="tab-pane @if($_GET['type']==6) active @endif " id="editor">
                                    <form id="editor_form" class="h-mt20" role="form" method="post">
                                        {{csrf_field()}}
                                        <div class="col-md-12 mt-20">
                                            <label>富文本编辑器</label>
                                            <br>
                                            @if($plugin_editor_list)
                                                @foreach($plugin_editor_list as $key => $plugin)
                                                    @if($plugin)
                                                        <label class="radio-inline">
                                                            <input type="radio" class="styled h-radio"
                                                                   value="{{$plugin['identification']}}"
                                                                   name="editor_driver"
                                                                   @if(!empty(__E("editor_driver")) && __E("editor_driver")==$plugin['identification']) checked @endif >
                                                            <span class="h-span-val">{{$plugin['name']}}</span>
                                                        </label>
                                                    @endif
                                                @endforeach
                                            @endif

                                        </div>
                                        <div class="h-clear-both"></div>

                                        <div class="h-clear-both mt-20"></div>
                                        <div class="form-group col-md-10 mt-20">
                                            <button type="button" onclick="formSub('editor_form','editor',6)"
                                                    class="btn btn-primary {{permissions('base/baseConfigSubmit')}}">
                                                提交
                                            </button>
                                        </div>


                                    </form>

                                </div>
                            </div>
                        </fieldset>
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
<script>
    function formSub(form, tig, type) {
        layer.load(1);
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': "{{ csrf_token() }}"
            },
            "method": "post",
            "url": "{{moduleAdminJump($moduleName,'base/baseSubmit?form=')}}" + tig,
            "data": new FormData($('#' + form)[0]),
            "dataType": 'json',
            "cache": false,
            "processData": false,
            "contentType": false,
            "success": function (res) {
                layer.closeAll();
                if (res.status == 200) {
                    layer.msg(res.msg, {icon: 1, time: 1000}, function () {
                        window.location.href = "{{moduleAdminJump($moduleName,'base/baseConfig?type=')}}" + type;
                    });
                } else {
                    layer.msg(res.msg, {icon: 5})
                }
            },
            "error": function (res) {
                console.log(res);
            }
        })
    }

    //email 测试
    $("#email_test_button").click(function () {
        layer.load(1);
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': "{{ csrf_token() }}"
            },
            "method": "post",
            "url": "{{moduleAdminJump($moduleName,'base/baseSubmit?form=test_email')}}",
            "data": {'email_adress': $("#email_adress").val(), "_token": '{{csrf_token()}}'},
            "dataType": 'json',
            // "cache": false,
            // "processData": false,
            // "contentType": false,
            "success": function (res) {
                layer.closeAll();
                if (res.status == 200) {
                    layer.msg(res.msg, {icon: 1, time: 1000});
                } else {
                    layer.msg(res.msg, {icon: 5})
                }
            },
            "error": function (res) {
                layer.closeAll();
                console.log(res);
            }
        })
    })

</script>
</body>
</html>
