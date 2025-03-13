<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>个人中心</title>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="token" id="token" content="{{csrf_token()}}">
    <!-- Favicon  -->
    <link rel="icon" type="image/png" href="{{GetLocalFileByPath(cacheGlobalSettingsByKey('webicon'))}}">
    <!-- Bootstrap Css -->
    <link href="{{asset("assets/member")}}/css/bootstrap.min.css" rel="stylesheet" type="text/css">
    <!-- Icons Css -->
    <link href="{{asset("assets/member")}}/css/icons.min.css" rel="stylesheet" type="text/css">
    <!-- App Css-->
    <link href="{{asset("assets/member")}}/css/app.min.css" rel="stylesheet" type="text/css">
    <script>
        var domainPre = "{{url('/')}}/";
    </script>
</head>
