var studentNationalId;

jQuery(function () {
    $.ajaxSetup({
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
    });
});

window.getStudentCourses = function () {
    var id = document.getElementById('search').value;
    var section2 = document.getElementById('section2');
    $.ajax({
        type: "get",
        url: '/deptBoss/student-info/' + id,
        headers: {
            Accept: "application/json",
            ContentType: "application/json",
        },
        dataType: "json",
        success: function (response) {
            Swal.fire({
                position: "center",
                icon: "success",
                showConfirmButton: false,
                timer: 1700,
            });
                section2.style.display = "flex";
                studentNationalId = response.national_id;
                fillStudentTable(response.student.courses);
        },
        error: function (response) {
            const message = response.responseJSON.message;
            Swal.fire({
                position: "center",
                icon: "error",
                title: message,
                showConfirmButton: true,
            });
        },
    });
}

function fillStudentTable(courses) {
    var tblAllCourses = document.getElementById('studentCourses');
    tblAllCourses.innerHTML = null;
    courses.forEach(course => {
        console.log(course);
        var row = tblAllCourses.insertRow(0);
        var code = row.insertCell(0);
        var name = row.insertCell(1);
        var level = row.insertCell(2);
        var CreditHours = row.insertCell(3);
        var ContactHours = row.insertCell(4);
        code.innerHTML = course.code;
        name.innerHTML = course.name;
        level.innerHTML = course.level;
        CreditHours.innerHTML = course.credit_hours;
        ContactHours.innerHTML = course.contact_hours;
    });
}

window.getMajors = function () {
    var programId = document.getElementById('program').value;
    axios.get('/majorsByProgramId/' + programId)
        .then((response) => {
            fillMajors(response.data.majors);
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

function fillMajors(majors) {
    var slctMajor = document.getElementById('major');
    slctMajor.innerHTML = '<option value="" disabled selected>حدد التخصص</option>';
    majors.forEach(major => {
        var option = document.createElement("option");
        option.innerHTML = major.name;
        option.value = major.id;
        slctMajor.appendChild(option);
    });
}

window.getCourses = function () {
    var majorId = document.getElementById('major').value;
    $.ajax({
        type: "get",
        url: '/major/' + majorId + '/courses',
        headers: {
            Accept: "application/json",
            ContentType: "application/json",
        },
        dataType: "json",
        success: function (response) {
            fillAllCoursesTable(response.courses);
        },
        error: function (response) {
            const message = response.responseJSON.message;
            Swal.fire({
                position: "center",
                icon: "error",
                title: 'error',
                showConfirmButton: true,
            });
        },
    });
}

function fillAllCoursesTable(courses) {
    var tblAllCourses = document.getElementById('allCourses');
    tblAllCourses.innerHTML = '';
    courses.forEach(course => {
        var row = tblAllCourses.insertRow(0);
        row.setAttribute("data-id", course.id);
        row.setAttribute("data-selected", false);
        row.addEventListener("click", (event) =>
            window.courseClicked(event)
        );
        var code = row.insertCell(0);
        var name = row.insertCell(1);
        var level = row.insertCell(2);
        var CreditHours = row.insertCell(3);
        var ContactHours = row.insertCell(4);
        // var remove = row.insertCell(5);
        code.innerHTML = course.code;
        name.innerHTML = course.name;
        level.innerHTML = course.level;
        CreditHours.innerHTML = course.credit_hours;
        ContactHours.innerHTML = course.contact_hours;
        // remove.innerHTML = '<p class="btn">حذف</p>';
    });
}


window.addCourseToStudentTable = function () {
    let tblAllCourses = document.getElementById("allCourses");
    let tblStudentCourses = document.getElementById("studentCourses");
    let selectedCourses = tblAllCourses.querySelectorAll("[data-selected='true']");
    let coursesData = {
        studentNationalId: studentNationalId,
        courses: [],
    };
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
        // // row = row.cloneNode(true);
        coursesData.courses.push(row.dataset.id);
        row.setAttribute("data-selected", false);
        row.classList.add("bg-light");
        row.classList.add("text-dark");
        row.classList.remove("bg-info");
        row.classList.remove("text-white");
        // tblStudentCourses.appendChild(row);
        
    });
    addCoursesRequset(coursesData);
}

function addCoursesRequset(coursesData) {
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
        .post('/student-courses/add', coursesData)
        .then((response) => {
            getStudentCourses();
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