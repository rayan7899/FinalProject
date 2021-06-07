@extends('layouts.app')
@section('content')
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
        <x-student-info :user="$user"/>

        <div class="alert alert-warning border">
            <b>تنبية</b>
            هذا الموقع مسؤول عن العمليات المالية فقط ويجب عليك التوجه الى موقع
            <a href="https://www.tvtc.gov.sa/rayat.html">رايات</a>
            لتسجيل الجدول بعد دفع الرسوم وإتاحة الساعات للتسجيل.
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
                                @elseif ($transaction->type == 'editPayment-deduction')
                                    <td class="text-danger"> خصم (اعادة تدقيق ايصال) </td>
                                    <td>{{ $transaction->payment->id ?? 'لا يوجد' }}</td>
                                @elseif ($transaction->type == 'editPayment-charge')
                                    <td class="text-success"> اضافة (اعادة تدقيق ايصال) </td>
                                    <td>{{ $transaction->payment->id ?? 'لا يوجد' }}</td>
                                @elseif ($transaction->type == 'editOrder-charge')
                                    <td class="text-success"> اضافة (تعديل ساعات) </td>
                                    <td>{{ $transaction->order->id ?? 'لا يوجد' }}</td>
                                @elseif ($transaction->type == 'editOrder-deduction')
                                    <td class="text-danger"> خصم (تعديل ساعات) </td>
                                    <td>{{ $transaction->order->id ?? 'لا يوجد' }}</td>
                                @else
                                    <td>لا يوجد</td>
                                    <td>Error</td>
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

                    <a href="{{ route('paymentForm') }}" class="btn @if (!$semester->can_request_hours) btn-primary @else btn-secondary @endif rounded">
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
                                $acceptedAmount = 0;
                                foreach ($payment->transactions as  $transaction) {
                                    if($transaction->type == 'editPayment-charge' || $transaction->type == 'recharge' || $transaction->type == 'manager_recharge'){
                                        $acceptedAmount += $transaction->amount;
                                    }else{
                                        $acceptedAmount -= $transaction->amount;
                                    }
                                }    
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
                    <a href="{{ route('orderForm') }}" class="btn @if ($semester->can_request_hours) btn-primary @else btn-secondary @endif rounded">
                        اضافة مقررات
                    </a>
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
