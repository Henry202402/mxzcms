<div class="{{$cssClass}}">
    <div class="container">
        <div class="row ">
            <div class="col-md-12">

                <div class="promo-title-wrapper ">
                    <h3 class="promo-title">
                        {{$data->name}}
                    </h3>
                    <p class="promo-description">
                        {{$data->home_page_describe}}
                    </p>
                </div>

                <div class="main-content">
                    @foreach(getListByModel($data,$data->home_page_num) as $d)
                        @foreach(json_decode($data->fields,true) as $field)
                            {!! toArray($d)[$field['identification']] !!}
                        @endforeach
                    @endforeach
                </div>

            </div>
        </div>
    </div>
</div>
