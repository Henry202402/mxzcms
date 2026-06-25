@if($homeMenu['bottomMenu'])
<div class="footer-extended mx-footer-top">
    <div class="container">
        <div class="mx-footer-top__grid">
            @foreach($homeMenu['bottomMenu'] as $menu)
                <div class="mx-footer-column">
                    <h5 class="mx-footer-column__title">{{$menu['name']}}</h5>
                    <ul class="mx-footer-column__list">
                        @foreach($menu['child'] as $child)
                            <li><a href="{{$child['url']}}" target="{{$child['target'] ?? '_self'}}" @if(($child['target'] ?? '_self') === '_blank') rel="noopener noreferrer" @endif>{{$child['name']}}</a></li>
                        @endforeach
                    </ul>
                </div>
            @endforeach
        </div>
    </div>
</div>
@endif
