<script src="{{HOME_ASSET}}default/assets/js/all.js"></script>
<script src="{{HOME_ASSET}}default/assets/js/custom.js"></script>
<script src="{{ADMIN_ASSET}}layer/layer/layer.js"></script>
{!! cacheGlobalSettingsByKey('foot_codes') !!}
@foreach(hook("GJsCss") as $v)
    {!! $v !!}
@endforeach
