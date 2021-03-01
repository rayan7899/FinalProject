@extends('layouts.app')
@section('content')
<table id="myTable" class="table table-bordered text-right table-striped compact">


    <thead>
        <tr>

            <p class="text-center">
                المتدربين المستجدين - قسم الحاسب الالي
            </p>

            <th>#</th>
            <th>رقم الهوية</th>
            <th>اسم المتقدم رباعي </th>
            <th>رقم الجوال</th>
            <th>البرنامج</th>
            <th>القسم</th>
            <th>التخصص</th>
            <th>عدد الساعات</th>
        </tr>
    </thead>
    <tbody>
        @if (isset($users))
            @forelse ($users as $user)
                <tr>
                    <th scope="row">{{ $loop->index + 1 ?? '' }}</th>
                    <td>{{ $user->national_id ?? 'لا يوجد' }} </td>
                    <td>{{ $user->name ?? 'لا يوجد' }} </td>
                    <td>{{ $user->phone ?? 'لا يوجد' }} </td>
                    <td>{{ $user->student->program->name ?? 'لا يوجد' }} </td>
                    <td>{{ $user->student->department->name ?? 'لا يوجد' }} </td>
                    <td>{{ $user->student->major->name ?? 'لا يوجد' }} </td>
                    <td>
                        @php
                            if($user->student->traineeState=='privateState'){
                                echo 16;
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

                                    case 'privateState':
                                        $discount=1;
                                        break;

                                    default:
                                        break;
                                }
                                $wallet = $user->student->wallet;
                                $hourCostAfterDiscount = $hourCost-$hourCost*$discount;
                                $maxHours=$user->student->major->hours;
                                $minHours=15;
                                $maxCost=$maxHours*$hourCostAfterDiscount;
                                $minCost=$minHours*$hourCostAfterDiscount;
                                $costRest = $wallet%$hourCostAfterDiscount;
                                $clearCost = $wallet-$costRest;
                                echo $clearCost/$hourCostAfterDiscount;
                            }
                        @endphp
                     </td>
                </tr>
            @empty
                لايوجد
            @endforelse
        @endif
    </tbody>
</table>
@stop