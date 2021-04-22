@extends('layouts.app')
@section('content')
    <!-- modal for showing student files -->
    <div class="modal fade" id="pick-courses" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
        <div class="modal-dialog" style="max-width: 75%" role="document">
            <div class="modal-content">
                <div class="modal-header" dir="rtl">
                    <h5 class="modal-title">قم باختيار المواد المطلوبة</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"
                        style="margin: 0px; padding: 0px">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">

                    <table class="table table-hover table-bordered bg-white">
                        <thead>
                            <tr>
                                <th class="text-center">رمز المقرر</th>
                                <th class="text-center">اسم المقرر</th>
                                <th class="text-center">المستوى</th>
                                <th class="text-center">الساعات</th>
                                <th class="text-center">المبلغ</th>
                                <th class="text-center @if ($user->student->level < 2) d-none @endif">
                                </th>
                            </tr>
                        </thead>
                        <tbody id="pick-courses">
                            @if (isset($major_courses))
                                @forelse ($major_courses as $course)
                                    <tr data-cost="{{ $course->credit_hours * $user->student->program->hourPrice }}">
                                        <td class="text-center">{{ $course->code }}</td>
                                        <td class="text-center">{{ $course->name }}</td>
                                        <td class="text-center">{{ $course->level }}</td>
                                        <td class="text-center">{{ $course->credit_hours }}</td>
                                        <td class="text-center">{{ $course->credit_hours * $user->student->program->hourPrice }}</td>
                                        <td class="text-center @if ($user->student->level < 2) d-none @endif">
                                                <input id="major_course_{{ $course->id }}" name="courses[]" type="checkbox"
                                                    value="{{ $course->id }}" onclick="window.toggleChecked(event)" />
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6">لا يوجد مقررات</td>
                                    </tr>
                            @endforelse
                        @else
                            <tr>
                                <td colspan="6">لا يوجد مقررات</td>
                            </tr>
                            @endif
                        </tbody>
                    </table>
                    <button class="btn btn-primary" onclick="window.addToCoursesTable()">إضافة</button>
                </div>
            </div>
        </div>
    </div>

    <div class="container">
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $message)
                        <li>{{ $message }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        @if (session()->has('error'))
            <div class="alert alert-danger">
                {{ session()->get('error') }}
            </div>
        @endif
        <form id="updateUserForm" action="{{ route('UpdateOneStudent') }}" method="post" accept-charset="utf-8"
            enctype="multipart/form-data">
            @csrf
            <!-- national ID -->
            <div class="form-group">
                <label for="national_id">رقم الهوية</label>
                <input disabled type="text" class="form-control p-1 m-1 " id="national_id" name="national_id"
                    value="{{ $user->national_id }}">
            </div>

            <!-- full name -->
            <div class="form-group">
                <label for="name">الاسم</label>
                <input disabled type="text" class="form-control p-1 m-1  " id="name" name="name"
                    value=" {{ $user->name }}">
            </div>

            <!-- phone number -->
            <div class="form-group">
                <label for="phone">رقم الجوال</label>
                <input required disabled="true" type="phone" class="form-control p-1 m-1" id="phone" name="phone"
                    value="{{ $user->phone }} ">
            </div>
            <!-- department and major -->
            <div class="form-row form-group">

                <!-- department -->
                <div class="col-sm-6">
                    <label for="department"> القسم </label>
                    <input disabled required type="text" class="form-control  " id="department" name="department"
                        value="{{ $user->student->department->name }}">
                </div>

                <!-- major -->
                <div class="col-sm-6">
                    <label for="major"> التخصص </label>
                    <input disabled required type="text" class="form-control  " id="major" name="major"
                        value="{{ $user->student->major->name }}">
                </div>
            </div>
            <!-- email -->
            <div class="form-group">
                <label for="email">البريد الالكتروني</label>
                <input required type="email" class="form-control p-1 m-1" id="email" name="email"
                    value="{{ $user->email ?? old('email') }} ">
            </div>


            {{-- password update --}}
            <div class="form-group">
                <label for="password">{{ __('كلمة المرور الجديدة') }}</label>
                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror"
                    name="password" required autocomplete="new-password">

                @error('password')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>

            <div class="form-group">
                <label for="password-confirm">{{ __('تأكيد كلمة المرور') }}</label>
                <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required
                    autocomplete="new-password">
            </div>

            <!-- national id image -->
            <div class="form-group">
                <label for="">صورة الهوية الوطنية </label>
                <input type="file" accept=".pdf,.png,.jpg,.jpeg" name="identity" class="form-control" value="">
            </div>

            <!-- certificate image -->
            <div class="form-group">
                <label for="">صورة من المؤهل </label>
                <input type="file" accept=".pdf,.png,.jpg,.jpeg" name="degree" class="form-control" value="">
            </div>

            <!-- submet button -->
            <div class="form-group my-3">
                <input type="submit" name="form_submit" id="form_submit" value="أرسال"
                    class="btn btn-primary">
            </div>
        </form>
    </div>
    
    </div>

@stop
