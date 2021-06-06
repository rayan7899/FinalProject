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

        <div dir="ltr" class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-md" role="document">
                <div class="modal-content">
                    <div dir="rtl" class="modal-header">
                        <h5 class="modal-title" id="editModalLabel">تعديل</h5>
                        <button style="margin:0px; padding: 0px;" type="button" class="close" data-dismiss="modal"
                            aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div dir="rtl" class="modal-body">
                        <table class="table hover">
                            <thead>
                                <tr>
                                    <th>رقم الطلب</th>
                                    <th>الساعات المطلوبة</th>
                                    {{-- <th>الساعات المقبولة</th> --}}
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody id="tblOrders">
                            </tbody>
                        </table>
                        
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary mr-auto" data-dismiss="modal">الغاء</button>
                        
                        {{-- <button id="rejectBtnModal" onclick="window.editAmount()"
                            class="btn btn-primary btn-md" style="display:block">تم</button> --}}
                    </div>
                </div>
            </div>
        </div>

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
                        <th class="text-center"></th>
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
