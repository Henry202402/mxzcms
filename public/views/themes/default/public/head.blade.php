<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="token" id="token" content="{{csrf_token()}}">
    {!! hook("Seo",compact("model","data"))[0] !!}
    <!-- Favicon  -->
    <link rel="icon" type="image/png" href="{{GetLocalFileByPath(cacheGlobalSettingsByKey('webicon'))}}">
    <!-- Include StyleSheets -->
    <link rel="stylesheet" href="{{HOME_ASSET}}default/assets/css/style.min.css">
    <link rel="stylesheet" href="{{HOME_ASSET}}default/assets/css/h-style.css">
    <!-- <link rel="stylesheet" href="css/custom.css"> -->
    <script src="{{HOME_ASSET}}default/assets/js/modernizr.js"></script>
    <script>
        var urlPre = "{{url('/')}}/";
    </script>
</head>
