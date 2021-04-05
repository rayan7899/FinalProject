@extends('layouts.app')
@section('content')
    {{-- @dd($user->student->final_accepted); --}}
    {{-- {{ $user->student->documents_verified == true && $user->student->final_accepted == true ? 'border-success text-success' : '' }} --}}
    @php
    $step_1 = $user->student->documents_verified == true ? 'border-success text-success' : '';
    $line_1 = $user->student->documents_verified == true ? 'bg-success' : '';

    $step_2 = $user->student->student_docs_verified == true ? 'border-success text-success' : '';
    $line_2 = $user->student->student_docs_verified == true ? 'bg-success' : '';

    $step_3 = $user->student->final_accepted == true  ? 'border-success text-success' : '';
    @endphp
    <div class="container">
        <div class="stepState">       
            <div class="flag {{ $step_1 }}">الايصال</div>
            <!-- <div class="line {{ $line_1 }}"></div> -->
            <div class="flag {{ $step_2 }}">الوثائق</div>
            <!-- <div class="line {{ $line_2 }}"></div> -->
            <div class="flag {{ $step_3 }}">مقبول</div>
        </div>
        <div class="row justify-content-center">
            <div class="col-10">
                @if (session()->has('success'))
                    <div class="alert alert-success">
                        {{ session()->get('success') }}
                    </div>
                @endif
                @if (isset($error))
                    <div class="alert alert-danger">
                        {{ $error }}
                    </div>
                @endif
                <div class="card my-4">
                    <div class="card-header">
                        <h5 class="card-title">
                            البيانات الشخصية
                        </h5>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <div dir="ltr" class="input-group mb-1">
                                <input readonly type="text" class="form-control text-right bg-white h5"
                                       value="{{ $user->name ?? 'لا يوجد' }}">
                                <div class="input-group-append">
                                    <span class="input-group-text text-center" style="width: 120px;"><label
                                                                                                         class="text-center m-0 p-0 w-100">الاسم</label></span>
                                </div>
                            </div>
                        </div>
                        <div class="col">
                            <div dir="ltr" class="input-group mb-1">
                                <input readonly type="text" class="form-control text-right bg-white"
                                       value="{{ $user->national_id ?? 'لا يوجد' }}">
                                <div class="input-group-append">
                                    <span class="input-group-text text-center" style="width: 120px;"><label
                                                                                                         class="text-center m-0 p-0 w-100">رقم الهوية</label></span>
                                </div>
                            </div>

                            <div dir="ltr" class="input-group mb-1">
                                <input readonly type="text" class="form-control text-right bg-white"
                                       value="{{ $user->phone ?? 'لا يوجد' }}">
                                <div class="input-group-append">
                                    <span class="input-group-text text-center" style="width: 120px;"><label
                                                                                                         class="text-center m-0 p-0 w-100">رقم الجوال</label></span>
                                </div>
                            </div>
                            <div dir="ltr" class="input-group mb-1">
                                <input readonly type="text" class="form-control text-right bg-white"
                                       value="{{ $user->email ?? 'لا يوجد' }}">
                                <div class="input-group-append">
                                    <span class="input-group-text text-center" style="width: 120px;"><label
                                                                                                         class="text-center m-0 p-0 w-100">البريد الألكتروني</label></span>
                                </div>
                            </div>
                        </div>
                        <div class="col">
                            <div dir="ltr" class="input-group mb-1">
                                <input readonly type="text" class="form-control text-right bg-white"
                                       value="{{ $user->student->program->name ?? 'لا يوجد' }}">
                                <div class="input-group-append">
                                    <span class="input-group-text text-center" style="width: 120px;"><label
                                                                                                         class="text-center m-0 p-0 w-100">البرنامج</label></span>
                                </div>
                            </div>
                            <div dir="ltr" class="input-group mb-1">
                                <input readonly type="text" class="form-control text-right bg-white"
                                       value="{{ $user->student->department->name ?? 'لا يوجد' }}">
                                <div class="input-group-append">
                                    <span class="input-group-text text-center" style="width: 120px;"><label
                                                                                                         class="text-center m-0 p-0 w-100">القسم</label></span>
                                </div>
                            </div>
                            <div dir="ltr" class="input-group mb-1">
                                <input readonly type="text" class="form-control text-right bg-white"
                                       value="{{ $user->student->major->name ?? 'لا يوجد' }}">
                                <div class="input-group-append">
                                    <span class="input-group-text text-center" style="width: 120px;"><label
                                                                                                         class="text-center m-0 p-0 w-100">التخصص</label></span>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>

            </div>
            <!-- courses -->
            <div class="col-10">
                <div class="card my-4">
                    <div class="card-header">
                        <h5 class="card-title"> المقررات المسجلة</h5>
                    </div>
                    <table class="table table-hover table-bordered bg-white">
                        <thead>
                            <tr>
                                <th class="text-center">رمز المقرر</th>
                                <th class="text-center">اسم المقرر</th>
                                <th class="text-center">المستوى</th>
                                <th class="text-center">الساعات</th>
                            </tr>
                        </thead>
                        <tbody id="courses">
                            @php
                            $default_cost = 0;
                            @endphp
                            @foreach ($user->student->courses as $course)
                                @php
                                $default_cost += $course->credit_hours * 550;
                                @endphp
                                <tr>
                                    <td class="text-center">{{ $course->code }}</td>
                                    <td class="text-center">{{ $course->name }}</td>
                                    <td class="text-center">{{ $course->level }}</td>
                                    <td class="text-center">{{ $course->credit_hours }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <style>
     .stepState {
         width: 100%;
         display: flex;
         justify-content: center;
         align-content: center;
         align-items: center;
     }

     .line {
         height: 3px;
         width: 5%;
         background: #b4b4b4;
     }

     .flag {
         display: flex;
         width: 70px;
         height: 70px;
         justify-content: center;
         align-content: center;
         align-items: center;
         border-radius: 50%;
         border: 3px solid #b4b4b4;
         background-color: #f3f3f3;
         font-weight: bold;
         margin-left: 2%;
         margin-right: 2%;
     }
    </style>
@endsection
