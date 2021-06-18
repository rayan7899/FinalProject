@extends('layouts.app')
@section('content')
    <div style="text-align: right !important" dir="rtl" lang="ar" class="container">
        @if (session()->has('success'))
            <div class="alert alert-success">
                {{ session()->get('success') }}
            </div>
        @endif
        @if (session()->has('error'))
            <div class="alert alert-danger">
                {{ session()->get('error') }}
            </div>
        @endif
        @if (session()->has('updatedCount'))
            @if (session()->get('hasReport'))
                <a class="btn btn-primary my-2" href="{{ route('excelReport',['filename' => session()->get('reportExcelFileName')]) }}" role="button"> تنزيل التقرير (excel) </a>
            @endif
        <div class="alert alert-success">
            <strong>
                تم معالجة بيانات {{ session()->get('updatedCount') }} من
                {{ session()->get('countOfStudents') }} متدرب
            </strong>
                <p class="p-1 m-1">
                    <b>{{ session()->get('notRegesterd') }} </b> متدرب/متدربين 
                     لا يوجد لديهم ساعات لهذا الفصل التدريبي
                </p>
                <p class="p-1 m-1">
                    <b>{{ session()->get('updatedBefore') }} </b> متدرب/متدربين 
                     تم تحديث الساعات المعتمدة مسبقاً
                </p>

                <p class="p-1 m-1">
                    <b>{{ session()->get('equal') }} </b> متدرب/متدربين
                    الساعات المعتمدة مساوية لساعات المضافة
                </p>

                <p class="p-1 m-1">
                    <b>{{ session()->get('restoreCount') }} </b> متدرب/متدربين
                    الساعات المعتمدة اقل من الساعات المضافة
                    (استرداد المبلغ الى المحفظة)
                </p>

                <p class="p-1 m-1">
                    <b>{{ session()->get('addCount') }} </b> متدرب/متدربين 
                    الساعات المعتمدة اكثر من الساعات المضافة
                    (خصم المبلغ من المحفظة)
                </p>
                

            </div>
        @endif
        @if(session()->has('deletedWaitingCount'))
            @if( session()->get('deletedWaitingCount') > 0)
            <div class="alert alert-info" role="alert">
                تم حذف <b>{{ session()->get('deletedWaitingCount') }} </b>  
                طلب معلق
            </div>
            @endif
        @endif
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $message)
                        <li>{{ $message }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        @if (session()->has('errorsArr'))
            @if (!session()->has('hasOtherMessage'))
                <div class="alert alert-danger" role="alert">
                    <b>{{ count(session()->get('errorsArr')) }} </b>
                    متدرب/متدربين يوجد اخطاء في بياناتهم
                </div>
            @endif
            <table class="table table-sm table-hover bg-white">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">الاسم</th>
                        <th scope="col">رقم الهوية</th>
                        <th scope="col">الحالة</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse (session()->get('errorsArr') as $error)
                        <tr>
                            <td scope="row">{{ $loop->index + 1 }}</td>
                            <td>{{ $error['userinfo']['name'] ?? 'null' }}</td>
                            <td>{{ $error['userinfo']['national_id'] ?? 'null' }}</td>
                            <td class="text-danger"> {{ $error['message'] ?? 'null' }} </td>
                        </tr>
                    @empty
                    @endforelse
                </tbody>
            </table>
        @endif

    {{-- @if (session()->has('waitingInfo'))
            <div class="alert alert-warning" role="alert">
                <b>{{ session()->get('waitingCount') }} </b>
                متدرب/متدربين لديهم طلبات اضافة مقررات قيد المراجعة
            </div>
            <table class="table table-sm table-hover bg-white">
            <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">الاسم</th>
                    <th scope="col">رقم الهوية</th>
                    <th scope="col">الحالة</th>
                </tr>
            </thead>
            <tbody>
                @forelse (session()->get('waitingInfo') as $error)
                    <tr>
                        <td scope="row">{{ $loop->index + 1 }}</td>
                        <td>{{ $error['userinfo']['name'] ?? 'null' }}</td>
                        <td>{{ $error['userinfo']['national_id'] ?? 'null' }}</td>
                        <td class="text-danger"> {{ $error['message'] ?? 'null' }} </td>
                    </tr>
                @empty
                @endforelse
            </tbody>
            </table>
    @endif --}}
        @if (session()->has('restoreInfo'))
            <div class="alert alert-info" role="alert">
                <strong> استرداد الى المحفظة: </strong>
                الساعات المعتمدة اقل من الساعات المضافة لعدد
                {{ session()->get('restoreCount') }}
                من المتدربين
            </div>
            <table class="table table-sm table-hover bg-white">
                <thead>
                    <tr>
                        <th class="text-center" scope="col">#</th>
                        <th class="text-center" scope="col">الاسم</th>
                        <th class="text-center" scope="col">رقم الهوية</th>
                        <th class="text-center" scope="col">الحالة</th>
                        <th class="text-center" scope="col">الساعات المستردة</th>
                        <th class="text-center" scope="col">المبلغ المسترد </th>
                        <th class="text-center" scope="col">الساعات المعتمدة</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse (session()->get('restoreInfo') as $info)
                        <tr>
                            <td class="text-center" scope="row">{{ $loop->index + 1 }}</td>
                            <td class="text-center"> {{ $info['name'] ?? 'null' }}</td>
                            <td class="text-center"> {{ $info['national_id'] ?? 'null' }}</td>
                            <td class="text-center"> {{ $info['traineeState'] ?? 'null' }}</td>
                            <td class="text-center"> {{ $info['hours'] ?? 'null' }} </td>
                            <td class="text-center"> {{ $info['amount'] ?? 'null' }} </td>
                            <td class="text-center"> {{ $info['creditHours'] ?? 'null' }} </td>
                        </tr>
                    @empty
                    @endforelse
                </tbody>
            </table>
        @endif
        @if (session()->has('addInfo'))
            <div class="alert alert-info" role="alert">
                <strong> خصم من المحفظة: </strong>
                الساعات المعتمدة اكثر من الساعات المضافة لعدد
                {{ session()->get('addCount') }}
                للمتدربين في الجدول التالي
            </div>
            <table class="table table-sm table-hover bg-white">
                <thead>
                    <tr>
                        <th class="text-center" scope="col">#</th>
                        <th class="text-center" scope="col">الاسم</th>
                        <th class="text-center" scope="col">رقم الهوية</th>
                        <th class="text-center" scope="col">الحالة</th>
                        <th class="text-center" scope="col">الساعات المضافة </th>
                        <th class="text-center" scope="col">المبلغ المخصوم </th>
                        <th class="text-center" scope="col">الساعات المعتمدة</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse (session()->get('addInfo') as $info)
                        <tr>
                            <td class="text-center" scope="row">{{ $loop->index + 1 }}</td>
                            <td class="text-center"> {{ $info['name'] ?? 'null' }}</td>
                            <td class="text-center"> {{ $info['national_id'] ?? 'null' }}</td>
                            <td class="text-center"> {{ $info['traineeState'] ?? 'null' }}</td>
                            <td class="text-center"> {{ $info['hours'] ?? 'null' }} </td>
                            <td class="text-center"> {{ $info['amount'] ?? 'null' }} </td>
                            <td class="text-center"> {{ $info['creditHours'] ?? 'null' }} </td>
                        </tr>
                    @empty
                    @endforelse
                </tbody>
            </table>
        @endif
        <div class="card">
            <div class="card-header">
                <h6> تحديث الساعات المعتمدة</h6>
            </div>
            <div class="card-body">
                <form id="excel_form" class="form" method="POST" action="{{ route('UpdateCreditHoursStore') }}"
                    enctype="multipart/form-data">
                    @csrf
                    <div class="form-group">
                        <label for="excel_file">أختر الملف</label>
                        <input required type="file" accept=".xls,.xlsx,.ods" class="form-control-file" id="excel_file"
                            name="excel_file">
                    </div>
                    <div class="form-group">
                        <button type="button" name="excel_submit" id="excel_submit"
                            class="btn btn-sm btn-primary">أرسال</button>
                        @error('excel_file')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </form>
            </div>
        </div>
    @stop
