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
<div class="stat_container">
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
    <div id="successSnackbar"></div>
    <div id="failedSnackbar"></div>
</div>
<div class="container" style="text-align: center">
    <br>
    <span style="font-size: 30px" id="count_text"></span>
    <br>
    <br>
    <div class="row">
        <div class=" col-md-6">
            <canvas id="countries-chart" width="400" height="250"></canvas>
        </div>
        <div class=" col-md-6">
            <canvas id="languages-chart" width="400" height="250"></canvas>
        </div>
    </div>
    <br>
    <br>
    <div class="row">
        <div class=" col-md-6">
            <canvas id="browsers-chart" width="400" height="250"></canvas>
        </div>
        <div class=" col-md-6">
            <canvas id="platforms-chart" width="400" height="250"></canvas>
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
                    clearInfo();
                } else {
                    stats = data;
                    $("#count_text").text('Переходов по ссылке ' + stats['result']['count']);
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
                    clearInfo();
                } else {
                    stats = data;
                    $("#count_text").text('Переходов по ссылке ' + stats['result']['count']);
                    draw();
                }
            }
        });
    }

    function clearInfo() {
        // let allCanvases = ['countries-chart', 'languages-chart', 'browsers-chart', 'platforms-chart'];
        //
        // if (allCanvases.length !== 0) {
        //     for (let i = 0; allCanvases.length; i++) {
        //         let canvas = document.getElementById(allCanvases[i]);
        //         let context = canvas.getContext("2d");
        //         context.clearRect(0, 0, canvas.width, canvas.height);
        //     }
        // }
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
        drawChart('countries', 'Страны', 'countries-chart');
        drawChart('languages', 'Языки', 'languages-chart');
        drawChart('browsers', 'Браузеры', 'browsers-chart');
        drawChart('platforms', 'Операционные системы', 'platforms-chart');

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
</script>
</body>
</html>
