@extends('layouts.app')
@section('content')
    <div class="mx-5">
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
                        <button onclick="sendStudentUpdate()" class="btn btn-primary">حفظ</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="table-responsive-lg">
            <table class="table table-sm table-bordered table-striped table-hover w-100">
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
                </thead>
                <tbody>
                    @if (isset($users))
                        @forelse ($users as $user)
                            <tr>
                                <th scope="row">{{ $loop->index + 1 ?? '' }}</th>
                                <td>{{ $user->national_id ?? 'لا يوجد' }} </td>
                                <td>{{ $user->name ?? 'لا يوجد' }} </td>
                                <td>{{ $user->phone ?? 'لا يوجد' }} </td>
                                <td>{{ $user->student->program->name ?? 'لا يوجد' }} </td>
                                <td>{{ $user->student->department->name ?? 'لا يوجد' }} </td>
                                <td>{{ $user->student->major->name ?? 'لا يوجد' }} </td>
                                <td>{{ __($user->student->traineeState) ?? 'لا يوجد' }} </td>

                                <td class="text-center">
                                    @forelse ($user['docs'] as $doc)
                                        @php
                                            $splitByDot = explode('.', $doc);
                                            $fileExtantion = end($splitByDot);
                                        @endphp
                                        @if ($fileExtantion == 'pdf' || $fileExtantion == 'PDF')
                                            <a class="d-block" target="_blank"
                                                href="{{ route('GetStudentDocument', ['path' => $doc]) }}">
                                                <img src=" {{ asset('/images/pdf.png') }}" style="width:25px;" alt="PDF File">
                                            </a>
                                        @else
                                            <a class="d-block" target="_blank"
                                                onclick=" Swal.fire({ imageUrl: '{{ route('GetStudentDocument', ['path' => $doc]) }}',confirmButtonText:'اغلاق', imageAlt: ''})">
                                                <img src=" {{ asset('/images/camera_img_icon.png') }}" style="width:25px;"
                                                    alt="Image File">
                                            </a>
                                        @endif

                                    @empty
                                        لايوجد
                        @endforelse
                        </td>
                        <td class="text-center">
                            <input id="check_{{ $user->national_id }}" type="checkbox"
                                onchange="checkChanged('{{ $user->national_id }}',event)" class="custom-checkbox"
                                style="width: 16px; height: 16px;"
                                {{ $user->student->documents_verified == true ? 'checked' : '' ?? '' }}
                                value="{{ $user->student->documents_verified }}">
                        </td>
                        <td id="note_{{ $user->national_id }}">{{ $user->student->note ?? '' }} </td>
                        <td>
                            <a data-toggle="modal" data-target="#editModal" href="#"
                                onclick="showModal('{{ $user->national_id }}','{{ $user->name }}','{{ $user->student->wallet }}','{{ $user->student->note }}')">
                                <img style="width: 20px" src="{{ asset('/images/edit.png') }}" />
                            </a>
                        </td>
                        </tr>
                    @empty
                        <td colspan="12">لا يوجد بيانات</td>
                    @endforelse
                    @endif
                </tbody>
            </table>

            <div class="text-right">
                <input type="submit" value="ارسال" class="btn btn-primary px-5">
            </div>
        </div>
        <script>
            var national_id = document.getElementById('national_id');
            var sname = document.getElementById('sname');
            var wallet = document.getElementById('wallet');
            var documents_verified = document.getElementById('documents_verified');
            var note = document.getElementById('note');

            function popup() {
                $('[data-toggle="popover"]').popover({
                    html: true
                });
            }

            function showModal(national_id, name, wallet, note) {
                this.documents_verified.checked = document.getElementById('check_' + national_id).checked;
                this.national_id.value = national_id;
                this.sname.value = name;
                this.wallet.value = wallet;
                this.note.innerHTML = note;

            }






            function sendStudentUpdate() {
                console.log("ok")
                let national_id = this.national_id.value;
                let documents_verified = this.documents_verified.checked;
                let wallet = this.wallet.value;
                let note = this.note.value;
                if (wallet == '') {
                    wallet = 0;
                }
                let token = "{{ csrf_token() }}";
                if (documents_verified) {
                    documents_verified = 1;
                } else {
                    documents_verified = 0;
                }
                let form = {
                    "_token": token,
                    "national_id": national_id,
                    "documents_verified": documents_verified,
                    "wallet": wallet,
                    "note": note,
                };
                Swal.fire({
                    html: '<h4>جاري تحديث البيانات</h4>',
                    timerProgressBar: true,
                    didOpen: () => {
                        Swal.showLoading()
                    }
                });
                $.ajax({
                    type: "post",
                    url: "{{ route('StudentAjaxUpdate') }}",
                    data: form,
                    headers: {
                        Accept: "application/json",
                        ContentType: "application/json"
                    },
                    dataType: "json",
                    success: function(response) {
                        document.getElementById('wallet_' + national_id).innerHTML = wallet;
                        document.getElementById('note_' + national_id).innerHTML = note;
                        document.getElementById('check_' + national_id).checked = documents_verified;

                        const message = response.message
                        Swal.fire({
                            position: 'center',
                            html: '<h4> تم تحديث البيانات بنجاح</h4>',
                            icon: 'success',
                            showConfirmButton: false,
                            timer: 1700
                        })
                        $('#editModal').modal('hide');
                    },
                    error: function(response) {
                        const message = response.responseJSON.message
                        Swal.fire({
                            position: 'center',
                            icon: 'error',
                            title: message,
                            showConfirmButton: true,
                        })
                    }
                });
                Swal.close()
            }






            function checkChanged(national_id, event) {
                let documents_verified = 0;
                if (event.target.checked) {
                    documents_verified = 1;
                } else {
                    documents_verified = 0;
                }

                Swal.fire({
                    html: '<h4>جاري تحديث البيانات</h4>',
                    timerProgressBar: true,
                    didOpen: () => {
                        Swal.showLoading()
                    }
                })
                $.ajax({
                    type: "post",
                    url: "{{ route('StudentUpdateDocsVerified') }}",
                    data: {
                        "_token": "{{ csrf_token() }}",
                        "national_id": national_id,
                        "documents_verified": documents_verified,
                    },
                    headers: {
                        Accept: "application/json",
                        ContentType: "application/json"
                    },
                    dataType: "json",
                    success: function(response) {
                        const message = response.message
                        Swal.fire({
                            position: 'center',
                            icon: 'success',
                            showConfirmButton: false,
                            timer: 1000
                        })
                    },
                    error: function(response) {
                        const message = response.responseJSON.message
                        Swal.fire({
                            position: 'center',
                            icon: 'error',
                            title: message,
                            showConfirmButton: true,
                        })
                    }
                });
                Swal.close()
            }

        </script>
    </div>
@stop
