@extends('layouts.app')
@section('content')
    <div class="container-fluid">
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $message)
                        <li>{{ $message }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        @if (session()->has('error') || isset($error))
            <div class="alert alert-danger">
                {{ session()->get('error') ?? $error }}
            </div>
        @endif
        @if (session()->has('success') || isset($success))
            <div class="alert alert-success">
                {{ session()->get('success') ?? $success }}
            </div>
        @endif
        <div class="justify-content-center">
            <div class="card  m-auto">
                <div class="card-header">
                    <div class="d-flex flex-row justify-content-between">
                        <h5>ادارة المقررات</h5>
                        @php
                            if (Auth::user()->hasRole('خدمة المجتمع')) {
                                $route = route('createCourseForm');
                                $type = 'community';
                            } elseif (Auth::user()->isDepartmentManager()) {
                                $route = route('deptCreateCourseForm');
                                $type = 'department-boss';
                            }
                        @endphp
                        <a href="{{ $route }}" class="btn btn-primary rounded">
                            اضافة مقرر جديد
                        </a>
                    </div>

                </div>
                <div class="card-body d-flex flex-column justify-content-center p-4">
                    <div class="row">
                        <div class="col">
                            <div class="form-row mb-3">
                                <div class="col-sm-4">
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
                                <div class="col-sm-4 ">
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
                                <div class="col-sm-4">
                                    <label for="major" class="pl-1"> التخصص </label>
                                    <select required name="major" id="major" class="form-control w-100"
                                        onchange="getCourses()">
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
                        <div class="col">
                            <div class="card">
                                <div class="card-header">
                                    <h6 class="d-inline">مقررات التخصص</h6>
                                    <select id="level" onchange="getCourses()" class="ml-0 d-inline mx-3">
                                        <option value="1"> المستوى الاول</option>
                                        <option value="2"> المستوى الثاني</option>
                                        <option value="3"> المستوى الثالث</option>
                                        <option value="4"> المستوى الرابع</option>
                                        <option value="5"> المستوى الخامس</option>
                                    </select>
                                </div>
                                <div class="card-body p-0">
                                    <table id="originalCoursesTbl" class="table table-hover">
                                        <thead>
                                            <tr>
                                                <th class="text-center">رمز المقرر</th>
                                                <th class="text-center">اسم المقرر</th>
                                                <th class="text-center">المستوى</th>
                                                <th class="text-center">الساعات المعتمدة</th>
                                                <th class="text-center">ساعات الإتصال</th>
                                                <th class="text-center">عدد الساعات - نظري</th>
                                                <th class="text-center">ساعات الاختبار - نظري</th>
                                                <th class="text-center">عدد الساعات - عملي</th>
                                                <th class="text-center">ساعات الاختبار - عملي</th>
                                                <th class="text-center"></th>
                                                <th class="text-center"></th>
                                            </tr>
                                        </thead>
                                        <tbody id="courses">
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>


        {{-- @php dd(json_decode($programs)); @endphp --}}
        <script>
            function getStrLevel(level) {
                level = parseInt(level);
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
            var programs = @php echo $programs; @endphp;
            var type = "{{ $type ?? 'null' }}";

            var courses = @php echo session()->get('courses') ?? 'null'; @endphp;

            window.addEventListener('DOMContentLoaded', (event) => {
                if (courses != null) {
                    let course = @php echo session()->get('course') ?? 'null'; @endphp;

                    document.getElementById('level').value = course.level;
                    document.getElementById('program').value = course.major.department.program.id;

                    let dept = document.getElementById("department");
                    dept.innerHTML = '';
                    let option = document.createElement("option");
                    option.innerHTML = course.major.department.name;
                    option.value = course.major.department.id;
                    dept.appendChild(option);

                    let mjr = document.getElementById("major");
                    mjr.innerHTML = '';
                    option = document.createElement("option");
                    option.innerHTML = course.major.name;
                    option.value = course.major.id;
                    mjr.appendChild(option);

                    window.fillCoursesTable(courses, course.level);

                }

            });
            // console.log(courses);
            // if (lastCourse != null) {
            //     var row = document.getElementById('courses').insertRow(0);
            //     row.setAttribute("data-id", lastCourse.id);
            //     row.setAttribute("data-selected", false);
            //     row.setAttribute("data-hours", lastCourse.credit_hours);
            //     row.addEventListener("click", (event) =>
            //         window.courseClicked(event)
            //     );
            //     let code = row.insertCell(0);
            //     let name = row.insertCell(1);
            //     let level = row.insertCell(2);
            //     let CreditHours = row.insertCell(3);
            //     let ContactHours = row.insertCell(4);
            //     let tHours = row.insertCell(5);
            //     let examTHours = row.insertCell(6);
            //     let pHours = row.insertCell(7);
            //     let examPHours = row.insertCell(8);
            //     let editBtn = row.insertCell(9);
            //     let deleteBtn = row.insertCell(10);
            //     code.className = "text-center";
            //     name.className = "text-center";
            //     level.className = "text-center";
            //     CreditHours.className = "text-center";
            //     ContactHours.className = "text-center";
            //     tHours.className = "text-center";
            //     examTHours.className = "text-center";
            //     pHours.className = "text-center";
            //     examPHours.className = "text-center";
            //     deleteBtn.className = "text-center";
            //     editBtn.className = "text-center";
            //     code.innerHTML = lastCourse.code;
            //     name.innerHTML = lastCourse.name;
            //     level.innerHTML = getStrLevel(lastCourse.level);
            //     CreditHours.innerHTML = lastCourse.credit_hours;
            //     ContactHours.innerHTML = lastCourse.contact_hours;
            //     tHours.innerHTML = lastCourse.theoretical_hours;
            //     examTHours.innerHTML = lastCourse.exam_theoretical_hours;
            //     pHours.innerHTML = lastCourse.practical_hours;
            //     examPHours.innerHTML = lastCourse.exam_practical_hours;
            //     deleteBtn.innerHTML = `<a href="#" onclick="window.deleteCourse(event,'${ lastCourse.id }')">
    //     <i class="fa fa-trash fa-lg text-danger" aria-hidden="true"></i></a>`;
            //     editBtn.innerHTML = `<a href="/${window.type}/courses/edit/${lastCourse.id}"> 
    //     <i class="fa fa-edit fa-lg text-primary" aria-hidden="true"></i></a>`;
            // }
        </script>
    </div>
@stop
