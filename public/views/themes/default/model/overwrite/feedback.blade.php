@php
    $fields = $model['frontend_schema']['form'] ?? [];
    $pageTitle = $model['home_config']['home_page_title'] ?? ($model['name'] ?? '在线留言');
    $pageDescription = $model['home_config']['home_page_describe'] ?? '填写你的问题、需求或建议，我们会尽快回复。';
    $records = [];
    if (isset($data)) {
        if (is_object($data) && method_exists($data, 'items')) {
            $records = $data->items();
        } elseif (is_iterable($data)) {
            foreach ($data as $item) {
                $records[] = $item;
            }
        }
    }
@endphp

<div id="content">
    <div class="container">
        @if(session('pageDataMsg'))
            <div class="alert {{session('pageDataStatus') == 200 ? 'alert-success' : 'alert-danger'}}">{{session('pageDataMsg')}}</div>
        @endif

        <div class="mx-page-shell">
            <div class="mx-main-card">
                <h1 class="mx-form-title">{{ $pageTitle }}</h1>
                <p class="mx-form-description">{{ $pageDescription }}</p>

                <form method="post" action="{{ url('handle/' . $param['model']) }}" enctype="multipart/form-data" class="mx-stack">
                    {{ csrf_field() }}

                    <div class="mx-field-grid">
                        @forelse($fields as $field)
                            @php
                                $required = ($field['required'] ?? '') === 'required';
                                $formtype = $field['formtype'] ?? 'text';
                                $options = $field['datas'] ?? [];
                                if (is_string($options)) {
                                    $options = json_decode($options, true) ?: [];
                                }
                                $isFullWidth = in_array($formtype, ['textarea', 'editor', 'json', 'code', 'upload', 'image', 'checkbox', 'checkboxList', 'selectMore', 'multipleSelect'], true);
                            @endphp

                            <div class="{{ $isFullWidth ? 'mx-field--full' : '' }}">
                                <label class="contacts-label">
                                    {{ $field['name'] ?? $field['identification'] }}
                                    @if($required)
                                        <span style="color:#dc2626;">*</span>
                                    @endif
                                </label>

                                @if(in_array($formtype, ['textarea', 'editor', 'json', 'code'], true))
                                    <textarea name="{{ $field['identification'] }}" class="form-control" rows="6" @if($required) required @endif></textarea>
                                @elseif($formtype === 'select')
                                    <select name="{{ $field['identification'] }}" class="form-control" @if($required) required @endif>
                                        <option value="">请选择</option>
                                        @foreach($options as $option)
                                            <option value="{{ $option['value'] ?? '' }}">{{ $option['name'] ?? ($option['value'] ?? '') }}</option>
                                        @endforeach
                                    </select>
                                @elseif(in_array($formtype, ['selectMore', 'multipleSelect'], true))
                                    <select name="{{ $field['identification'] }}[]" class="form-control" multiple @if($required) required @endif>
                                        @foreach($options as $option)
                                            <option value="{{ $option['value'] ?? '' }}">{{ $option['name'] ?? ($option['value'] ?? '') }}</option>
                                        @endforeach
                                    </select>
                                @elseif(in_array($formtype, ['radio', 'switch'], true))
                                    <div class="mx-stack">
                                        @foreach($options as $option)
                                            <label class="contacts-label" style="margin-bottom:0;font-weight:500;">
                                                <input type="radio" name="{{ $field['identification'] }}" value="{{ $option['value'] ?? '' }}" @if($required) required @endif>
                                                <span>{{ $option['name'] ?? ($option['value'] ?? '') }}</span>
                                            </label>
                                        @endforeach
                                    </div>
                                @elseif(in_array($formtype, ['checkbox', 'checkboxList'], true))
                                    <div class="mx-stack">
                                        @foreach($options as $option)
                                            <label class="contacts-label" style="margin-bottom:0;font-weight:500;">
                                                <input type="checkbox" name="{{ $field['identification'] }}[]" value="{{ $option['value'] ?? '' }}">
                                                <span>{{ $option['name'] ?? ($option['value'] ?? '') }}</span>
                                            </label>
                                        @endforeach
                                    </div>
                                @elseif(in_array($formtype, ['upload', 'image', 'file'], true))
                                    <input type="file" name="{{ $field['identification'] }}" class="form-control" @if($required) required @endif>
                                @else
                                    <input
                                        type="{{ in_array($formtype, ['email', 'url', 'number', 'date', 'time'], true) ? $formtype : 'text' }}"
                                        name="{{ $field['identification'] }}"
                                        class="form-control"
                                        @if($required) required @endif
                                    >
                                @endif

                                @if(!empty($field['notes']))
                                    <p class="mx-submit-tip">{{ $field['notes'] }}</p>
                                @endif
                            </div>
                        @empty
                            <div class="mx-empty mx-field--full">当前留言模型没有可显示的前台字段，请先在后台启用前台留言表单字段。</div>
                        @endforelse
                    </div>

                    @if($fields)
                        <div class="mx-submit-bar">
                            <span class="mx-submit-tip">你的留言会立即入库，页面支持继续扩展图片、附件等表单字段。</span>
                            <button type="submit" class="button h-sub">提交留言</button>
                        </div>
                    @endif
                </form>
            </div>

            <aside class="mx-side-card">
                <div class="mx-stack">
                    <div>
                        <h3 class="contacts-info-title">最新留言</h3>
                        @if($records)
                            <div class="mx-card-list">
                                @foreach(array_slice($records, 0, 6) as $record)
                                    @php($item = (array) $record)
                                    <div class="mx-card">
                                        <h4 class="mx-card__title" style="font-size:17px;">{{ $item['full_name'] ?? '匿名访客' }}</h4>
                                        <p class="mx-card__desc">{{ $item['content'] ?? '暂无留言内容' }}</p>
                                        <div class="mx-card__meta">
                                            <span>{{ $item['company'] ?? '访客留言' }}</span>
                                            @if(!empty($item['created_at']))
                                                <span>{{ $item['created_at'] }}</span>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="mx-empty">当前还没有留言记录，你可以先提交第一条留言。</div>
                        @endif
                    </div>

                    <div>
                        <h3 class="contacts-info-title">说明</h3>
                        <p class="contacts-info-description">该页面同时作为留言表单和留言展示页使用，首页和菜单都可以直接跳到这里。</p>
                    </div>
                </div>
            </aside>
        </div>

        @if(isset($data) && is_object($data) && method_exists($data, 'links'))
            <div class="row">
                <div class="col-md-12">
                    {{ $data->appends($_GET)->links('themes.default.public.pagination',['data'=>['side_num'=>2,'page_position'=>data_get($model, 'home_config.list_page_template', $model['list_page_template'] ?? 'center')]]) }}
                </div>
            </div>
        @endif
    </div>
</div>
