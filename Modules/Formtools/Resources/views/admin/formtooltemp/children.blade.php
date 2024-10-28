@if($d->children)
    @foreach($d->children as $dd)
        <tr class="
        @foreach(explode(',',$dd->pid_path) as $pid)
                subpid_path_{{$pid}}
        @endforeach

                ">
            @foreach($pageData['fields'] as $f)
                @if($f['identification']!="rightaction")
                    @if($f['callback'])
                        <td style="padding-left: {{$level*30}}px" class="{{is_array($f['cssClass'])?$f['cssClass'][toArray($dd)[$f['identification']]]:$f['cssClass']}}"
                            @if($f['jsfunction']) onclick="{{$f['jsfunction']}}('{{toArray($dd)[$f['identification']]}}')" @endif>
                            @if($f['datas'])
                                <label class="label {{toArray($dd)[$f['identification'].'CssClass']?:'label-info'}}">{{$f['callback']($f['datas'][toArray($dd)[$f['identification']]])}}</label>
                            @elseif(strpos($f['identification'], ',') !== false)
                                @foreach(explode(',',$f['identification']) as $name)
                                    {{toArray($dd)[$name]}}<br>
                                @endforeach
                            @else
                                {{$f['callback'](toArray($dd)[$f['identification']])}}
                            @endif
                        </td>
                    @elseif($f['formtype']=='file')
                        <td style="padding-left: {{$level*30}}px">
                            @if(in_array(end(explode('.',toArray($dd)[$f['identification']])),['jpg','jpeg','png']))
                                <img src="{{GetUrlByPath(toArray($dd)[$f['identification']])}}"
                                     class="cursor-pointer" width="30"
                                     onclick="clickImage('{{GetUrlByPath(toArray($dd)[$f['identification']])}}')">
                            @else
                                <i class="cursor-pointer icon-file-download2"
                                   onclick="fileDownload('{{GetUrlByPath(toArray($dd)[$f['identification']])}}')"
                                   style="font-size: 25px;"></i>
                            @endif
                        </td>
                    @else
                        <td style="padding-left: {{$level*30}}px" class="{{is_array($f['cssClass'])?$f['cssClass'][toArray($dd)[$f['identification']]]:$f['cssClass']}}"
                            @if($f['jsfunction']) onclick="{{$f['jsfunction']}}('{{toArray($dd)[$f['identification']]}}')" @endif>
                            @if($f['datas'])
                                <label class="label {{toArray($dd)[$f['identification'].'CssClass']?:'label-info'}}">{{$f['datas'][toArray($dd)[$f['identification']]]}}</label>
                            @elseif(strpos($f['identification'], ',') !== false)
                                @foreach(explode(',',$f['identification']) as $name)
                                    @if(toArray($dd)[$name])
                                        {{toArray($dd)[$name]}}<br>
                                    @endif
                                @endforeach
                            @else
                                {{toArray($dd)[$f['identification']]}}
                            @endif

                        </td>
                    @endif

                @elseif($f['identification']=="rightaction")
                    <td>
                        @if(!in_array(toArray($dd)[$f['actionby']],$f['notIdArray']?:[]))
                            @foreach($f['datas'] as $act)
                                @if(!$act["show"] || ($act["show"] && toArray($dd)[$act["show"]['field']] == $act["show"]['value']))
                                    @if(!in_array(toArray($dd)[$f['actionby']],$act['notIdArray']?:[]))
                                        <a
                                                @if($act['confirm'])
                                                onclick="confirm('{{$act['actionUrl']}}?{{http_build_query(array_merge($act['param']?:[],[$f['actionby']=>toArray($dd)[$f['actionby']]]))}}')"
                                                @else
                                                href="{{$act['actionUrl']}}?{{http_build_query(array_merge($act['param']?:[],[$f['actionby']=>toArray($dd)[$f['actionby']]]))}}">
                                            @endif

                                            <button type="button"
                                                    class="h-button-edit btn {{$act['cssClass']}} btn-xs">
                                                {{$act['actionName']}}
                                            </button>
                                        </a>
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
