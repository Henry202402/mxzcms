function _confirm(clear_url, alert_data, title, yes, no, info) {
    if (!title) title = alert_title;
    if (!yes) yes = alert_yes;
    if (!no) no = alert_no;
    if (!info) info = alert_info;
    //询问框
    layer.confirm(title, {
        btn: [yes, no],//按钮
        title: info
    }, function () {
        layer.load(1);
        $.post(clear_url, alert_data,
            function (data) {
                layer.closeAll();
                console.log(data.status);
                // data = JSON.parse(data);
                if (data.status == 200) {
                    layer.msg(data.msg, {icon: 1, time: 1000}, function () {
                        window.location.reload();
                    });
                } else {
                    layer.msg(data.msg, {icon: 2});
                }
            });
    });
}

function viewImg(obj, w, h) {
    if (w <= 0) w = 350;
    if (h <= 0) h = 350;
    var src = $(obj).attr('src');
    if (!src) return
    //自定义页
    layer.open({
        title: "",
        type: 1,
        skin: 'layui-layer-demo', //样式类名
        closeBtn: 0, //不显示关闭按钮
        anim: 2,
        shadeClose: true, //开启遮罩关闭
        area: [w + 'px', h + 'px'],
        content: "<img width='" + w + "' src='" + src + "'>"
    });
}

function admin_send_goods_prev(id) {
    if (id <= 0) return
    $('#admin_logistics_id').val(id)
    //页面层
    layer.open({
        type: 1,
        skin: 'layui-layer-rim', //加上边框
        area: ['420px', '240px'], //宽高
        content: $('#admin_send_goods_prev_div')
    });
}

$('#admin_logistics_btn').click(function () {
    var id = $('#admin_logistics_id').val();
    var logistics_tig = $('#admin_logistics_tig').val();
    var logistics_number = $('#admin_logistics_number').val();
    if (id <= 0) {
        layer.msg(_id_err, {icon: 2});
        return
    }
    if (!logistics_tig) {
        layer.msg(_logistics_tig_err, {icon: 2});
        return
    }
    if (!logistics_number) {
        layer.msg(_logistics_num_err, {icon: 2});
        return
    }
    var reg = /^[0-9]*$/;
    if (!reg.test(logistics_number)) {
        layer.msg(_logistics_num_err, {icon: 2});
        return
    }
    i = layer.load(1)
    $.post(admin_send_url, {
            _token: _token,
            order_id: id,
            logistics_tig: logistics_tig,
            logistics_number: logistics_number
        },
        function (data) {
            layer.close(i)
            data = JSON.parse(data)
            if (data['status'] == 200) {
                layer.msg(data['msg'], {icon: 1, time: 1000}, function () {
                    window.location.reload()
                })
            } else {
                layer.msg(data['msg'], {icon: 2})
            }
        })
})
var wap_host = window.location.host;
var wap_url = document.location.toString();
var wap_arrUrl = wap_url.split("//");
var domain = wap_arrUrl[0] + "//" + wap_host + '/';

//分页加载
function getPage(url, para, fn,type="GET",dataType="html") {
    $.ajax({
        type: type,
        url: url,
        data: para,
        dataType: dataType,
        success: fn
    });
}

$('.h-search-show').click(function () {
    $('.h-search-show-content').show();
});
$('.h-search-hide').click(function () {
    $('.h-search-show-content').hide();
});

function GetQueryString(name) {
    var reg = new RegExp("(^|&)" + name + "=([^&]*)(&|$)");
    var r = window.location.search.substr(1).match(reg);
    if (r != null) return unescape(r[2]);
    return null;
}

//显示图片
function showFile(file_id, img_id) {
    var file = document.getElementById(file_id).files[0];
    if (file) {
        var reader = new FileReader();
        reader.onload = function (event) {
            var src_text = event.target.result;
            document.getElementById(img_id).src = src_text;
            $('.fileinput-exists').show();
            $('.fileinput-new').hide();
        };
    } else {
        document.getElementById(img_id).src = '';
    }
    reader.readAsDataURL(file);
}

// 删除img
function deleteImg(img) {
    document.getElementById(img).src = '';
    $("#images").val('');
}

//复制
function h_copy(id) {
    var Url2 = document.getElementById(id);
    Url2.select(); // 选择对象
    document.execCommand("Copy"); // 执行浏览器复制命令
    layer.msg('复制成功', {icon: 1})
}

function updateAddress(clear_url, alert_data, title, yes, no, info) {
    if (!title) title = alert_title
    if (!yes) yes = alert_yes
    if (!no) no = alert_no
    if (!info) info = alert_info
    //询问框
    layer.confirm(title, {
        btn: [yes, no],//按钮
        title: info
    }, function () {
        $.post(clear_url, alert_data,
            function (data) {
                data = JSON.parse(data)
                if (data['status'] == 200) {
                    layer.msg(data['msg'], {icon: 1, time: 1000})
                    $('#h-detail-update-address-div').css('display', 'block');
                } else {
                    layer.msg(data['msg'], {icon: 2})
                }
            })
    });
}
