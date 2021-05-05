@extends('layouts.app')
@section('content')
<div class="container-fluid">
    @if (isset($error) || !empty($fetch_errors))
        <div class="alert alert-danger">
            @if (isset($error))
                {{ $error }}
            @endif
            @if (isset($fetch_errors))
                <p>حصل خطا في جلب بعض ملفات المتدربين:</p>
                @foreach ($fetch_errors as $err)
                    <ul>
                        <li>{{ $err }}</li>
                    </ul>
                @endforeach
            @endif
        </div>
    @endif
    <div dir="ltr" class="modal fade" id="acceptModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel"
        aria-hidden="true" data-backdrop="static">
        <div class="modal-dialog " role="document">
            <div class="modal-content">
                <div dir="rtl" class="modal-header">
                    <h5 class="modal-title" id="editModalLabel">قبول</h5>
                    <button style="margin:0px; padding: 0px;" type="button" class="close" data-dismiss="modal"
                        aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div dir="rtl" class="modal-body">
                    <form class="row">
                        <div class="form-group col-lg-6">
                            <label for="national_id">رقم الهوية</label>
                            <input type="text" class="form-control" id="national_id" aria-describedby="national_id"
                                disabled="true">
                        </div>
                        <div class="form-group col-lg-6">
                            <label for="name">الاسم</label>
                            <input type="text" class="form-control" id="sname" aria-describedby="name" disabled="true">
                        </div>
                        <div class="form-group col-lg-6">
                            <label for="amount">المبلغ</label>
                            <input type="text" class="form-control" id="amount" aria-describedby="amount" disabled>
                        </div>
                        <div class="form-group col-lg-6">
                            <label for="requestDate">تاريخ الطلب</label>
                            <input type="text" class="form-control" id="requestDate" aria-describedby="requestDate" disabled>
                        </div>
                        <div class="form-group col-lg">
                            <label for="range">فترة الطلب</label>
                            <select required class="form-control" name="range" id="range">
                              <option disabled>حدد الفترة</option>
                              <option value="1">قبل بداية التدريب</option>
                              <option value="0.6">قبل الاسبوع الرابع</option>
                              <option value="0">انتهت فترة الاسترداد</option>
                            </select>
                        </div>

                        <div class="form-group col-lg-12">
                            <label for="" class="col-form-label">ملاحظات</label>
                            <textarea class="form-control" id="acceptNote"></textarea>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary mr-auto" data-dismiss="modal">الغاء</button>
                    <button onclick="accept()" class="btn btn-primary btn-md">تم</button>
                </div>
            </div>
        </div>
    </div>

    <div dir="ltr" class="modal fade" id="rejectModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel"
        aria-hidden="true" data-backdrop="static">
        <div class="modal-dialog " role="document">
            <div class="modal-content">
                <div dir="rtl" class="modal-header">
                    <h5 class="modal-title" id="editModalLabel">رفض</h5>
                    <button style="margin:0px; padding: 0px;" type="button" class="close" data-dismiss="modal"
                        aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div dir="rtl" class="modal-body">
                    <form class="row">
                        <div class="form-group col-lg-12">
                            <label for="" class="col-form-label">ملاحظات</label>
                            <textarea class="form-control" id="rejectNote">انتهت فترة الاسترداد</textarea>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary mr-auto" data-dismiss="modal">الغاء</button>
                    <button onclick="reject()" class="btn btn-danger btn-md">رفض</button>
                </div>
            </div>
        </div>
    </div>

    <div class="table-responsive p-2 bg-white rounded border">
        <table class="table nowrap display cell-border" id="mainTable">
            <thead class="text-center">
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">رقم الهوية</th>
                    <th scope="col">الرقم التدريبي</th>
                    <th scope="col">الاسم</th>
                    <th scope="col">رقم الجوال</th>
                    <th scope="col">رقم الايبان</th>
                    <th scope="col">البنك</th>
                    <th scope="col">المبلغ</th>
                    <th scope="col">السبب</th>
                    <th scope="col">تاريخ الطلب</th>
                    <th scope="col">ملاحظات</th>
                    <th scope="col">قبول الطلب</th>
                </tr>
                {{-- <tr>
                    <th class="filterhead" scope="col"></th>
                    <th class="filterhead" scope="col"></th>
                    <th class="filterhead" scope="col"></th>
                    <th class="filterhead" scope="col"></th>
                    <th class="filterhead" scope="col"></th>
                    <th class="filterhead" scope="col"></th>
                    <th class="filterhead" scope="col"></th>
                    <th class="filterhead" scope="col"></th>
                    <th class="filterhead" scope="col"></th>
                    <th class="filterhead" scope="col"></th>
                    <th class="filterhead" scope="col"></th>
                    <th class="filterhead" scope="col"></th>
                </tr> --}}
            </thead>
            <tbody>
                @if (isset($refunds))
                    @forelse ($refunds as $refund)
                        {{-- @if (isset($refund->student->refunds)) --}}
                            @php
                            @endphp
                            <tr id="{{$refund->student->user->national_id ?? 0}}">
                                <th class="text-center" scope="row">{{ $loop->index + 1 ?? '' }}</th>
                                <td class="text-center">{{ $refund->student->user->national_id ?? 'لا يوجد' }} </td>
                                <td class="text-center">{{ $refund->student->rayat_id ?? 'لا يوجد' }} </td>
                                <td>{{ $refund->student->user->name ?? 'لا يوجد' }} </td>
                                <td class="text-center">{{ $refund->student->user->phone ?? 'لا يوجد' }} </td>
                                <td class="text-center">{{ $refund->IBAN ?? 'لا يوجد' }} </td>
                                <td class="text-center">{{ $refund->bank ?? 'لا يوجد' }} </td>
                                <td class="text-center">{{ $refund->amount ?? 'لا يوجد' }} </td>
                                @switch($refund->reason)
                                    @case('drop-out')
                                        <td>انسحاب</td>
                                        @break
                                    @case('graduate')
                                        <td>خريج</td>
                                        @break
                                    @case('exception')
                                        <td>استثناء</td>
                                        @break
                                    @default
                                        <td>لا يوجد</td>
                                @endswitch
                                <td>{{ $refund->created_at->toDateString() ?? 'لا يوجد' }} - {{ date_diff(date_create(date('y-m-d')), $refund->created_at)->format('منذ %d ايام') ?? 'لا يوجد' }}</td>
                                <td>{{ $refund->student_note ?? 'لا يوجد' }}</td>
                                <td class="text-center">
                                    <button class="btn btn-primary px-2 py-0" data-toggle="modal" data-target="#acceptModal" onclick="fillModal('{{ $refund->student->user->national_id ?? 0 }}','{{ $refund->id ?? 0 }}','{{ $refund->student->user->name ?? 0 }}','{{ $refund->amount ?? 0 }}')">قبول</button>
                                    <button class="btn btn-danger px-2 py-0" data-toggle="modal" data-target="#rejectModal" onclick="fillModal('{{ $refund->student->user->national_id ?? 0 }}','{{ $refund->id ?? 0 }}','{{ $refund->student->user->name ?? 0 }}','{{ $refund->amount ?? 0 }}')">رفض</button>
                                </td>
                                {{-- <td class="text-center">
                                    <button class="btn btn-primary px-2 py-0"
                                        onclick="accept('{{$refund->student->user->national_id}}', {{ $refund->id ?? 0 }}, event)">قبول</button>
                                    <button class="btn btn-danger px-2 py-0"
                                    onclick="reject('{{$refund->student->user->national_id}}', {{ $refund->id ?? 0 }}, event)">رفض</button>
                                </td> --}}


                            </tr>
                        {{-- @endif --}}
                    @empty
                    @endforelse
                @endif


            </tbody>
            <tfoot>
                <tr>
                    <th scope="col"></th>
                    <th scope="col"></th>
                    <th scope="col"></th>
                    <th scope="col"></th>
                    <th scope="col"></th>
                    <th scope="col"></th>
                    <th scope="col"></th>
                    <th scope="col"></th>
                    <th scope="col"></th>
                    <th scope="col"></th>
                    <th scope="col"></th>
                    <th scope="col"></th>

                </tr>
            </tfoot>
        </table>
    </div>
    <script defer>
        var refundOrdersUpdate = "{{ route('apiRefundOrdersUpdate') }}";

    </script>
</div>
@endsection