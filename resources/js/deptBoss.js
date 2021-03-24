// const { data } = require("jquery");
// jQuery(function(){
//     window.studentsTbl =  $("#studentsTbl").DataTable({
//         orderCellsTop: true,
//         deferLoading: true,       
//         bDestroy: true,
//     })
// });

window.getStringLevel = function (level) {
    switch (level) {
        case "1":
            return "الاول";
            break;
        case "2":
            return "الثاني";
            break;
        case "3":
            return "الثالث";
            break;
        case "4":
            return "الرابع";
            break;
        case "5":
            return "الخامس";
            break;
    }
};

window.findCourses = function (major) {
    let program_id = document.getElementById("program").value;
    let department_id = document.getElementById("department").value;
    for (let i = 0; i < window.programs.length; i++) {
        for (let j = 0; j < window.programs[i].departments.length; j++) {
            if (
                window.programs[i].id == program_id &&
                window.programs[i].departments[j].id == department_id
            ) {
                // return window.programs[i].departments[j].majors;
                for (
                    let k = 0;
                    k < window.programs[i].departments[j].majors.length;
                    k++
                ) {
                    if (
                        window.programs[i].departments[j].majors[k].id == major
                    ) {
                        return window.programs[i].departments[j].majors[k]
                            .courses;
                    }
                }
            }
        }
    }
};

window.fillCourses = function () {
    let dataState = getCoursesData();
    if (!dataState) {
        return;
    }

    var tblCourses = document.getElementById("courses");
    let originalLevel = document.getElementById("originalLevel");
    tblCourses.innerHTML = "<tr><td></td></tr>";
    let major = document.getElementById("major").value;
    if (major !== "") {
        var courses = findCourses(major);
    } else {
        return;
    }
    for (var i = 0; i < courses.length; i++) {
        let tblIndex = 0;
        if (
            courses[i].suggested_level == 0 &&
            courses[i].level == parseInt(originalLevel.value)
        ) {
            let row = tblCourses.insertRow(tblIndex);
            row.setAttribute("data-id", courses[i].id);
            row.setAttribute("data-level", courses[i].level);
            row.setAttribute("data-selected", false);
            row.addEventListener("click", (event) =>
                window.courseClicked(event)
            );
            let code = row.insertCell(0);
            let name = row.insertCell(1);
            let level = row.insertCell(2);
            let credit_hours = row.insertCell(3);
            let contact_hours = row.insertCell(4);
            code.className = "text-center";
            name.className = "text-center";
            credit_hours.className = "text-center";
            contact_hours.className = "text-center";
            code.innerHTML = courses[i].code;
            name.innerHTML = courses[i].name;
            level.innerHTML = getStringLevel(courses[i].level);
            credit_hours.innerHTML = courses[i].credit_hours;
            contact_hours.innerHTML = courses[i].contact_hours;
            tblIndex++;
        }
    }
    // $('#originalCoursesTbl').??????????????();
    fillSuggestedCourses(false);
};

window.fillSuggestedCourses = function (shouldUpdateData = true) {
    if (shouldUpdateData) {
        let dataState = getCoursesData();
        if (!dataState) {
            return;
        }
    }

    var suggestedLevelBody = document.getElementById("suggestedLevelBody");
    suggestedLevelBody.innerHTML = null;
    let suggLevel = document.getElementById("suggestedLevel");
    let major = document.getElementById("major").value;
    if (major !== "") {
        var courses = findCourses(major);
    } else {
        return;
    }
    for (let i = 0; i < courses.length; i++) {
        let suggLevelIndex = 0;
        if (courses[i].suggested_level == parseInt(suggLevel.value)) {
            let suggLevelRow = suggestedLevelBody.insertRow(suggLevelIndex);
            suggLevelRow.setAttribute("data-id", courses[i].id);
            suggLevelRow.setAttribute("data-selected", false);
            suggLevelRow.setAttribute("data-level", courses[i].level);
            suggLevelRow.addEventListener("click", (event) =>
                window.courseClicked(event)
            );
            let code = suggLevelRow.insertCell(0);
            let name = suggLevelRow.insertCell(1);
            let level = suggLevelRow.insertCell(2);
            let credit_hours = suggLevelRow.insertCell(3);
            let contact_hours = suggLevelRow.insertCell(4);
            code.className = "text-center";
            name.className = "text-center";
            level.className = "text-center";
            credit_hours.className = "text-center";
            contact_hours.className = "text-center";
            code.innerHTML = courses[i].code;
            name.innerHTML = courses[i].name;
            level.innerHTML = getStringLevel(courses[i].level);
            credit_hours.innerHTML = courses[i].credit_hours;
            contact_hours.innerHTML = courses[i].contact_hours;
            suggLevelIndex++;
        }
    }
};

