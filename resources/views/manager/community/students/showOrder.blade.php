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
        <form class="w-25 my-1" id="orderSearchForm" method="GET">
            <div class="input-group" id="searchContainer">
                <input type="text" class="form-control rounded" id="orderId" value="" placeholder=" رقم الطلب">
                <input type="button" onclick="getOrder()" value="بحث" class="btn btn-primary">
            </div>
        </form>


        <div class="card">
            <div class="card-header">
                <h5 class="card-title">
                    بيانات المتدرب
                </h5>
            </div>
            <div class="row">

                <div class="col-md-6">
                    <div dir="ltr" class="input-group mb-1">
                        <input readonly type="text" class="form-control text-right bg-white h5"
                            value="{{ $user->name ?? 'لا يوجد' }}">
                        <div class="input-group-append">
                            <span class="input-group-text text-center" style="width: 120px;"><label
                                    class="text-center m-0 p-0 w-100">الاسم</label></span>
                        </div>
                    </div>
                </div>


                <div class="col-md-6">
                    <div dir="ltr" class="input-group mb-1">
                        <input readonly type="text" class="form-control text-right bg-white"
                            value="{{ $user->student->program->name ?? 'لا يوجد' }}">
                        <div class="input-group-append">
                            <span class="input-group-text text-center" style="width: 120px;"><label
                                    class="text-center m-0 p-0 w-100">البرنامج</label></span>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div dir="ltr" class="input-group mb-1">
                        <input readonly type="text" class="form-control text-right bg-white"
                            value="{{ $user->national_id ?? 'لا يوجد' }}">
                        <div class="input-group-append">
                            <span class="input-group-text text-center" style="width: 120px;"><label
                                    class="text-center m-0 p-0 w-100">رقم الهوية</label></span>
                        </div>
                    </div>
                </div>


                <div class="col-md-6">
                    <div dir="ltr" class="input-group mb-1">
                        <input readonly type="text" class="form-control text-right bg-white"
                            value="{{ $user->student->department->name ?? 'لا يوجد' }}">
                        <div class="input-group-append">
                            <span class="input-group-text text-center" style="width: 120px;"><label
                                    class="text-center m-0 p-0 w-100">القسم</label></span>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div dir="ltr" class="input-group mb-1">
                        <input readonly type="text" class="form-control text-right bg-white"
                            value="{{ $user->phone ?? 'لا يوجد' }}">
                        <div class="input-group-append">
                            <span class="input-group-text text-center" style="width: 120px;"><label
                                    class="text-center m-0 p-0 w-100">رقم الجوال</label></span>
                        </div>
                    </div>
                </div>



                <div class="col-md-6">
                    <div dir="ltr" class="input-group mb-1">
                        <input readonly type="text" class="form-control text-right bg-white"
                            value="{{ $user->student->major->name ?? 'لا يوجد' }}">
                        <div class="input-group-append">
                            <span class="input-group-text text-center" style="width: 120px;"><label
                                    class="text-center m-0 p-0 w-100">التخصص</label></span>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div dir="ltr" class="input-group mb-1">
                        <input readonly type="text" class="form-control text-right bg-white"
                            value="{{ $user->email ?? 'لا يوجد' }}">
                        <div class="input-group-append">
                            <span class="input-group-text text-center" style="width: 120px;"><label
                                    class="text-center m-0 p-0 w-100">البريد الالكتروني</label></span>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
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

                <div class="col-md-6">
                    <div dir="ltr" class="input-group mb-1">
                        <input readonly type="text" class="form-control text-right bg-white"
                            value="{{ $total_hours ?? 0 }}">
                        <div class="input-group-append">
                            <span class="input-group-text text-center" style="width: 120px;"><label
                                    class="text-center m-0 p-0 w-100">الساعات الكلية</label></span>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div dir="ltr" class="input-group mb-1">
                        <input readonly type="text" class="form-control text-right bg-white"
                            value="{{ $user->student->credit_hours ?? 0 }}">
                        <div class="input-group-append">
                            <span class="input-group-text text-center" style="width: 120px;"><label
                                    class="text-center m-0 p-0 w-100">الساعات المعتمدة</label></span>
                        </div>
                    </div>
                </div>
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



        {{-- orders --}}
        <div id="orders" class="card tables">
            <div class="card-header">
                <h5 class="card-title">
                    بيانات الطلب
                </h5>
            </div>
            <div class="card-body table-responsive p-0">
                <table class="table text-nowrap table-striped table-hover table-bordered p-0 m-0">
                    <thead>
                        <tr>
                            <th class="text-center">رقم الطلب</th>
                            <th class="text-center">عدد الساعات</th>
                            <th class="text-center">المبلغ</th>
                            <th class="text-center">حالة تسجيل الساعات في رايات</th>
                            <th class="text-center">التاريخ</th>
                            <th class="text-center">الملاحظات</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="text-center">
                            <td>{{ $order->id ?? 'Error' }}</td>
                            <td>{{ $order->requested_hours ?? 'Error' }}</td>
                            <td>{{ $order->amount ?? 'Error' }}</td>
                            @if ($order->private_doc_verified === false || $order->private_doc_verified === '0' || $order->private_doc_verified === 0)
                                <td class="text-danger">مرفوض</td>
                                <td style="min-width: 100px">{{ $order->created_at->toDateString() ?? 'Error' }}
                                </td>
                                <td class="text-right">{{ $order->note ?? 'لا يوجد' }}</td>
                            @else
                                @if ($order->transaction_id == null)
                                    <td>قيد المراجعة</td>
                                    <td style="min-width: 100px">
                                        {{ $order->created_at->toDateString() ?? 'Error' }}
                                    </td>
                                    <td class="text-right">{{ $order->note ?? 'لا يوجد' }}</td>
                                @else
                                    <td class="text-success">مقبول</td>
                                    <td style="min-width: 100px">
                                        {{ $order->created_at->toDateString() ?? 'Error' }}</td>
                                    <td class="text-right">{{ $order->note ?? 'لا يوجد' }}</td>
                                @endif
                            @endif
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <script>
        function getOrder() {

            let id = document.getElementById("orderId").value;
            document.getElementById("orderSearchForm").action = "/community/students/show-order/" + id
            document.getElementById("orderSearchForm").submit();
        }

    </script>
@stop
