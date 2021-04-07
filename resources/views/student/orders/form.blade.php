@extends('layouts.app')
@section('content')
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
                                    <tr data-cost="{{ $course->credit_hours * 550 }}" data-hours="{{ $course->credit_hours }}">
                                        <td class="text-center">{{ $course->code }}</td>
                                        <td class="text-center">{{ $course->name }}</td>
                                        <td class="text-center">{{ $course->level }}</td>
                                        <td class="text-center">{{ $course->credit_hours }}</td>
                                        <td class="text-center">{{ $course->credit_hours * 550 }}</td>
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
        <div class="card">
            <div class="card-header">
                اصافة مقررات
            </div>
            <div class="card-body">
                <form id="addCoursesOrder" action="{{ route('orderStore') }}" method="post" accept-charset="utf-8" enctype="multipart/form-data">
                    @csrf
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
                    <div class="form-group">
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
                                    $default_total_hours = 0;
                                @endphp
                                @if (isset($courses))
                                    @foreach ($courses as $course)
                                        @php
                                            $default_cost += $course->credit_hours * 550;
                                            $default_total_hours += $course->credit_hours;
                                        @endphp
                                        <tr>
                                            <td class="text-center">{{ $course->code }}</td>
                                            <td class="text-center">{{ $course->name }}</td>
                                            <td class="text-center">{{ $course->level }}</td>
                                            <td class="text-center">{{ $course->credit_hours }}</td>
                                            <td class="text-center">{{ $course->credit_hours * 550 }}</td>
                                            <td class="text-center @if ($user->student->level < 2) d-none @endif">
                                                    <input id="course_{{ $course->id }}" type="checkbox" name="courses[]"
                                                        value="{{ $course->id }}" onclick="window.calcCost(event)" checked />
                                            </td>
                                        </tr>
                                    @endforeach
                                @endif
                            </tbody>
                        </table>
                        <div id="courses-error" class="alert alert-danger" style="display: none;"></div>
                        @if ($user->student->level > 1)
                            <button type="button" data-toggle="modal" data-target="#pick-courses"
                                class="btn btn-primary">اضافة مقرارات</button>
                        @endif
                    </div>
                    <!-- trainee state -->
                    <div>
                        <label>فئة المتدرب</label>
                        <div class="form-group bg-white border px-4 py-3">
                            <div class="form-row">
                                <div class="custom-control custom-radio custom-control-inline col-sm-3 m-0">
                                    <input value="trainee" type="radio" onclick="window.calcCost()" id="trainee"
                                        name="traineeState" class="custom-control-input" checked>
                                    <label class="custom-control-label" for="trainee">متدرب</label>
                                </div>
                                <div class="custom-control custom-radio custom-control-inline col-sm-3 m-0">
                                    <input value="employee" type="radio" onclick="window.calcCost()" id="employee"
                                        name="traineeState" class="custom-control-input">
                                    <label class="custom-control-label" for="employee">أحد منسوبي المؤسسة</label>
                                </div>
                                <div class="custom-control custom-radio custom-control-inline col-sm-3 m-0">
                                    <input value="employeeSon" type="radio" onclick="window.calcCost()" id="employeeSon"
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
                                    <input value="privateState" type="radio" onclick="window.calcCost()" id="privateState"
                                        name="traineeState" class="custom-control-input">
                                    <label class="custom-control-label" for="privateState">الظروف الخاصة</label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div id="paymentGroup" class="form-group">
                        {{-- Total Hours Cost --}}
                        <div class="form-group">
                            <label class=" align-self-center " for="totalHoursCost">مجموع مبلغ الساعات المضافة</label>
                            <div class=" input-group" dir="ltr">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">SR</span>
                                </div>
                                <input dir="rtl" disabled required type="text" class="form-control text-center"
                                    id="totalHoursCost" name="totalHoursCost" value="{{ $default_cost }}">
                            </div>
                        </div>
                        <!-- wallet -->
                        <div class="form-group">
                            <label class=" align-self-center " for="wallet"> الرصيد الحالي </label>
                            <div class=" input-group" dir="ltr">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">SR</span>
                                </div>
                                <input disabled type="text" class="form-control text-center" id="wallet"
                                    value="{{ $user->student->wallet }}">
                            </div>
                        </div>
                        <!-- wallet after calc -->
                        <div class="form-group">
                            <label class=" align-self-center " for="walletAfterCalc"> الرصيد المتبقي </label>
                            <div class=" input-group" dir="ltr">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">SR</span>
                                </div>
                                <input disabled type="text" class="form-control text-center" id="walletAfterCalc"
                                    value="{{ $user->student->wallet }}">
                            </div>
                        </div>
                        <!-- cost -->
                        <div id="costFormGroup" class="form-group" style="display: none;">
                            <label class=" align-self-center " for="cost">المبلغ المراد سداده</label>
                            <div class=" input-group" dir="ltr">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">SR</span>
                                </div>
                                <input dir="rtl" disabled type="text" class="form-control text-center" id="cost" name="cost"
                                    value="">
                            </div>
                        </div>


                    </div>

                    <!-- payment receipt image -->
                    <div class="form-group" id="receipt">
                        <label for="receiptImg"> صورة إيصال السداد</label>
                        <input type="file" name="payment_receipt" class="form-control" id="receiptImg">
                    </div>

                    <!-- requiered documents -->
                    <div class="form-group" id="privateStateDocGroup" style="display: none;">
                        <label for="privateStateDoc"> صور المستندات المطلوبة</label>
                        <input type="file" name="privateStateDoc" class="form-control" id="privateStateDoc" multiple
                            disabled>
                    </div>
                    <!-- submet button -->
                    <div class="form-group my-3">
                        <input type="submit" name="form_submit" id="form_submit" value="أرسال" class="btn btn-primary">
                    </div>
                </form>
            </div>
        </div>
        <script>
            var courses =
                @php
                if (isset($courses)) {
                    echo json_encode($courses) . ';';
                } else {
                    echo "\n";
                }
                @endphp
            var major_courses =
                @php
                if (isset($major_courses)) {
                    echo json_encode($major_courses) . ';';
                } else {
                    echo "\n";
                }
                @endphp
            var traineeState = "{{ $user->student->traineeState ?? 'trainee' }}"
            var wallet = "{{ $user->student->wallet ?? 0 }}"
            var t_cost = 0;
            var total_hours = 0;
            var new_cost = 0;

        </script>
    </div>
@stop
