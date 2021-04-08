

window.toggleChecked = function (event) {
    let row = event.target.parentNode.parentNode;
    let cost = parseInt(row.dataset.cost);
    if (event.currentTarget.checked == true) {
        window.new_cost += cost;
        // if (window.t_cost + window.new_cost > window.wallet) {
        //     window.new_cost
        //     event.currentTarget.checked = false;
        //     Swal.fire({
        //         position: "center",
        //         html: "<h4>الرصيد لا يكفي</h4>",
        //         icon: "warning",
        //         showConfirmButton: true,
        //     });
        //     window.new_cost -= cost;
        //     return;
        // }
    } else {
        window.new_cost -= cost;
    }
    
    if (event.currentTarget.checked) {
        row.dataset.selected = true;
    } else {
        row.dataset.selected = false;
    }
}



window.addToCoursesTable = function () {
    window.new_cost = 0;

    let tablePickCourses = document.getElementById("pick-courses");
    let tableCourses = document.getElementById("courses");
    let selectedCourses = tablePickCourses.querySelectorAll("[data-selected='true']");
    let newTotal = 0;
    selectedCourses.forEach((row) => {
        row.children[5].children[0].setAttribute('onclick', 'window.calcCost(event);')
        let id = row.children[5].children[0].value;
        row.children[5].children[0].setAttribute('id', 'course_' + id);
        // let result_row = tableCourses.children[0];
        // tableCourses.insertBefore(row, result_row);
        let cost = parseInt(row.dataset.cost);
        newTotal += cost;
        // if ((window.t_cost + newTotal) <= window.wallet) {
        //     
        tableCourses.appendChild(row);
        // }
       
    });
    window.calcCost();
    $('#pick-courses').modal('hide');
}


window.calcCost = function (event) {
    window.t_cost = 0;
    window.total_hours = 0;
    document.getElementById('totalHoursCost').value = 0;
    let coursesTable = document.getElementById("courses");
    for (let course of coursesTable.children) {
        if (course.children[5].children[0].checked == true) {
            window.t_cost += parseInt(course.dataset.cost);
            window.total_hours += parseInt(course.dataset.hours);
        }
    }
    if(window.total_hours < 12 || window.total_hours > 21){
        $("#courses-error").html("يجب ان يكون مجموع الساعات بين 12 و 21 ساعة");
        $("#courses-error").show();
    }else{
        $("#courses-error").html("");
        $("#courses-error").hide();
        
    }
    

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
            document.getElementById('totalHoursCost').value = window.t_cost * 0.25;
            $('#pledgeSection').show();
            $('#receiptImg').prop('disabled', false);
            $('#paymentGroup').show();
            $('#receipt').show();
            $('#privateStateDocGroup').hide();
            $('#privateStateDoc').prop('disabled', true);
            break;

        case 'employeeSon':
            document.getElementById('totalHoursCost').value = window.t_cost * 0.5;
            $('#pledgeSection').show();
            $('#receiptImg').prop('disabled', false);
            $('#paymentGroup').show();
            $('#receipt').show();
            $('#privateStateDocGroup').hide();
            $('#privateStateDoc').prop('disabled', true);
            break;

        case 'privateState':
            document.getElementById('totalHoursCost').value = 0;
            $('#paymentGroup').hide();
            $('#receipt').hide();
            $('#receiptImg').prop('disabled', true);
            $('#privateStateDoc').prop('disabled', false);
            $('#privateStateDocGroup').show();
            break;

        // case 'trainee':
        default:
            document.getElementById('totalHoursCost').value = window.t_cost;
            $('#pledgeSection').hide();
            $('#receiptImg').prop('disabled', false);
            $('#paymentGroup').show();
            $('#receipt').show();
            $('#privateStateDocGroup').hide();
            $('#privateStateDoc').prop('disabled', true);
            break;
    }

    let walletAfterCalc = window.wallet - window.t_cost;
    let cost = 0;
    if (walletAfterCalc >= 0) {
        document.getElementById("walletAfterCalc").value = walletAfterCalc;
        $('#costFormGroup').hide();
        $('#receiptImg').prop('disabled', true);
        document.getElementById("cost").value = 0;;
    } else {
        cost = Math.abs(walletAfterCalc);        
        document.getElementById("walletAfterCalc").value = 0;
        $('#costFormGroup').show();
        $('#receiptImg').prop('disabled', false);
        document.getElementById("cost").value = cost;
    }

}


window.popup = function () {
    $('#info-popup').popover({
        html: true,
    });
    // $('[data-toggle="popover"]').popover();
}
