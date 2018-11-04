<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>URL minimizer</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css"
          integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="{{ url('/css/main.css') }}">
</head>
<body>
<header>
    <nav class="navbar navbar-expand-sm bg-light navbar-light">
        <a class="navbar-brand" href="{{url('/')}}">Сокращатель</a>

        <ul class="navbar-nav float-right">
            <li class="nav-item">
                <a class="nav-link" href="{{url('/')}}">Главная</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{url('/stat')}}">Статистика</a>
            </li>
        </ul>
    </nav>
</header>
<div class="err_container">
    <div style="text-align: center;" class="container">
        <br>
        <h1>{{$error_msg}}</h1>
    </div>
</div>
</body>
</html>