    // window.EditPhoneClicked() {
    //     var editPhoneBtn = document.getElementById('editPhoneBtn');


    //     if (document.getElementById('phone').disabled == true) {
    //         document.getElementById('phone').disabled = false;
    //         editPhoneBtn.classList.remove('btn-primary');
    //         editPhoneBtn.classList.add('btn-success');
    //         editPhoneBtn.innerHTML = " تـم ";
    //     } else {
    //         document.getElementById('phone').disabled = true;
    //         editPhoneBtn.classList.remove('btn-success');
    //         editPhoneBtn.classList.add('btn-primary');
    //         editPhoneBtn.innerHTML = "تعديل";
    //     }

    // }

    window.formSubmit = function() {
        document.getElementById('cost').disabled=false;
        document.getElementById('updateUserForm').submit();
}

window.changeTraineeState = function(state) {

    var hours = student[0].major.hours;
    var hourCost;
    var costGroup = document.getElementById('costGroup');
    if (student[0].program_id == 1) {
        hourCost = 550;
    } else {
        hourCost = 400;
    }
    switch (state) {
        case 'trainee':
            document.getElementById('cost').value = hours * hourCost;
            $('#pledgeSection').hide();
            $('#receiptImg').prop('disabled', false);
            $('#costGroup').show();
            $('#receipt').show();
            $('#privateStateDocGroup').hide();
            $('#privateStateDoc').prop('disabled', true);
            break;

        case 'employee':
            document.getElementById('cost').value = hours * hourCost - (hours * hourCost * 0.75);
            $('#pledgeSection').show();
            $('#receiptImg').prop('disabled', false);
            $('#costGroup').show();
            $('#receipt').show();
            $('#privateStateDocGroup').hide();
            $('#privateStateDoc').prop('disabled', true);
            break;

        case 'employeeSon':
            document.getElementById('cost').value = hours * hourCost - (hours * hourCost * 0.5);
            $('#pledgeSection').show();
            $('#receiptImg').prop('disabled', false);
            $('#costGroup').show();
            $('#receipt').show();
            $('#privateStateDocGroup').hide();
            $('#privateStateDoc').prop('disabled', true);
            break;

        case 'privateState':
            $('#costGroup').hide();
            $('#receipt').hide();
            $('#receiptImg').prop('disabled', true);
            $('#privateStateDoc').prop('disabled', false);
            $('#privateStateDocGroup').show();
            break;

        default:
            break;
    }
}


window.popup = function() {
    $('[data-toggle="popover"]').popover();
}