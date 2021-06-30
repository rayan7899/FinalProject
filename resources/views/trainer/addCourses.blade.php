@extends('layouts.app')
@section('content')
    <div class="container-fluid">
        <div class="modal fade" id="studentsModal" tabindex="-1" role="dialog" aria-labelledby="modalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-scrollable" style="max-width: 75%" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title"></h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <table id="studentsTbl" class="table">
                            <thead>
                                <tr>
                                    <th class="text-center">رقم الهوية</th>
                                    <th class="text-center">الرقم التدريبي</th>
                                    <th class="text-center">الاسم</th>
                                    <th class="text-center">الحالة</th>
                                </tr>
                            </thead>
                            <tbody id="studentsTblBody">
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-header">
                <h5>الجداول المقترحة</h5>
            </div>
            <div class="card-body d-flex flex-column justify-content-center p-4">
                <div class="row">
                    <div class="col">
                        <div class="form-row mb-3">
                            <div class="col-md-3">
                                <label class="pl-1"> الاسم </label>
                                <input disabled type="text" class="form-control" value="{{ $user->name ?? 'Error' }}">
                            </div>
                            <div class="col-md-3">
                                <label class="pl-1"> رقم الحاسب </label>
                                <input disabled type="text" class="form-control"
                                    value="{{ $user->trainer->computer_number ?? 'Error' }}">
                            </div>
                            <div class="col-md-3">
                                <label class="pl-1"> القسم </label>
                                <input disabled type="text" class="form-control"
                                    value="{{ $user->trainer->department->name ?? 'Error' }}">
                            </div>
                            <div class="col-md-3">
                                <label class="pl-1"> عدد الاسابيع الفصلية المتوقعة </label>
                                <input disabled type="text" class="form-control" value="12">
                            </div>
                            <div class="col-md-4">
                                <label for="program" class="pl-1"> البرنامج </label>
                                <select required name="program" id="program" class="form-control w-100"
                                    onchange="fillDepartments()">
                                    <option value="0" disabled selected>أختر</option>
                                    @forelse (json_decode($programs) as $program)
                                        <option value="{{ $program->id }}">{{ $program->name }}</option>
                                    @empty
                                    @endforelse

                                </select>
                                @error('program')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="col-md-4 ">
                                <label for="department" class="pl-1"> القسم </label>
                                <select required name="department" id="department" class="form-control w-100 "
                                    onchange="fillMajors()">
                                    <option value="0" disabled selected>أختر</option>
                                </select>
                                @error('department')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="col-md-4">
                                <label for="major" class="pl-1"> التخصص </label>
                                <select required name="major" id="major" class="form-control w-100"
                                    onchange="fillAllCourses()">
                                    <option value="0" disabled selected>أختر</option>
                                </select>
                                @error('major')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row justify-content-center">
                    <!-- جدول المقررات المتاحة -->
                    <div class="col-sm-6 p-0">
                        <div class="card">
                            <div class="card-header">
                                <h6 class="d-inline">المقررات المتاحة</h6>
                                <select id="originalLevel" onchange="fillAllCourses()" class="ml-0 d-inline mx-3">
                                    <option value="1"> المستوى الاول</option>
                                    <option value="2"> المستوى الثاني</option>
                                    <option value="3"> المستوى الثالث</option>
                                    <option value="4"> المستوى الرابع</option>
                                    <option value="5"> المستوى الخامس</option>
                                </select>
                            </div>
                            <div class="card-body p-0">
                                <table id="originalCoursesTbl" class="table table-sm text-nowrap table-hover table-responsive mb-0">
                                    <thead>
                                        <tr>
                                            <th class="text-center">رمز المقرر</th>
                                            <th class="text-center">اسم المقرر</th>
                                            <th class="text-center">المستوى</th>
                                            <th class="text-center">نوع المقرر</th>
                                            <th class="text-center">الساعات المعتمدة</th>
                                            <th class="text-center">ساعات الإتصال</th>
                                            <th class="text-center">رقم الشعبة</th>
                                            <th class="text-center">عدد المتدربين</th>
                                        </tr>
                                    </thead>
                                    <tbody id="courses">
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <div class="col-1 d-lg-block p-0">
                        <div class="row justify-content-center mt-2">
                            <i class="btn btn-outline-secondary fa fa-plus mb-3" onclick="addSelectedCourses(event)"></i>
                        </div>
                    </div>

                    <!-- جدول المدرب -->
                    <div class="col-sm-5 p-0">
                        <div class="card">
                            <div class="card-header">
                                <div class="d-inline">
                                    <h6 class="d-inline">جدول المدرب</h6>
                                </div>
                            </div>
                            <div class="card-body p-0">
                                <table id="trainerTable" class="table table-sm mb-0 table-responsive">
                                    <thead>
                                        <tr>
                                            <th class="text-center">رمز المقرر</th>
                                            <th class="text-center">اسم المقرر</th>
                                            <th class="text-center">المستوى</th>
                                            <th class="text-center">نوع المقرر</th>
                                            <th class="text-center">الساعات المعتمدة</th>
                                            <th class="text-center">ساعات الإتصال</th>
                                            <th class="text-center">رقم الشعبة</th>
                                            <th class="text-center">عدد المتدربين</th>
                                            <th class="text-center"></th>
                                        </tr>
                                    </thead>
                                    <tbody id="trainerTableBody">
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="justify-content-center text-center pt-5">
                    <button onclick="save()" class="btn btn-primary px-5">ارسال</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        var programs = @php echo $programs; @endphp;
        var updateCoursesLevelUrl = "{{ route('apiUpdateCoursesLevel') }}";
        var getStudentOnLevelUrl = "{{ route('getStudentOnLevel') }}";
        var getCoursesUrl = "{{ route('apiGetCourses') }}";
        var updateStudentState = "{{ route('updateStudentState') }}";

        function fillAllCourses() {

            var tblCourses = document.getElementById("courses");
            let originalLevel = document.getElementById("originalLevel");
            tblCourses.innerHTML = "";
            let major = document.getElementById("major").value;
            if (major !== "") {
                var courses = findCourses(major);
                if (courses == undefined || courses == null) {
                    return;
                }
            } else {
                return;
            }
            for (var i = 0; i < courses.length; i++) {
                let tblIndex = 0;
                if (
                    courses[i].suggested_level == 0 &&
                    courses[i].level == parseInt(originalLevel.value)
                ) {
                    if(courses[i].practical_hours > 0){
                        let row = tblCourses.insertRow(tblIndex);
                        row.setAttribute("data-id", courses[i].id);
                        row.setAttribute("data-level", courses[i].level);
                        row.setAttribute("data-selected", false);
                        row.addEventListener("click", (event) =>
                            onCourseClicked(event)
                        );
                        let code = row.insertCell(0);
                        let name = row.insertCell(1);
                        let level = row.insertCell(2);
                        let type = row.insertCell(3);
                        let credit_hours = row.insertCell(4);
                        let contact_hours = row.insertCell(5);
                        let division = row.insertCell(6);
                        let students = row.insertCell(7);
                        code.className = "text-center";
                        name.className = "text-center";
                        type.className = "text-center";
                        credit_hours.className = "text-center";
                        contact_hours.className = "text-center";
                        division.className = "text-center";
                        students.className = "text-center";
                        code.innerHTML = courses[i].code;
                        name.innerHTML = courses[i].name;
                        level.innerHTML = getStringLevel(courses[i].level);
                        type.innerHTML = 'عملي';
                        credit_hours.innerHTML = courses[i].credit_hours;
                        contact_hours.innerHTML = courses[i].practical_hours;
                        division.innerHTML =
                            '<input type="text" class="form-control self-align-top" placeholder="رقم الشعبة"/>';
                        students.innerHTML =
                            '<input type="text" class="form-control self-align-top" placeholder="عدد المتدربين"/>';
                        tblIndex++;
                    }


                    if(courses[i].theoretical_hours > 0){
                        let row2 = tblCourses.insertRow(tblIndex);
                        row2.setAttribute("data-id", courses[i].id);
                        row2.setAttribute("data-level", courses[i].level);
                        row2.setAttribute("data-selected", false);
                        row2.addEventListener("click", (event) =>
                            onCourseClicked(event)
                        );
                        let code2 = row2.insertCell(0);
                        let name2 = row2.insertCell(1);
                        let level2 = row2.insertCell(2);
                        let type2 = row2.insertCell(3);
                        let credit_hours2 = row2.insertCell(4);
                        let contact_hours2 = row2.insertCell(5);
                        let division2 = row2.insertCell(6);
                        let students2 = row2.insertCell(7);
                        code2.className = "text-center";
                        name2.className = "text-center";
                        type2.className = "text-center";
                        credit_hours2.className = "text-center";
                        contact_hours2.className = "text-center";
                        division2.className = "text-center";
                        students2.className = "text-center";
                        code2.innerHTML = courses[i].code;
                        name2.innerHTML = courses[i].name;
                        level2.innerHTML = getStringLevel(courses[i].level);
                        type2.innerHTML = 'نظري';
                        credit_hours2.innerHTML = courses[i].credit_hours;
                        contact_hours2.innerHTML = courses[i].theoretical_hours;
                        division2.innerHTML =
                            '<input type="text" class="form-control self-align-top" placeholder="رقم الشعبة"/>';
                        students2.innerHTML =
                            '<input type="text" class="form-control self-align-top" placeholder="عدد المتدربين"/>';
                        tblIndex++;
                    }
                }
            }
        }


        function addSelectedCourses(event) {
            event.preventDefault();

            let tblCourses = document.getElementById("courses");
            let selectedCourses = tblCourses.querySelectorAll("[data-selected='true']");


            if (selectedCourses.length < 1) {
                Swal.fire({
                    position: "center",
                    html: "<h4>يجب تحديد مقرر واحد على الاقل</h4>",
                    icon: "warning",
                    showConfirmButton: true,
                });
                return;
            }else{
                Swal.fire({
                    html: "<h4>جاري تحديث البيانات</h4>",
                    timerProgressBar: true,
                    didOpen: () => {
                        Swal.showLoading();
                    },
                });
            }

            var isThereEmptyField = false;
            var division_numbers = [];
            selectedCourses.forEach((row) => {
                if (row.children[7].firstChild.value == null || row.children[7].firstChild.value == '') {
                    isThereEmptyField = true;
                }
                division_numbers.push(row.children[6].firstChild.value);
            });
            if (isThereEmptyField) {
                Swal.fire({
                    position: "center",
                    html: "<h4>يجب ادخال عدد الطلاب لجميع المقررات المحددة</h4>",
                    icon: "warning",
                    showConfirmButton: true,
                });
                return;
            }

            axios.post(`/api/trainer/check-division-number`, {
                    division_numbers: division_numbers
                })
                .then((response) => {
                    console.log(response);
                    if (response.data.message) {
                        Swal.fire({
                            position: "center",
                            html: "<h4>رقم الشعبة المدخل مستخدم من قبل</h4>",
                            icon: "warning",
                            showConfirmButton: true,
                        });
                        return;
                    } else {
                        Swal.fire({
                            position: "center",
                            // html: "<h4>"+response.data.message+"</h4>",
                            icon: "success",
                            showConfirmButton: false,
                            timer: 1000,
                        });
                        selectedCourses.forEach((row) => {
                            var newRow = row.cloneNode(true);
                            row.setAttribute("data-selected", false);
                            newRow.classList.remove("bg-info");
                            newRow.classList.remove("text-white");
                            newRow.children[6].innerHTML = row.children[6].firstChild.value;
                            newRow.children[7].innerHTML = row.children[7].firstChild.value;
                            let icon = newRow.insertCell(8);
                            icon.innerHTML = '<i class="fa fa-trash fa-lg btn text-danger" aria-hidden="true" onclick="console.log(this.parentNode.parentNode.remove())"></i>';
                            trainerTableBody.appendChild(newRow);

                            row.children[6].firstChild.value = '';
                            row.children[7].firstChild.value = '';
                            row.classList.remove("bg-info");
                            row.classList.remove("text-white");
                            row.setAttribute("data-selected", false);
                        });
                    }
                })
                .catch((error) => {
                    Swal.fire({
                        position: "center",
                        html: "<h4>" + error.response.data.message + "</h4>",
                        icon: "error",
                        showConfirmButton: true,
                    });
                });



            // Swal.fire({
            //     position: "center",
            //     // html: "<h4>"+response.data.message+"</h4>",
            //     icon: "success",
            //     showConfirmButton: false,
            //     timer: 1000,
            // });

        }


        function onCourseClicked(event) {
            let courseRow = event.currentTarget;
            if (courseRow.dataset.selected == "true" && (courseRow.children[6].firstChild !== document.activeElement &&
                    courseRow.children[7].firstChild !== document.activeElement)) {
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
        }

        function save() {

            var orders = [];

            Array.from(trainerTableBody.children).forEach(row => {
                orders.push({
                    course_id: row.dataset.id,
                    count_of_students: row.children[7].firstChild.data,
                    division_number: row.children[6].firstChild.data,
                    course_type: row.children[3].firstChild.data,
                });
            });
            if(orders.length == 0){
                Swal.fire({
                    position: "center",
                    html: "<h4>يجب اضافة مقرر واحد على الاقل</h4>",
                    icon: "warning",
                    showConfirmButton: true,
                });
                return;
            }else{
                Swal.fire({
                    html: "<h4>جاري ارسال الطلب</h4>",
                    timerProgressBar: true,
                    didOpen: () => {
                        Swal.showLoading();
                    },
                });
            }
            axios.post('{{ route('addCoursesToTrainerStore') }}', {
                    orders: orders
                })
                .then((response) => {
                    window.location.reload();
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
        }

    </script>
    </div>
@stop
