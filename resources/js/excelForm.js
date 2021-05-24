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
    var majors;
    Object.values(programs).forEach(program => {
        Object.values(program.departments).forEach(department => {
            if (program.id == program_id && department.id == department_id) {
                majors = department.majors;
            }
        });
    });
    return majors;
    // for (var i = 0; i < programs.length; i++) {
    //     for (var j = 0; j < programs[i].departments.length; j++) {
    //         if (
    //             programs[i].id == program_id &&
    //             programs[i].departments[j].id == department_id
    //         ) {
    //             return programs[i].departments[j];
    //         }
    //     }
    // }
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
        console.error(`program id ${prog} is undefined`);
        return;
    }

    var dept = document.getElementById("department");
    dept.innerHTML = '<option value="0" disabled selected>أختر</option>';
    var departments = findDepartment(window.programs, prog);
    if (departments === undefined) {
        return;
    }
    
    Object.values(departments).forEach(department => {
        var option = document.createElement("option");
        option.innerHTML = department.name;
        option.value = department.id;
        dept.appendChild(option);
    });
    fillMajors();
};

window.fillMajors = function () {
    var prog = document.getElementById("program").value;
    var dept = document.getElementById("department").value;
    var mjr = document.getElementById("major");
    mjr.innerHTML = '<option value="0" disabled selected>أختر</option>';
    if (dept !== "0" && prog !== "0") {
        var majors = findMajor(window.programs, prog, dept);
        if (majors === undefined) {
            return;
        }
        // for (var i = 0; i < majors.length; i++) {
        //     var option = document.createElement("option");
        //     option.innerHTML = majors[i].name;
        //     option.value = majors[i].id;
        //     mjr.appendChild(option);
        // }

        Object.values(majors).forEach(major => {
            var option = document.createElement("option");
            option.innerHTML = major.name;
            option.value = major.id;
            mjr.appendChild(option);
        });
    }
};
