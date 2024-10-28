@if($f['formtype']=='imageAjax')
    <style>
        .bar {
            height: 18px;
            background: #328046;
        }
    </style>
    <div class="form-group row">
        <label class="control-label col-lg-1">@if($f['required'])<span style="color:red;">*</span> @endif{{$f['name']}}</label>
        <div class="col-lg-11">
            <input type="hidden" name="{{$f['identification']}}" id="{{$f['identification']}}"
                   {{$f['disabled']}} value="{{$f['value']}}"
                   @if($f['required']) required @endif >
            <input type="file" id="{{$f['identification']}}Input" class="file-styled-primary" accept="image/*">
{{--            <span class="help-block">支持格式: gif, png, jpg，jpeg. 最大文件 2Mb</span>--}}
            @if($f['notes'])
                <span class="help-block">{{ $f['notes'] }}</span>
            @endif
            <div id="{{$f['identification']}}Div" style="display:flex;margin-top: 10px;">
                <span style="width: 90%;" class="progress">
                    <div class="bar" style="width: 0%;"></div>
                </span>
                <span style="width: 10%;margin-left: 10px;" class="number">0</span>
            </div>
        </div>
    </div>
    <script>
        $(function () {
            $(".file-styled-primary").uniform({
                wrapperClass: 'bg-warning',
                fileButtonHtml: '<i class="icon-plus2"></i>'
            });

            $("#{{$f['identification']}}Input").on('change', function () {
                var file = this.files[0];
                if (file.size > 1024 * 2000) {
                    return layer.msg('文件最大2M', {icon: 2});
                }
                ajaxUpload_{{$f['identification']}}("{{$f['identification']}}");
            });
        });


        function ajaxUpload_{{$f['identification']}}(identification) {
            layer.load(1);
            var xhr, formData;
            xhr = new XMLHttpRequest();
            xhr.withCredentials = false;
            xhr.open('POST', "{{moduleAdminJump("formtools","model?_token=".csrf_token())}}");
            xhr.timeout = 0;
            xhr.ontimeout = function () {
                xhr.abort(); // 在超时后中止请求
                error(identification);
            };
            xhr.onload = function () {
                layer.closeAll();
                if (xhr.status == 500) return error(identification);
                var json = JSON.parse(xhr.responseText);
                if (json.location) {
                    layer.msg('上传成功', {icon: 1});
                    $("#" + identification).val(json.location);
                    progress_bar_{{$f['identification']}}(100);
                } else {
                    error(identification);
                }
            };
            xhr.upload.onprogress = function (event) {
                if (event.lengthComputable) {
                    var progress = (event.loaded / event.total) * 100; // 更新上传进度条逻辑
                    if (progress == 100) progress = 99;
                    progress_bar_{{$f['identification']}}(progress);
                }
            };
            formData = new FormData();
            formData.append('file', $(`#${identification}Input`)[0].files[0]);
            formData.append('moduleName', '{{$_GET['moduleName']}}');//此处与源文档不一样
            formData.append('action', 'uploadImg');//此处与源文档不一样
            formData.append('model', '{{$_GET['model']}}');//此处与源文档不一样
            xhr.send(formData);
        }

        function error(identification) {
            layer.closeAll();
            layer.msg('上传失败', {icon: 2});
            $(`#uniform-${identification}Input .filename`).html('');
            $(`#${identification}Input`).val('');
            $(`#${identification}Div .progress .bar`).css('width', '0%');
            $(`#${identification}Div .number`).text('0');
        }

        function progress_bar_{{$f['identification']}}(progress) {
            $("#{{$f['identification']}}Div .progress .bar").css('width', progress + '%');
            $("#{{$f['identification']}}Div .number").text(parseInt(progress, 10) + '%');
        }
    </script>
@endif



