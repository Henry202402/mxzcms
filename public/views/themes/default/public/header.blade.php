@if($model['home_page_title'] || $model['home_page_describe'])
    <div class="header-back header-back-simple header-back-small">
        <div class="header-back-container">
            <div class="container">
                <div class="row">
                    <div class="col-md-12">
                        <!-- Page Info -->
                        <div class="page-info page-info-simple">

                            <h1 class="page-title">{!! $model['home_page_title'] !!}</h1>


                            <h2 class="page-description">{!! $model['home_page_describe'] !!}</h2>

                        </div>
                        <!-- End Page Info -->
                    </div>
                </div>
            </div>
        </div>
    </div>
@endif
