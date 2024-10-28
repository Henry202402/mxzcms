
<!-- Jquery UI -->
<script src="{{ADMIN_ASSET}}assets/lib/jquery-ui/jquery-ui.min.js"></script>
<script src="{{ADMIN_ASSET}}assets/js/jquery.ui.custom.js"></script>

<!--Sweet Alerts-->
<script type="text/javascript" src="{{ADMIN_ASSET}}assets/js/jquery-confirm.min.js"></script>

<script type="text/javascript" src="{{ADMIN_ASSET}}assets/other/DialogJS/javascript/zepto.min.js"></script>
<script type="text/javascript" src="{{ADMIN_ASSET}}assets/other/DialogJS/javascript/dialog.min.js"></script>

<script type="text/javascript" src="{{ADMIN_ASSET}}assets/other/jqueryToast/js/toast.script.js"></script>

<script type="text/javascript" src="{{ADMIN_ASSET}}assets/layer/layer/layer.js"></script>
<!---->
@foreach($load as $value)
    @if($value)
        <script src="{{ADMIN_ASSET}}assets/js/{{$value}}.js"></script>
    @endif
@endforeach
@include('admin.public.cmsjs')
<script>

    var toastMsg;
    var toastType
    var interval_id;

    @if(session('errormsg'))
        toastMsg = "{{session('errormsg')}}";
        toastType = 'error';
    @elseif(session('successmsg'))
        toastMsg = "{{session('successmsg')}}";
        toastType = 'success';
    @endif

    if (toastMsg) {
        $.Toast("温馨提示!", toastMsg, toastType, {
            // append to body
            appendTo: "body",
            // is stackable?
            stack: true,
            // 'toast-top-left'
            // 'toast-top-right'
            // 'toast-top-center'
            // 'toast-bottom-left'
            // 'toast-bottom-right'
            // 'toast-bottom-center'
            position_class: "toast-bottom-right",
            // true = snackbar
            fullscreen: false,
            // width
            width: 250,
            // space between toasts
            spacing: 20,
            // in milliseconds
            timeout: 2000,
            // has close button
            has_close_btn: false,
            // has icon
            has_icon: false,
            // is sticky
            sticky: false,
            // border radius in pixels
            border_radius: 6,
            // has progress bar
            has_progress: true,
            // RTL support
            rtl: false
        });
        toastMsg = null

    }
</script>





