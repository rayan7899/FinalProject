window.accept = function () {
    let row = document.getElementById(window.national_id.value);
    axios.post(window.refundOrdersUpdate, {
        refund_id: window.order_id,
        national_id: window.national_id.value,
        note: window.acceptNote.value,
        range: window.range.value,
        accepted: true,
    })
    .then((response) => {
        if (row !== null) {
            row.remove();
        }
        $("#acceptModal").modal("hide");
        Swal.fire({
            position: "center",
            html: "<h4>"+response.data.message+"</h4>",
            icon: "success",
            showConfirmButton: false,
            timer: 1000,
        });
    })
    .catch((error) => {
        Swal.fire({
            position: "center",
            html: "<h4>" + error.response.data.message + "</h4>",
            icon: "error",
            showConfirmButton: true,
        });
    });
}

window.reject = function () {
    let row = document.getElementById(window.national_id.value);
    axios.post(window.refundOrdersUpdate, {
        refund_id: window.order_id,
        national_id: window.national_id.value,
        note: window.rejectNote.value,
        accepted: false,
    })
    .then((response) => {
        if (row !== null) {
            row.remove();
        }
        $("#rejectModal").modal("hide");
        Swal.fire({
            position: "center",
            html: "<h4>"+response.data.message+"</h4>",
            icon: "success",
            showConfirmButton: false,
            timer: 1000,
        });
    })
    .catch((error) => {
        console.log(error);
        Swal.fire({
            position: "center",
            html: "<h4>" + error.response.data.message + "</h4>",
            icon: "error",
            showConfirmButton: true,
        });
    });
}

window.fillModal = function (national_id, order_id, name, amount) {
    window.national_id.value = national_id;
    window.requestDate.value = document.getElementById(national_id).cells[9].innerHTML;
    window.sname.value = name;
    window.order_id = order_id;
    if (window.amount !== null) {
        window.amount.value = amount;
    }
};