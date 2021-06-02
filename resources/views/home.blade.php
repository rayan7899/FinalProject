@extends('layouts.app')
@section('content')
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
            <div class="modal-dialog" role="document">
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
        <div class="card my-4">
            <div class="card-header">
                <h5 class="card-title">
                    البيانات الشخصية
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

        <div class="alert alert-warning border">
            <b>تنبية</b>
            هذا الموقع مسؤول عن العمليات المالية فقط ويجب عليك التوجه الى موقع
            <a href="https://www.tvtc.gov.sa/rayat.html">رايات</a>
            لتسجيل الجدول بعد دفع الرسوم وإتاحة الساعات لتسجيل.
        </div>

        <ul class="nav nav-tabs border rounded bg-white p-0">
            <li class="nav-item">
                <a onclick="tabClicked('transactions',event)"
                    class="nav-link h5 {{ count($user->student->transactions) > 0 ? 'active' : '' }}" href="#">جميع
                    العمليات المالية</a>
            </li>
            <li class="nav-item">
                <a onclick="tabClicked('payments',event)"
                    class="nav-link h5 {{ count($user->student->transactions) == 0 ? 'active' : '' }}"
                    href="#">المحفظة</a>
            </li>
            <li class="nav-item">
                <a onclick="tabClicked('orders',event)" class="nav-link h5" href="#">اضافة المقررات</a>
            </li>
            <li class="nav-item">
                <a onclick="tabClicked('refunds',event)" class="nav-link h5" href="#">طلبات الاسترداد</a>
            </li>
        </ul>
        {{-- transactions --}}
        <div id="transactions" style="display: {{ count($user->student->transactions) > 0 ? 'block' : 'none' }};"
            class="card tables">
            {{-- <div class="card-header">
                <div class="d-flex flex-row justify-content-between">
                    <div class="h5">
                        جميع العمليات المالية
                    </div>
                    <div>
                        <p class="h5 d-inline"> الرصيد الحالي : </p>
                        <p class="h5 d-inline">
                            {{ $user->student->wallet ?? 'لا يوجد' }}
                        </p>
                    </div>
                </div>
            </div> --}}
            <div class="card-body table-responsive p-0">
                <table class="table text-nowrap table-striped table-hover table-bordered p-0 m-0">
                    <thead>
                        <tr style="background-color: rgba(0, 0, 0, 0.03);">
                            <th scope="row">الرصيد الحالي</th>
                            <th scope="row" colspan="6">{{ $user->student->wallet ?? 'لا يوجد' }}</th>
                        </tr>
                        <tr>
                            <th class="text-center" scope="col">رقم العملية</th>
                            <th class="text-center" scope="col">نوع العملية</th>
                            <th class="text-center" scope="col">رقم الطلب (شحن / اضافة مقررات)</th>
                            <th class="text-center" scope="col">المبلغ</th>
                            <th class="text-center" scope="col">التاريخ</th>
                            <th class="text-center" scope="col">الملاحظات</th>
                            <th class="text-center" scope="col">ايصال السداد</th>
                        </tr>


                    </thead>
                    <tbody>
                        @forelse ($user->student->transactions as $transaction)
                            @php
                                $hoursNote = '';
                            @endphp
                            <tr class="text-center">
                                <td>{{ $transaction->id ?? 'Error' }}</td>
                                @if ($transaction->type == 'deduction')
                                    @php
                                        if (isset($transaction->order)) {
                                            $hoursNote = '( مقابل اضافة ' . $transaction->order->requested_hours . ' ساعة / ساعات )';
                                        }
                                    @endphp
                                    <td class="text-danger"> خصم (اضافة مقررات)</td>
                                    <td>{{ $transaction->order->id ?? 'Error' }}</td>
                                @elseif ($transaction->type == 'recharge')
                                    <td class="text-success"> اضافة (شحن المحفظة) </td>
                                    <td>{{ $transaction->payment->id ?? 'Error' }}</td>
                                @elseif ($transaction->type == 'refund-to-bank')
                                    <td class="text-danger"> خصم (استرداد الى البنك) </td>
                                    <td>{{ $transaction->refund->id ?? 'Error' }}</td>
                                @elseif ($transaction->type == 'refund-to-wallet')
                                    <td class="text-success"> اضافة (استرداد الى المحفظة) </td>
                                    <td>{{ $transaction->refund->id ?? 'Error' }}</td>
                                @elseif ($transaction->type == 'manager_recharge')
                                    <td class="text-success"> اضافة (من الادارة) </td>
                                    <td>{{ $transaction->payment->id ?? 'لا يوجد' }}</td>
                                @elseif ($transaction->type == 'manager_deduction')
                                    <td class="text-danger"> خصم (من الادارة) </td>
                                    <td>لا يوجد</td>
                                @else
                                    <td>لا يوجد</td>
                                    <td>{{ $transaction->refund_order_id ?? 'Error' }}</td>
                                @endif
                                <td style="min-width: 100px">{{ $transaction->amount ?? 'Error' }}</td>
                                <td style="min-width: 100px">{{ $transaction->created_at->toDateString() ?? 'Error' }}
                                </td>

                                <td class="text-right">
                                    @if ($transaction->type == 'deduction')
                                        {{ $hoursNote ?? '' }}
                                        <br>
                                        {{ $transaction->note ?? '' }}
                                    @else
                                        {{ $transaction->note ?? 'لا يوجد' }}
                                    @endif
                                </td>
                                @if (in_array($transaction->type, ['recharge', 'manager_recharge']))
                                    @if ($transaction->payment != null)
                                        <td>
                                            @php
                                                $splitByDot = explode('.', $transaction->payment->receipt_file_id);
                                                $fileExtantion = end($splitByDot);
                                            @endphp
                                            @if ($fileExtantion == 'pdf' || $fileExtantion == 'PDF')
                                                <a data-toggle="modal" data-target="#pdfModal" href="#"
                                                    onclick="showPdf('{{ route('GetStudentDocumentApi', ['national_id' => $user->national_id, 'filename' => $transaction->payment->receipt_file_id]) }}','pdf')">
                                                    <img style="width: 20px" src="{{ asset('/images/pdf.png') }}" />
                                                </a>
                                            @else
                                                <a data-toggle="modal" data-target="#pdfModal" href="#"
                                                    onclick="showPdf('{{ route('GetStudentDocumentApi', ['national_id' => $user->national_id, 'filename' => $transaction->payment->receipt_file_id]) }}','img')">
                                                    <img src=" {{ asset('/images/camera_img_icon.png') }}"
                                                        style="width:25px;" alt="Image File">
                                                </a>
                                            @endif
                                        </td>
                                    @else
                                        <td></td>
                                    @endif
                                @else
                                    <td></td>
                                @endif

                            </tr>
                        @empty

                        @endforelse

                    </tbody>
                </table>
            </div>
        </div>

        {{-- payments --}}
        <div id="payments" style="display: {{ count($user->student->transactions) == 0 ? 'block' : 'none' }};"
            class="card tables">
            <div class="card-header bg-white">
                <div class="d-flex flex-row justify-content-between">
                    <div class="h5">
                        {{-- المحفظة --}}
                    </div>

                    <a href="{{ route('paymentForm') }}" class="btn btn-primary rounded">
                        شحن المحفظة
                    </a>
                </div>
            </div>
            <div class="card-body table-responsive p-0">
                <table class="table text-nowrap table-striped table-hover table-bordered m-0 p-0">
                    <thead>
                        <tr>
                            <th class="text-center">رقم الطلب</th>
                            <th class="text-center">المبلغ</th>
                            <th class="text-center">حالة السداد</th>
                            <th class="text-center">التاريخ</th>
                            <th class="text-center">الملاحظات</th>
                            <th class="text-center">ايصال السداد</th>
                            <th class="text-center"></th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $countWaitingPayment = 0;
                        @endphp
                        @forelse ($user->student->payments as $payment)
                            @php
                                if ($payment->accepted === null) {
                                    $countWaitingPayment++;
                                }
                                $acceptedAmount =
                                    $user->student
                                        ->transactions()
                                        ->where('payment_id', $payment->id)
                                        ->first()->amount ?? null;
                            @endphp
                            <tr class="text-center" id="{{ $payment->id }}">
                                <td>{{ $payment->id }}</td>
                                @if ($payment->transaction_id != null && $payment->amount != $acceptedAmount)
                                    <td><del class="text-muted">{{ $payment->amount }}</del>
                                        {{ $acceptedAmount }}
                                    </td>
                                @else
                                    <td>{{ $payment->amount }}</td>
                                @endif
                                @if ($payment->accepted === null)
                                    <td>قيد المراجعة</td>
                                @else
                                    @if ($payment->accepted == true || $payment->accepted == 1)
                                        <td class="text-success">مقبول</td>
                                    @else
                                        <td class="text-danger">مرفوض</td>
                                    @endif
                                @endif
                                <td style="min-width: 100px">{{ $payment->created_at->toDateString() ?? 'Error' }}</td>
                                <td class="text-right">{{ $payment->note ?? 'لا يوجد' }}</td>

                                <td class="">

                                    @php
                                        $splitByDot = explode('.', $payment->receipt_file_id);
                                        $fileExtantion = end($splitByDot);
                                    @endphp
                                    @if ($fileExtantion == 'pdf' || $fileExtantion == 'PDF')
                                        <a data-toggle="modal" data-target="#pdfModal" href="#"
                                            onclick="showPdf('{{ route('GetStudentDocumentApi', ['national_id' => $user->national_id, 'filename' => $payment->receipt_file_id]) }}','pdf')">
                                            <img style="width: 20px" src="{{ asset('/images/pdf.png') }}" />
                                        </a>
                                    @else
                                        <a data-toggle="modal" data-target="#pdfModal" href="#"
                                            onclick="showPdf('{{ route('GetStudentDocumentApi', ['national_id' => $user->national_id, 'filename' => $payment->receipt_file_id]) }}','img')">
                                            <img src=" {{ asset('/images/camera_img_icon.png') }}" style="width:25px;"
                                                alt="Image File">
                                        </a>
                                    @endif
                                </td>
                                @if ($payment->accepted === null)
                                    <td><i class="fa btn fa-trash fa-lg text-danger p-0" aria-hidden="true"
                                            onclick="deletePayment({{ $payment->id }})"></i></td>
                                @else
                                    <td></td>
                                @endif
                            </tr>
                        @empty

                        @endforelse

                    </tbody>
                </table>
            </div>
        </div>

        {{-- orders --}}
        <div id="orders" style="display: none;" class="card tables">
            <div class="card-header  bg-white">
                <div class="d-flex flex-row justify-content-between">
                    {{-- <p class="h5">طلبات اضافة المقررات</p> --}}
                    <p></p>
                    {{-- @if ($user->student->level > 1) --}}
                    <a href="{{ route('orderForm') }}" class="btn btn-primary rounded">
                        اضافة مقررات
                    </a>
                    {{-- @endif --}}
                </div>
            </div>
            <div class="card-body table-responsive p-0">
                <table class="table text-nowrap table-striped table-hover table-bordered p-0 m-0">
                    <thead>
                        <tr>
                            <th class="text-center">رقم الطلب</th>
                            <th class="text-center">المبلغ</th>
                            <th class="text-center">إتاحة الساعات في رايات</th>
                            <th class="text-center">الساعات المطلوبة</th>
                            <th class="text-center">التاريخ</th>
                            <th class="text-center">الملاحظات</th>
                            <th class="text-center"></th>
                        </tr>
                    </thead>
                    <tbody>

                        @forelse ($user->student->orders as $order)
                            @php
                                $hasEnoughMoney = true;
                                if ($order->amount > $user->student->wallet && $countWaitingPayment == 0) {
                                    $hasEnoughMoney = false;
                                }
                            @endphp
                            <tr class="text-center" id="{{ $order->id }}">
                                <td>{{ $order->id ?? 'Error' }}</td>
                                <td>{{ $order->amount ?? 'Error' }}</td>
                                @if ($order->private_doc_verified === false || $order->private_doc_verified === '0' || $order->private_doc_verified === 0)
                                    <td class="text-danger">مرفوض</td>
                                    <td>{{ $order->requested_hours ?? 'Error' }}</td>
                                    <td style="min-width: 100px">{{ $order->created_at->toDateString() ?? 'Error' }}
                                    </td>
                                    <td class="text-right">{{ $order->note ?? 'لا يوجد' }}</td>
                                @else
                                    @if ($order->transaction_id == null)
                                        @if ($hasEnoughMoney == true || $user->student->traineeState == 'privateState')
                                            <td>قيد المراجعة</td>
                                            <td>{{ $order->requested_hours ?? 'Error' }}</td>
                                            <td style="min-width: 100px">
                                                {{ $order->created_at->toDateString() ?? 'Error' }}</td>
                                            <td class="text-right">{{ $order->note ?? 'لا يوجد' }}</td>
                                            <td><i class="fa btn fa-trash fa-lg text-danger p-0" aria-hidden="true"
                                                    onclick="deleteOrder({{ $order->id }})"></i></td>
                                        @else
                                            <td>معلق</td>
                                            <td>{{ $order->requested_hours ?? 'Error' }}</td>
                                            <td style="min-width: 100px">
                                                {{ $order->created_at->toDateString() ?? 'Error' }}</td>
                                            <td class="text-danger text-right">يرجى شحن المحفظة لا يوجد رصيد كافي
                                            </td>

                                            <td><i class="fa btn fa-trash fa-lg text-danger p-0" aria-hidden="true"
                                                    onclick="deleteOrder({{ $order->id }})"></i></td>
                                        @endif
                                    @else
                                        <td class="text-success">متاح</td>
                                        <td>{{ $order->requested_hours ?? 'Error' }}</td>
                                        <td style="min-width: 100px">
                                            {{ $order->created_at->toDateString() ?? 'Error' }}</td>
                                        <td class="text-right">{{ $order->note ?? 'لا يوجد' }}</td>
                                        <td></td>
                                    @endif
                                @endif
                            </tr>
                        @empty

                        @endforelse

                    </tbody>
                </table>
            </div>
        </div>

        {{-- refunds --}}
        <div id="refunds" style="display: none;" class="card tables">
            <div class="card-header  bg-white">
                <div class="d-flex flex-row justify-content-between">
                    {{-- <p class="h5">طلبات الاسترداد</p> --}}
                    <p></p>
                    <a href="{{ route('refundOrderForm') }}" class="btn btn-primary rounded">
                        طلب استرداد
                    </a>
                </div>
            </div>
            <div class="card-body table-responsive p-0">
                <table class="table text-nowrap table-striped table-hover table-bordered p-0 m-0">
                    <thead>
                        <tr>
                            <th class="text-center">رقم الطلب</th>
                            <th class="text-center">المبلغ المستحق</th>
                            <th class="text-center">حالة الطلب</th>
                            <th class="text-center">السبب</th>
                            <th class="text-center">تاريخ الطلب</th>
                            <th class="text-center">ملاحظة المتدرب</th>
                            <th class="text-center">ملاحظة المشرف</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($user->student->refunds as $refund)
                            @php
                            @endphp
                            <tr class="text-center">
                                <td>{{ $refund->id ?? 'Error' }}</td>
                                @if ($refund->accepted === null)
                                    <td>قيد المراجعة</td>
                                @else
                                    <td>{{ $refund->amount ?? 'Error' }}</td>
                                @endif
                                @if ($refund->accepted === null)
                                    <td>قيد المراجعة</td>
                                @elseif($refund->accepted)
                                    <td class="text-success">مقبول</td>
                                @else
                                    <td class="text-danger">مرفوض</td>
                                @endif
                                @switch($refund->reason)
                                    @case('drop-out')
                                        <td>انسحاب</td>
                                    @break
                                    @case('graduate')
                                        <td>خريج</td>
                                    @break
                                    @case('exception')
                                        <td>استثناء</td>
                                    @break
                                    @case('not-opened-class')
                                        <td>لم تتاح الشعبة</td>
                                    @break
                                    @case('get-wallet-amount')
                                        <td>استرداد مبلغ المحفظة</td>
                                    @break
                                    @default
                                        <td>لا يوجد</td>
                                @endswitch
                                <td>{{ $refund->created_at->toDateString() ?? 'لا يوجد' }}</td>
                                <td class="text-right">{{ $refund->student_note ?? 'لا يوجد' }}</td>
                                <td class="text-right">{{ $refund->manager_note ?? 'لا يوجد' }}</td>
                            </tr>
                            @empty

                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>




        </div>

        <script>
            var deleteOrder = "{{ route('deleteOrder') }}";
            var deleteOrder = "{{ route('deletePayment') }}";

            function tabClicked(id, event) {
                event.preventDefault();
                let cards = document.getElementsByClassName('tables');
                let navLinks = document.getElementsByClassName('nav-link');
                for (let link of navLinks) {
                    link.classList.remove('active');
                }
                for (let card of cards) {
                    card.style.display = 'none';
                }
                document.getElementById(id).style.display = 'block';
                event.target.classList.add('active');
            }

        </script>
    @endsection
