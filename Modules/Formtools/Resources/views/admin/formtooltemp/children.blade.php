@if($d->children)
    @foreach($d->children as $dd)
        @php($rowData = toArray($dd))
        @php($rowActionField = $pageData['fields']['rightaction']['actionby'] ?? 'id')
        @php($rowActionId = $rowData[$rowActionField] ?? ($rowData['id'] ?? ''))
        <tr class="
        @foreach(explode(',',$dd->pid_path) as $pid)
                subpid_path_{{$pid}}
        @endforeach

                ">
            @foreach($pageData['fields'] as $f)
                @if($f['identification']!="rightaction")
                    @if($f['callback'])
                        <td style="padding-left: {{$level*30}}px" class="{{is_array($f['cssClass'])?$f['cssClass'][$rowData[$f['identification']]]:$f['cssClass']}}"
                            @if($f['jsfunction']) onclick="{{$f['jsfunction']}}('{{$rowData[$f['identification']]}}')" @endif>
                            @if($f['datas'])
                                <label class="label {{$rowData[$f['identification'].'CssClass']?:'label-info'}}">{{$f['callback']($f['datas'][$rowData[$f['identification']]])}}</label>
                            @elseif(strpos($f['identification'], ',') !== false)
                                @foreach(explode(',',$f['identification']) as $name)
                                    {{$rowData[$name]}}<br>
                                @endforeach
                            @else
                                {{$f['callback']($rowData[$f['identification']])}}
                            @endif
                        </td>
                    @elseif(in_array($f['formtype'], ['upload', 'uploadAjax', 'image', 'imageAjax'], true))
                        @php($fileValue = $rowData[$f['identification']] ?? '')
                        <td style="padding-left: {{$level*30}}px">
                            @if($fileValue && in_array(strtolower(end(explode('.',$fileValue))),['jpg','jpeg','png']))
                                <img src="{{GetUrlByPath($fileValue)}}"
                                     class="cursor-pointer" width="30"
                                     onclick="clickImage('{{GetUrlByPath($fileValue)}}')">
                            @elseif($fileValue)
                                <i class="cursor-pointer icon-file-download2"
                                   onclick="fileDownload('{{GetUrlByPath($fileValue)}}')"
                                   style="font-size: 25px;"></i>
                            @else
                                -
                            @endif
                        </td>
                    @else
                        <td style="padding-left: {{$level*30}}px" class="{{is_array($f['cssClass'])?$f['cssClass'][$rowData[$f['identification']]]:$f['cssClass']}}"
                            @if($f['jsfunction']) onclick="{{$f['jsfunction']}}('{{$rowData[$f['identification']]}}')" @endif>
                            @if($f['datas'])
                                <label class="label {{$rowData[$f['identification'].'CssClass']?:'label-info'}}">{{$f['datas'][$rowData[$f['identification']]]}}</label>
                            @elseif(strpos($f['identification'], ',') !== false)
                                @foreach(explode(',',$f['identification']) as $name)
                                    @if($rowData[$name])
                                        {{$rowData[$name]}}<br>
                                    @endif
                                @endforeach
                            @else
                                {{$rowData[$f['identification']]}}
                            @endif

                        </td>
                    @endif

                @elseif($f['identification']=="rightaction")
                    <td>
                        @if(!in_array($rowData[$f['actionby']],$f['notIdArray']?:[]))
                            @foreach($f['datas'] as $act)
                                @if(!$act["show"] || ($act["show"] && $rowData[$act["show"]['field']] == $act["show"]['value']))
                                    @if(!in_array($rowData[$f['actionby']],$act['notIdArray']?:[]))
                                        @if($act['actionType']=="modal")
                                            <button type="button" class="h-button-edit btn {{$act['cssClass']}} btn-xs" data-toggle="modal" data-target="#modal_default_{{$rowActionId}}">{{$act['actionName']}}</button>
                                            <div id="modal_default_{{$rowActionId}}" class="modal fade">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                            <h5 class="modal-title">{{$rowData[$act['titleField']]?:$act['titleField']}}的详情</h5>
                                                        </div>
                                                        <div class="modal-body" id="modal_default_content_{{$rowActionId}}" style="height: 600px;overflow: auto">
                                                            {!! $rowData[$act['field']] !!}
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-link" data-dismiss="modal">关闭</button>
                                                            <button type="button" class="btn btn-info" onclick="exportImg('modal_default_content_{{$rowActionId}}')">导出为图片</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @else
                                            @php($rowActionUrl = rtrim($act['actionUrl'].'?'.http_build_query(array_merge($act['param']?:[],[$f['actionby']=>$rowData[$f['actionby']]])),"?"))
                                            <a
                                                @if($act['popup'])
                                                    onclick="popupPage('{{$rowActionUrl}}')"
                                                @elseif($act['confirm'])
                                                    onclick="formtoolConfirmNavigate('{{$rowActionUrl}}')"
                                                @else
                                                    href="{{$rowActionUrl}}"
                                                @endif
                                                @if(!empty($act['target'])) target="{{$act['target']}}" @endif>
                                                <button type="button" class="h-button-edit btn {{$act['cssClass']}} btn-xs">
                                                    {{$act['actionName']}}
                                                </button>
                                            </a>
                                        @endif
                                    @endif
                                @endif
                            @endforeach
                        @endif
                    </td>
                @endif

            @endforeach
        </tr>
        @if($dd->children)
            @include(moduleAdminTemplate("formtools")."formtooltemp.children",['d'=>$dd,'pageData'=>$pageData,'level'=>$level+1])
        @endif
    @endforeach
@endif
