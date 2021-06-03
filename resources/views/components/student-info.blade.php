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
                بيانات المتدرب
            </h5>
        </div>
        <div class="row">

            <div class="col-md-6">

                {{-- Name --}}
                <div class="row p-0 m-0">
                    <div dir="ltr" class="input-group mb-1">
                        <input readonly type="text" class="form-control text-right bg-white h5"
                            value="{{ $user->name ?? 'لا يوجد' }}">
                        <div class="input-group-append">
                            <span class="input-group-text text-center" style="width: 120px;"><label
                                    class="text-center m-0 p-0 w-100">الاسم</label></span>
                        </div>
                    </div>
                </div>

                {{-- national_id --}}
                <div class="row p-0 m-0">
                    <div dir="ltr" class="input-group mb-1">
                        <input readonly type="text" class="form-control text-right bg-white"
                            value="{{ $user->national_id ?? 'لا يوجد' }}">
                        <div class="input-group-append">
                            <span class="input-group-text text-center" style="width: 120px;"><label
                                    class="text-center m-0 p-0 w-100">رقم الهوية</label></span>
                        </div>
                    </div>
                </div>

                {{-- rayat id --}}
                <div class="row p-0 m-0">
                    <div dir="ltr" class="input-group mb-1">
                        <input readonly type="text" class="form-control text-right bg-white"
                            value="{{ $user->student->rayat_id ?? 'لا يوجد' }}">
                        <div class="input-group-append">
                            <span class="input-group-text text-center" style="width: 120px;"><label
                                    class="text-center m-0 p-0 w-100">الرقم التدريبي</label></span>
                        </div>
                    </div>
                </div>

                {{-- phone --}}
                <div class="row p-0 m-0">
                    <div dir="ltr" class="input-group mb-1">
                        <input readonly type="text" class="form-control text-right bg-white"
                            value="{{ $user->phone ?? 'لا يوجد' }}">
                        <div class="input-group-append">
                            <span class="input-group-text text-center" style="width: 120px;"><label
                                    class="text-center m-0 p-0 w-100">رقم الجوال</label></span>
                        </div>
                    </div>
                </div>

                {{-- email --}}
                <div class="row p-0 m-0">
                    <div dir="ltr" class="input-group mb-1">
                        <input readonly type="text" class="form-control text-right bg-white"
                            value="{{ $user->email ?? 'لا يوجد' }}">
                        <div class="input-group-append">
                            <span class="input-group-text text-center" style="width: 120px;"><label
                                    class="text-center m-0 p-0 w-100">البريد الالكتروني</label></span>
                        </div>
                    </div>
                </div>


                {{-- trainee state --}}
                <div class="row p-0 m-0">
                    <div dir="ltr" class="input-group mb-1">
                        <input readonly type="text" class="form-control text-right bg-white"
                            value="{{ __($user->student->traineeState) ?? 'لا يوجد' }}">
                        <div class="input-group-append">
                            <span class="input-group-text text-center" style="width: 120px;"><label
                                    class="text-center m-0 p-0 w-100">الحالة</label></span>
                        </div>
                    </div>
                </div>


            </div>
            <div class="col-md-6">
                {{-- program --}}
                <div class="row p-0 m-0">
                    <div dir="ltr" class="input-group mb-1">
                        <input readonly type="text" class="form-control text-right bg-white"
                            value="{{ $user->student->program->name ?? 'لا يوجد' }}">
                        <div class="input-group-append">
                            <span class="input-group-text text-center" style="width: 120px;"><label
                                    class="text-center m-0 p-0 w-100">البرنامج</label></span>
                        </div>
                    </div>
                </div>

                {{-- department --}}
                <div class="row p-0 m-0">
                    <div dir="ltr" class="input-group mb-1">
                        <input readonly type="text" class="form-control text-right bg-white"
                            value="{{ $user->student->department->name ?? 'لا يوجد' }}">
                        <div class="input-group-append">
                            <span class="input-group-text text-center" style="width: 120px;"><label
                                    class="text-center m-0 p-0 w-100">القسم</label></span>
                        </div>
                    </div>
                </div>
                {{-- major --}}
                <div class="row p-0 m-0">
                    <div dir="ltr" class="input-group mb-1">
                        <input readonly type="text" class="form-control text-right bg-white"
                            value="{{ $user->student->major->name ?? 'لا يوجد' }}">
                        <div class="input-group-append">
                            <span class="input-group-text text-center" style="width: 120px;"><label
                                    class="text-center m-0 p-0 w-100">التخصص</label></span>
                        </div>
                    </div>
                </div>

                {{-- level --}}
                <div class="row p-0 m-0">
                    <div dir="ltr" class="input-group mb-1">
                        <input readonly type="text" class="form-control text-right bg-white"
                            value="{{ $stringLevel ?? 'Error' }}">
                        <div class="input-group-append">
                            <span class="input-group-text text-center" style="width: 120px;"><label
                                    class="text-center m-0 p-0 w-100">المستوى</label></span>
                        </div>
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

                {{-- credit_hours --}}
                <div class="row p-0 m-0">
                    <div dir="ltr" class="input-group mb-1">
                        <input readonly type="text" class="form-control text-right bg-white"
                            value="{{ $user->student->credit_hours ?? 0 }}">
                        <div class="input-group-append">
                            <span class="input-group-text text-center" style="width: 120px;">
                                <span class=" d-inline text-center m-0 p-0 w-100">
                                    <a role="button" class="mx-1" data-toggle="popover" title="الساعات المتاحة"
                                        data-content="عدد الساعات المتاحة للإضافة عبر موقع رايات">
                                        <i class="fa fa-info-circle d-inline"></i>
                                    </a> الساعات المتاحة
                                </span>
                            </span>
                        </div>
                    </div>
                </div>

                {{-- rayat hours --}}
                <div class="row p-0 m-0">
                    <div dir="ltr" class="input-group mb-1">
                        <input readonly type="text" class="form-control text-right bg-white" value="0">
                        <div class="input-group-append">
                            <span class="input-group-text text-center" style="width: 120px;">
                                <a role="button" class="mx-1" data-toggle="popover" title="الساعات المعتمدة"
                                    data-content="عدد الساعات التي تم اضافتها من قبل المتدرب في رايات">
                                    <i class="fa fa-info-circle d-inline"></i>
                                </a>
                                الساعات المعتمدة
                            </span>
                        </div>
                    </div>
                </div>


                {{-- accepted state --}}
                @if ($user->student->level == 1)
                    @php
                        $acceptMessage = 'مقبول مبدئي'; // default message
                        if ($user->student->final_accepted == true || $user->student->final_accepted == 1) {
                            if ($user->student->credit_hours == 0) {
                                $acceptMessage = 'مقبول نهائي - بانتظار إتاحة الساعات في رايات';
                            } else {
                                $acceptMessage = 'مقبول نهائي - تم إتاحة الساعات في رايات يتوجب عليك الدخول الى رايات وتسجيل المقررات';
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
</div>
