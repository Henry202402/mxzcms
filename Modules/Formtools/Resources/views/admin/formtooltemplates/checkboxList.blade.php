@if($f['formtype']=='checkboxList')
    <style>
        .h-checkbox-one {
            height: 40px;
            line-height: 46px;
            padding-left: 20px;
            font-size: 16px;
            color: #000;
            background: #F3F3F3;
            border-bottom: 1px solid #e1e1e1;
        }
    </style>
    <div class="form-group row">
        <label class="control-label col-lg-1">@if($f['required'])<span style="color:red;">*</span> @endif{{$f['name']}}</label>
        <div class="col-lg-11" style="border: 1px solid #e1e1e1;padding: 0 0 20px 0;">
            <dl class="permission-list" style="margin-top: -16px;">
                <dd>
                    <?php
                    $f['value'] = $f['value'] ? json_decode($f['value'], true) : [];
                    ?>
                    @foreach($f['datas'] as $one)
                        <dl class="cl permission-list2">
                            <dt>
                                <label class="i-checks col-lg-12 h-checkbox-one"
                                       style="margin-top: 12px;font-weight: bold;">
                                    <input type="checkbox"
                                           name="{{$f['identification']}}[]"
                                           style="width: 17px;height: 17px;"
                                           value="{{$one['value']}}" {{$f['disabled']}}
                                           @if(in_array($one['value'],$f['value'])) checked @endif
                                    >
                                    <span class="h-one-title"
                                          style="position: relative;top: -3px;font-size: 18px;cursor: pointer;">{{$one['name']}}</span>

                                </label>
                                <span style="float: right;margin-top: -4px;font-size: 1.3rem;cursor: pointer;font-weight: 400;padding: 0 20px;position: relative;top: -32px;"
                                      onclick="clickToggle(this)"
                                >
                                        <i class="icon-menu-open"></i>
                                    </span>
                            </dt>
                            <dd class="col-lg-12" style="margin-top: -15px;">
                                @foreach($one['children'] as $two)
                                    <label class="checkbox-inline i-checks col-lg-1.5 h-checkbox-two"
                                           style="margin-left: 15px;">
                                        <input type="checkbox" name="{{$f['identification']}}[]"
                                               style="width: 17px;height: 17px;"
                                               value="{{$two['value']}}" {{$f['disabled']}}
                                               @if(in_array($two['value'],$f['value'])) checked @endif
                                        >
                                        <span class="h-two-title"
                                              style="position: relative;top: 2px;font-size: 14px;cursor: pointer;">{{$two['name']}}</span>
                                    </label>
                                @endforeach
                            </dd>
                        </dl>
                    @endforeach
                </dd>
            </dl>
            @if($f['notes'])
                <span class="help-block">{{ $f['notes'] }}</span>
            @endif
        </div>
    </div>

    <script>
        /*按钮选择*/
        $(function () {
            $(".permission-list dt input:checkbox").click(function () {
                $(this).closest("dl").find("dd input:checkbox").prop("checked", $(this).prop("checked"));
            });
            $(".permission-list2 dd input:checkbox").click(function () {
                var l = $(this).parent().parent().find("input:checked").length;
                var l2 = $(this).parents(".permission-list").find(".permission-list2 dd").find("input:checked").length;
                if ($(this).prop("checked")) {
                    $(this).closest("dl").find("dt input:checkbox").prop("checked", true);
                    // $(this).parents(".permission-list").find("dt").first().find("input:checkbox").prop("checked", true);
                } else {
                    if (l == 0) {
                        $(this).closest("dl").find("dt input:checkbox").prop("checked", false);
                    } else if (l2 == 0) {
                        $(this).parents(".permission-list").find("dt").first().find("input:checkbox").prop("checked", false);
                    }
                }

            });

        });

        function clickToggle(obj) {
            var elem = $(obj).closest("dl").find("dd").toggle();
        }
    </script>
@endif
