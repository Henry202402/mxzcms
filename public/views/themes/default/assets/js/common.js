function getToken() {
    return $('#token').attr("content")
}

function ajaxForm(myForm, fun, url = window.location, method = 'POST') {
    layer.load(1);
    $.ajax({
        "method": method,
        "url": url,
        "data": new FormData($('#' + myForm)[0]),
        "dataType": 'json',
        "cache": false,
        "processData": false,
        "contentType": false,
        "success": fun,
        "error": function (res) {
            layer.closeAll();
            console.log(res);
        }
    })
}

function ajaxData(data, fun, url = window.location, method = 'POST') {
    if (!data.no_load) layer.load(1);
    $.ajax({
        headers: {
            'X-CSRF-TOKEN': getToken()
        },
        "method": method,
        "url": url,
        "data": data,
        "dataType": 'json',
        "success": fun,
        "error": function (res) {
            layer.closeAll();
            console.log(res);
        }
    })
}

function homeJump(url) {
    return window.location.href = url;
}

function __confirm(url, data, title) {
    //询问框
    layer.confirm(title, {
        btn: ['确认', '取消'],//按钮
        title: '提示'
    }, function () {
        ajaxData(data, function (data) {
            layer.closeAll();
            // data = JSON.parse(data);
            if (data.status == 200) {
                layer.msg(data.msg, {icon: 1, time: 1000}, function () {
                    window.location.reload();
                });
            } else {
                layer.msg(data.msg, {icon: 2});
            }
        }, url);
    });
}