window.courseClicked = function (event) {
    let courseRow = event.currentTarget;
    if (courseRow.dataset.selected == "true") {
        courseRow.setAttribute("data-selected", false);
        courseRow.classList.add("bg-light");
        courseRow.classList.add("text-dark");
        courseRow.classList.remove("bg-info");
        courseRow.classList.remove("text-white");
    } else {
        courseRow.setAttribute("data-selected", true);
        courseRow.classList.add("bg-info");
        courseRow.classList.add("text-white");
        courseRow.classList.remove("bg-light");
        courseRow.classList.remove("text-dark");
    }
};

window.addCourses = function (event) {
    let suggLevel = document.getElementById("suggestedLevel");
    let coursesData = {
        suggested_level: suggLevel.value,
        courses: [],
    };
    event.preventDefault();
    let tblCourses = document.getElementById("courses");
    let suggestedLevelBody = document.getElementById("suggestedLevelBody");
    let selectedCourses = tblCourses.querySelectorAll("[data-selected='true']");
    if (selectedCourses.length < 1) {
        Swal.fire({
            position: "center",
            html: "<h4>يجب تحديد مقرر واحد على الاقل</h4>",
            icon: "warning",
            showConfirmButton: true,
        });
        return;
    }
    selectedCourses.forEach((row) => {
        // row = row.cloneNode(true);
        coursesData.courses.push(row.dataset.id);
        row.setAttribute("data-selected", false);
        row.classList.add("bg-light");
        row.classList.add("text-dark");
        row.classList.remove("bg-info");
        row.classList.remove("text-white");
        suggestedLevelBody.appendChild(row);
    });
    updateCoursesRequset(coursesData);
};

window.removeCourses = function (event) {
    event.preventDefault();
    let coursesData = {
        suggested_level: 0,
        courses: [],
    };
    let tblCourses = document.getElementById("courses");
    let suggestedLevelBody = document.getElementById("suggestedLevelBody");
    let originalLevel = document.getElementById("originalLevel");
    let selectedCourses = suggestedLevelBody.querySelectorAll(
        "[data-selected='true']"
    );
    if (selectedCourses.length < 1) {
        Swal.fire({
            position: "center",
            html: "<h4>يجب تحديد مقرر واحد على الاقل</h4>",
            icon: "warning",
            showConfirmButton: true,
        });
        return;
    }
    selectedCourses.forEach((row) => {
        // row = row.cloneNode(true);
        coursesData.courses.push(row.dataset.id);
        row.setAttribute("data-selected", false);
        row.classList.add("bg-light");
        row.classList.add("text-dark");
        row.classList.remove("bg-info");
        row.classList.remove("text-white");

        if (row.dataset.level == parseInt(originalLevel.value)) {
            tblCourses.appendChild(row);
        } else {
            row.parentElement.removeChild(row);
        }
    });
    updateCoursesRequset(coursesData);
    //fillCourses();
};

