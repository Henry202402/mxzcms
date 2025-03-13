$('.select-all-checkbox').click(function () {
    if ($('.select-all-checkbox:checked').val()) {
        $('.select-checkbox').prop('checked', true);
    } else {
        $('.select-checkbox').prop('checked', false);
    }
});
$('.select-checkbox').click(function () {
    var length = $('.select-checkbox:checked').length;
    if (length > 0) {
        $('.select-all-checkbox').prop('checked', true);
    } else {
        $('.select-all-checkbox').prop('checked', false);
    }
});

function getCheckboxValue(str) {
    var checkboxes = document.querySelectorAll(str);
    var values = [];
    for (var i = 0; i < checkboxes.length; i++) {
        values.push(checkboxes[i].value);
    }
    return values;
}

$('.readUserMessage').click(function () {
    var ids = getCheckboxValue('input[name="ids[]"]:checked');
    if (ids.length <= 0) return layer.msg('请选择信息选项', {icon: 2});
    readMessage(ids);
});

function readMessage(ids, no_load = false, no_reload = false) {
    ajaxData({
        operate_type: 1, ids: ids, no_load: no_load
    }, function (res) {
        layer.closeAll();
        if (res.status == 200) {
            if (!no_reload) {
                layer.msg(res.msg, {icon: 1, time: 500}, function () {
                    window.location.reload();
                })
            }
        } else {
            layer.msg(res.msg, {icon: 2})
        }
    }, domainPre + 'member/message/read');
}

$('.readAllUserMessage').click(function () {
    ajaxData({
        operate_type: 2
    }, function (res) {
        layer.closeAll();
        if (res.status == 200) {
            layer.msg(res.msg, {icon: 1, time: 500}, function () {
                window.location.reload();
            })
        } else {
            layer.msg(res.msg, {icon: 2})
        }
    }, domainPre + 'member/message/read');
});

$('.deleteUserMessage').click(function () {
    var ids = getCheckboxValue('input[name="ids[]"]:checked');
    if (ids.length <= 0) return layer.msg('请选择信息选项', {icon: 2});
    ajaxData({
        ids: ids
    }, function (res) {
        layer.closeAll();
        if (res.status == 200) {
            layer.msg(res.msg, {icon: 1, time: 500}, function () {
                window.location.reload();
            })
        } else {
            layer.msg(res.msg, {icon: 2})
        }
    }, domainPre + 'member/message/delete');
});

$('.h-accordion-header').click(function () {
    var id = $(this).attr('data-id');
    if ($(this).find('> *').find('.h-new-message').length > 0) {
        $(this).find('> *').find('.h-new-message').removeClass('h-new-message');
        readMessage(id, 1, 1);
    }
});


$('.updateUserInfo').click(function () {
    ajaxForm('myForm', function (res) {
        layer.closeAll();
        if (res.status == 200) {
            layer.msg(res.msg, {icon: 1, time: 500}, function () {
                window.location.reload();
            })
        } else {
            layer.msg(res.msg, {icon: 2})
        }
    }, domainPre + 'member/mine');
});
$('.updateUserPassword').click(function () {
    var data = {};
    data.old_password = $('input[name=old_password]').val();
    data.new_password = $('input[name=new_password]').val();
    data.confirm_password = $('input[name=confirm_password]').val();
    ajaxData(data, function (res) {
        layer.closeAll();
        if (res.status == 200) {
            layer.msg(res.msg, {icon: 1, time: 500}, function () {
                window.location.reload();
            })
        } else {
            layer.msg(res.msg, {icon: 2})
        }
    }, domainPre + 'member/password');
});