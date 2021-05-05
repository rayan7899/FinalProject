<table>
    <thead>
        <tr>
            <th scope="col">#</th>
            <th scope="col">المعرف</th>
            <th scope="col">رقم الهوية</th>
            <th scope="col">الاسم</th>
            <th scope="col">رقم الجوال</th>
            <th scope="col">البرنامج</th>
            <th scope="col">القسم</th>
            <th scope="col">التخصص</th>
            <th scope="col">الحالة</th>
            <th scope="col">المبلغ المدفوع</th>
            <th scope="col">حالة التدقيق</th>
            <th scope="col">ملاحظات المدقق</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($users as $user)
            <tr>
                <th class="text-center" scope="row">{{ $loop->index + 1 ?? '' }}</th>
                <td>{{$user->id}}</td>
                <td class="text-center">{{ $user->national_id ?? 'لا يوجد' }} </td>
                <td>{{ $user->name ?? 'لا يوجد' }} </td>
                <td class="text-center">{{ $user->phone ?? 'لا يوجد' }} </td>
                <td class="text-center">{{ $user->student->program->name ?? 'لا يوجد' }} </td>
                <td class="text-center">{{ $user->student->department->name ?? 'لا يوجد' }} </td>
                <td class="text-center">{{ $user->student->major->name ?? 'لا يوجد' }} </td>
                <td class="text-center">{{ __($user->student->traineeState) ?? 'لا يوجد' }} </td>
                <td class="text-center">{{ $user->student->wallet ?? 'لا يوجد' }} </td>
                <td>{{ $user->student->not }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
