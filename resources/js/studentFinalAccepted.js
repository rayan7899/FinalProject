jQuery(function () {
    $.ajaxSetup({
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
    });
});



window.finalAcceptChanged = function (national_id, event) {
    let studentRowInputs = event.target.parentNode.parentNode.querySelectorAll("input");
    let studentDocsVerified = studentRowInputs[0].checked;
    console.log("st "+studentDocsVerified);
    let finalAccepted       = studentRowInputs[1].checked;
    console.log("fi "+finalAccepted);
    
    if (studentDocsVerified) {
        studentDocsVerified = 1;
    } else {
        studentDocsVerified = 0;
    }
    if (finalAccepted) {
        finalAccepted = 1;
    } else {
        finalAccepted = 0;
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
            final_accepted: finalAccepted,
            student_docs_verified: studentDocsVerified,
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