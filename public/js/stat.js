let stats = null;
let shortUrl = null;

$(document).ready(function () {
    shortUrl = null;

    shortUrl = resolveLastSegment(shortUrl);
    getData(shortUrl);
});

$('#stat_form').submit(function (event) {

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
                let route = `${window.location.protocol}//${window.location.host}`;
                $("#stat_for_text").text("Статистика для ссылки " + route + "/" + shortUrl);
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
                let route = `${window.location.protocol}//${window.location.host}`;
                $("#stat_for_text").text("Статистика для ссылки " + route + "/" + shortUrl);
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