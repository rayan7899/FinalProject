@extends('layouts.app')
@section('content')
    {{-- @dd($user->student->final_accepted); --}}
    {{-- {{ $user->student->documents_verified == true && $user->student->final_accepted == true ? 'border-success text-success' : '' }} --}}
    @php
    $total_cost = 0;
    $total_hours = 0;
    $paymentsState = true;
    foreach ($user->student->courses as $course) {
        $total_hours += $course->credit_hours;
        $total_cost += $course->credit_hours * 550;
    }
    $paymentsCount = count($user->student->payments);
    // dd($user->student->payments[$paymentsCount - 1]);
    if ($paymentsCount > 0) {
        if ($user->student->payments[$paymentsCount - 1]->transaction_id == null) {
            $total_hours = 0;
            $paymentsState = false;
        }
    } else {
        $total_hours = 0;
        $paymentsState = false;
    }

    $step_1 = $paymentsState == true ? 'border-success text-success' : '';
    $line_1 = $paymentsState == true ? 'bg-success' : '';

    $step_2 = $user->student->published == true ? 'border-success text-success' : '';
    $line_2 = $user->student->published == true ? 'bg-success' : '';

    // $step_3 = $user->student->final_accepted == true ? 'border-success text-success' : '';

    @endphp
    <div class="container">
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
        <div class="modal fade" id="pdfModal" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
            <div class="modal-dialog" style="max-width: 75%" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="pdfName"></h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <iframe id="pdfIfreme" src="" width="100%" height="600px"></iframe>
                        <div class="text-center">
                            <img id="modalImage" src="" alt="image" class="img-fluid" />
                        </div>
                    </div>
                </div>
            </div>
        </div>
        {{-- <div class="stepState">
            <div class="flag {{ $step_1 }}">ايصالات الدفع</div>
            <div class="line {{ $line_1 }}"></div>
            <div class="flag {{ $step_2 }}">اعتماد الساعات</div>
            <div class="line {{ $line_2 }}"></div>
            <div class="flag {{ $step_3 }}">مقبول</div>
        </div> --}}
        <div class="row justify-content-center">
            <div class="col-12">

                <div class="card my-4">
                    <div class="card-header">
                        <h5 class="card-title">
                            البيانات الشخصية
                        </h5>
                    </div>
                    <div class="row">

                        <div class="col-6">
                            <div dir="ltr" class="input-group mb-1">
                                <input readonly type="text" class="form-control text-right bg-white h5"
                                    value="{{ $user->name ?? 'لا يوجد' }}">
                                <div class="input-group-append">
                                    <span class="input-group-text text-center" style="width: 120px;"><label
                                            class="text-center m-0 p-0 w-100">الاسم</label></span>
                                </div>
                            </div>
                        </div>


                        <div class="col-6">
                            <div dir="ltr" class="input-group mb-1">
                                <input readonly type="text" class="form-control text-right bg-white"
                                    value="{{ $user->student->program->name ?? 'لا يوجد' }}">
                                <div class="input-group-append">
                                    <span class="input-group-text text-center" style="width: 120px;"><label
                                            class="text-center m-0 p-0 w-100">البرنامج</label></span>
                                </div>
                            </div>
                        </div>

                        <div class="col-6">
                            <div dir="ltr" class="input-group mb-1">
                                <input readonly type="text" class="form-control text-right bg-white"
                                    value="{{ $user->national_id ?? 'لا يوجد' }}">
                                <div class="input-group-append">
                                    <span class="input-group-text text-center" style="width: 120px;"><label
                                            class="text-center m-0 p-0 w-100">رقم الهوية</label></span>
                                </div>
                            </div>
                        </div>


                        <div class="col-6">
                            <div dir="ltr" class="input-group mb-1">
                                <input readonly type="text" class="form-control text-right bg-white"
                                    value="{{ $user->student->department->name ?? 'لا يوجد' }}">
                                <div class="input-group-append">
                                    <span class="input-group-text text-center" style="width: 120px;"><label
                                            class="text-center m-0 p-0 w-100">القسم</label></span>
                                </div>
                            </div>
                        </div>

                        <div class="col-6">
                            <div dir="ltr" class="input-group mb-1">
                                <input readonly type="text" class="form-control text-right bg-white"
                                    value="{{ $user->phone ?? 'لا يوجد' }}">
                                <div class="input-group-append">
                                    <span class="input-group-text text-center" style="width: 120px;"><label
                                            class="text-center m-0 p-0 w-100">رقم الجوال</label></span>
                                </div>
                            </div>
                        </div>



                        <div class="col-6">
                            <div dir="ltr" class="input-group mb-1">
                                <input readonly type="text" class="form-control text-right bg-white"
                                    value="{{ $user->student->major->name ?? 'لا يوجد' }}">
                                <div class="input-group-append">
                                    <span class="input-group-text text-center" style="width: 120px;"><label
                                            class="text-center m-0 p-0 w-100">التخصص</label></span>
                                </div>
                            </div>
                        </div>

                        <div class="col-6">
                            <div dir="ltr" class="input-group mb-1">
                                <input readonly type="text" class="form-control text-right bg-white"
                                    value="{{ $user->email ?? 'لا يوجد' }}">
                                <div class="input-group-append">
                                    <span class="input-group-text text-center" style="width: 120px;"><label
                                            class="text-center m-0 p-0 w-100">البريد الألكتروني</label></span>
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

                        <div class="col-6">
                            <div dir="ltr" class="input-group mb-1">
                                <input readonly type="text" class="form-control text-right bg-white"
                                    value="{{ $user->student->level ?? 'لا يوجد' }}">
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

                        <div class="col-6">
                            <div dir="ltr" class="input-group mb-1">
                                <input readonly type="text" class="form-control text-right bg-white"
                                    value="{{ $total_hours ?? 0 }}">
                                <div class="input-group-append">
                                    <span class="input-group-text text-center" style="width: 120px;"><label
                                            class="text-center m-0 p-0 w-100">الساعات الكلية</label></span>
                                </div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div dir="ltr" class="input-group mb-1">
                                <input readonly type="text" class="form-control text-right bg-white"
                                    value="{{ $user->student->credit_hours ?? 0 }}">
                                <div class="input-group-append">
                                    <span class="input-group-text text-center" style="width: 120px;"><label
                                            class="text-center m-0 p-0 w-100">الساعات المعتمدة</label></span>
                                </div>
                            </div>
                        </div>
                    @if ($user->student->final_accepted == false)
                        <div class="col-12">
                            <div dir="ltr" class="input-group mb-1">
                                <input readonly type="text" class="form-control text-right bg-white"
                                    value="قبول مبدئي">
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



            @if ($user->student->final_accepted == true)
                <div class="col-12">


                    {{-- payments --}}
                    <div class="card">
                        <div class="card-header">
                            <div class="d-flex flex-row justify-content-between">
                                <div>
                                    <p class="h5 d-inline"> الرصيد الحالي: </p>
                                    {{-- <input readonly type="text" class="text-center bg-white d-inline"
                                    value="{{ $user->student->wallet ?? 'لا يوجد' }}"> --}}
                                    <p class="h5 d-inline">
                                        {{ $user->student->wallet ?? 'لا يوجد' }}
                                    </p>

                                </div>


                                <a href="{{ route('paymentForm') }}" class="btn btn-primary rounded">
                                    شحن المحفظة
                                </a>
                            </div>
                        </div>
                        <div class="card-body p-0">
                            <table class="table table-bordered m-0 p-0">
                                <thead>
                                    <tr>
                                        <th class="text-center">رقم الطلب</th>
                                        <th class="text-center">المبلغ</th>
                                        <th class="text-center">حالة الطلب</th>
                                        <th>الملاحظات</th>
                                        <th class="text-center">صورة لايصال</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($user->student->payments as $payment)
                                        @php
                                            $user->student->receipt = Storage::disk('studentDocuments')->files($user->national_id . '/receipts/' . $payment->receipt_file_id)[0];
                                        @endphp
                                        <tr class="text-center">
                                            <td>{{ $payment->id }}</td>
                                            <td>{{ $payment->amount }}</td>
                                            <td>{{ $payment->transaction_id !== null ? 'مقبول' : 'قيد المراجعة' }}</td>
                                            <td class="text-right">{{ $payment->note ?? 'لا يوجد' }}</td>
                                            <td class="">

                                                @php
                                                    $splitByDot = explode('.', $user->student->receipt);
                                                    $fileExtantion = end($splitByDot);
                                                @endphp
                                                @if ($fileExtantion == 'pdf' || $fileExtantion == 'PDF')
                                                    <a data-toggle="modal" data-target="#pdfModal" href="#"
                                                        onclick="showPdf('{{ route('GetStudentDocument', ['path' => $user->student->receipt]) }}','pdf')">
                                                        <img style="width: 20px" src="{{ asset('/images/pdf.png') }}" />
                                                    </a>
                                                @else
                                                    <a data-toggle="modal" data-target="#pdfModal" href="#"
                                                        onclick="showPdf('{{ route('GetStudentDocument', ['path' => $user->student->receipt]) }}','img')">
                                                        <img src=" {{ asset('/images/camera_img_icon.png') }}"
                                                            style="width:25px;" alt="Image File">
                                                    </a>
                                                @endif

                                            </td>
                                        </tr>
                                    @empty

                                    @endforelse

                                </tbody>
                            </table>
                        </div>
                    </div>





                    {{-- orders --}}
                    <div class="card my-4">
                        <div class="card-header">
                            <div class="d-flex flex-row justify-content-between">
                                <p class="h5">طلبات اضافة المقررات</p>
                                <a href="{{ route('orderForm') }}" class="btn btn-primary rounded">
                                    اضافة مقررات
                                </a>
                            </div>
                        </div>
                        <div class="card-body p-0">
                            <table class="table table-bordered p-0 m-0">
                                <thead>
                                    <tr>
                                        <th class="text-center">رقم الطلب</th>
                                        <th class="text-center">عدد الساعات</th>
                                        <th class="text-center">حالة الطلب</th>
                                        <th>الملاحظات</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($user->student->orders as $order)
                                        <tr class="text-center">
                                            <td>{{ $order->id ?? 'Error' }}</td>
                                            <td>{{ $order->requested_hours ?? 'Error' }}</td>
                                            @if ($order->private_doc_verified === false || $order->private_doc_verified === '0')
                                                <td class="text-danger">مرفوض</td>
                                            @else
                                                @if ($order->transaction_id == null)
                                                    <td>قيد المراجعة</td>
                                                @else
                                                    <td class="text-success">مقبول</td>
                                                @endif
                                            @endif
                                            <td class="text-right">{{ $order->note ?? 'لا يوجد' }}</td>
                                        </tr>
                                    @empty

                                    @endforelse

                                </tbody>
                            </table>
                        </div>
                    </div>








                    {{-- transactions --}}
                    <div class="card my-4">
                        <div class="card-header">
                            <div class="h5">
                                جميع العمليات المالية
                            </div>
                        </div>
                        <div class="card-body p-0">
                            <table class="table table-bordered m-0">
                                <thead>
                                    <tr>
                                        <th class="text-center">رقم العملية</th>
                                        <th class="text-center">نوع العملية</th>
                                        <th class="text-center">رقم الطلب (شحن / اضافة مقررات)</th>
                                        <th class="text-center">المبلغ</th>
                                        <th>الملاحظات</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($user->student->transactions as $transaction)
                                        @php $hoursNote = ""; @endphp
                                        <tr class="text-center">
                                            <td>{{ $transaction->id ?? 'Error' }}</td>

                                            @if ($transaction->type == 'deduction')
                                                @php
                                                    $hoursNote = '( مقابل اضافة ' . $transaction->order->requested_hours . ' ساعة / ساعات )';
                                                @endphp
                                                <td class="text-danger"> خصم (اضافة مقررات)</td>
                                                <td>{{ $transaction->order->id ?? 'Error' }}</td>
                                            @endif
                                            @if ($transaction->type == 'recharge')
                                                <td class="text-success"> اضافة (شحن المحفظة) </td>
                                                <td>{{ $transaction->payment->id ?? 'Error' }}</td>
                                            @endif
                                            <td style="min-width: 100px">{{ $transaction->amount ?? 'Error' }}</td>
                                            <td class="text-right">
                                                @if ($transaction->type == 'deduction')
                                                    {{ $hoursNote ?? '' }}
                                                    <br>
                                                @endif
                                                {{ $transaction->note ?? '' }}
                                            </td>

                                        </tr>
                                    @empty

                                    @endforelse

                                </tbody>
                            </table>
                        </div>
                    </div>


                </div>

            @endif
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
            width: 120px;
            padding: 5px;
            justify-content: center;
            align-content: center;
            align-items: center;
            border-radius: 5px;
            border: 3px solid #b4b4b4;
            background-color: #f3f3f3;
            font-weight: bold;
            margin-left: 2%;
            margin-right: 2%;
        }

    </style>
@endsection
