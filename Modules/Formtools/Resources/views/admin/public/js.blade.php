
<!-- Theme JS files -->
<script type="text/javascript"
        src="{{asset("assets/module")}}/js/plugins/visualization/d3/d3.min.js"></script>
<script type="text/javascript"
        src="{{asset("assets/module")}}/js/plugins/visualization/d3/d3_tooltip.js"></script>
<script type="text/javascript"
        src="{{asset("assets/module")}}/js/plugins/forms/styling/switchery.min.js"></script>
<script type="text/javascript"
        src="{{asset("assets/module")}}/js/plugins/forms/styling/uniform.min.js"></script>
<script type="text/javascript" src="{{asset("assets/module")}}/js/pages/form_layouts.js"></script>
<script type="text/javascript"
        src="{{asset("assets/module")}}/js/plugins/forms/selects/bootstrap_multiselect.js"></script>
<script type="text/javascript" src="{{asset("assets/module")}}/js/plugins/ui/moment/moment.min.js"></script>
<script type="text/javascript"
        src="{{asset("assets/module")}}/js/plugins/pickers/daterangepicker.js"></script>
<script type="text/javascript" src="{{asset("assets/module")}}/js/plugins/forms/selects/select2.min.js"></script>
<script type="text/javascript" src="{{asset("assets/module")}}/js/core/app.js"></script>

<!-- /theme JS files -->

<!--   弹窗 -->
<script type="text/javascript" src="{{ADMIN_ASSET}}layer/layer/layer.js"></script>
<!--   弹窗end -->
<!--   日期 -->
<script type="text/javascript" src="{{url('assets/laydate/laydate.js')}}"></script>

<script>
    var alert_title = "你确定要此操作吗？";
    var alert_yes = "确认";
    var alert_no = "取消";
    var alert_info = "信息";
    var choose_file = "选择文件";
    var clear_url = "";
    var alert_data = {};

    @if(session('pageDataMsg'))
    layer.msg("{{session('pageDataMsg')}}", {
        area: ['100%', '50px']
        , offset: 'rt'//具体配置参考：offset参数项
        , shade: 0 //不显示遮罩
    });
    $('.layui-layer-msg').css({
        'backgroundColor': '{{session('pageDataStatus')==200?'#4caf50':'#f44336'}}'
    });
    {{session(['pageDataMsg'=>null])}}
    @endif

</script>
<script type="text/javascript" src="{{asset("assets/module")}}/js/h-app.js"></script>
