<script src="{{HOME_ASSET}}default/assets/js/all.js"></script>
<script src="{{HOME_ASSET}}default/assets/js/custom.js"></script>
<script src="{{asset("views/admin/assets/layer/layer/layer.js")}}"></script>
@foreach(hook("GJsCss") as $v)
    {!! $v !!}
@endforeach
