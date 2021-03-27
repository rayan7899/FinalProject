@extends('layouts.app')
@section('content')

    <div class="container-fluid ">
        <div class="form-inline m-3">
            <div class="form-group">
                <input type="text" class="form-control" name="search" id="search"
                    placeholder="ادخل رقم الطالب المدني او الاكاديمي" value="">
                <input type="button" onclick="getStudentCourses()" value="بحث" class="btn btn-primary">
            </div>
        </div>
        <div class="justify-content-center" id="section2" style="display: none">


            <!--------------------------------------------------------------------->
            <!--------------------------------------------------------------------->
            <!----------------student information------------------------------------>
            <!--------------------------------------------------------------------->
            <!--------------------------------------------------------------------->
            <div class="container-fluid">
                <div class="form-group row">
                    <div dir="ltr" class="input-group col-md-4 mb-1">
                        <input readonly type="text" class="form-control text-right bg-white" placeholder=""
                            aria-label="Recipient's username" aria-describedby="basic-addon2" id="studentName">
                        <div class="input-group-append">
                            <span class="input-group-text text-center" style="width: 100px;">الاسم</span>
                        </div>
                    </div>
                    <div dir="ltr" class="input-group col-md-4 mb-1">
                        <input readonly type="text" class="form-control text-right bg-white" placeholder=""
                            aria-label="Recipient's username" aria-describedby="basic-addon2" id="national_id">
                        <div class="input-group-append">
                            <span class="input-group-text text-center" style="width: 100px;">رقم الهوية</span>
                        </div>
                    </div>
                    <div dir="ltr" class="input-group col-md-4 mb-1">
                        <input readonly type="text" class="form-control text-right bg-white" placeholder=""
                            aria-label="Recipient's username" aria-describedby="basic-addon2" id="wallet">
                        <div class="input-group-append">
                            <span class="input-group-text text-center" style="width: 100px;">الرصيد</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="container-fluid row m-0">
                <!--------------------------------------------------------------------->
                <!--------------------------------------------------------------------->
                <!----------------all courses table------------------------------------>
                <!--------------------------------------------------------------------->
                <!--------------------------------------------------------------------->
                <div class="col-sm-5 p-0">
                    <div class="card">
                        <div class="card-header">
                            <div class="row">
                                <h6 class="d-inline col-4">المقررات</h6>
                                <select required name="program" id="program" class="form-controller col-4"
                                    onchange="getMajors()">
                                    <option value="" disabled selected>حدد البرنامج</option>
                                    <option value="1">بكالوريوس</option>
                                    <option value="2">دبلوم</option>
                                </select>
                                <select required name="major" id="major" class="form-controller col-4"
                                    onchange="getCourses()">
                                    <option value="" disabled selected>حدد التخصص</option>
                                </select>
                                @error('major')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                        </div>
                        <div class="card-body p-0 text-center">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th class="text-center">رمز المقرر</th>
                                        <th class="text-center">اسم المقرر</th>
                                        <th class="text-center">المستوى</th>
                                        <th class="text-center">الساعات المعتمدة</th>
                                        <th class="text-center">ساعات الإتصال</th>
                                    </tr>
                                </thead>
                                <tbody id="allCourses">
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="col-2 d-none d-md-block p-0">
                    <div class="row justify-content-center mt-2">
                        <a href="" onclick="addCourseToStudentTable(event)"
                            class="border border-dark rounded w-25 text-center btn btn-light px-2 my-2"
                            style="padding-bottom: 2px">
                            <img style="width: 16px; height: 14px;  margin-bottom: 3px;"
                                src="{{ asset('images/left-arrow.png') }}" alt="left-arrow-icon">
                        </a>
                    </div>
                </div>

                <div class="row d-flex justify-content-center justify-items-center d-sm-none p-3">
                    <div class="col justify-content-center">
                        <a href="" onclick="addCourseToStudentTable(event)"
                            class="border border-dark rounded w-25 text-center btn btn-light px-2 my-2"
                            style="padding-bottom: 2px">
                            <img style="width: 16px; height: 14px; transform: rotate(-90deg);"
                                src="{{ asset('images/left-arrow.png') }}" alt="left-arrow-icon">
                        </a>
                    </div>
                </div>

                <!--------------------------------------------------------------------->
                <!--------------------------------------------------------------------->
                <!----------------student courses table------------------------------------>
                <!--------------------------------------------------------------------->
                <!--------------------------------------------------------------------->
                <div class="col-sm-5 p-0">
                    <div class="card">
                        <div class="card-header">
                            <h6 class="d-inline">جدول الطالب</h6>
                        </div>
                        <div class="card-body p-0">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th class="text-center">رمز المقرر</th>
                                        <th class="text-center">اسم المقرر</th>
                                        <th class="text-center">المستوى</th>
                                        <th class="text-center">الساعات المعتمدة</th>
                                        <th class="text-center">ساعات الإتصال</th>
                                        <th class="text-center"></th>
                                    </tr>
                                </thead>
                                <tbody id="studentCourses">
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
    <script>
        // function fillTable(courses) {
        //     tblCourses = document.getElementById(tblCourses);
        //     console.log(courses);
        //     for (var i = 0; i < courses.length; i++) {
        //         let tblIndex = 0;
        // let row = tblCourses.insertRow(tblIndex);
        // row.setAttribute("data-id", courses[i].id);
        // row.setAttribute("data-selected", false);
        // row.addEventListener("click", (event) =>
        //     window.courseClicked(event)
        // );
        //         let code = row.insertCell(0);
        //         let name = row.insertCell(1);
        //         let level = row.insertCell(2);
        //         let credit_hours = row.insertCell(3);
        //         let contact_hours = row.insertCell(4);
        //         code.className = "text-center";
        //         name.className = "text-center";
        //         credit_hours.className = "text-center";
        //         contact_hours.className = "text-center";
        //         code.innerHTML = courses[i].code;
        //         name.innerHTML = courses[i].name;
        //         level.innerHTML = getStringLevel(courses[i].level);
        //         credit_hours.innerHTML = courses[i].credit_hours;
        //         contact_hours.innerHTML = courses[i].contact_hours;
        //         tblIndex++;
        //     }
        // }



        // fillAllCourses();
        // function fillAllCourses() {
        //     var tblAllCourses = document.getElementById("allCourses");
        //     var courses = window.findCourses(0);
        //     console.log(courses);
        // }

    </script>

@stop
