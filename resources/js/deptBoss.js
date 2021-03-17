window.findCourses = function (major) {
    let program_id = document.getElementById('program').value;
    let department_id = document.getElementById('department').value;
    for (let i = 0; i < window.programs.length; i++) {
        for (let j = 0; j < window.programs[i].departments.length; j++) {
            if (window.programs[i].id == program_id && window.programs[i].departments[j].id == department_id) {
                // return window.programs[i].departments[j].majors;
                for (let k = 0; k < window.programs[i].departments[j].majors.length; k++) {
                    if (window.programs[i].departments[j].majors[k].id == major) {
                        return window.programs[i].departments[j].majors[k].courses;
                    }
                }
            }
        }
    }
}

window.getCourses = function () {

    let tblCourses = document.getElementById('courses');
    tblCourses.innerHTML = null;
    let major = document.getElementById('major').value;
    let courses = findCourses(major);
    for (let i = 0; i < courses.length; i++) {
        let row = tblCourses.insertRow(i);
        row.setAttribute('data-id',courses[i].id);
        row.setAttribute('data-selected',false);
        row.addEventListener('click',(event) => window.courseClicked(event))
        let code = row.insertCell(0);
        let name = row.insertCell(1);
        let hours = row.insertCell(2);
        code.className ="text-center";
        name.className ="text-center";
        hours.className ="text-center";
        code.innerHTML = courses[i].code;
        name.innerHTML = courses[i].name;
        hours.innerHTML = courses[i].hours;
    }
}

window.courseClicked = function(event){
    let courseRow = event.currentTarget;
console.log(courseRow);
if(courseRow.dataset.selected == "true"){
    courseRow.setAttribute('data-selected',false);
    courseRow.classList.add('bg-light');
    courseRow.classList.add('text-dark');
    courseRow.classList.remove('bg-info');
    courseRow.classList.remove('text-white');
}else{
    courseRow.setAttribute('data-selected',true);
    courseRow.classList.add('bg-info');
    courseRow.classList.add('text-white');
    courseRow.classList.remove('bg-light');
    courseRow.classList.remove('text-dark');
}



}

window.addCourses = function(event){
    event.preventDefault();
    let tblCourses = document.getElementById('courses');
    let levelCourses = document.getElementById('levelCourses');
    let selectedCourses = tblCourses.querySelectorAll("[data-selected='true']");
    selectedCourses.forEach(row => {
        // row = row.cloneNode(true);
        row.setAttribute('data-selected',false);
        row.classList.add('bg-light');
        row.classList.add('text-dark');
        row.classList.remove('bg-info');
        row.classList.remove('text-white');
        levelCourses.appendChild(row)
      })
    console.log(selectedCourses);
}

window.removeCourses = function(event){
    event.preventDefault();
    let tblCourses = document.getElementById('courses');
    let levelCourses = document.getElementById('levelCourses');
    let selectedCourses = levelCourses.querySelectorAll("[data-selected='true']");
    selectedCourses.forEach(row => {
        // row = row.cloneNode(true);
        row.setAttribute('data-selected',false);
        row.classList.add('bg-light');
        row.classList.add('text-dark');
        row.classList.remove('bg-info');
        row.classList.remove('text-white');
        tblCourses.appendChild(row)
      })
    console.log(selectedCourses);
}