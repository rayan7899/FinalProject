@extends('layouts.app')
@section('content')
<div class="container-fluid">
    @if (isset($error) || !empty($fetch_errors))
        <div class="alert alert-danger">
            @if (isset($error))
                {{ $error }}
            @endif
            @if (isset($fetch_errors))
                <p>حصل خطا في جلب بعض ملفات المتدربين:</p>
                @foreach ($fetch_errors as $err)
                    <ul>
                        <li>{{ $err }}</li>
                    </ul>
                @endforeach
            @endif
        </div>
    @endif
    

    <div class="p-2 bg-white rounded border">
        <table class="table table-responsive nowrap display cell-border" id="mainTable">
            <thead class="text-center">
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">رقم الهوية</th>
                    <th scope="col">الرقم التدريبي</th>
                    <th scope="col">الاسم</th>
                    <th scope="col">رقم الجوال</th>
                    <th scope="col">رقم الايبان</th>
                    <th scope="col">البنك</th>
                    <th scope="col">المبلغ المسترد</th>
                    <th scope="col">السبب</th>
                    <th scope="col">تاريخ الطلب</th>
                    <th scope="col">ملاحظة المتدرب</th>
                    <th scope="col">قبول الطلب</th>
                    <th scope="col">المشرف المدقق</th>
                    <th scope="col">ملاحظة المشرف</th>
                </tr>
                {{-- <tr>
                    <th class="filterhead" scope="col"></th>
                    <th class="filterhead" scope="col"></th>
                    <th class="filterhead" scope="col"></th>
                    <th class="filterhead" scope="col"></th>
                    <th class="filterhead" scope="col"></th>
                    <th class="filterhead" scope="col"></th>
                    <th class="filterhead" scope="col"></th>
                    <th class="filterhead" scope="col"></th>
                    <th class="filterhead" scope="col"></th>
                    <th class="filterhead" scope="col"></th>
                    <th class="filterhead" scope="col"></th>
                    <th class="filterhead" scope="col"></th>
                </tr> --}}
            </thead>
            <tbody>
                @if (isset($refunds))
                    @forelse ($refunds as $refund)
                        @php
                        @endphp
                        <tr id="{{$refund->student->user->national_id ?? 0}}">
                            <th class="text-center" scope="row">{{ $loop->index + 1 ?? '' }}</th>
                            <td class="text-center">{{ $refund->student->user->national_id ?? 'لا يوجد' }} </td>
                            <td class="text-center">{{ $refund->student->rayat_id ?? 'لا يوجد' }} </td>
                            <td>{{ $refund->student->user->name ?? 'لا يوجد' }} </td>
                            <td class="text-center">{{ $refund->student->user->phone ?? 'لا يوجد' }} </td>
                            <td class="text-center">{{ $refund->IBAN ?? 'لا يوجد' }} </td>
                            <td class="text-center">{{ $refund->bank ?? 'لا يوجد' }} </td>
                            <td class="text-center">{{ $refund->amount ?? 'لا يوجد' }} </td>
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
                            <td>{{ $refund->student_note ?? 'لا يوجد' }}</td>
                            @if ($refund->accepted)
                                <td class="text-success text-center">مقبول</td>
                            @else
                                <td class="text-danger text-center">مرفوض</td>
                            @endif
                            <td class="text-center">{{ $refund->transaction->manager->name ?? 'لا يوجد' }}</td>
                            <td>{{ $refund->manager_note ?? 'لا يوجد' }}</td>
                        </tr>
                    @empty
                    @endforelse
                @endif


            </tbody>
            <tfoot>
                <tr>
                    <th scope="col"></th>
                    <th scope="col"></th>
                    <th scope="col"></th>
                    <th scope="col"></th>
                    <th scope="col"></th>
                    <th scope="col"></th>
                    <th scope="col"></th>
                    <th scope="col"></th>
                    <th scope="col"></th>
                    <th scope="col"></th>
                    <th scope="col"></th>
                    <th scope="col"></th>
                    <th scope="col"></th>
                    <th scope="col"></th>
                </tr>
            </tfoot>
        </table>
    </div>
    <script defer>
        var refundOrdersUpdate = "{{ route('apiRefundOrdersUpdate') }}";

    </script>
</div>
@endsection