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
                                <label for="wallet">المبلغ المدفوع</label>
                                <input required type="number" class="form-control" id="wallet" aria-describedby="wallet">
                            </div>
                            <div class="form-group">
                                <label for="verified">حالة التدقيق</label>
                                <input type="checkbox" id="documents_verified"
                                    style="padding:10px; width: 20px; height: 20px;">
                            </div>

                            <div class="form-group">
                                <label for="" class="col-form-label">ملاحظات المدقق</label>
                                <textarea class="form-control" id="note"></textarea>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">الغاء</button>
                        <button onclick="window.sendStudentUpdate()" class="btn btn-primary">حفظ</button>
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
                        <img id="modalImage" src="" alt="image" class="img-fluid"/>
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
                        <th scope="col">ايصالات السداد</th>
                        <th scope="col">المبلغ المدفوع</th>
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
                                <td class="text-center">{{ __($user->student->traineeState) ?? 'لا يوجد' }} </td>

                                {{-- <td>
                    <a data-toggle="popover"  onclick="window.popup()" title="الايصالات" class="link p-0 m-0"
                        data-content='
                                @foreach ($user['receipts'] as $receipt)
                                    <a class="d-block" href="{{ route('GetStudentDocument',['path' => $receipt ]) }}">{{ substr($receipt, 20, 10) }}</a>
                                @endforeach
                            '>عرض الايصالات</a>
                </td> --}}
                                <td class="text-center">
                                    @forelse ($user['receipts'] as $receipt)
                                        @php
                                            $splitByDot = explode('.', $receipt);
                                            $fileExtantion = end($splitByDot);
                                        @endphp
                                        @if ($fileExtantion == 'pdf' || $fileExtantion == 'PDF')
                                            {{-- <a class="d-block" target="_blank"
                                                href="{{ route('GetStudentDocument', ['path' => $receipt]) }}">
                                                <img src=" {{ asset('/images/pdf.png') }}" style="width:25px;" alt="PDF File">
                                            </a> --}}
                                            <a data-toggle="modal" data-target="#pdfModal" href="#"
                                                onclick="showPdf('{{ route('GetStudentDocument', ['path' => $receipt]) }}','pdf')">
                                                <img style="width: 20px" src="{{ asset('/images/pdf.png') }}" />
                                            </a>
                                        @else
                                        {{-- <a class="d-block" target="_blank"
                                        onclick=" window.Swal.fire({ imageUrl: '{{ route('GetStudentDocument', ['path' => $receipt]) }}',confirmButtonText:'اغلاق', imageAlt: ''})"> --}}
                                        <a data-toggle="modal" data-target="#pdfModal" href="#"
                                        onclick="showPdf('{{ route('GetStudentDocument', ['path' => $receipt]) }}','img')">        
                                        <img src=" {{ asset('/images/camera_img_icon.png') }}" style="width:25px;"
                                                    alt="Image File">
                                            </a>
                                        @endif

                                    @empty
                                        لايوجد
                        @endforelse
                        </td>

                        <td class="text-center" id="wallet_{{ $user->national_id }}">
                            {{ $user->student->wallet ?? 'لا يوجد' }}
                        </td>


                        <td class="text-center">
                            @if ($user->student->final_accepted == 1)
                                مقبول نهائي
                            @else
                                <input id="check_{{ $user->national_id }}" type="checkbox"
                                    onchange="window.checkChanged('{{ $user->national_id }}',event)" class="custom-checkbox"
                                    style="width: 16px; height: 16px;"
                                    {{ $user->student->documents_verified == true ? 'checked' : '' ?? '' }}
                                    value="{{ $user->student->documents_verified }}">
                            @endif
                        </td>


                        <td id="note_{{ $user->national_id }}">{{ $user->student->note ?? '' }} </td>

                        <td class="text-center">
                            @if ($user->student->final_accepted == 1)

                            @else
                                <a data-toggle="modal" data-target="#editModal" href="#"
                                    onclick="window.showModal('{{ $user->national_id }}','{{ $user->name }}','{{ $user->student->wallet }}','{{ $user->student->note }}')">
                                    <img style="width: 20px" src="{{ asset('/images/edit.png') }}" />
                                </a>
                            @endif
                        </td>

                        </tr>
                    @empty
                        <td colspan="12">لا يوجد بيانات</td>
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
            var docsVerified = "{{ route('studentDocumentsReviewVerifiyDocs') }}";
            var studentUpdate = "{{ route('studentDocumentsReviewUpdate') }}";

            function showPdf(url,type) {
             
                if(type == "pdf"){
                    $("#modalImage").hide();
                    $("#pdfIfreme").show();
                    $("#pdfIfreme").attr("src","");
                    $("#pdfIfreme").attr("src",url);
                }else{
                    $("#pdfIfreme").hide();
                    $("#modalImage").show();
                    $("#modalImage").attr("src","");
                    $("#modalImage").attr("src",url);
                }
            }

        </script>
    </div>
@stop
