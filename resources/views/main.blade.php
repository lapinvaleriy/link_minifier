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
    <link rel="stylesheet" type="text/css" href="{{ url('/css/loginModal.css') }}">
</head>
<body>
<header>
    <nav class="navbar navbar-expand-sm bg-light navbar-light">
        <a class="navbar-brand" href="#">Сокращатель</a>

        <ul class="navbar-nav mr-auto"></ul>

        <ul class="navbar-nav float-right">
            @guest
                <li class="nav-item">
                    <a class="nav-link" href="#" onclick="document.getElementById('loginform').style.display='block';">Войти</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#" onclick="document.getElementById('regform').style.display='block';">Регистрация</a>
                </li>
            @endguest

            @auth
                <li class="nav-item">
                    <a class="nav-link" href="{{url('/logout')}}">Выход</a>
                </li>
            @endauth
        </ul>
    </nav>
</header>

<div class="main-contrainer">
    <div class="container">
        <br>
        <h1>Сократи свою ссылку</h1>
        <br>
        <div class="input-group mb-3">
            <input style="max-width: 70%" id="root" name="root" type="text" maxlength="250" class="form-control"
                   placeholder="Введите url">
            <button type="button" id="result_btn" onclick="getShortUrl()">Cократить</button>
        </div>

        @auth
            <div class="individual">
                <span>Хотите индивидуальную ссылку?</span>
                <button type="button" id="indiv_yes" class="btn btn-light">Да</button>
                <a href="#" data-toggle="tooltip" title="Вы можете создать свою индивидульную ссылку">Что это значит?</a>
                <div id="panel">
                    Hello world
                </div>
            </div>
        @endauth

        <br>
        <div>
            <input style="width: 70%;background-color: #ffffff" class="form-control" id="result" readonly
                   placeholder="Результат...">
            <div id="successSnackbar"></div>
            <div id="failedSnackbar"></div>
        </div>
    </div>
</div>
<br>
<br>
<br>

@auth
    <div class="container">
        <h2>Ваши ссылки</h2>
        <div class="table-responsive">
            <table class="table">
                <thead>
                <tr>
                    <th>#</th>
                    <th></th>
                    <th>Lastname</th>
                    <th>Age</th>
                    <th>City</th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td>1</td>
                    <td>Anna</td>
                    <td>Pitt</td>
                    <td>35</td>
                    <td>New York</td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>
@endauth

@guest
    <div class="container">
        <h3 style="text-align: center">Хотите иметь возможность создавать индивидуальные ссылки? А также просматривать
            историю и
            статистику? <a href="#" onclick="document.getElementById('loginform').style.display='block';">Войдите</a>
        </h3>
    </div>
@endguest


<div id="loginform" class="login-modal">
    <form class="login-modal-content modal-animate">
        <span onclick="document.getElementById('loginform').style.display='none'" class="login-close">&times;</span>
        <h3 class="top-text">Вход</h3>
        <div class="login-container">
            <input class="login-input" type="text" placeholder="Введите email..." name="username" id="login-uname"
                   required>
            <input class="login-input" type="password" placeholder="Введите пароль..." name="password" id="login-pwd"
                   required>
            <input type="button" class="login-button" onclick="loginUser()" value="Вход">
            <div id="login-failed"></div>
        </div>
        <div class="login-container" style="background-color:#f1f1f1">
            <span>Нет аккаунта? <a href="#" onclick="openRegModal()">Регистрация</a></span>
        </div>
    </form>
</div>


<div id="regform" class="login-modal">
    <form class="login-modal-content modal-animate" action="{{'UserController@register'}}">
        <span onclick="document.getElementById('regform').style.display='none'" class="login-close">&times;</span>
        <h3 class="top-text">Регистрация</h3>
        <div class="login-container">
            <input class="login-input" type="text" placeholder="Ваш email" id="reg-email" required>
            <input class="login-input" type="password" placeholder="Пароль" id="reg-psw" required minlength="6"
                   maxlength="14">
            <input type="button" class="login-button" onclick="registerUser()" value="Регистрация">
            <div id="reg-failed"></div>
        </div>
    </form>
</div>


<script>
    $(document).ready(function () {
        $('[data-toggle="tooltip"]').tooltip();
    });

    $(document).ready(function(){
        $("#indiv_yes").click(function(){
            $("#panel").slideToggle("slow");
        });
    });

    function getShortUrl() {
        let rootUrl = $("#root").val();
        $('#result').val('');

        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type: 'POST',
            url: '/minify',
            data: {
                "root_url": rootUrl
            },
            success: function (data) {
                if (data.status === 'success') {
                    $("#result").val(data.result);
                    showSnackbar(data.status, data.msg);
                } else if (data.status === 'failed') {
                    showSnackbar(data.status, data.msg);
                }
            }
        });
    }

    function loginUser() {
        let email = $("#login-uname").val();
        let password = $("#login-pwd").val();

        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type: 'POST',
            url: '/login',
            data: {
                "email": email,
                "password": password
            },
            success: function (data) {
                if (data.status === 'success') {
                    $("#loginform").hide();
                    location.reload();
                } else if (data.status === 'failed') {
                    $("#login-failed").text(data.msg);
                }
            }
        });
    }

    function registerUser() {
        let email = $("#reg-email").val();
        let password = $("#reg-psw").val();

        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type: 'POST',
            url: '/register',
            data: {
                "email": email,
                "password": password
            },
            success: function (data) {
                if (data.status === 'success') {
                    $("#regform").hide();
                } else if (data.status === 'failed') {
                    $("#reg-failed").text(data.msg);
                }
            }
        });
    }


    function showSnackbar(status, msg) {
        let snackBar;

        if (status === "success") {
            snackBar = document.getElementById("successSnackbar");
        } else if (status === "failed") {
            snackBar = document.getElementById("failedSnackbar");
        }

        snackBar.className = "show";

        if (status === "success") {
            $("#successSnackbar").text(msg);
        } else if (status === "failed") {
            $("#failedSnackbar").text(msg);
        }

        setTimeout(function () {
            snackBar.className = snackBar.className.replace("show", "");
        }, 3000);
    }

    function openRegModal() {
        $("#loginform").hide();
        $("#regform").show();
    }
</script>

<script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
</body>
</html>