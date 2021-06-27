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
                                <input type="text" class="form-control" id="national_id" name="national_id"
                                    aria-describedby="national_id" disabled="true">
                            </div>
                            <div class="form-group">
                                <label for="name">الاسم</label>
                                <input type="text" class="form-control" id="name" aria-describedby="name" disabled="true">
                            </div>
                            <div id="inputsGroup">
                                <div class="form-group">
                                    <label for="name">جهة العمل</label>
                                    <input type="text" class="form-control" id="employer" name="employer"
                                        aria-describedby="name">
                                </div>
                                <div class="form-group">
                                    <label for="qualification"> المؤهل </label>
                                    <select required name="qualification" id="qualification" class="form-control">
                                        <option value="" disabled selected>أختر</option>
                                        <option value="bachelor">{{ __('bachelor') }}</option>
                                        <option value="master">{{ __('master') }}</option>
                                        <option value="doctoral">{{ __('doctoral') }}</option>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group" id="noteDiv">
                                <label for="note" class="col-form-label">ملاحظات المدقق</label>
                                <textarea class="form-control" id="note" name="note"></textarea>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button id="acceptBtn" type="button" class="btn btn-primary"
                            onclick="sendRequest('edit')">ارسال</button>
                        <button id="rejectBtn" type="button" class="btn btn-danger"
                            onclick="sendRequest('reject')">رفض</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="table-responsive p-2 bg-white rounded border">
            <table id="trainerDataReviewTbl" class="table nowrap display cell-border">
                <thead>
                    <tr>

                        <th class="text-center">#</th>
                        <th class="text-center">رقم الهوية</th>
                        <th class="text-center">الرقم الوظيفي</th>
                        <th class="text-center">الاسم </th>
                        <th class="text-center">جهة العمل </th>
                        <th class="text-center">القسم</th>
                        <th class="text-center">المؤهل</th>
                        <th class="text-center">المرفق</th>

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
                    </tr>
                </thead>
                <tbody>
                    @if (isset($users))
                        @forelse ($users as $user)
                            @php
                                $url = route('getTrainerDocument', ['national_id' => $user->national_id, 'filename' => 'degree']);
                                $files = Storage::disk('trainerDocuments')->files($user->national_id);
                                $filePath = $files[array_key_first(preg_grep('/degree/', $files))];
                                $ext = explode('.', $filePath);
                                $fileExtantion = end($ext);
                            @endphp
                            <tr id="{{ $user->national_id }}">
                                <th class="text-center" scope="row">{{ $loop->index + 1 ?? '' }}</th>
                                <td class="text-center">{{ $user->national_id ?? 'لا يوجد' }} </td>
                                <td class="text-center">{{ $user->trainer->bct_id ?? 'لا يوجد' }} </td>
                                <td>{{ $user->name ?? 'لا يوجد' }} </td>
                                <td class="text-center">{{ $user->trainer->employer ?? 'لا يوجد' }} </td>
                                <td class="text-center">{{ $user->trainer->department->name ?? 'لا يوجد' }} </td>
                                <td class="text-center">{{ __($user->trainer->qualification) ?? 'لا يوجد' }} </td>
                                <td class="text-center">
                                    @if ($fileExtantion == 'pdf' || $fileExtantion == 'PDF')
                                        <a data-toggle="modal" data-target="#pdfModal" href="#"
                                            onclick="showPdf('{{ $url }}','pdf')">
                                            <img style="width: 20px" src="{{ asset('/images/pdf.png') }}" />
                                        </a>
                                    @else
                                        <a data-toggle="modal" data-target="#pdfModal" href="#"
                                            onclick="showPdf('{{ $url }}','img')">
                                            <img src=" {{ asset('/images/camera_img_icon.png') }}" style="width:25px;"
                                                alt="Image File">
                                        </a>
                                    @endif
                                </td>
                      
                            </tr>
                        @empty
                            لايوجد
                        @endforelse
                    @endif
                </tbody>
            </table>
        </div>
    </div>
    <script>
        window.addEventListener('DOMContentLoaded', (event) => {
            // window.changeHoursInputs();
            // Swal.fire({
            //     html: "<h4>جاري جلب البيانات</h4>",
            //     timerProgressBar: true,
            //     showClass: {
            //         popup: '',
            //         icon: ''
            //     },
            //     hideClass: {
            //         popup: '',
            //     },
            //     didOpen: () => {
            //         Swal.showLoading();
            //     },
            // });
            // Swal.close();

            if (document.getElementById("trainerDataReviewTbl") != undefined && document.getElementById(
                    "trainerDataReviewTbl") != null) {
                let trainerDataReviewTbl = $('#trainerDataReviewTbl').DataTable({
                    ajax: window.rayatReportApi,
                    dataSrc: "data",
                    rowId: 'student.id',
                    columnDefs: [{
                        searchable: false,
                        orderable: false,
                        targets: 0
                    }],
                    // columns: [
                    //     {
                    //         data: null
                    //     },
                    //     {
                    //         data: "national_id"
                    //     },
                    //     {
                    //         data: "student.rayat_id"
                    //     },
                    //     {
                    //         data: "name",
                    //     },
                    //     {
                    //         data: "phone"
                    //     },
                    //     {
                    //         data: "student.program.name"
                    //     },
                    //     {
                    //         data: "student.department.name"
                    //     },
                    // ],
                    order: [
                        [0, "asc"]
                    ],
                    language: {
                        emptyTable: "ليست هناك بيانات متاحة في الجدول",
                        loadingRecords: "جارٍ التحميل...",
                        processing: "جارٍ التحميل...",
                        lengthMenu: "أظهر _MENU_ مدخلات",
                        zeroRecords: "لم يعثر على أية سجلات",
                        info: "إظهار _START_ إلى _END_ من أصل _TOTAL_ مدخل",
                        infoEmpty: "يعرض 0 إلى 0 من أصل 0 سجل",
                        infoFiltered: "(منتقاة من مجموع _MAX_ مُدخل)",
                        search: "ابحث:",
                        paginate: {
                            first: "الأول",
                            previous: "السابق",
                            next: "التالي",
                            last: "الأخير",
                        },
                        aria: {
                            sortAscending: ": تفعيل لترتيب العمود تصاعدياً",
                            sortDescending: ": تفعيل لترتيب العمود تنازلياً",
                        },
                        select: {
                            rows: {
                                _: "%d قيمة محددة",
                                0: "",
                                1: "1 قيمة محددة",
                            },
                            1: "%d سطر محدد",
                            _: "%d أسطر محددة",
                            cells: {
                                1: "1 خلية محددة",
                                _: "%d خلايا محددة",
                            },
                            columns: {
                                1: "1 عمود محدد",
                                _: "%d أعمدة محددة",
                            },
                        },
                        buttons: {
                            print: "طباعة",
                            copyKeys: "زر <i>ctrl</i> أو <i>⌘</i> + <i>C</i> من الجدول<br>ليتم نسخها إلى الحافظة<br><br>للإلغاء اضغط على الرسالة أو اضغط على زر الخروج.",
                            copySuccess: {
                                _: "%d قيمة نسخت",
                                1: "1 قيمة نسخت",
                            },
                            pageLength: {
                                "-1": "اظهار الكل",
                                _: "إظهار %d أسطر",
                            },
                            collection: "مجموعة",
                            copy: "نسخ",
                            copyTitle: "نسخ إلى الحافظة",
                            csv: "CSV",
                            excel: "Excel",
                            pdf: "PDF",
                            colvis: "إظهار الأعمدة",
                            colvisRestore: "إستعادة العرض",
                        },
                        autoFill: {
                            cancel: "إلغاء",
                            info: "مثال عن الملئ التلقائي",
                            fill: "املأ جميع الحقول بـ <i>%d&lt;\\/i&gt;</i>",
                            fillHorizontal: "تعبئة الحقول أفقيًا",
                            fillVertical: "تعبئة الحقول عموديا",
                        },
                        searchBuilder: {
                            add: "اضافة شرط",
                            clearAll: "ازالة الكل",
                            condition: "الشرط",
                            data: "المعلومة",
                            logicAnd: "و",
                            logicOr: "أو",
                            title: ["منشئ البحث"],
                            value: "القيمة",
                            conditions: {
                                date: {
                                    after: "بعد",
                                    before: "قبل",
                                    between: "بين",
                                    empty: "فارغ",
                                    equals: "تساوي",
                                    not: "ليس",
                                    notBetween: "ليست بين",
                                    notEmpty: "ليست فارغة",
                                },
                                number: {
                                    between: "بين",
                                    empty: "فارغة",
                                    equals: "تساوي",
                                    gt: "أكبر من",
                                    gte: "أكبر وتساوي",
                                    lt: "أقل من",
                                    lte: "أقل وتساوي",
                                    not: "ليست",
                                    notBetween: "ليست بين",
                                    notEmpty: "ليست فارغة",
                                },
                                string: {
                                    contains: "يحتوي",
                                    empty: "فاغ",
                                    endsWith: "ينتهي ب",
                                    equals: "يساوي",
                                    not: "ليست",
                                    notEmpty: "ليست فارغة",
                                    startsWith: " تبدأ بـ ",
                                },
                            },
                            button: {
                                0: "فلاتر البحث",
                                _: "فلاتر البحث (%d)",
                            },
                            deleteTitle: "حذف فلاتر",
                        },
                        searchPanes: {
                            clearMessage: "ازالة الكل",
                            collapse: {
                                0: "بحث",
                                _: "بحث (%d)",
                            },
                            count: "عدد",
                            countFiltered: "عدد المفلتر",
                            loadMessage: "جارِ التحميل ...",
                            title: "الفلاتر النشطة",
                        },
                        searchPlaceholder: "ابحث ...",
                    },
                    initComplete: function() {
                        Swal.close();
                        var api = this.api();
                        $(".filterhead", api.table().header()).each(function(i) {
                            if (false) {
                                var column = api.column(i);
                                var select = $(
                                        '<select><option value="">الكل</option></select>'
                                    )
                                    .appendTo($(this).empty())
                                    .on("change", function() {
                                        // FIXME: error dataTable undefined
                                        var val = $.fn.dataTable.util.escapeRegex(
                                            $(this).val()
                                        );

                                        column
                                            .search(
                                                val ? "^" + val + "$" : "",
                                                true,
                                                false
                                            )
                                            .draw();
                                    });

                                column
                                    .data()
                                    .unique()
                                    .sort()
                                    .each(function(d, j) {
                                        select.append(
                                            '<option value="' +
                                            d +
                                            '">' +
                                            d +
                                            "</option>"
                                        );
                                    });
                            }
                        });
                    },
                });

                trainerDataReviewTbl.on('order.dt search.dt', function() {
                    trainerDataReviewTbl.column(0, {
                        search: 'applied',
                        order: 'applied'
                    }).nodes().each(function(cell, i) {
                        cell.innerHTML = i + 1;
                    });
                }).draw();
            }
        });
    </script>
@stop
