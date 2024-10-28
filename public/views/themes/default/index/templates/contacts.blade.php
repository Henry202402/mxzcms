<div class="{{$cssClass}}">
    <div class="container">
        <div class="row">
            <div class="promo-title-wrapper ">
                <h3 class="promo-title">
                    {{$data->name}}
                </h3>
                <p class="promo-description">
                    {{$data->home_page_describe}}
                </p>
            </div>
            <div class="col-md-12">
                <!-- Contacts info -->
                <div class="contacts-info">
                    @foreach(getListByModel($data,$data->home_page_num) as $d)
                        @foreach(json_decode($data->fields,true) as $field)
                            <h4 class="contacts-info-title">
                                {{$field['name']}}
                            </h4>
                            <div class="contacts-info-data">
                                <a href="">
                                    {!! toArray($d)[$field['identification']] !!}
                                </a>
                            </div>
                        @endforeach
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
