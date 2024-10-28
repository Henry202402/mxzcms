<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title></title>
    <style>
        pre {
            display: block;
            padding: 9.5px;
            margin: 0 0 10px;
            font-size: 13px;
            line-height: 0.7;
            color: #333;
            word-break: break-all;
            word-wrap: break-word;
            background-color: #f5f5f5;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        .h-line{
            border-bottom: 1px solid #ccc;
            padding-bottom: 15px;
        }
    </style>
</head>
<body>
<pre class="crontab-log"
     style="overflow: auto; border: 0 none; padding: 15px; margin: 0;white-space: pre-wrap; height: 400px; background-color: rgb(51,51,51);color:#f1f1f1;border-radius:0;">
    @foreach($data as $d)
        <div>{{$d['context']['result']}}</div>
        <div class="h-line">★[{{$d['created_at']}}] Successful</div>
    @endforeach
</pre>
</body>
</html>