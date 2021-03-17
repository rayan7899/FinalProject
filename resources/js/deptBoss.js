window.findCourses = function (major) {
    var program_id = document.getElementById('program').value;
    var department_id = document.getElementById('department').value;
    for (var i = 0; i < window.programs.length; i++) {
        for (var j = 0; j < window.programs[i].departments.length; j++) {
            if (window.programs[i].id == program_id && window.programs[i].departments[j].id == department_id) {
                // return window.programs[i].departments[j].majors;
                for (var k = 0; k < window.programs[i].departments[j].majors.length; k++) {
                    if (window.programs[i].departments[j].majors[k].id == major) {
                        return window.programs[i].departments[j].majors[k].courses;
                    }
                }
            }
        }
    }
}

window.getCourses = function () {

    var tblCourses = document.getElementById('courses');
    var major = document.getElementById('major').value;
    var courses = findCourses(major);
    for (let i = 0; i < courses.length; i++) {
        var row = tblCourses.insertRow(i);
        var name = row.insertCell(0);
        var hours = row.insertCell(1);
        name.innerHTML = courses[i].name;
        hours.innerHTML = courses[i].hours;
    }
}