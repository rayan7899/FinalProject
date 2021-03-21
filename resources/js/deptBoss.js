// const { default: axios } = require("axios");

window.getStringLevel = function (level) {
    switch (level) {
        case 1:
            return "الاول";
            break;
        case 2:
            return "الثاني";
            break;
        case 3:
            return "الثالث";
            break;
        case 4:
            return "الرابع";
            break;
        case 5:
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
    getCoursesData();
    var tblCourses = document.getElementById("courses");
    tblCourses.innerHTML = null;
    let major = document.getElementById("major").value;
    if (major !== "") {
        var courses = findCourses(major);
    } else {
        return;
    }
    for (var i = 0; i < courses.length; i++) {
        let tblIndex = 0;
        if (courses[i].suggested_level == 0) {
            let row = tblCourses.insertRow(tblIndex);
            row.setAttribute("data-id", courses[i].id);
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
    suggestedLevelChanged();
};

window.suggestedLevelChanged = function (event = null) {
    getCoursesData();
    var suggestedLevelTbl = document.getElementById("suggestedLevelTbl");
    suggestedLevelTbl.innerHTML = null;
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
            let suggLevelRow = suggestedLevelTbl.insertRow(suggLevelIndex);
            suggLevelRow.setAttribute("data-id", courses[i].id);
            suggLevelRow.setAttribute("data-selected", false);
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
 
}

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
    let suggestedLevelTbl = document.getElementById("suggestedLevelTbl");
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
        suggestedLevelTbl.appendChild(row);
    });
    updateCoursesRequset(coursesData);
};

window.removeCourses = function (event) {
    let coursesData = {
        suggested_level: 0,
        courses: [],
    };
    event.preventDefault();
    let tblCourses = document.getElementById("courses");
    let suggestedLevelTbl = document.getElementById("suggestedLevelTbl");
    let selectedCourses = suggestedLevelTbl.querySelectorAll(
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
        tblCourses.appendChild(row);
    });
    updateCoursesRequset(coursesData);
};

function updateCoursesRequset(coursesData) {
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

function getCoursesData() {
    axios
        .get(window.getCoursesDataUrl)
        .then((response) => {
            window.programs = response.data;
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
