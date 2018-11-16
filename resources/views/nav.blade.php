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