<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>URL minimizer</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css"
          integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="{{ url('/css/main.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ url('/css/snackbar.css') }}">
</head>
<body>
<header>
    <nav class="navbar navbar-expand-sm bg-light navbar-light">
        <a class="navbar-brand" href="{{url('/')}}">Сокращатель</a>

        <ul class="navbar-nav float-right">
            <li class="nav-item">
                <a class="nav-link" href="{{url('/stat')}}">Статистика</a>
            </li>
        </ul>
    </nav>
</header>

<div class="main-contrainer">
    <div class="container">
        <br>
        <h1>Сократи свою ссылку</h1>
        <br>
        <form id="main_form" action="{{ action('LinkController@create') }}" method="POST">
            <div class="input-group mb-3">
                <input type="url" required="required" style="max-width: 70%" id="root_url" name="root_url"
                       class="form-control"
                       placeholder="Введите url">
                <input type="submit" id="result_btn" value="Сократить">
            </div>


            <label style="color: white;" for="custom-url">Индивидуальная ссылка(не обязательно)</label>
            <div style="width: 70%" class="input-group mb-3">
                <div class="input-group-prepend">
                    <span class="input-group-text">{{ route('main').'/' }}</span>
                </div>
                <input style="max-width: 100%" type="text" class="form-control" id="custom_url" name="custom_url">
            </div>

            <label style="color: white;" for="expires_at">Дата истечения(не обязательно)</label>
            <div class="form-group">
                <input style="max-width: 70%" type="date" class="form-control" id="expiry_date" name="expiry_date">
            </div>
            <br>
            <br>
        </form>

        <div id="success_snackbar"></div>
        <div id="failed_snackbar"></div>
    </div>
</div>
<br>
<br>
<div class="container text-center">
    <h3>Вы всегда можете посмотреть статистику по ссылкам <a href="{{url('/stat')}}">здесь</a></h3>
</div>
<script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
<script src="{{asset('js/main.js')}}"></script>
</body>
</html>