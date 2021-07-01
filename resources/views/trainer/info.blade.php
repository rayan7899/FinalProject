@extends('layouts.app')
@section('content')
<div class="container">
    <x-trainer-info :user="$user"/>
    

    @isset($user)
        <div class="card tables">
            <div class="card-header bg-white">
                <h5>عقود التدريب</h5>
            </div>
            <div class="card-body table-responsive p-0">
                <table class="table table-sm text-nowrap table-striped table-hover table-bordered m-0 p-0">
                    <thead>
                        <tr>
                            <th class="text-center">رقم الطلب</th>
                            <th class="text-center">اسم المقرر</th>
                            <th class="text-center">رمز المقرر</th>
                            <th class="text-center">نوع المقرر</th>
                            <th class="text-center">رقم الشعبة</th>
                            <th class="text-center">ساعات الاتصال</th>
                            <th class="text-center">ساعات الاختبار</th>
                            <th class="text-center">حالة الطلب</th>
                            <th class="text-center">ملاحظة رئيس القسم</th>
                            <th class="text-center">ملاحظة خدمة المجتمع</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                        @endphp
                        @forelse ($user->trainer->coursesOrders as $order)
                            @php
                            @endphp
                            <tr class="text-center" id="{{ $order->id }}">
                                <td>{{ $order->id ?? 'لا يوجد' }}</td>
                                <td>{{ $order->course->name ?? 'لا يوجد' }}</td>
                                <td>{{ $order->course->code ?? 'لا يوجد' }}</td>
                                <td>{{ $order->course_type ?? 'لا يوجد' }}</td>
                                <td>{{ $order->division_number ?? 'لا يوجد' }}</td>
                                <td>{{ $order->course_type == 'عملي' ? $order->course->practical_hours : $order->course->theoretical_hours ?? 'لا يوجد' }}</td>
                                <td>{{ $order->course_type == 'عملي' ? $order->course->exam_practical_hours : $order->course->exam_theoretical_hours ?? 'لا يوجد' }}</td>
                                <td>
                                    @if ($order->accepted_by_dept_boss === null && $order->accepted_by_community === null)   
                                        <span>قيد المراجعة</span>
                                    @elseif($order->accepted_by_dept_boss == true && $order->accepted_by_community === null)
                                        <span>بانتظار موافقة خدمة المجتمع</span>
                                    @elseif($order->accepted_by_dept_boss == false)
                                        <span class="text-danger">مرفوض</span>
                                    @elseif($order->accepted_by_dept_boss == true && $order->accepted_by_community == true)
                                        <span class="text-success">مقبول</span>
                                    @else
                                        <span>قيد المراجعة</span>
                                    @endif
                                </td>
                                <td>{{ $order->dept_boss_note ?? 'لا يوجد' }}</td>
                                <td>
                                    @if ($order->accepted_by_dept_boss == true && $order->accepted_by_community == true)
                                        لا يوجد
                                    @else
                                        {{ $order->community_note ?? 'لا يوجد' }}
                                    @endif
                                </td>
                            </tr>
                        @empty

                        @endforelse

                    </tbody>
                </table>
            </div>
        </div>
    @endisset
</div>
@stop