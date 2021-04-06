window.formSubmit = function() {
        document.getElementById('cost').disabled=false;
        document.getElementById('updateUserForm').submit();
}

window.toggleChecked = function(event) {
    let row = event.target.parentNode.parentNode;
    if (event.currentTarget.checked) {
        row.dataset.selected = true;
    } else {
        row.dataset.selected = false;
    }
}

window.addToCoursesTable = function() {
    let tablePickCourses = document.getElementById("pick-courses");
    let tableCourses = document.getElementById("courses");
    let selectedCourses = tablePickCourses.querySelectorAll("[data-selected='true']");
    selectedCourses.forEach((row) => {
        row.children[5].children[0].setAttribute('onclick', 'changeTraineeState();')
        let id = row.children[5].children[0].value;
        row.children[5].children[0].setAttribute('id', 'course_' + id);
        let result_row = tableCourses.children[0];
        tableCourses.insertBefore(row, result_row);
    });
    $('#pick-courses').modal('hide');
    changeTraineeState();
}

window.changeTraineeState = function() {
    let courses = window.courses.concat(window.major_courses);
    let new_cost = courses.map(course => {
        let checkbox = document.getElementById("course_" + course.id);
        if (checkbox == null) {
            return 0;
        }
        if (checkbox.checked == true) {
            return course.credit_hours * 550;
        } else {
            return 0;
        }
    }).reduce((total, cost) => total + cost);

    let new_total_hours = courses.map(course => {
        let checkbox = document.getElementById("course_" + course.id);
        if (checkbox == null) {
            return 0;
        }
        if (checkbox.checked == true) {
            return parseInt(course.credit_hours);
        } else {
            return 0;
        }
    }).reduce((total, hour) => total + hour);
    let courses_error = document.getElementById("courses-error");
    if (new_total_hours < 11 || new_total_hours > 21) {
        courses_error.innerText = "يجب أن يكون مجموع ساعات الجدول بين 11 و 21";
        courses_error.classList.remove("d-none");
    } else {
        courses_error.classList.add("d-none");
    }

    let total_hours = document.getElementById("total_hours");
    if (total_hours != undefined) {
        total_hours.innerText = new_total_hours;
    }
    let total_cost = document.getElementById("total_cost");
    if (total_cost != undefined) {
        total_cost.innerText = new_cost;
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
