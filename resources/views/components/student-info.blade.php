<div>
    <!-- Be present above all else. - Naval Ravikant -->

    @php
        $stringLevel = '';
        switch ($user->student->level) {
            case 1:
                $stringLevel = 'الاول';
                break;
            case 2:
                $stringLevel = 'الثاني';
                break;
            case 3:
                $stringLevel = 'الثالث';
                break;
            case 4:
                $stringLevel = 'الرابع';
                break;
            case 5:
                $stringLevel = 'الخامس';
                break;
            default:
                $stringLevel = 'Error';
        }
    @endphp

    <div class="card my-4">
        <div class="card-header">
            <h5 class="card-title">
                البيانات الشخصية
            </h5>
        </div>
        <div class="row">

            {{-- right side --}}
            <div class="col-md-6">
                <div dir="ltr" class="input-group mb-1">
                    <input readonly type="text" class="form-control text-right bg-white h5"
                        value="{{ $user->name ?? 'لا يوجد' }}">
                    <div class="input-group-append">
                        <span class="input-group-text text-center" style="width: 120px;"><label
                                class="text-center m-0 p-0 w-100">الاسم</label></span>
                    </div>
                </div>

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
                        value="{{ $user->student->rayat_id ?? 'لا يوجد' }}">
                    <div class="input-group-append">
                        <span class="input-group-text text-center" style="width: 120px;"><label
                                class="text-center m-0 p-0 w-100">الرقم التدريبي</label></span>
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
                                class="text-center m-0 p-0 w-100">البريد الالكتروني</label></span>
                    </div>
                </div>

                @php
                    $total_hours = 0;
                    foreach ($user->student->orders as $order) {
                        if ($order->transaction_id !== null && $order->private_doc_verified == true) {
                            $total_hours += $order->requested_hours;
                        }
                    }
                @endphp

                <div dir="ltr" class="input-group mb-1">
                    <input readonly type="text" class="form-control text-right bg-white"
                        value="{{ $total_hours ?? 0 }}">
                    <div class="input-group-append">
                        <span class="input-group-text text-center" style="width: 120px;"><label
                                class="text-center m-0 p-0 w-100">الساعات الكلية</label></span>
                    </div>
                </div>
            </div>



            {{-- left side --}}
            <div class="col-md-6">
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

                <div dir="ltr" class="input-group mb-1">
                    <input readonly type="text" class="form-control text-right bg-white"
                        value="{{ $stringLevel ?? 'Error' }}">
                    <div class="input-group-append">
                        <span class="input-group-text text-center" style="width: 120px;"><label
                                class="text-center m-0 p-0 w-100">المستوى</label></span>
                    </div>
                </div>

                @php
                    switch ($user->student->traineeState) {
                        case 'employee':
                            $traineeStateString = "منسوب ";
                            break;

                        case 'employeeSon':
                            $traineeStateString = "ابن منسوب";
                            break;

                        case 'privateState':
                            $traineeStateString = "ظروف خاصة";
                            break;

                        default:
                            $traineeStateString = "متدرب";
                            break;
                    }
                @endphp
                <div dir="ltr" class="input-group mb-1">
                    <input readonly type="text" class="form-control text-right bg-white"
                        value="{{ $traineeStateString ?? 'Error' }}">
                    <div class="input-group-append">
                        <span class="input-group-text text-center" style="width: 120px;"><label
                                class="text-center m-0 p-0 w-100">الحالة</label></span>
                    </div>
                </div>

                <div dir="ltr" class="input-group mb-1">
                    <input readonly type="text" class="form-control text-right bg-white"
                        value="{{ $user->student->credit_hours ?? 0 }}">
                    <div class="input-group-append">
                        <span class="input-group-text text-center" style="width: 120px;"><label
                                class="text-center m-0 p-0 w-100">الساعات المعتمدة</label></span>
                    </div>
                </div>
            </div>

            {{-- <div class="col-6">
                <div dir="ltr" class="input-group mb-1">
                    <input readonly type="text" class="form-control text-right bg-white"
                        value="{{ $user->student->wallet ?? 'لا يوجد' }}">
                    <div class="input-group-append">
                        <span class="input-group-text text-center" style="width: 120px;"><label
                                class="text-center m-0 p-0 w-100">رصيد المحفظة</label></span>
                    </div>
                </div>
            </div> --}}

            {{-- accepted state --}}
            @if ($user->student->level == 1)
                @php
                    $acceptMessage = 'مقبول مبدئي'; // default message
                    if ($user->student->final_accepted == true || $user->student->final_accepted == 1) {
                        if ($user->student->credit_hours == 0) {
                            $acceptMessage = 'مقبول نهائي - بانتظار اتاحة الساعات في رايات';
                        } else {
                            $acceptMessage = 'مقبول نهائي - تم اتاحة الساعات في رايات يتوجب عليك الدخول الى رايات وتسجيل المقررات';
                        }
                    }
                @endphp
                <div class="col-12">
                    <div dir="ltr" class="input-group mb-1">
                        <input readonly type="text" class="form-control text-right bg-white"
                            value="{{ $acceptMessage ?? 'Error' }}">
                        <div class="input-group-append">
                            <span class="input-group-text text-center" style="width: 120px;"><label
                                    class="text-center m-0 p-0 w-100">حالة القبول</label></span>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
