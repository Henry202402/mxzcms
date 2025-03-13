// 扫码支付
function confirmPay() {
    var url = '';
    var checkUrl = '';
    var param = {};
    var pay_method = paymentFormData.pay_method == 1 ? 'Alipay' : 'WeChat';//微信 支付宝
    var pay_type = 'pc';//支付端
    if (paymentFormData.payType == 'vip') {
        url = domainPre + 'member/vipPay';
        param = {
            id: paymentFormData.id,
            pay_method: pay_method,
            pay_type: pay_type,
        };
        checkUrl = domainPre + 'member/checkVipPayStatus';
    } else {
        return layer.msg('类型错误');
    }

    ajaxData(param, function (res) {
        layer.closeAll();
        if (res.status == 200) {
            $('#qrcode').html('');
            //微信二维码赋值
            jQuery('#qrcode').qrcode(res.data.code_url);
            //显示二维码
            showContent('#qrcode');
            //5秒轮询
            timingCheck(checkUrl, {
                order_id: res.data.order_id,
                no_load: 1
            }, function (data) {
                if (data.status == 200) {
                    layer.msg('支付成功', {icon: 1, time: 1000}, function () {
                        window.location.reload();
                    })
                }
            }, 5);
            //10分钟没支付，刷新页面
            setTimeout(function () {
                window.location.reload();
            }, 60 * 1000 * 10)
        } else {
            layer.msg(res.msg, {icon: 2})
        }
    }, url);
}

function timingCheck(url, param, func, timeout = 5) {
    timeout = timeout * 1000;
    setInterval(function () {
        ajaxData(param, func, url)
    }, timeout);
}

function showContent(id) {
    //自定页
    layer.open({
        type: 1,
        scrollbar: false,
        title: '微信支付', //不显示标题
        skin: 'layui-layer-demo', //样式类名
        closeBtn: 1, //不显示关闭按钮
        anim: 2,
        shadeClose: false, //开启遮罩关闭
        content: $(id)
    });
}