@extends('layouts.app')
@section('content')
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
                <!-- <div class="input-group mb-3">
                    <button type="button" onclick="EditPhoneClicked()" id="editPhoneBtn" class="btn btn-sm px-2 m-1 btn-primary font-weight-bold">تعديل</button>
                </div> -->
            </div>

            <!-- email -->
            <div class="form-group">
                <label for="email">البريد الالكتروني</label>
                <input required type="email" class="form-control p-1 m-1" id="email" name="email"
                    value="{{ $user->email ?? old('email') }} ">
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

            <!-- suggested courses -->
            <div class="from-group">
                <label>المقررات المقترحة</label>
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
                    <tbody id="courses">
                        @php
                            $default_cost = 0;
                        @endphp
                        @if (isset($courses))
                            @forelse ($courses as $course)
                                @php
                                    $default_cost += $course->credit_hours * 550;
                                @endphp
                                <tr>
                                    <td class="text-center">{{ $course->code }}</td>
                                    <td class="text-center">{{ $course->name }}</td>
                                    <td class="text-center">{{ $course->level }}</td>
                                    <td class="text-center">{{ $course->credit_hours }}</td>
                                    <td class="text-center">{{ $course->credit_hours * 550 }}</td>
                                    <td class="text-center @if ($user->student->level < 2) d-none @endif">
                                            <input id="course_{{ $course->id }}" type="checkbox" name="courses[]"
                                                value="{{ $course->id }}" onclick="changeTraineeState();" checked />
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6">لا يوجد مقررات مقترحة</td>
                                </tr>
                        @endforelse
                    @else
                        <tr>
                            <td colspan="6">لا يوجد مقررات مقترحة</td>
                        </tr>
                        @endif
                    </tbody>
                </table>
            </div>
            <!-- trainee state -->
            <label>فئة المتدرب</label>
            <div class="form-group bg-white border px-4 py-3">
                <div class="form-row">
                    <div class="custom-control custom-radio custom-control-inline col-sm-3 m-0">
                        <input value="trainee" type="radio" onclick="changeTraineeState()" id="trainee" name="traineeState"
                            class="custom-control-input" checked>
                        <label class="custom-control-label" for="trainee">متدرب</label>
                    </div>
                    <div class="custom-control custom-radio custom-control-inline col-sm-3 m-0">
                        <input value="employee" type="radio" onclick="changeTraineeState()" id="employee"
                            name="traineeState" class="custom-control-input">
                        <label class="custom-control-label" for="employee">أحد منسوبي المؤسسة</label>
                    </div>
                    <div class="custom-control custom-radio custom-control-inline col-sm-3 m-0">
                        <input value="employeeSon" type="radio" onclick="changeTraineeState()" id="employeeSon"
                            name="traineeState" class="custom-control-input">
                        <label class="custom-control-label" for="employeeSon">من ابناء منسوبي المؤسسة</label>
                    </div>
                    <div class="custom-control custom-radio custom-control-inline col-sm-3 m-0">
                        <a id="info-popup" data-toggle="popover" onclick="popup()" title="حالات الضروف الخاصة"
                            class="h5 text-right mx-2" data-content="
                               <div class='text-right' dir='rtl' style='width: 30%;'>
                               ١- اذا كان المتدرب من ابناء شهداء الواجب (استشهاد والده) 
                               <br>
                               ٢- اذا كان المتدرب من الايتام المسجلين في دور الرعاية الاجتماعية
                               <br>
                               ٣- اذا كان المتدرب من المسجلين نطاما في احدى الجمعيات الخيرية الرسمية
                               <br>
                               ٤- اذا كان المتدرب من ابناء السجناء المسجلين بلجنة تراحم وحالته تتطلب المساعدة
                               <br>
                               ٥- اذا كان المتدرب من ذوي الاعاقة بموجب تقرير رسمي من الجهات ذات العلاقة (وزارة العمل والتنمية الاجتماعية)
                               </div>
                               ">( ! )</a>
                        <input value="privateState" type="radio" onclick="changeTraineeState()" id="privateState"
                            name="traineeState" class="custom-control-input">
                        <label class="custom-control-label" for="privateState">الظروف الخاصة</label>
                    </div>
                </div>
            </div>
            <!-- cost -->
            <div id="costGroup" class="form-row mb-3">
                <label class="col-2 align-self-center m-0" for="cost">المبلغ المراد سداده</label>
                <div class="col-2 input-group" dir="ltr">
                    <div class="input-group-prepend">
                        <span class="input-group-text">SR</span>
                    </div>
                    <input dir="rtl" disabled required type="text" class="form-control text-center" id="cost" name="cost"
                        value="{{ $default_cost }}">
                </div>

                <div class="form-check align-self-center d-none">
                    <input type="checkbox" class="form-check-input" name="pledge" id="pledge">
                    <label class="form-check-label mr-3">اتعهد بدفع كامل المبلغ في حالة عدم موافقة المؤسسة</label>
                </div>
            </div>


            <div class="row">
                <div class="col">
                    <!-- national id image -->
                    <div class="form-group">
                        <label for="">صورة الهوية الوطنية </label>
                        <input type="file" name="identity" class="form-control" value="">
                    </div>
                </div>
                <div class="col">
                    <!-- certificate image -->
                    <div class="form-group">
                        <label for="">صورة من المؤهل </label>
                        <input type="file" name="degree" class="form-control" value="">
                    </div>
                </div>
                <div class="col" id="receipt">
                    <!-- payment receipt image -->
                    <div class="form-group">
                        <label for="receiptImg"> صورة إيصال السداد</label>
                        <input type="file" name="payment_receipt" class="form-control" id="receiptImg">
                    </div>
                </div>
                <div class="col" id="privateStateDocGroup" style="display: none">
                    <!-- requiered documents -->
                    <div class="form-group">
                        <label for="privateStateDoc"> صور المستندات المطلوبة</label>
                        <input type="file" name="privateStateDoc" class="form-control" id="privateStateDoc" disabled>
                    </div>
                </div>
            </div>








            <!-- submet button -->
            <div class="form-group my-3">
                <input type="button" onclick="formSubmit()" name="form_submit" id="form_submit" value="أرسال"
                    class="btn btn-primary">
            </div>
        </form>
    </div>
    <script>
        var courses = @php 
        if(isset($courses)){
           echo json_encode($courses);
        }
        @endphp;
       </script>
    </div>

@stop
