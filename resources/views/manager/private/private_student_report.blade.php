@extends('layouts.app')
@section('content')
    <div class="container-fluid">
        <div dir="ltr" class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel"
            aria-hidden="true">
            <div class="modal-dialog " role="document">
                <div class="modal-content">
                    <div dir="rtl" class="modal-header">
                        <h5 class="modal-title" id="editModalLabel">تعديل</h5>
                        <button style="margin:0px; padding: 0px;" type="button" class="close" data-dismiss="modal"
                            aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div dir="rtl" class="modal-body">
                        <form>
                            <div class="form-group">
                                <label for="national_id">رقم الهوية</label>
                                <input type="text" class="form-control" id="national_id" aria-describedby="national_id"
                                    disabled="true">
                            </div>
                            <div class="form-group">
                                <label for="name">الاسم</label>
                                <input type="text" class="form-control" id="sname" aria-describedby="name" disabled="true">
                            </div>
                            <div class="form-group">
                                <label for="" class="col-form-label">ملاحظات المدقق</label>
                                <textarea class="form-control" id="note"></textarea>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary mr-auto" data-dismiss="modal">الغاء</button>
                        <button id="acceptBtnModal" onclick="privateDocDecision('accept','modal')"
                            class="btn btn-primary btn-md">قبول</button>
                        <button id="rejectBtnModal" onclick="privateDocDecision('reject','modal')"
                            class="btn btn-danger btn-md">رفض</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="pdfModal" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
            <div class="modal-dialog" style="max-width: 75%" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="pdfName"></h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <iframe id="pdfIfreme" src="" width="100%" height="600px"></iframe>
                        <div class="text-center">
                            <img id="modalImage" src="" alt="image" class="img-fluid" />
                        </div>
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
                        <th scope="col">البرنامج</th>
                        <th scope="col">القسم</th>
                        <th scope="col">التخصص</th>
                        <th scope="col">الحالة</th>
                        <th scope="col">وثائق الحالة</th>
                        <th scope="col">الاجراء</th>
                    </tr>
                    <tr>
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
                    </tr>
                </thead>
                <tbody>
                    @if (isset($orders))
                        @forelse ($orders as $order)
                            <tr>
                                <th class="text-center" scope="row">{{ $loop->index + 1 ?? '' }}</th>
                                <td class="text-center">{{ $order->student->user->national_id ?? 'لا يوجد' }} </td>
                                <td class="text-center">{{ $order->student->rayat_id ?? 'لا يوجد' }} </td>
                                <td>{{ $order->student->user->name ?? 'لا يوجد' }} </td>
                                <td class="text-center">{{ $order->student->user->phone ?? 'لا يوجد' }} </td>
                                <td class="text-center">{{ $order->student->program->name ?? 'لا يوجد' }} </td>
                                <td class="text-center">{{ $order->student->department->name ?? 'لا يوجد' }} </td>
                                <td class="text-center">{{ $order->student->major->name ?? 'لا يوجد' }} </td>
                                <td class="text-center">{{ __($order->student->traineeState) ?? 'لا يوجد' }} </td>
                                <td class="text-center">
                                    @forelse ($order->student->docs as $doc)
                                        @php
                                            $splitByDot = explode('.', $doc);
                                            $fileExtantion = end($splitByDot);
                                        @endphp
                                        @if ($fileExtantion == 'pdf' || $fileExtantion == 'PDF')
                                            <a data-toggle="modal" data-target="#pdfModal" href="#"
                                                onclick="showPdf('{{ route('GetStudentDocument', ['path' => $doc]) }}','pdf')">
                                                <img style="width: 20px" src="{{ asset('/images/pdf.png') }}" />
                                            </a>
                                        @else
                                            <a data-toggle="modal" data-target="#pdfModal" href="#"
                                                onclick="showPdf('{{ route('GetStudentDocument', ['path' => $doc]) }}','img')">
                                                <img src=" {{ asset('/images/camera_img_icon.png') }}"
                                                    style="width:25px;" alt="Image File">
                                            </a>
                                        @endif

                                    @empty
                                        لايوجد
                                    @endforelse
                                </td>
                                @php
                                    if ($order->private_doc_verified == true || $order->private_doc_verified == 1) {
                                        $status = 'مقبول';
                                        $class = 'text-success';
                                    } else {
                                        $status = 'مرفوض';
                                        $class = 'text-danger';
                                    }
                                @endphp
                                <td class="text-center {{ $class }}">{{ $status ?? 'لا يوجد' }} </td>
                            </tr>

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
                    </tr>
                </tfoot>
            </table>
        </div>
        <script defer>
            var privateDocDecisionRoute = "{{ route('privateDocDecision') }}";

        </script>
    </div>
@stop
