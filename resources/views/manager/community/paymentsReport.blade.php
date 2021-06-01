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
        <div dir="ltr" class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel"
            aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div dir="rtl" class="modal-header">
                        <h5 class="modal-title" id="editModalLabel">تعديل</h5>
                        <button style="margin:0px; padding: 0px;" type="button" class="close" data-dismiss="modal"
                            aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div dir="rtl" class="modal-body">
                        <form class="row">
                            <div id="amountFormGroup" class="form-group col-md-6" style="display:block">
                                <label for="oldAmount">المبلغ السابق</label>
                                <input disabled type="number" class="form-control" id="oldAmount" aria-describedby="oldAmount">
                            </div>
                            <div id="amountFormGroup" class="form-group col-md-6" style="display:block">
                                <label for="newAmount">المبلغ الجديد</label>
                                <input required type="number" class="form-control" id="newAmount" aria-describedby="newAmount">
                            </div>
                            <div class="form-group col-12">
                                <label for="note" class="col-form-label">ملاحظات المدقق</label>
                                <textarea class="form-control" id="note" name="note"></textarea>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary mr-auto" data-dismiss="modal">الغاء</button>
                        
                        <button id="rejectBtnModal" onclick="window.editAmount()"
                            class="btn btn-primary btn-md" style="display:block">تم</button>
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
                        <button onclick="window.rotateImg()" type="button" class="btn btn-primary">
                            تدوير الصورة

                            <i class="fa fa-rotate-right"></i>
                        </button>
                    </div>
                    <div class="modal-body">
                        <iframe id="pdfIfreme" src="" width="100%" height="600px"></iframe>
                        <div class="text-center" id="modalImageDev">
                            <img id="modalImage" src="" alt="image" class="img-fluid" />
                        </div>
                        <div class="text-center" id="oldReceipts"></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="table-responsive p-2 bg-white rounded border">
            <table class="table nowrap display cell-border" id="paymentsReportTbl">
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
                        <th scope="col">تاريخ الطلب</th>
                        <th scope="col">ايصال السداد</th>
                        <th scope="col">المبلغ المدفوع</th>
                        <th scope="col">الاجراء </th>
                        <th scope="col">الملاحظات </th>
                        <th scope="col"></th>
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
                        <th class="filterhead" scope="col"></th>

                    </tr>
                </thead>
                <tbody>
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
                        <th scope="col"></th>
                        <th scope="col"></th>

                    </tr>
                </tfoot>
            </table>
        </div>
        <script defer>
            var paymentVerified = "{{ route('paymentsReviewVerifiyDocs') }}";
            var paymentWithNote = "{{ route('paymentsReviewUpdate') }}";
            var paymentsReviewJson = "{{ route('paymentsReviewJson',['type' =>'report']) }}"
            var editOldPayment = "{{ route('editOldPayment') }}"
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

        </script>
    </div>
@stop
