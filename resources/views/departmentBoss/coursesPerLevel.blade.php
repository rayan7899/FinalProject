@extends('layouts.app')
@section('content')
    <div class="container-fluid">
        <div class="modal fade" id="studentsModal" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
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
        </div>
        <div class="card">
            <div class="card-header">
                <h5>الجداول المقترحة</h5>
            </div>
            <div class="card-body d-flex flex-column justify-content-center p-4">
                <div class="row">
                    <div class="col">
                        <form id="excel_form" class="form" method="POST" action="{{ route('importExcel') }}"
                            enctype="multipart/form-data">
                            @csrf
                            <div class="form-row mb-3">
                                <div class="col-sm-4">
                                    <label for="program" class="pl-1"> البرنامج </label>
                                    <select required name="program" id="program" class="form-controller w-100"
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
                                    <select required name="department" id="department" class="form-controller w-100 "
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
                                    <select required name="major" id="major" class="form-controller w-100"
                                        onchange="fillCourses()">
                                        <option value="0" disabled selected>أختر</option>
                                    </select>
                                    @error('major')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="row justify-content-center">
                    <div class="col-sm-5 p-0">
                        <div class="card">
                            <div class="card-header">
                                <h6 class="d-inline">مقررات التخصص</h6>
                                <select id="originalLevel" onchange="fillCourses()" class="ml-0 d-inline mx-3">
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
                                        </tr>
                                    </thead>
                                    <tbody id="courses">
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <div class="col-2 d-none d-md-block p-0">
                        <div class="row justify-content-center mt-2">
                            <a href="#" onclick="window.addCourses(event)"
                                class="border border-dark rounded w-25 text-center btn btn-light px-2 my-2"
                                style="padding-bottom: 2px">
                                <img style="width: 16px; height: 14px;  margin-bottom: 3px;"
                                    src="{{ asset('images/left-arrow.png') }}" alt="left-arrow-icon">
                            </a>
                        </div>
                        <div class="row justify-content-center">
                            <a href="#" onclick="window.removeCourses(event)"
                                class="border border-dark rounded w-25 text-center btn btn-light px-2 my-2"
                                style="padding-bottom: 2px">
                                <img style="width: 16px; height: 14px; margin-bottom: 3px;"
                                    src="{{ asset('images/right-arrow.png') }}" alt="left-arrow-icon">
                            </a>
                        </div>
                    </div>

                    <div class="row d-flex justify-content-center justify-items-center d-sm-none p-3">
                        <div class="col justify-content-center">
                            <a href="#" onclick="window.addCourses(event)"
                                class="border border-dark rounded w-25 text-center btn btn-light px-2 my-2"
                                style="padding-bottom: 2px">
                                <img style="width: 16px; height: 14px; transform: rotate(-90deg);"
                                    src="{{ asset('images/left-arrow.png') }}" alt="left-arrow-icon">
                            </a>
                        </div>
                        <div class="col justify-content-center">
                            <a href="#" onclick="window.removeCourses(event)"
                                class="border border-dark rounded w-25 text-center btn btn-light px-2 my-2"
                                style="padding-bottom: 2px">
                                <img style="width: 16px; height: 14px; transform: rotate(-90deg);"
                                    src="{{ asset('images/right-arrow.png') }}" alt="left-arrow-icon">
                            </a>
                        </div>
                    </div>
                    <div class="col-sm-5 p-0">
                        <div class="card">
                            <div class="card-header">
                                <div class="d-inline">
                                    <h6 class="d-inline">الجداول المقترح</h6>
                                    <select id="suggestedLevel" onchange="fillSuggestedCourses()"
                                        class="ml-0 d-inline mx-3 d-inline">
                                        <option value="1"> المستوى الاول</option>
                                        <option value="2"> المستوى الثاني</option>
                                        <option value="3"> المستوى الثالث</option>
                                        <option value="4"> المستوى الرابع</option>
                                        <option value="5"> المستوى الخامس</option>
                                    </select>
                                </div>
                                <div class="d-inline float-left">
                                    <a onclick="window.showStudent()" class="btn btn-sm btn-primary" href="#"
                                        role="button">عرض بيانات الطلاب</a>
                                </div>
                            </div>
                            <div class="card-body p-0">
                                <table id="suggestedLevelTbl" class="table">
                                    <thead>
                                        <tr>
                                            <th class="text-center">رمز المقرر</th>
                                            <th class="text-center">اسم المقرر</th>
                                            <th class="text-center">المستوى</th>
                                            <th class="text-center">الساعات المعتمدة</th>
                                            <th class="text-center">ساعات الإتصال</th>
                                        </tr>
                                    </thead>
                                    <tbody id="suggestedLevelBody">
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- @php dd(json_decode($programs)); @endphp --}}
        <script>
            var programs = @php echo $programs; @endphp;    
            var updateCoursesLevelUrl = "{{ route('apiUpdateCoursesLevel') }}";
            var getStudentOnLevelUrl = "{{ route('getStudentOnLevel') }}";
            var getCoursesUrl = "{{ route('apiGetCourses') }}";
            var updateStudentState = "{{ route('updateStudentState') }}";


        </script>
    </div>
@stop
