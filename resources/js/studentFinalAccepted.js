jQuery(function () {
    $.ajaxSetup({
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
    });
});



window.finalAcceptChanged = function (national_id, event) {
    let final_accepted = 0;
    if (event.target.checked) {
        final_accepted = 1;
    } else {
        final_accepted = 0;
    }
    
    Swal.fire({
        html: "<h4>جاري تحديث البيانات</h4>",
        timerProgressBar: true,
        didOpen: () => {
            Swal.showLoading();
        },
    });
    $.ajax({
        type: "post",
        url: window.finalAcceptedRoute,
        data: {
            national_id: national_id,
            final_accepted: final_accepted,
        },
        headers: {
            Accept: "application/json",
            ContentType: "application/json",
        },
        dataType: "json",
        success: function (response) {
            const message = response.message;
            Swal.fire({
                position: "center",
                icon: "success",
                showConfirmButton: false,
                timer: 1000,
            });
        },
        error: function (response) {
            const message = response.responseJSON.message;
            Swal.fire({
                position: "center",
                icon: "error",
                title: message,
                showConfirmButton: true,
            });
        },
    });
    Swal.close();
};