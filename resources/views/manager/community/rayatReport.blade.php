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
            <table id="rayatReportTbl" class="table nowrap display cell-border">
                <thead>
                    <tr>
                        <p class="text-center">
                            تقرير رايات
                        </p>

                        <th class="text-center">#</th>
                        <th class="text-center">رقم الهوية</th>
                        <th class="text-center">الرقم التدريبي</th>
                        <th>اسم المتدرب </th>
                        <th class="text-center">رقم الجوال</th>
                        <th class="text-center">البرنامج</th>
                        <th class="text-center">القسم</th>
                        <th class="text-center">التخصص</th>
                        <th class="text-center">عدد الساعات</th>
                        <th class="text-center">حالة التسجيل</th>
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
                        <th class="filterhead"></th>
                    </tr>
                </thead>
                <tbody>
                    {{-- @if (isset($users))
                        @forelse ($users as $user)
                            <tr>
                                <th scope="row">{{ $loop->index + 1 ?? '' }}</th>
                                <td>{{ $user->national_id ?? 'لا يوجد' }} </td>
                                <td>{{ $user->name ?? 'لا يوجد' }} </td>
                                <td>{{ $user->phone ?? 'لا يوجد' }} </td>
                                <td>{{ $user->student->program->name ?? 'لا يوجد' }} </td>
                                <td>{{ $user->student->department->name ?? 'لا يوجد' }} </td>
                                <td>{{ $user->student->major->name ?? 'لا يوجد' }} </td>
                                <td>{{ $user->student->credit_hours ?? 0 }}</td>
                                <td class="text-success">مسجل في رايات</td>
                            </tr>
                        @empty
                            لايوجد
                    @endforelse
                    @endif --}}
                </tbody>
            </table>
        </div>
    </div>
    <script>
        var rayatReportApi;
        if("{{$type}}" == "departmentBoss"){
            rayatReportApi = "{{ route('rayatReportCommunityApi', ['type' => $type]) }}";
        }else{
            rayatReportApi =
                "{{ $type == 'community' ? route('rayatReportCommunityApi', ['type' => $type]) : route('rayatReportAffairsApi', ['type' => $type]) }}"
        }

        window.addEventListener('DOMContentLoaded', (event) => {
            window.changeHoursInputs();
            Swal.fire({
                html: "<h4>جاري جلب البيانات</h4>",
                timerProgressBar: true,
                showClass: {
                    popup: '',
                    icon: ''
                },
                hideClass: {
                    popup: '',
                },
                didOpen: () => {
                    Swal.showLoading();
                },
            });
        });

    </script>
@stop
