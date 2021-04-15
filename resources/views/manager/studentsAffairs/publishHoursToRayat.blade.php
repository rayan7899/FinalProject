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
                        الرفع الى رايات - شؤون المتدربين - مستجدين
                    </p>

                    <th>#</th>
                    <th>رقم الهوية</th>
                    <th>اسم المتقدم رباعي </th>
                    <th>رقم الجوال</th>
                    <th>البرنامج</th>
                    <th>القسم</th>
                    <th>التخصص</th>
                    <th>عدد الساعات</th>
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
                            <td>{{ $total_hours ?? 0 }}</td>
                            {{-- <td>
                                @php
                                    if($user->student->traineeState=='privateState'){
                                        echo $user->student->major->hours;
                                    }else{
                                        $hourCost = $user->student->program->id==1?500:400;
                                        $discount = 0;
                                        switch($user->student->traineeState){
                                            case 'trainee':
                                                $discount = 0;
                                                break;
                                            case 'employee':
                                                $discount = 0.75;
                                                break;
                                            case 'employeeSon':
                                                $discount=0.5;
                                                break;
                                            default:
                                                break;
                                        }
                                        $wallet = $user->student->wallet;
                                        $hourCostAfterDiscount = $hourCost-$hourCost*$discount;
                                        // $maxHours=$user->student->major->hours;
                                        // $minHours=15;
                                        // $maxCost=$maxHours*$hourCostAfterDiscount;
                                        // $minCost=$minHours*$hourCostAfterDiscount;
                                        $costRest = $wallet%$hourCostAfterDiscount;
                                        $clearCost = $wallet-$costRest;
                                        echo $clearCost/$hourCostAfterDiscount;
                                    }
                                @endphp
                            </td> --}}
                            <td><input type="checkbox" name="published" id="published" onchange="publishStudentHours({{$user->national_id}}, event)"></td>
                        </tr>
                    @empty
                        لايوجد
                    @endforelse
                @endif
            </tbody>
        </table>
    </div>
    <script>
         var publishToRayat = "{{ route('publishToRayatAffairs') }}";
    </script>
</div>
@stop