@if($homeMenu['bottomMenu'])
<div class="footer-extended">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="footer-extended-container">
                    <div class="row">
                        @foreach($homeMenu['bottomMenu'] as $menu)
                            <div class="col-md-2 col-sm-2 col-xs-4">
                                <div class="footer-extended-menu">
                                    <h5 class="footer-extended-menu-title text-primary">{{$menu['name']}}</h5>
                                    <ul class="footer-extended-menu-list">
                                        @foreach($menu['child'] as $child)
                                            <li><a href="{{$child['url']}}">{{$child['name']}}</a></li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endif
