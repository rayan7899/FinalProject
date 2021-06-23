window.accept = function () {
    let row = document.getElementById(window.national_id.value);
    axios.post(window.refundOrdersUpdate, {
        refund_id: window.order_id,
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
        Swal.fire({
            position: "center",
            html: "<h4>" + error.response.data.message + "</h4>",
            icon: "error",
            showConfirmButton: true,
        });
    });
}

window.changeAmount = function () {
    $('#radioWallet').parent().removeClass('btn-outline-secondary');
    $('#radioWallet').parent().addClass('btn-outline-primary');
    $('#radioWallet').removeAttr('disabled');
    $('#discountMsg').hide();

    switch (window.reason.value) {
        case 'drop-out':
            window.amount.value = window.radioBank.checked ? window.creditHoursCost + window.wallet : window.creditHoursCost;
            $('#discountMsg').show();
            break;

        case 'not-opened-class':
            window.amount.value = window.radioBank.checked ? window.creditHoursCost + window.wallet : window.creditHoursCost;
            break;

        case 'exception':
            window.amount.value = window.radioBank.checked ? window.creditHoursCost + window.wallet : window.creditHoursCost;
            break;
            
        case 'graduate':
            window.amount.value = window.wallet;
            $('#radioWallet').removeAttr('checked');
            $('#radioWallet').parent().removeClass('active');
            $('#radioWallet').parent().removeClass('btn-outline-primary');
            $('#radioWallet').parent().addClass('btn-outline-secondary');
            $('#radioWallet').attr('disabled', true);

            $('#radioBank').prop('checked', true);
            $('#radioBank').parent().addClass('active');
            break;

        case 'get-wallet-amount':
            window.amount.value = window.wallet;
            $('#radioWallet').removeAttr('checked');
            $('#radioWallet').parent().removeClass('active');
            $('#radioWallet').parent().removeClass('btn-outline-primary');
            $('#radioWallet').parent().addClass('btn-outline-secondary');
            $('#radioWallet').attr('disabled', true);

            $('#radioBank').prop('checked', true);
            $('#radioBank').parent().addClass('active');
            break;
    
        default:
            break;
    }
}




window.fillModal = function (national_id, order_id, name, amount, reason) {
    $('#before-training').removeAttr('disabled');
    $('#before-4th-week').removeAttr('disabled');
    // $('#rangeSection').show();
    window.national_id.value = national_id;
    window.requestDate.value = document.getElementById(national_id).cells[9].innerHTML;
    window.sname.value = name;
    window.order_id = order_id;
    if (window.amount !== null) {
        window.amount.value = amount;
    }
    if(reason == 'get-wallet-amount' || reason == 'graduate'){
        // $('#rangeSection').hide();
        $('#before-training').attr('disabled', true);
        $('#before-4th-week').attr('disabled', true);
    }
};