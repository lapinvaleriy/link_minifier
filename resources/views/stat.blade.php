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
                <a class="nav-link" href="{{url('/')}}">Главная</a>
            </li>
        </ul>
    </nav>
</header>
<div class="stat-container">
    <div class="container">
        <br>
        <h1>Статистика</h1>
        <br>
        <form action="{{action('StatisticsController@getData')}}" method="get">
            <div class="input-group mb-3">
                <input style="max-width: 70%" id="short_url" name="short_url" required type="url" maxlength="250"
                       class="form-control"
                       placeholder="Введите url">
                <input type="submit" id="result_btn" value="Показать">
            </div>
        </form>
    </div>
    <div id="success_snackbar"></div>
    <div id="failed_snackbar"></div>
</div>
<div class="container" style="text-align: center">
    <br>
    <span style="font-size: 30px; font-family: Georgia;" id="stat_for_text"></span>
    <p><span style="font-size: 30px; font-family: Georgia;" id="count_text"></span></p>
    <br>
    <br>
    <div class="row">
        <div class="col-md-6">
            <canvas id="countries_chart" width="400" height="250"></canvas>
        </div>
        <div class=" col-md-6">
            <canvas id="languages_chart" width="400" height="250"></canvas>
        </div>
    </div>
    <br>
    <br>
    <div class="row">
        <div class="col-md-6">
            <canvas id="browsers_chart" width="400" height="250"></canvas>
        </div>
        <div class="col-md-6">
            <canvas id="platforms_chart" width="400" height="250"></canvas>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.4.0/Chart.min.js"></script>

<script>
    let stats = null;
    let shortUrl = null;

    $(document).ready(function () {
        shortUrl = null;

        shortUrl = resolveLastSegment(shortUrl);
        getData(shortUrl);
    });

    $('form').submit(function (event) {

        event.preventDefault();

        shortUrl = $("#short_url").val();
        shortUrl = resolveLastSegment(shortUrl);

        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type: 'GET',
            url: '/get_data',
            data: {
                'short_url': shortUrl
            },
            success: function (data) {
                if (data.status === 'failed') {
                    showSnackbar(data.status, data.msg);
                } else {
                    stats = data;
                    $("#stat_for_text").text("Статистика для ссылки {{ route('main')}}/" + shortUrl);
                    $("#count_text").text("Переходов по ссылке: " + stats['result']['count']);
                    draw();
                }
            }
        });

    });

    function getData(shortUrl) {
        console.log('getData f call ' + shortUrl);
        if (shortUrl === null) {
            shortUrl = $("#short_url").val();
            console.log(shortUrl);
            if (shortUrl === '')
                return;

            shortUrl = resolveLastSegment(shortUrl);
        }

        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type: 'GET',
            url: '/get_data',
            data: {
                'short_url': shortUrl
            },
            success: function (data) {
                if (data.status === 'failed') {
                    showSnackbar(data.status, data.msg);
                } else {
                    stats = data;
                    $("#stat_for_text").text("Статистика для ссылки {{ route('main')}}/" + shortUrl);
                    $("#count_text").text("Переходов по ссылке: " + stats['result']['count']);
                    draw();
                }
            }
        });
    }

    function resolveLastSegment(url) {
        if (url === null) {
            url = location.href;
        }
        let array = url.split('/');
        let lastSegment = array[array.length - 1];

        return lastSegment !== 'stat' ? lastSegment : null;
    }

    function draw() {
        drawChart('countries', 'Страны', 'countries_chart');
        drawChart('languages', 'Языки', 'languages_chart');
        drawChart('browsers', 'Браузеры', 'browsers_chart');
        drawChart('platforms', 'Операционные системы', 'platforms_chart');

        function drawChart(arrayName, title, divId) {
            new Chart(document.getElementById(divId), {
                type: 'doughnut',
                data: {
                    labels: stats['result'][arrayName]['labels'],
                    datasets: [
                        {
                            label: "",
                            backgroundColor: ["#3e95cd", "#8e5ea2", "#3cba9f", "#e8c3b9", "#c45850"],
                            data: stats['result'][arrayName]['count']
                        }
                    ]
                },
                options: {
                    title: {
                        display: true,
                        text: title,
                        fontSize: 20
                    }
                }
            });
        }
    }

    function showSnackbar(status, msg) {
        let snackBar;

        if (status === "success") {
            snackBar = document.getElementById("success_snackbar");
        } else if (status === "failed") {
            snackBar = document.getElementById("failed_snackbar");
        }

        snackBar.className = "show";

        if (status === "success") {
            $("#success_snackbar").text(msg);
        } else if (status === "failed") {
            $("#failed_snackbar").text(msg);
        }

        setTimeout(function () {
            snackBar.className = snackBar.className.replace("show", "");
        }, 3000);
    }
</script>
</body>
</html>
