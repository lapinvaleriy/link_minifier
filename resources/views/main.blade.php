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
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <a class="navbar-brand" href="#">URL minifier</a>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav">
                <li class="nav-item active">
                    <a class="nav-link" href="#">Главная <span class="sr-only">(current)</span></a>
                </li>
                <li class="nav-item active">
                    <a class="nav-link" href="#">Статистика <span class="sr-only">(current)</span></a>
                </li>
            </ul>
        </div>
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
<div class="">
    <h3 style="text-align: center">Хотите возможность создавать индивидуальные ссылки? А также увидеть историю и
        статистику? <a href="#" onclick="document.getElementById('loginform').style.display='block';">Войдите</a></h3>
</div>


<div id="loginform" class="login-modal">
    <form class="login-modal-content modal-animate">
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
        let modal = document.getElementById('loginform');
        modal.style.display = "none";

        document.getElementById('regform').style.display = 'block';
    }

    // // Get the modal
    // let modal = document.getElementById('loginform');
    //
    // // When the user clicks anywhere outside of the modal, close it
    // window.onclick = function (event) {
    //     if (event.target == modal) {
    //         modal.style.display = "none";
    //     }
    // }

    // let regmodal = document.getElementById('regform');
    //
    // // When the user clicks anywhere outside of the modal, close it
    // window.onclick = function (event) {
    //     if (event.target == regmodal) {
    //         regmodal.style.display = "none";
    //     }
    // }
</script>

<script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
</body>
</html>