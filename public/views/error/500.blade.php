<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>500</title>
    <style type="text/css">
        body {
            font-family: 'Courgette', cursive;
        }

        body {
            background-color: rgba(247, 250, 252, var(--bg-opacity));
        }

        .wrap {
            margin: 0 auto;
        }

        .logo {
            margin-top: 50px;
        }

        .logo h1 {
            font-size: 200px;
            color: #8F8E8C;
            text-align: center;
            margin-bottom: 1px;
            text-shadow: 1px 1px 6px #fff;
        }

        .logo p {
            color: rgb(228, 146, 162);
            font-size: 20px;
            margin-top: 1px;
            text-align: center;
        }

        .logo p span {
            color: lightgreen;
        }

        .sub a {
            color: white;
            background: #8F8E8C;
            text-decoration: none;
            padding: 7px 120px;
            font-size: 13px;
            font-family: arial, serif;
            font-weight: bold;
            -webkit-border-radius: 3em;
            -moz-border-radius: .1em;
            -border-radius: .1em;
        }
    </style>
</head>
<body>
<div class="wrap">
    <div class="logo">
        <h1>500</h1>
        <p>
            错误信息：{{str_replace(base_path(), '.', $msg)}}
        </p>
        <p>
            所在文件：{{str_replace(base_path(), '.', $file)}}
        </p>
        <p>
            行号：{{$line}}
        </p>
        <div class="sub">
            <p><a href="{{url('/')}}">回到首页</a></p>
        </div>
    </div>
</div>
</body>
</html>
