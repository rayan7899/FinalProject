const {
    default: axios
} = require("axios");

window.getCourses = async function () {

    if (window.courses == undefined || window.courses == null) {
        let major = document.getElementById("major").value;
        if (major !== "") {
            await axios.get('/api/major/courses/' + major)
                .then((response) => {
                    window.courses = response.data;
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
    }

    var level = document.getElementById('level');
    window.fillCoursesTable(window.courses,level.value);

};

window.fillCoursesTable = function (courses,level) {
    var tblAllCourses = document.getElementById('courses');
    tblAllCourses.innerHTML = '';
    courses.forEach(course => {
        if (course.level == level) {

            var row = tblAllCourses.insertRow(0);
            row.setAttribute("data-id", course.id);
            row.setAttribute("data-selected", false);
            row.setAttribute("data-hours", course.credit_hours);
            row.addEventListener("click", (event) =>
                window.courseClicked(event)
            );
            let code = row.insertCell(0);
            let name = row.insertCell(1);
            let level = row.insertCell(2);
            let CreditHours = row.insertCell(3);
            let ContactHours = row.insertCell(4);
            let tHours = row.insertCell(5);
            let examTHours = row.insertCell(6);
            let pHours = row.insertCell(7);
            let examPHours = row.insertCell(8);
            let editBtn = row.insertCell(9);
            let deleteBtn = row.insertCell(10);
            code.className = "text-center";
            name.className = "text-center";
            level.className = "text-center";
            CreditHours.className = "text-center";
            ContactHours.className = "text-center";
            tHours.className = "text-center";
            examTHours.className = "text-center";
            pHours.className = "text-center";
            examPHours.className = "text-center";
            deleteBtn.className = "text-center";
            editBtn.className = "text-center";
            code.innerHTML = course.code;
            name.innerHTML = course.name;
            level.innerHTML = window.getStringLevel(course.level);
            CreditHours.innerHTML = course.credit_hours;
            ContactHours.innerHTML = course.contact_hours;
            tHours.innerHTML = course.theoretical_hours;
            examTHours.innerHTML = course.exam_theoretical_hours;
            pHours.innerHTML = course.practical_hours;
            examPHours.innerHTML = course.exam_practical_hours;
            deleteBtn.innerHTML = `<a href="#" onclick="window.deleteCourse(event,'${ course.id }')">
                <i class="fa fa-trash fa-lg text-danger" aria-hidden="true"></i></a>`;
            editBtn.innerHTML = `<a href="/${window.type}/courses/edit/${course.id}"> 
                <i class="fa fa-edit fa-lg text-primary" aria-hidden="true"></i></a>`;

        }
    });
}

window.deleteCourse = function (event, id) {
    if (event !== null) {
        var row = event.currentTarget.parentNode.parentNode;
    }
    event.preventDefault();
    Swal.fire({
        title: ' هل انت متأكد ؟',
        text: "سيم حذف المقر ، لا يمكن التراجع عن هذا الاجراء",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'نعم',
        cancelButtonText: 'الغاء',
    }).then((result) => {
        if (result.isConfirmed) {
            deleteRequest(row, id);
        }
    })
}

function deleteRequest(row, id) {

    Swal.fire({
        html: "<h4>جاري تحديث البيانات</h4>",
        timerProgressBar: true,
        didOpen: () => {
            Swal.showLoading();
        },
    });
    axios.get('/department-boss/courses/delete/' + id)
        .then((response) => {
            row.remove();
            // location.reload();
            Swal.fire({
                position: "center",
                html: "<h4>" + response.data.message + "</h4>",
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

    Swal.close();
}
