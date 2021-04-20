window.national_id = document.getElementById("national_id");
window.sname = document.getElementById("sname");
window.note = document.getElementById("note");
window.order_id = 0;

window.popup = function () {
    $('[data-toggle="popover"]').popover({
        html: true,
    });
};

window.showPrivateModal = function (national_id, name, order_id,note) {

    window.national_id.value = national_id;
    window.sname.value = name;
    window.order_id = order_id;
    window.note.value = note

};

window.privateDocDecision = function (decision, reqType,national_id = null, order_id = null, event = null) {
  
    if (event !== null) {

        var row = event.currentTarget.parentNode.parentNode;
    }
  

    if (reqType == "modal") {
        let national_id = window.national_id.value;
        let order_id    = window.order_id;
        let note        = window.note.value

        var form = {
            national_id: national_id,
            order_id: order_id,
            decision: decision,
            note: note,
        };

    } else {

        var form = {
            national_id: national_id,
            order_id: order_id,
            decision: decision,
            note: null,
        };
    }



    Swal.fire({
        html: "<h4>جاري تحديث البيانات</h4>",
        timerProgressBar: true,
        didOpen: () => {
            Swal.showLoading();
        },
    });

    axios.post(window.privateDocDecisionRoute, form)
        .then((response) => {
            if (event !== null) {
                row.remove();
            }else{
                if (document.getElementById(window.national_id.value) !== null) {
                    document.getElementById(window.national_id.value).remove();
                }
            }
            
            Swal.fire({
                position: "center",
                html: "<h4>" + response.data.message + "</h4>",
                icon: "success",
                showConfirmButton: false,
                timer: 1000,
            });
              $("#editModal").modal("hide");
        })
        .catch((error) => {
            Swal.fire({
                position: "center",
                html: "<h4>" + error.response.data.message + "</h4>",
                icon: "error",
                showConfirmButton: true,
            });
        });

    Swal.close();
};

window.okClicked = function (national_id, payment_id, event) {
    let row = event.currentTarget.parentNode.parentNode;
    Swal.fire({
        html: "<h4>جاري تحديث البيانات</h4>",
        timerProgressBar: true,
        didOpen: () => {
            Swal.showLoading();
        },
    });
    $.ajax({
        type: "post",
        url: window.paymentVerified,
        data: {
            national_id: national_id,
            payment_id: payment_id,
        },
        headers: {
            Accept: "application/json",
            ContentType: "application/json",
        },
        dataType: "json",
        success: function (response) {
            const message = response.message;
            row.remove();
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