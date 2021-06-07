@extends('layouts.app')
@section('content')
    <div class="container-fluid">
        @if (isset($error) || !empty($fetch_errors))
            <div class="alert alert-danger">
                @if (isset($error))
                    {{ $error }}
                @endif
                @if (isset($fetch_errors))
                    <p>حدث خطا في جلب بعض ملفات المتدربين:</p>
                    @foreach ($fetch_errors as $err)
                        <ul>
                            <li>{{ $err }}</li>
                        </ul>
                    @endforeach
                @endif
            </div>
        @endif
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
                            <div id="amountFormGroup" class="form-group" style="display:block">
                                <label for="amount">المبلغ المدفوع</label>
                                <input required type="number" class="form-control" id="amount" aria-describedby="amount">
                            </div>

                            {{-- <div class="form-group">
                                <label for="noteShortcuts">اختصارات الملاحظات</label>
                                <select onchange="fillNote()" class="form-control" id="noteShortcuts">
                                    <option value="0" selected disabled> اختر</option>
                                    <option value="wrong-receipt">الايصال غير معمتد</option>
                                    <option value="else">اخرى</option>
                                </select>
                            </div> --}}

                            <div class="form-group">
                                <label for="note" class="col-form-label">ملاحظات المدقق</label>
                                <div id="rejectMsgs">
                                    <span class="btn badge badge-pill badge-info" onclick="window.note.value = 'ايصال السداد غير معتمد. يجب رفع كشف مختوم من البنك بعملية الايداع أو التحويل.'">الايصال غير معمتد</span>
                                    <span class="btn badge badge-pill badge-info" onclick="window.note.value = 'الايصال غير واضح.'">الايصال غير واضح</span>
                                    <span class="btn badge badge-pill badge-info" onclick="window.note.value = 'تم استخدام الايصال مسبقًا.'">الايصال مستخدم</span>
                                </div>
                                <div id="acceptMsgs">
                                    <span class="btn badge badge-pill badge-info"
                                        onclick="window.note.value = `تم تعديل المبلغ الى ${window.amount.value} حسب الايصال`">تم
                                        تعديل المبلغ</span>
                                </div>
                                <textarea class="form-control" id="note"></textarea>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary mr-auto" data-dismiss="modal">الغاء</button>
                        <button id="acceptBtnModal" onclick="window.checkerSendDecisionWithNote('accept')"
                            class="btn btn-primary btn-md" style="display:block">قبول</button>
                        <button id="rejectBtnModal" onclick="window.checkerSendDecisionWithNote('reject')"
                            class="btn btn-danger btn-md" style="display:block">رفض</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="pdfModal" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-scrollable" style="max-width: 75%" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="pdfName"></h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        <button onclick="window.rotateImg()" type="button" class="btn btn-primary">
                            تدوير الصورة

                            <i class="fa fa-rotate-right"></i>
                        </button>
                        <button onclick="printImg()" type="button" class="btn btn-primary mx-2">
                            <i class="fa fa-print"></i>
                        </button>
                    </div>
                    <div class="modal-body">
                        <iframe id="pdfIfreme" src="#" width="100%" height="600px"></iframe>
                        <iframe style="display: none; position: fixed;" id="imgIframe"  width="100%" height="100%"></iframe>
                        <div class="text-center" id="modalImageDev">
                            <img id="modalImage" src="" alt="image" class="img-fluid" />
                            <h3 class="w-100 bg-secondary my-3 py-3">الايصالات السابقة</h3>
                        </div>
                        <div class="text-center" id="oldReceipts"></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="table-responsive p-2 bg-white rounded border">
            <table class="table nowrap display cell-border" id="checkerPaymentsReviewTbl">
                <thead class="text-center">
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">رقم الهوية</th>
                        <th scope="col">الاسم</th>
                        {{-- <th scope="col">رقم الجوال</th>
                        <th scope="col">البرنامج</th>
                        <th scope="col">القسم</th>
                        <th scope="col">التخصص</th> --}}
                        <th scope="col">الحالة</th>
                        <th scope="col">ايصال السداد</th>
                        <th scope="col">المبلغ المدفوع</th>
                        <th scope="col">الملاحظات </th>
                        <th scope="col">الاجراء </th>
                    </tr>
                    <tr>
                        <th class="filterhead" scope="col"></th>
                        <th class="filterhead" scope="col"></th>
                        <th class="filterhead" scope="col"></th>
                        {{-- <th class="filterhead" scope="col"></th>
                        <th class="filterhead" scope="col"></th>
                        <th class="filterhead" scope="col"></th>
                        <th class="filterhead" scope="col"></th> --}}
                        <th class="filterhead" scope="col"></th>
                        <th class="filterhead" scope="col"></th>
                        <th class="filterhead" scope="col"></th>
                        <th class="filterhead" scope="col"></th>
                        <th class="filterhead" scope="col"></th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
                <tfoot>
            </table>
        </div>
        <script defer>
            var checkerPaymentVerified = "{{ route('checkerPaymentsReviewVerifiyDocs') }}";
            var checkerPaymentWithNote = "{{ route('checkerPaymentsReviewUpdate') }}";
            var checkerPaymentsReviewJson = "{{ route('checkerPaymentsReviewJson', ['type' => 'review']) }}"
            window.addEventListener('DOMContentLoaded', (event) => {
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

            // function fillNote() {
            //     let noteShortcuts = document.getElementById("noteShortcuts").value;
            //     let note = "";
            //     switch (noteShortcuts) {
            //         case 'wrong-receipt':
            //             note =
            //                 "ايصال السداد غير معتمد. يجب رفع كشف مختوم من البنك بعملية الايداع أو التحويل."
            //             break;
            //         case 'else':
            //             note =
            //                 ""
            //             break;
            //     }
            //     document.getElementById("note").value = note; 
            // }

        </script>
    </div>
@stop
