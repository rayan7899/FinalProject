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
                        <button onclick="privateDocDecision('accept','modal')" class="btn btn-primary btn-md">قبول</button>
                        <button onclick="privateDocDecision('reject','modal')" class="btn btn-danger btn-md">رفض</button>
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
                        <th scope="col">الاسم</th>
                        <th scope="col">رقم الجوال</th>
                        <th scope="col">البرنامج</th>
                        <th scope="col">القسم</th>
                        <th scope="col">التخصص</th>
                        <th scope="col">الحالة</th>
                        <th scope="col">وثائق الحالة</th>
                        <th scope="col">حالة التدقيق</th>
                        <th scope="col">ملاحظات المدقق</th>
                        <th scope="col"> </th>
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
                        <th class="filterhead" scope="col"></th>
                    </tr>
                </thead>
                <tbody>
                    @if (isset($users))
                        @forelse ($users as $user)
                            <tr id="{{$user->national_id ?? 0}}"> 
                                <th class="text-center" scope="row">{{ $loop->index + 1 ?? '' }}</th>
                                <td class="text-center">{{ $user->national_id ?? 'لا يوجد' }} </td>
                                <td>{{ $user->name ?? 'لا يوجد' }} </td>
                                <td class="text-center">{{ $user->phone ?? 'لا يوجد' }} </td>
                                <td class="text-center">{{ $user->student->program->name ?? 'لا يوجد' }} </td>
                                <td class="text-center">{{ $user->student->department->name ?? 'لا يوجد' }} </td>
                                <td class="text-center">{{ $user->student->major->name ?? 'لا يوجد' }} </td>
                                <td class="text-center">{{ __($user->student->traineeState) ?? 'لا يوجد' }} </td>
                                <td class="text-center">
                                    @forelse ($user->student->docs as $doc)
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
                                                <img src=" {{ asset('/images/camera_img_icon.png') }}" style="width:25px;"
                                                    alt="Image File">
                                            </a>
                                        @endif

                                    @empty
                                        لايوجد
                                    @endforelse
                                </td>
                                <td class="text-center">
                                   <button onclick="window.privateDocDecision('accept','direct',{{$user->national_id ?? 0}},{{$user->student->order->id ?? 0}},event)" class="btn btn-primary btn-sm">قبول</button>
                                   <button onclick="window.privateDocDecision('reject','direct',{{$user->national_id ?? 0}},{{$user->student->order->id ?? 0}},event)" class="btn btn-danger btn-sm">رفض</button>
                                </td>
                                <td id="note_{{ $user->national_id }}">{{ $user->student->order->note ?? '' }} </td>
                                <td class="text-center">
                                    <a data-toggle="modal" data-target="#editModal" href="#"
                                        onclick="window.showPrivateModal('{{ $user->national_id ?? ''}}','{{ $user->name ?? ''}}','{{ $user->student->order->id ?? 0 }}','{{$user->student->order->note ?? ''}}',event)">
                                        <img style="width: 20px" src="{{ asset('/images/edit.png') }}" />
                                    </a>
                                </td>
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
