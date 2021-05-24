<table>
    <thead>
        <tr>
            <th style="text-align: center">#</th>
            <th style="text-align: center">المعرف</th>
            <th style="text-align: center">رقم الهوية</th>
            <th style="text-align: center">الرقم التدريبي</th>
            <th style="text-align: center">الاسم</th>
            <th style="text-align: center">رقم الجوال</th>
            <th style="text-align: center">البرنامج</th>
            <th style="text-align: center">القسم</th>
            <th style="text-align: center">التخصص</th>
            <th style="text-align: center">الحالة</th>
            <th style="text-align: center">الساعات المعتمدة</th>
            <th style="text-align: center">رصيد المحفظة</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($users as $user)
            <tr>
                <th style="text-align: center">{{ $loop->index + 1 ?? '' }}</th>
                <td style="text-align: center">{{$user->id}}</td>
                <td style="text-align: center">{{ $user->national_id ?? 'لا يوجد' }} </td>
                <td style="text-align: center">{{ $user->student->rayat_id ?? 'لا يوجد' }} </td>
                <td style="text-align: right">{{ $user->name ?? 'لا يوجد' }} </td>
                <td style="text-align: center">{{ $user->phone ?? 'لا يوجد' }} </td>
                <td style="text-align: center">{{ $user->student->program->name ?? 'لا يوجد' }} </td>
                <td style="text-align: center">{{ $user->student->department->name ?? 'لا يوجد' }} </td>
                <td style="text-align: center">{{ $user->student->major->name ?? 'لا يوجد' }} </td>
                <td style="text-align: center">{{ __($user->student->traineeState) ?? 'لا يوجد' }} </td>
                <td style="text-align: center">{{ $user->student->credit_hours ?? 'لا يوجد' }} </td>
                <td style="text-align: center">{{ $user->student->wallet ?? 'لا يوجد' }} </td>
            </tr>
        @endforeach
    </tbody>
</table>
