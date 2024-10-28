<div class="{{$cssClass}}">
    <div class="container ">
        <div class="row">
            <div class="col-md-12">
                <!-- Promo Title -->
                <div class="promo-title-wrapper ">
                    <h3 class="promo-title">
                        {{$data->name}}
                    </h3>
                    <p class="promo-description">
                        {{$data->home_page_describe}}
                    </p>
                </div>
                <!-- End of Promo Title -->

                <div class="row">
                    <div class="col-md-12">
                        <!-- Category List -->
                        <div class="category-list">
                            <style>
                                .category-list-content-item{
                                    width: 33%;
                                    float: left;
                                }
                            </style>
                            <ul class="category-list-content">

                                @foreach(getListByModel($data,$data->home_page_num) as $d)
                                    <li class="category-list-content-item">
                                        <a href="{{url("detail/".$data->access_identification."/".$d->id)}}" class="category-list-content-item-text">{{$d->title}}</a>
                                    </li>
                                @endforeach

                            </ul>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <div class="helper center">
                            <a href="{{url("list/".$data->access_identification)}}" class="faq-grid-show-more">查看更多 <i class="fa fa-angle-right"></i></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
