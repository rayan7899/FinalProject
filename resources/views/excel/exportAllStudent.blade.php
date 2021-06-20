
<table>
    <thead>
        <tr>
            <th style="text-align: center; background-color: lightblue; font-size: 11px; font-weight: bold;">#</th>
            <th style="text-align: center; background-color: lightblue; font-size: 11px; font-weight: bold;">المعرف</th>
            <th style="text-align: center; background-color: lightblue; font-size: 11px; font-weight: bold;">رقم الهوية</th>
            <th style="text-align: center; background-color: lightblue; font-size: 11px; font-weight: bold;">الرقم التدريبي</th>
            <th style="text-align: center; background-color: lightblue; font-size: 11px; font-weight: bold;">الاسم</th>
            <th style="text-align: center; background-color: lightblue; font-size: 11px; font-weight: bold;">رقم الجوال</th>
            <th style="text-align: center; background-color: lightblue; font-size: 11px; font-weight: bold;">البرنامج</th>
            <th style="text-align: center; background-color: lightblue; font-size: 11px; font-weight: bold;">القسم</th>
            <th style="text-align: center; background-color: lightblue; font-size: 11px; font-weight: bold;">التخصص</th>
            <th style="text-align: center; background-color: lightblue; font-size: 11px; font-weight: bold;">الحالة</th>
            <th style="text-align: center; background-color: lightblue; font-size: 11px; font-weight: bold;">الساعات المعتمدة</th>
            <th style="text-align: center; background-color: lightblue; font-size: 11px; font-weight: bold;">رصيد المحفظة</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($users as $user)
            <tr>
                <th style="text-align: center; font-size: 10px;">{{ $loop->index + 1 ?? '' }}</th>
                <td style="text-align: center; font-size: 10px;">{{$user->id}}</td>
                <td style="text-align: center; font-size: 10px;">{{ $user->national_id ?? 'لا يوجد' }} </td>
                <td style="text-align: center; font-size: 10px;">{{ $user->student->rayat_id ?? 'لا يوجد' }} </td>
                <td style="text-align: rightf; ont-size:  10px;">{{ $user->name ?? 'لا يوجد' }} </td>
                <td style="text-align: center; font-size: 10px;">{{ $user->phone ?? 'لا يوجد' }} </td>
                <td style="text-align: center; font-size: 10px;">{{ $user->student->program->name ?? 'لا يوجد' }} </td>
                <td style="text-align: center; font-size: 10px;">{{ $user->student->department->name ?? 'لا يوجد' }} </td>
                <td style="text-align: center; font-size: 10px;">{{ $user->student->major->name ?? 'لا يوجد' }} </td>
                <td style="text-align: center; font-size: 10px;">{{ __($user->student->traineeState) ?? 'لا يوجد' }} </td>
                <td style="text-align: center; font-size: 10px;">{{ $user->student->available_hours ?? 'لا يوجد' }} </td>
                <td style="text-align: center; font-size: 10px;">{{ $user->student->wallet ?? 'لا يوجد' }} </td>
            </tr>
        @endforeach
    </tbody>
</table>
