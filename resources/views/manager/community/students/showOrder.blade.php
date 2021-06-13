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

        <x-student-info :user="$user"/>
        
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
                                    <td class="text-success">متاح</td>
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
