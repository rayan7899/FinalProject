window.fillManageCoursesTbl = function (courses) {

    if (courses == undefined || courses == null) {
        let major = document.getElementById("major").value;
        if (major !== "") {
            var courses = findCourses(major);
            if (courses == undefined || courses == null) {
                return;
            }
        }
    }


    var tblAllCourses = document.getElementById('courses');
    var level = document.getElementById('level');

    tblAllCourses.innerHTML = '';
    courses.forEach(course => {
        if (course.level == level.value) {

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
            let editBtn = row.insertCell(5);
            let deleteBtn = row.insertCell(6);
            code.className = "text-center";
            name.className = "text-center";
            level.className = "text-center";
            CreditHours.className = "text-center";
            ContactHours.className = "text-center";
            deleteBtn.className = "text-center";
            editBtn.className = "text-center";
            code.innerHTML = course.code;
            name.innerHTML = course.name;
            level.innerHTML = window.getStringLevel(course.level);
            CreditHours.innerHTML = course.credit_hours;
            ContactHours.innerHTML = course.contact_hours;
            deleteBtn.innerHTML = '<a href="#" onclick="window.deleteCourse(event,' + course.id + ')">' +
                '<i class="fa fa-trash fa-lg text-danger" aria-hidden="true"></i></a>';
            editBtn.innerHTML = '<a href="/community/courses/edit/' + course.id + '">' +
                '<i class="fa fa-edit fa-lg text-primary" aria-hidden="true"></i></a>';

        }
    });
};

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
    axios.get('/community/courses/delete/' + id)
        .then((response) => {
            row.remove();
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


