@extends('layouts.app')

@section('main-content')
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

    <div id="loginform" class="login-modal">
        <form class="login-modal-content modal-animate">
            <span onclick="document.getElementById('loginform').style.display='none'" class="login-close">&times;</span>
            <h3 class="top-text">Вход</h3>
            <div class="login-container">
                <input class="login-input" type="text" placeholder="Введите email..." name="username" id="login-uname"
                       required>
                <input class="login-input" type="password" placeholder="Введите пароль..." name="password"
                       id="login-pwd"
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

    <br>
    <br>
@endsection

@section('table-content')
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
            <h3 style="text-align: center">Хотите иметь возможность создавать индивидуальные ссылки? А также
                просматривать
                историю и статистику? <a href="#" onclick="document.getElementById('loginform').style.display='block';">Войдите</a>
            </h3>
        </div>
    @endguest
@endsection


<script>
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

    function openRegModal() {
        $("#loginform").hide();
        $("#regform").show();
    }
</script>
