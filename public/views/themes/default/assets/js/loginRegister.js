// 倒计时
let waitSecond = 60;
let emailKey = 'getEmailCodeTime';
let emailSale = parseInt(Math.random() * 1000000);

function countEmailDown() {
    let date = new Date().getTime() / 1000;
    date = Math.round(date);
    if (!localStorage.getItem(emailKey)) {
        return;
    }
    if (localStorage.getItem(emailKey) - date > 0) {
        let email_time = localStorage.getItem(emailKey) - date;
        let email_timer = setInterval(() => {
            $(".email_captcha_btn").text(email_time + 's');
            email_time = email_time - 1;
            if (email_time < 0) {
                clearInterval(email_timer);
                localStorage.removeItem(emailKey);
                $(".email_captcha_btn").text("发送");
            }
        }, 1000)
    }
}

$('.email_captcha_btn').click(function () {
    let tempDate = new Date().getTime() / 1000;
    let newSecond = localStorage.getItem(emailKey);
    if (newSecond) {
        if (newSecond < tempDate) {
            localStorage.removeItem(emailKey)
        } else {
            let lastSecond = parseInt(newSecond - tempDate);
            layer.msg(`请${lastSecond}秒后再操作`, {
                icon: 5
            });
            return;
        }
    }
    var email = $("input[name=email]").val();
    if (!email) return $("input[name=email]").focus();
    //if ($("#btnSendCode").text().indexOf("Send") == -1) return;

    /*var nowTime = new Date();
    var day = nowTime.toLocaleDateString().replaceAll('/', '') + '';
    var str = sale + 'novelperson' + day + email + sale;
    var key = md5(str);*/
    var code_type = $("#email_code_type").val();
    if (!code_type) return layer.msg('code_type不能为空');
    ajaxData({
        object_type: 'email', email: email, key: emailSale, code_type: code_type,
    }, function (res) {
        layer.closeAll();
        if (res.status == 200) {
            // 开始显示计时
            tempDate = Math.round(tempDate) + waitSecond;
            localStorage.setItem(emailKey, tempDate);
            $('input[name=email_key]').val(res.data.email_key);
            countEmailDown();
            // 开始显示计时END——
        } else {
            layer.msg(res.msg, {icon: 2})
        }
    }, urlPre + 'sendCode');
});


/***/

let phoneKey = 'getPhoneCodeTime';
let phoneSale = parseInt(Math.random() * 1000000);

function countPhoneDown() {
    let date = new Date().getTime() / 1000;
    date = Math.round(date);
    if (!localStorage.getItem(phoneKey)) {
        return;
    }
    if (localStorage.getItem(phoneKey) - date > 0) {
        let phone_time = localStorage.getItem(phoneKey) - date;
        let phone_timer = setInterval(() => {
            $(".phone_captcha_btn").text(phone_time + 's');
            phone_time = phone_time - 1;
            if (phone_time < 0) {
                clearInterval(phone_timer);
                localStorage.removeItem(phoneKey);
                $(".phone_captcha_btn").text("发送");
            }
        }, 1000)
    }
}

$('.phone_captcha_btn').click(function () {
    let tempDate = new Date().getTime() / 1000;
    let newSecond = localStorage.getItem(phoneKey);
    if (newSecond) {
        if (newSecond < tempDate) {
            localStorage.removeItem(phoneKey)
        } else {
            let lastSecond = parseInt(newSecond - tempDate);
            layer.msg(`请${lastSecond}秒后再操作`, {
                icon: 5
            });
            return;
        }
    }
    var phone = $("input[name=phone]").val();
    if (!phone) return $("input[name=phone]").focus();
    //if ($("#btnSendCode").text().indexOf("Send") == -1) return;

    /*var nowTime = new Date();
    var day = nowTime.toLocaleDateString().replaceAll('/', '') + '';
    var str = sale + 'novelperson' + day + phone + sale;
    var key = md5(str);*/

    var code_type = $("#phone_code_type").val();
    if (!code_type) return layer.msg('code_type不能为空');
    ajaxData({
        object_type: 'phone', phone: phone, key: phoneSale, code_type: code_type,
    }, function (res) {
        layer.closeAll();
        if (res.status == 200) {
            // 开始显示计时
            tempDate = Math.round(tempDate) + waitSecond;
            localStorage.setItem(phoneKey, tempDate);
            $('input[name=phone_key]').val(res.data.phone_key);
            countPhoneDown();
            // 开始显示计时END——
        } else {
            layer.msg(res.msg, {icon: 2})
        }
    }, urlPre + 'sendCode');
});
/****/

$('.registerBtn').click(function () {
    ajaxForm('myForm', function (res) {
        layer.closeAll();
        if (res.status == 200) {
            layer.msg(res.msg, {icon: 1, time: 500}, function () {
                window.location = res.data.url;
            })
        } else {
            $('.captcha').click();
            layer.msg(res.msg, {icon: 2})
        }
    }, urlPre + 'register');
});
$('.forgotBtn').click(function () {
    ajaxForm('myForm', function (res) {
        layer.closeAll();
        if (res.status == 200) {
            layer.msg(res.msg, {icon: 1, time: 500}, function () {
                window.location = urlPre + 'login';
            })
        } else {
            layer.msg(res.msg, {icon: 2})
        }
    }, urlPre + 'forgot');
});

$('.updateUserEmailInfo').click(function () {
    ajaxForm('myForm', function (res) {
        layer.closeAll();
        if (res.status == 200) {
            layer.msg(res.msg, {icon: 1, time: 500}, function () {
                window.location.reload();
            })
        } else {
            layer.msg(res.msg, {icon: 2})
        }
    }, urlPre + 'member/email');
});

$('.updateUserPhoneInfo').click(function () {
    ajaxForm('myForm', function (res) {
        layer.closeAll();
        if (res.status == 200) {
            layer.msg(res.msg, {icon: 1, time: 500}, function () {
                window.location.reload();
            })
        } else {
            layer.msg(res.msg, {icon: 2})
        }
    }, urlPre + 'member/phone');
});
