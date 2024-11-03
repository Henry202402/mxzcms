<script>
    checkcms();
    //清空缓存
    function clearCache() {

        var index = layer.load(1, {
            shade: [0.5, '#000'],//0.1透明度的白色背景
            content: '正在清理中...',
            success: function (layero) {
                layero.find('.layui-layer-content').css({
                    'padding-top': '39px',
                    'width': '100px',
                    "color": "#FFF",
                    "background-position": "center center",
                    "text-align": "center",
                });
            }
        });

        $.ajax({
            "method": "post",
            "url": "{{url('admin/clear')}}",
            "timeout": 0,
            "dataType": 'json',
            "data": {"_token": "{{csrf_token()}}"},
            "success": function (res) {
                layer.closeAll();
                if (res.status == 200) {
                    popup({
                        type: "success", msg: res.msg, delay: 2000, callBack: function () {
                            window.location.reload();
                        }
                    });
                } else {
                    popup({
                        type: "error", msg: res.msg, delay: 2000, callBack: function () {
                            window.location.reload();
                        }
                    });
                }
            },
            "error": function (res) {
                console.log(res);
            }
        })
    }
    //更新
    function cmsUpdateVersion() {
        $.confirm({
            title: '{{getTranslateByKey("common_tip")}}',
            content: '{{getTranslateByKey("common_sure_to_update_cms")}}',
            type: 'default',
            buttons: {
                ok: {
                    text: "{{getTranslateByKey('common_ensure')}}",
                    btnClass: 'btn-primary',
                    keys: ['enter'],
                    action: function () {
                        download();
                    }
                },
                cancel: {
                    text: "{{getTranslateByKey('common_cancel')}}"
                }
            }
        });
    }
    //开始下载
    function download() {

        var index = layer.load(1, {
            shade: [0.6, '#000'],//0.1透明度的白色背景
            content: '<span class="layer-span">版本备份中...</span>',
            success: function (layero) {
                layero.find('.layui-layer-content').css({
                    'padding-top': '39px',
                    'width': 'auto',
                    "color": "#FFF",
                });
                //layer-span
                layero.find('.layer-span').css({
                    "margin-left": "-18px",
                });

            }
        });

        bakFiles();
    }
    function checkcms(){

        var params = '?identification=cms&action=check';
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': "{{ csrf_token() }}"
            },
            "method": "post",
            "url": "{{url('admin/cms/updateCmsVersion')}}" + params,
            "timeout": 0,
            "dataType": 'json',
            "cache": false,
            "processData": false,
            "contentType": false,
            "success": function (res) {
                if (res.status == 200) {
                    $('.updateCMS').css('display', 'block');
                    $('.hiden').css('visibility', 'visible');

                } else {

                }
            },
            "error": function (res) {
                console.log(res);
            }
        });
    }
    //备份
    function bakFiles() {
        var params = '?identification=cms&action=backup';
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': "{{ csrf_token() }}"
            },
            "method": "post",
            "url": "{{url('admin/cms/updateCmsVersion')}}" + params,
            "timeout": 0,
            "dataType": 'json',
            "cache": false,
            "processData": false,
            "contentType": false,
            "success": function (res) {
                if (res.status == 200) {
                    layer.closeAll();
                    popup({type: "success", msg: res.msg, delay: 2000});
                    setTimeout(function () {
                        update("cms","cms");
                    }, 2000);

                } else {
                    popup({type: "error", msg: res.msg, delay: 2000});
                }
            },
            "error": function (res) {
                console.log(res);
            }
        });

    }
    //更新
    function update(identification,cloudtype) {

        layer.open({
            type: 1,
            skin: 'layui-layer-demo', //样式类名
            closeBtn: 1, //不显示关闭按钮
            anim: 2,
            shadeClose: false, //开启遮罩关闭
            content: '<div class="col-md-12">\n' +
                '    <div class="card">\n' +
                '        <div class="card-header card-default">\n' +
                '            正在下载最新版本，请稍后...\n' +
                '        </div>\n' +
                '        <div class="card-body">\n' +
                '            <div class="progress-info text-muted">完成 <span class="float-right" id="progress-info">0%</span></div>\n' +
                '            <div class="progress">\n' +
                '                <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" style="width: 0%;" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">0%</div>\n' +
                '            </div>\n' +
                '        </div>\n' +
                '    </div>\n' +
                '</div>'
        });

        var file_size = 0;
        var progress = 0;

        var params = '?identification='+identification+'&action=prepare-download&cloudtype=' + cloudtype;

        $.ajax({
            headers: {
                'X-CSRF-TOKEN': "{{ csrf_token() }}"
            },
            "method": "post",
            "url": "{{url('admin/cms/updateCmsVersion')}}" + params,
            "timeout": 0,
            "dataType": 'json',
            "cache": false,
            "processData": false,
            "contentType": false,
        })
            .done(function (json) {

                file_size = json.file_size;

                var params = "?identification="+identification+"&action=start-download&cloudtype="+cloudtype;

                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': "{{ csrf_token() }}"
                    },
                    "method": "post",
                    "url": "{{url('admin/cms/updateCmsVersion')}}" + params,
                    "timeout": 0,
                    "dataType": 'json',
                    "cache": false,
                    "processData": false,
                    "contentType": false,
                })
                    .done(function (json) {
                        // set progress to 100 when got the response
                        if(json.status == 200){
                            setTimeout(function () {
                                progress = 100;
                                Finished(identification,cloudtype);
                            }, 1000);
                        }else {
                            clearInterval(interval_id);
                            layer.closeAll();
                            popup({type: "error", msg: json.msg, delay: 2000});
                        }
                        console.log("Downloading finished");
                        console.log(json);
                    })
                    .fail(showAjaxError);

                interval_id = window.setInterval(function () {

                    $('#progress-info').html(progress + "%");
                    $('.progress-bar').css('width', progress + '%').attr('aria-valuenow', progress).html(progress + "%");

                    if (progress >= 100) {
                        //Finished(identification,cloudtype);
                    } else {
                        var params = '?identification='+identification+'&action=get-file-size&cloudtype=' + cloudtype;

                        $.ajax({
                            headers: {
                                'X-CSRF-TOKEN': "{{ csrf_token() }}"
                            },
                            "method": "post",
                            "url": "{{url('admin/cms/updateCmsVersion')}}" + params,
                            "timeout": 0,
                            "dataType": 'json',
                            "cache": false,
                            "processData": false,
                            "contentType": false,
                        })
                            .done(function (json) {

                                let file_size2 = file_size * 10000;
                                progress = Math.floor(json.size / file_size2) / 100 ;

                                // updateProgress(progress);

                                console.log("Progress: " + progress);
                            })
                            .fail(showAjaxError);
                    }

                }, 1000);

            })
            .fail(showAjaxError);

    }
    function Finished(identification,cloudtype) {
        clearInterval(interval_id);
        layer.closeAll();
        // 到此远程文件下载完成，继续其他逻辑
        var index = layer.load(1, {
            shade: [0.5, '#000'],//0.1透明度的白色背景
            content: '版本升级中，不要刷新网页...',
            success: function (layero) {
                layero.find('.layui-layer-content').css({
                    'padding-top': '39px',
                    'width': 'auto',
                    "color": "#FFF",
                    "background-position": "center center"
                });
            }
        });

        var params = '?identification='+identification+'&action=unzip-file&cloudtype=' + cloudtype;

        $.ajax({
            headers: {
                'X-CSRF-TOKEN': "{{ csrf_token() }}"
            },
            "method": "post",
            "url": "{{url('admin/cms/updateCmsVersion')}}" + params,
            "timeout": 0,
            "dataType": 'json',
            "cache": false,
            "processData": false,
            "contentType": false,
        })
            .done(function (res) {
                layer.closeAll();
                if (res.status == 200) {
                    popup({type: "success", msg: res.msg, delay: 2000});
                    setTimeout(function () {
                        location.reload();
                    }, 2000);

                } else {
                    popup({type: "error", msg: res.msg, delay: 2000});
                }

            })
            .fail(showAjaxError)
    }
    function showAjaxError(e) {
        layer.closeAll();
        clearInterval(interval_id);
        layer.alert('网络错误！', {
            icon: 2,
            skin: 'layer-ext-moon'
        })
    }
    function getsdks() {
        var params = '?identification=cms&action=get-sdks';
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': "{{ csrf_token() }}"
            },
            "method": "post",
            "url": "{{url('admin/cms/updateCmsVersion')}}" + params,
            "timeout": 0,
            "dataType": 'json',
            "cache": false,
            "processData": false,
            "contentType": false,
            "success": function (res) {
                if (res.status == 200) {
                    var data = res.data.list.data;
                    var html = '';
                    for (var i = 0; i < data.length; i++) {
                        html += "<span >"+ data[i].name + " <a href='" + data[i].url + "' target='_blank'>" + data[i].url + "</a></span> &nbsp;&nbsp;&nbsp;&nbsp;";
                    }
                    $('#sdks').html(html);
                } else {
                }
            },
            "error": function (res) {
                console.log(res);
            }
        });
    }

    function checkModuleVersion(identification,cloudtype,version){
        var params = '?identification='+identification+'&action=check&cloudtype='+cloudtype+'&version='+version;
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': "{{ csrf_token() }}"
            },
            "method": "post",
            "url": "{{url('admin/cms/updateCmsVersion')}}" + params,
            "timeout": 0,
            "dataType": 'json',
            "cache": false,
            "processData": false,
            "contentType": false,
            "success": function (res) {
                if (res.status == 200) {
                    $('.module-update-'+identification).css('visibility', 'visible');
                } else {

                }
            },
            "error": function (res) {
                console.log(res);
            }
        });
    }

    function updateVersion(identification,cloudtype){
        $.confirm({
            title: '{{getTranslateByKey("common_tip")}}',
            content: '确定更新版本吗？',
            type: 'default',
            buttons: {
                ok: {
                    text: "{{getTranslateByKey('common_ensure')}}",
                    btnClass: 'btn-primary',
                    keys: ['enter'],
                    action: function () {
                        update(identification,cloudtype);
                    }
                },
                cancel: {
                    text: "{{getTranslateByKey('common_cancel')}}"
                }
            }
        });
    }
</script>