async function updateCoursesRequset(coursesData) {
    if (coursesData == "" || coursesData == undefined || coursesData == null) {
        return false;
    }
    Swal.fire({
        html: "<h4>جاري تحديث البيانات</h4>",
        timerProgressBar: true,
        didOpen: () => {
            Swal.showLoading();
        },
    });
    axios
        .post(window.updateCoursesLevelUrl, coursesData)
        .then((response) => {
            Swal.fire({
                position: "center",
                // html: "<h4>"+response.data.message+"</h4>",
                icon: "success",
                showConfirmButton: false,
                timer: 1000,
            });
            window.programs = JSON.parse(response.data.programs);
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

async function getCoursesData() {
    let state = false;
    Swal.fire({
        html: "<h4>جاري تحديث البيانات</h4>",
        timerProgressBar: true,
        allowOutsideClick: false,
        showConfirmButton: false,
        willOpen() {
            Swal.showLoading();
        },
        willOpen() {
            Swal.hideLoading();
        },
    });
    await axios
        .get(window.getCoursesDataUrl)
        .then((response) => {
            window.programs = response.data;
            state = true;
            Swal.close();
        })
        .catch((error) => {
            Swal.fire({
                position: "center",
                html: "<h4>" + error.response.data.message + "</h4>",
                icon: "error",
                showConfirmButton: true,
            });
            state = false;
        });
    return state;
}

async function getStudentOnLevel() {
    var studentsData = null;
    let suggLevel = document.getElementById("suggestedLevel");
    Swal.fire({
        html: "<h4>جاري تحديث البيانات</h4>",
        timerProgressBar: true,
        allowOutsideClick: false,
        showConfirmButton: false,
        willOpen() {
            Swal.showLoading();
        },
        willOpen() {
            Swal.hideLoading();
        },
    });
    await axios
        .get(window.showStudentOnLevelUrl + "/" + suggLevel.value)
        .then((response) => {
            studentsData = response.data.students;
            Swal.close();
        })
        .catch((error) => {
            Swal.fire({
                position: "center",
                html: "<h4>" + error.response.data.message + "</h4>",
                icon: "error",
                showConfirmButton: true,
            });
        });
    return studentsData;
}

window.showStudent = async function () {
    let studentsTbl = document.getElementById("studentsTblBody");
    studentsTbl.innerHTML = null;
    var studentsData = await getStudentOnLevel();
    if (studentsData === null) {
        return;
    }
    if (studentsData.length < 1) {
        Swal.fire({
            position: "center",
            html: "<h4>لا يوجد متدربين في هذا المستوى</h4>",
            icon: "warning",
            showConfirmButton: true,
        });
        return;
    }
    $("#studentsModal").modal("show")
    for (var i = 0; i < studentsData.length; i++) {
        let row = studentsTbl.insertRow(i);

        let national_id = row.insertCell(0);
        national_id.className = "text-center";
        national_id.innerHTML = studentsData[i].national_id;

        let rayat_id = row.insertCell(1);
        rayat_id.className = "text-center";
        rayat_id.innerHTML = studentsData[i].student.rayat_id != null ? studentsData[i].student.rayat_id : 'لا يوجد';

        let name = row.insertCell(2);
        name.className = "text-center";
        name.innerHTML = studentsData[i].name;

        let checkBoxCell = row.insertCell(3);
        checkBoxCell.className = "text-center";
        let checkBox = document.createElement("input");
        checkBox.setAttribute("type", "checkbox");
        if(studentsData[i].student.studentState == 1){
            checkBox.setAttribute("checked", "true");
        }
        checkBox.setAttribute("value", "1");
        checkBox.setAttribute("data-national_id", studentsData[i].national_id);
        checkBox.setAttribute("data-level", studentsData[i].student.level);
        checkBox.addEventListener("click", (event) =>
            window.studentCheckBoxChanged(event)
        );
        checkBoxCell.appendChild(checkBox);
    }
};

window.studentCheckBoxChanged = function (event) {
    let student = event.target;
    let formData = {
      'national_id':  student.dataset.national_id,
      'studentState': student.checked,
    }; 
    Swal.fire({
        html: "<h4>جاري تحديث البيانات</h4>",
        timerProgressBar: true,
        allowOutsideClick: false,
        showConfirmButton: false,
        willOpen() {
            Swal.showLoading();
        },
        willOpen() {
            Swal.hideLoading();
        },
    });
     axios
        .post(window.updateStudentState,formData)
        .then((response) => {
            Swal.fire({
                position: "center",
                // html: "<h4>"+response.data.message+"</h4>",
                icon: "success",
                showConfirmButton: false,
                timer: 1000,
            });
        })
        .catch((error) => {
            Swal.fire({
                position: "center",
                html: "<h4>" +error.response.data.message+ "</h4>",
                icon: "error",
                showConfirmButton: true,
            });
        });


};
