<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>URL minimizer</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css"
          integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
    <style>/* The snackbar - position it at the bottom and in the middle of the screen */
        #successSnackbar {
            visibility: hidden; /* Hidden by default. Visible on click */
            min-width: 250px; /* Set a default minimum width */
            margin-left: -125px; /* Divide value of min-width by 2 */
            background-color: green; /* Black background color */
            color: #fff; /* White text color */
            text-align: center; /* Centered text */
            border-radius: 2px; /* Rounded borders */
            padding: 16px; /* Padding */
            position: fixed; /* Sit on top of the screen */
            z-index: 1; /* Add a z-index if needed */
            /*left: 50%; !* Center the snackbar *!*/
            right: 30px; /* 30px from the bottom */
        }

        #failedSnackbar {
            visibility: hidden; /* Hidden by default. Visible on click */
            min-width: 250px; /* Set a default minimum width */
            margin-left: -125px; /* Divide value of min-width by 2 */
            background-color: #b52610; /* Black background color */
            color: #fff; /* White text color */
            text-align: center; /* Centered text */
            border-radius: 2px; /* Rounded borders */
            padding: 16px; /* Padding */
            position: fixed; /* Sit on top of the screen */
            z-index: 1; /* Add a z-index if needed */
            /*left: 50%; !* Center the snackbar *!*/
            right: 30px; /* 30px from the bottom */
        }

        /* Show the snackbar when clicking on a button (class added with JavaScript) */
        #successSnackbar.show, #failedSnackbar.show {
            visibility: visible; /* Show the snackbar */
            /* Add animation: Take 0.5 seconds to fade in and out the snackbar.
           However, delay the fade out process for 2.5 seconds */
            -webkit-animation: fadein 0.5s, fadeout 0.5s 2.5s;
            animation: fadein 0.5s, fadeout 0.5s 2.5s;
        }

        /* Animations to fade the snackbar in and out */
        @-webkit-keyframes fadein {
            from {
                right: 0;
                opacity: 0;
            }
            to {
                right: 30px;
                opacity: 1;
            }
        }

        @keyframes fadein {
            from {
                right: 0;
                opacity: 0;
            }
            to {
                right: 30px;
                opacity: 1;
            }
        }

        @-webkit-keyframes fadeout {
            from {
                right: 30px;
                opacity: 1;
            }
            to {
                right: 0;
                opacity: 0;
            }
        }

        @keyframes fadeout {
            from {
                right: 30px;
                opacity: 1;
            }
            to {
                right: 0;
                opacity: 0;
            }
        }

        .main-contrainer {
            width: 100%;
            height: 350px;
            background: #6200EE;
        }

        h1 {
            color: #f7f7f7;
        }

        #result_btn {
            width: 150px;
            height: 40px;
            margin-left: 30px;
            border: 1px solid #fff;
            border-radius: 3px;
            background: #f7f7f7;
            color: #7646bb;
            font-size: 18px;
            font-weight: 500;
        }

    </style>
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
            <input style="width: 70%" class="form-control" id="result" placeholder="Результат...">
            <div id="successSnackbar"></div>
            <div id="failedSnackbar"></div>
        </div>
    </div>
</div>
<br>
<br>
<br>
<div class="">
    <h3 style="text-align: center">Хотите увидеть историю и статистику? <a href="">Войдите</a></h3>
</div>

<script>
    function getShortUrl() {
        let rootUrl = $("#root").val();

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
                    $("#result").html(data.result);
                    showSnackbar(data.status, data.msg);
                } else if (data.status === 'failed') {
                    // $("#result").html(data.result); ОЧИСТИТЬ
                    showSnackbar(data.status, data.msg);
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
</script>

<script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
</body>
</html>