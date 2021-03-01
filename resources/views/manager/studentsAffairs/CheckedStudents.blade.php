@extends('layouts.app')
@section('content')
<div class="container">

      <table class="table table-sm table-bordered table-striped  table-hover">
        <thead class="text-center">
          <tr>
            <th scope="col">#</th>
            <th scope="col">رقم الهوية</th>
            <th scope="col">اسم المتقدم</th>
            <th scope="col">رقم الجوال</th>
            <th scope="col">البرنامج</th>
            <th scope="col">القسم</th>
            <th scope="col">التخصص</th>
            <th scope="col">الحالة</th>
            <th scope="col">ملاحظات المدقق</th>
          </tr>
        </thead>
        <tbody>
          @if(isset($users))
            @forelse($users as $user)
              <tr>
                <th scope="row">{{ $loop->index + 1 ?? '' }}</th>
                <td>{{ $user->national_id ?? 'لا يوجد' }} </td>
                <td>{{ $user->name ?? 'لا يوجد' }} </td>
                <td>{{ $user->phone ?? 'لا يوجد' }} </td>
                <td>{{ $user->student->program->name ?? 'لا يوجد' }} </td>
                <td>{{ $user->student->department->name ?? 'لا يوجد' }} </td>
                <td>{{ $user->student->major->name ?? 'لا يوجد' }} </td>
                <td>{{ __($user->student->traineeState) ?? 'لا يوجد' }} </td>
                <td>{{ $user->student->note ?? 'لا يوجد' }} </td>
              </tr>
            @empty
              <td colspan="9"> لا يوجد بيانات</td>
            @endforelse
          @else
            <p>لا يوجد بيانات</p>
          @endif

        </tbody>
      </table>
</div>
@stop