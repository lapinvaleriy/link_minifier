$(document).ready(function () {

    $('#main_form').submit(function (event) {

        let rootUrl = $("#root_url").val();
        let customUrl = $("#custom_url").val();
        let expiryDate = $("#expiry_date").val();
        $('#result').val('');

        event.preventDefault();

        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type: 'POST',
            url: '/minify',
            data: {
                "root_url": rootUrl,
                "custom_url": customUrl,
                "expiry_date": expiryDate,
            },
            success: function (data) {
                if (data.status === 'success') {
                    showResult(data);
                    showSnackbar(data.status, data.msg);
                } else if (data.status === 'failed') {
                    deleteResultDiv();
                    showSnackbar(data.status, data.msg);
                }
            }
        });
    });
});

function showResult(data) {
    deleteResultDiv();

    $('form').after(function () {
        $(".main-contrainer").css("height", 600);
        let res = '<div class="result">';

        res +=
            '<span style="color:#fff">Ваша короткая ссылка </span>' +
            '<div style="max-width: 70%" class="alert alert-light">' + data.short_url + '</div>' +
            '<span style="color:#fff">Ваша ссылка для статистики</span>' +
            '<div style="max-width: 70%" class="alert alert-light">' + data.stat_url + '</div>';

        res += '</div>';

        return res;
    });
}

function deleteResultDiv() {
    $('.result').remove();
    $(".main-contrainer").css("height", 380);
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