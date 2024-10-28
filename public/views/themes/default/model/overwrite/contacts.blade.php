<div id="content">
    <div class="container">
        <div class="row">

            @if($list[0]['is_open_leave']==1)
                <div class="col-md-7">
                    <!-- Contacts -->
                    <div class="contacts">
                        <h3 class="contacts-title">
                            给我们留言吧
                        </h3>

                        <!-- Success Modal -->
                        {{--<div class="contacts-success js-contacts-success">
                            <i class="pe-7s-check"></i>
                            Your message was sent successfully. <br>Thank you!
                        </div>--}}

                        <!-- End of Success Modal -->
                        <form action="" id="myForm">
                            <ul class="contacts-inputs">
                                @foreach(\Modules\Formtools\Services\ServiceModel::getLeaveField() as $field)
                                    @if($field['is_show_home_form']==1)
                                        <li class="contacts-item">
                                            <label for="full-name" class="contacts-label">
                                                {{$field['name']}}
                                                @if($field['required']=='required')<span
                                                    style="color:red;">*</span>@endif
                                            </label>
                                            @if($field['formtype']=='text')
                                                <input type="text" name="{{$field['identification']}}"
                                                       class="contacts-input form-control"
                                                       @if($field['required']=='required') required @endif>
                                            @elseif($field['formtype']=='textarea')
                                                <textarea name="{{$field['identification']}}"
                                                          @if($field['required']=='required') required @endif
                                                          class="contacts-input form-control"></textarea>
                                            @elseif($field['formtype']=='radio')
                                                @foreach($field['datas'] as $datas)
                                                    <label class="radio-inline">
                                                        <input type="radio" name="{{$field['identification']}}"
                                                               class="contacts-radio"
                                                               value="{{$datas['value']}}"
                                                               @if($field['required']=='required') required @endif>
                                                        <span>{{$datas['name']}}</span>
                                                    </label>
                                                @endforeach
                                            @endif
                                        </li>
                                    @endif
                                @endforeach
                            </ul>
                            <button type="submit" class="button blue full h-sub">提交信息</button>
                        </form>
                    </div>
                    <!-- End of Contacts -->
                </div>
            @endif
            <div class="col-md-5">
                <!-- Contacts info -->
                <div class="contacts-info">
                    @foreach($model['fields'] as $fields)
                        @if($fields['is_show_home_form']==1)
                            <h4 class="contacts-info-title">
                                {{$fields['name']}}
                            </h4>
                            <p class="contacts-info-description">
                                {{$list[0][$fields['identification']]}}
                            </p>

                            {{--<div class="contacts-info-data">
                                <a href="#">support@vsdocs.com</a>
                            </div>--}}
                        @endif
                    @endforeach
                </div>
                <!-- End of Contacts info -->

            </div>
        </div>
    </div>
</div>
