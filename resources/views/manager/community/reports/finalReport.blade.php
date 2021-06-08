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
                        <button onclick="printImg()" type="button" class="btn btn-primary mx-2">
                            <i class="fa fa-print"></i>
                        </button>
                    </div>
                    <div class="modal-body">
                        <iframe id="pdfIfreme" src="#" width="100%" height="600px"></iframe>
                        <iframe style="display: none; position: fixed;" id="imgIframe" width="100%" height="100%"></iframe>
                        <div class="text-center" id="modalImageDev">
                            <img id="modalImage" src="" alt="image" class="img-fluid" />
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="table-responsive p-2 bg-white rounded border">
            <table class="table nowrap display cell-border" id="finalReportTbl">
                <thead class="text-center">
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">رقم الهوية</th>
                        <th scope="col">الاسم</th>
                        <th scope="col">الحالة</th>
                        <th scope="col">تاريخ الطلب</th>
                        <th scope="col">ايصال السداد</th>
                        <th scope="col">المبلغ المدفوع</th>
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

                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>

        <script defer>
            var finalReviewReprotJson = "{{ route('finalReviewReprotJson') }}"
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
