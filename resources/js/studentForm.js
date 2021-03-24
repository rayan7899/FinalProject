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

window.changeTraineeState = function() {
    let new_cost = courses.map(course => {
        console.log(course.id);
        if (document.getElementById("course_" + course.id).checked == true) {
            return course.credit_hours * 550;
        } else {
            return 0;
        }
    }).reduce((total, cost) => total + cost);
    
    let state = 'trainee';
    if (document.getElementById("employee").checked) {
        state = 'employee';
    } else if (document.getElementById("employeeSon").checked) {
        state = 'employeeSon';
    } else if (document.getElementById("privateState").checked) {
        state = 'privateState';
    }
    switch (state) {
        case 'employee':
            document.getElementById('cost').value = new_cost * 0.25;
            $('#pledgeSection').show();
            $('#receiptImg').prop('disabled', false);
            $('#costGroup').show();
            $('#receipt').show();
            $('#privateStateDocGroup').hide();
            $('#privateStateDoc').prop('disabled', true);
            break;

        case 'employeeSon':
            document.getElementById('cost').value = new_cost * 0.5;
            $('#pledgeSection').show();
            $('#receiptImg').prop('disabled', false);
            $('#costGroup').show();
            $('#receipt').show();
            $('#privateStateDocGroup').hide();
            $('#privateStateDoc').prop('disabled', true);
            break;

        case 'privateState':
            document.getElementById('cost').value = 0;
            $('#costGroup').hide();
            $('#receipt').hide();
            $('#receiptImg').prop('disabled', true);
            $('#privateStateDoc').prop('disabled', false);
            $('#privateStateDocGroup').show();
            break;

        // case 'trainee':
        default:
            document.getElementById('cost').value = new_cost;
            $('#pledgeSection').hide();
            $('#receiptImg').prop('disabled', false);
            $('#costGroup').show();
            $('#receipt').show();
            $('#privateStateDocGroup').hide();
            $('#privateStateDoc').prop('disabled', true);
            break;
    }
}


window.popup = function() {
    $('#info-popup').popover({
        html: true,
    });
    // $('[data-toggle="popover"]').popover();
}
