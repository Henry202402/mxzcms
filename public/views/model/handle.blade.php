<div id="content">
    <div class="container">
        @if(session('pageDataMsg'))
            <div class="alert {{session('pageDataStatus') == 200 ? 'alert-success' : 'alert-danger'}}">{{session('pageDataMsg')}}</div>
        @endif
        <div class="panel panel-default">
            <div class="panel-heading">{{$model['name'] ?? '在线提交'}}</div>
            <div class="panel-body">
                <form method="post" action="{{url('handle/'.$param['model'])}}" enctype="multipart/form-data">
                    {{csrf_field()}}
                    @foreach(($model['frontend_schema']['form'] ?? []) as $field)
                        @php($required = ($field['required'] ?? '') === 'required')
                        <div class="form-group">
                            <label>{{$field['name']}}@if($required)<span style="color:red;">*</span>@endif</label>
                            @if(in_array($field['formtype'], ['textarea', 'editor', 'json', 'code'], true))
                                <textarea name="{{$field['identification']}}" class="form-control" rows="5" @if($required) required @endif></textarea>
                            @elseif(in_array($field['formtype'], ['select'], true))
                                <select name="{{$field['identification']}}" class="form-control" @if($required) required @endif>
                                    <option value="">请选择</option>
                                    @foreach(($field['datas'] ?? []) as $option)
                                        <option value="{{$option['value']}}">{{$option['name']}}</option>
                                    @endforeach
                                </select>
                            @elseif(in_array($field['formtype'], ['selectMore', 'multipleSelect'], true))
                                <select name="{{$field['identification']}}[]" class="form-control" multiple @if($required) required @endif>
                                    @foreach(($field['datas'] ?? []) as $option)
                                        <option value="{{$option['value']}}">{{$option['name']}}</option>
                                    @endforeach
                                </select>
                            @elseif(in_array($field['formtype'], ['radio', 'switch'], true))
                                <div>
                                    @foreach(($field['datas'] ?? []) as $option)
                                        <label class="radio-inline" style="margin-right: 15px;">
                                            <input type="radio" name="{{$field['identification']}}" value="{{$option['value']}}" @if($required) required @endif> {{$option['name']}}
                                        </label>
                                    @endforeach
                                </div>
                            @elseif(in_array($field['formtype'], ['checkbox', 'checkboxList'], true))
                                <div>
                                    @foreach(($field['datas'] ?? []) as $option)
                                        <label class="checkbox-inline" style="margin-right: 15px;">
                                            <input type="checkbox" name="{{$field['identification']}}[]" value="{{$option['value']}}"> {{$option['name']}}
                                        </label>
                                    @endforeach
                                </div>
                            @elseif(in_array($field['formtype'], ['upload', 'image'], true))
                                <input type="file" name="{{$field['identification']}}" class="form-control" @if($required) required @endif>
                            @else
                                <input type="text" name="{{$field['identification']}}" class="form-control" @if($required) required @endif>
                            @endif
                            @if(!empty($field['notes']))
                                <p class="help-block">{{$field['notes']}}</p>
                            @endif
                        </div>
                    @endforeach
                    <button type="submit" class="btn btn-primary">提交</button>
                </form>
            </div>
        </div>
    </div>
</div>
