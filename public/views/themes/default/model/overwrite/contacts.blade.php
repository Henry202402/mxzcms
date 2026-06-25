<div id="content">
    <div class="container">
        @php($record = frontendRecordData($detailRecord ?? $data ?? []))
        <div class="mx-detail-shell">
            @include('themes.default.public.detailHero', [
                'model' => $model,
                'data' => $data,
                'detailRecord' => $record,
                'detailTitle' => $model['home_config']['detail_page_title'] ?? ($model['home_config']['home_page_title'] ?? ($model['name'] ?? '联系我们')),
                'detailSummary' => $model['home_config']['detail_page_describe'] ?? ($model['home_config']['home_page_describe'] ?? ''),
            ])

            <div class="mx-detail-contact-grid">
                @if(($record['is_open_leave'] ?? 2) == 1)
                    <div class="mx-detail-contact-form">
                        <h3 class="mx-detail-contact-form__title">{{ themeTrans('contacts.leave_message') }}</h3>
                        <form action="" id="myForm">
                            <ul class="contacts-inputs">
                                @foreach(\Modules\Formtools\Services\ServiceModel::getLeaveField() as $field)
                                    @if($field['is_show_home_form']==1)
                                        <li class="contacts-item">
                                            <label for="full-name" class="contacts-label">
                                                {{$field['name']}}
                                                @if($field['required']=='required')<span style="color:red;">*</span>@endif
                                            </label>
                                            @if($field['formtype']=='text')
                                                <input type="text" name="{{$field['identification']}}" class="contacts-input form-control" @if($field['required']=='required') required @endif>
                                            @elseif($field['formtype']=='textarea')
                                                <textarea name="{{$field['identification']}}" @if($field['required']=='required') required @endif class="contacts-input form-control"></textarea>
                                            @elseif($field['formtype']=='radio')
                                                @foreach($field['datas'] as $datas)
                                                    <label class="radio-inline">
                                                        <input type="radio" name="{{$field['identification']}}" class="contacts-radio" value="{{$datas['value']}}" @if($field['required']=='required') required @endif>
                                                        <span>{{$datas['name']}}</span>
                                                    </label>
                                                @endforeach
                                            @endif
                                        </li>
                                    @endif
                                @endforeach
                            </ul>
                            <button type="submit" class="button blue full h-sub">{{ themeTrans('contacts.submit_message') }}</button>
                        </form>
                    </div>
                @endif

                <div class="mx-detail-contact-card">
                    <h3 class="mx-detail-contact-card__title">{{ $model['name'] ?? '联系信息' }}</h3>
                    <div class="mx-detail-contact-list">
                        @foreach($model['fields'] as $fields)
                            @if($fields['is_show_home_form']==1 && !empty($record[$fields['identification']]))
                                <div class="mx-detail-contact-item">
                                    <span class="mx-detail-contact-item__label">{{$fields['name']}}</span>
                                    <p class="mx-detail-contact-item__value">{{$record[$fields['identification']]}}</p>
                                </div>
                            @endif
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
