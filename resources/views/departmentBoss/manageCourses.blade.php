@extends('layouts.app')
@section('content')
<div class="container">
    <div class="card">
        <div class="card-header">
            <p>ادارة المقررات</p>
        </div>
        <div class="card-body">
            <form id="excel_form" class="form" method="POST" action="{{ route('importExcel') }}" enctype="multipart/form-data">
                @csrf
                <div class="form-row mb-3">
                    <div class="col-sm-4">
                        <label for="program" class="pl-1"> البرنامج </label>
                        <select required name="program" id="program" class="form-controller w-100" onchange="fillDepartments()">
                            <option value="" disabled selected>أختر</option>
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
                    <div class="col-sm-4">
                        <label for="department" class="pl-1"> القسم </label>
                        <select required name="department" id="department" class="form-controller w-100 " onchange="fillMajors()">
                            <option value="" disabled selected>أختر</option>
                        </select>
                        @error('department')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>
                    <div class="col-sm-4">
                        <label for="major" class="pl-1"> التخصص </label>
                        <select required name="major" id="major" class="form-controller w-100" onchange="getCourses()">
                            <option value="" disabled selected>أختر</option>
                        </select>
                        @error('major')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>
                </div>
            </form>
            <div class="row">
                <div class="col-5">
                    <div class="card">
                        <div class="card-body p-0">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th class="text-center">رمز المقرر</th>
                                        <th class="text-center">اسم المقرر</th>
                                        <th class="text-center">عدد الساعات</th>
                                    </tr>
                                </thead>
                                <tbody id="courses">
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="col-2">
                    <div class="row justify-content-center mt-2">
                        <a href="#" onclick="window.addCourses(event)" class="border border-dark rounded px-2 my-2" style="padding-bottom: 2px">
                            <img style="width: 14px; height: 14px;" src="{{asset('images/left-arrow.png')}}" alt="left-arrow-icon">
                        </a>
                    </div>
                    <div class="row justify-content-center">
                        <a href="#" onclick="window.removeCourses(event)" class="border border-dark rounded px-2 my-2"  style="padding-bottom: 2px">
                            <img style="width: 14px; height: 14px;" src="{{asset('images/right-arrow.png')}}" alt="left-arrow-icon">
                        </a>
                    </div>
                </div>

                <div class="col-5">
                    <div class="card">
                        <div class="card-header">
                            <select class="ml-0">
                                <option value="1"> المستوى الاول</option>
                                <option value="2"> المستوى الثاني</option>
                                <option value="3"> المستوى الثالث</option>
                                <option value="4"> المستوى الرابع</option>
                                <option value="5"> المستوى الخامس</option>
                            </select>
                        </div>
                        <div class="card-body p-0">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>رمز المقرر</th>
                                        <th>اسم المقرر</th>
                                        <th>عدد الساعات</th>
                                    </tr>
                                </thead>
                                <tbody id="levelCourses">
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <script>
        var programs = @php echo $programs; @endphp;
    </script>
</div>
@stop