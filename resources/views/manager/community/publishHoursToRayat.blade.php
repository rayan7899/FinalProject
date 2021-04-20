@extends('layouts.app')
@section('content')

    {{-- @dd($users[0]->student->courses) --}}
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
                            الرفع الى رايات 
                        </p>

                        <th>#</th>
                        <th>رقم الهوية</th>
                        <th>اسم المتقدم رباعي </th>
                        <th>رقم الجوال</th>
                        <th>البرنامج</th>
                        <th>القسم</th>
                        <th>التخصص</th>
                        <th> عدد الساعات</th>
                        <th>التسجيل في رايات</th>
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
                                switch ($user->student->traineeState) {
                                    case 'privateState':
                                        $discount = 0; // = %100 discount
                                        break;
                                    case 'employee':
                                        $discount = 0.25; // = %75 discount
                                        break;
                                    case 'employeeSon':
                                        $discount = 0.5; // = %50 discount
                                        break;
                                    default:
                                        $discount = 1; // = %0 discount
                                }
                                
                                $hoursCost = $user->student->order->requested_hours * 550;
                                $hoursCost = $hoursCost * $discount;
                                $requested_hours = $user->student->order->requested_hours;
                                $maxHours =  $requested_hours;
                                if ($user->student->traineeState != 'privateState') {
                                    if ($hoursCost >= $user->student->wallet) {
                                        $maxHours = floor($user->student->wallet / 550);
                                        $requested_hours = $maxHours;
                                    }
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
                                <td><input type="number" min="1" max="{{ $maxHours ?? 0 }}" class="p-0" name="requested_hours"
                                        id="requested_hours" value="{{ $requested_hours ?? 0 }}"><small> الحد الاعلى
                                        {{ $maxHours ?? 0 }}</small></td>
                                <td><button class="btn btn-primary btn-sm"
                                        onclick="publishToRayatCommunity({{ $user->national_id }},{{ $user->student->order->id }},event)">تم</button>
                                </td>
                            </tr>
                        @empty
                            لايوجد
                    @endforelse
                    @endif
                </tbody>
            </table>
        </div>
        <script>
            var publishToRayat = "{{ route('publishToRayatCommunity') }}";

        </script>

    </div>
@stop
