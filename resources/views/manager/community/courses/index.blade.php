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
                            } elseif (Auth::user()->isDepartmentManager()) {
                                $route = route('deptCreateCourseForm');
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
                                        onchange="fillManageCoursesTbl()">
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
                                    <select id="level" onchange="fillManageCoursesTbl()" class="ml-0 d-inline mx-3">
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
            var programs = @php echo $programs; @endphp;
        </script>
    </div>
@stop
