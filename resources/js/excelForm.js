jQuery(function () {

    $("#excel_submit").on("click", function (e) {
        e.preventDefault();
        Swal.fire({
            html: "<h4> جاري ارسال الطلب</h4>",
            timerProgressBar: true,
            didOpen: () => {
                Swal.showLoading();
                setTimeout(function () { $("#excel_form").submit(); }, 500);
            },
        });
    });
});

window.findMajor = function (programs, program_id, department_id) {
    for (var i = 0; i < programs.length; i++) {
        for (var j = 0; j < programs[i].departments.length; j++) {
            if (
                programs[i].id == program_id &&
                programs[i].departments[j].id == department_id
            ) {
                return programs[i].departments[j];
            }
        }
    }
};

window.findDepartment = function (programs, program_id) {
    if (programs !== undefined && programs !== null) {
        for (var i = 0; i < programs.length; i++) {
            if (programs[i].id == program_id) {

                return programs[i].departments;
            }
        }
    }
};

window.fillDepartments = function () {
    var prog = document.getElementById("program").value;

    if (prog == undefined) {
        return;
    }

    var dept = document.getElementById("department");
    dept.innerHTML = '<option value="0" disabled selected>أختر</option>';
    var departments = findDepartment(window.programs, prog);
    if (departments === undefined) {
        return;
    }
    for (var i = 0; i < departments.length; i++) {
        var option = document.createElement("option");
        option.innerHTML = departments[i].name;
        option.value = departments[i].id;
        dept.appendChild(option);
    }
    fillMajors();
};

window.fillMajors = function () {
    var prog = document.getElementById("program").value;
    var dept = document.getElementById("department").value;
    var mjr = document.getElementById("major");
    mjr.innerHTML = '<option value="0" disabled selected>أختر</option>';
    if (dept !== "0" && prog !== "0") {
        var majors = findMajor(window.programs, prog, dept).majors;

        for (var i = 0; i < majors.length; i++) {
            var option = document.createElement("option");
            option.innerHTML = majors[i].name;
            option.value = majors[i].id;
            mjr.appendChild(option);
        }
    }
};
