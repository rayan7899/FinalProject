@extends('layouts.app')
@section('content')
    <div class="container-fluid">
        @if ($errors->any() || isset($error))
            <div class="alert alert-danger">
                @if ($errors->any())
                    <ul>
                        @foreach ($errors->all() as $message)
                            <li>{{ $message }}</li>
                        @endforeach
                    </ul>
                @endif
                @if (isset($error))
                    {{ $error }}
                @endif
            </div>
        @endif
        <div class="table-responsive p-2 bg-white rounded border">
            <table id="mainTable" class="table nowrap display cell-border">
                <thead>
                    <tr>
                        <p class="text-center">
                            تقرير رايات - خدمة المجتمع
                        </p>

                        <th>#</th>
                        <th>رقم الهوية</th>
                        <th>اسم المتقدم رباعي </th>
                        <th>رقم الجوال</th>
                        <th>البرنامج</th>
                        <th>القسم</th>
                        <th>التخصص</th>
                        <th>عدد الساعات</th>
                        <th>حالة التسجيل</th>
                    </tr>
                    <tr>
                        <th class="filterhead"></th>
                        <th class="filterhead"></th>
                        <th class="filterhead"></th>
                        <th class="filterhead"></th>
                        <th class="filterhead"></th>
                        <th class="filterhead"></th>
                        <th class="filterhead"></th>
                        <th class="filterhead"></th>
                        <th class="filterhead"></th>
                    </tr>
                </thead>
                <tbody>
                    @if (isset($users))
                        @forelse ($users as $user)
                            @php
                                $total_cost = 0;
                                $total_hours = 0;
                                foreach ($user->student->courses as $course) {
                                    $total_hours += $course->credit_hours;
                                    $total_cost += $course->credit_hours * 550;
                                }
                            @endphp
                            <tr>
                                <th scope="row">{{ $loop->index + 1 ?? '' }}</th>
                                <td>{{ $user->national_id ?? 'لا يوجد' }} </td>
                                <td>{{ $user->name ?? 'لا يوجد' }} </td>
                                <td>{{ $user->phone ?? 'لا يوجد' }} </td>
                                <td>{{ $user->student->program->name ?? 'لا يوجد' }} </td>
                                <td>{{ $user->student->department->name ?? 'لا يوجد' }} </td>
                                <td>{{ $user->student->major->name ?? 'لا يوجد' }} </td>
                                <td>{{$total_hours ?? 0}}</td>
                                <td class="text-success">مسجل في رايات</td>
                            </tr>
                        @empty
                            لايوجد
                    @endforelse
                    @endif
                </tbody>
            </table>
        </div>
    </div>
@stop
