@extends('layouts.app')
@section('content')
    <div class="container-fluid">
        <div class="table-responsive p-2 bg-white rounded border">
            <h6 class="text-center" style="position: relative; top:10px">القبول النهائي - خدمة المجتمع</h6>
            <table class="table nowrap display cell-border" id="mainTable">
                <thead class="text-center">
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">رقم الهوية</th>
                        <th scope="col">الاسم</th>
                        <th scope="col">رقم الجوال</th>
                        <th scope="col">البرنامج</th>
                        <th scope="col">القسم</th>
                        <th scope="col">التخصص</th>
                    </tr>
                    <tr>
                        <th class="filterhead" scope="col"></th>
                        <th class="filterhead" scope="col"></th>
                        <th class="filterhead" scope="col"></th>
                        <th class="filterhead" scope="col"></th>
                        <th class="filterhead" scope="col"></th>
                        <th class="filterhead" scope="col"></th>
                        <th class="filterhead" scope="col"></th>
                    </tr>
                </thead>
                <tbody>
                    @if (isset($users))
                        @forelse ($users as $user)
                            <tr>
                                <th class="text-center" scope="row">{{ $loop->index + 1 ?? '' }}</th>
                                <td class="text-center">{{ $user->national_id ?? 'لا يوجد' }} </td>
                                <td>{{ $user->name ?? 'لا يوجد' }} </td>
                                <td class="text-center">{{ $user->phone ?? 'لا يوجد' }} </td>
                                <td class="text-center">{{ $user->student->program->name ?? 'لا يوجد' }} </td>
                                <td class="text-center">{{ $user->student->department->name ?? 'لا يوجد' }} </td>
                                <td class="text-center">{{ $user->student->major->name ?? 'لا يوجد' }} </td>
                            </tr>
                        @empty
                            <td colspan="12">لا يوجد بيانات</td>
                    @endforelse
                    @endif
                </tbody>
            </table>
        </div>
        <script>
            var finalAcceptedRoute = "{{ route('finalAcceptedUpdate') }}";

        </script>
    </div>
@stop
