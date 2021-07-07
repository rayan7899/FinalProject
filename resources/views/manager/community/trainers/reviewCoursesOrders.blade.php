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

        <div dir="ltr" class="modal fade" id="ordersModal" tabindex="-1" role="dialog" aria-labelledby="ordersModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-xl" role="document">
                <div class="modal-content">
                    <div dir="rtl" class="modal-header">
                        <h5 class="modal-title" id="editModalLabel">جميع الطلبات لهذا الفصل</h5>
                        <button style="margin:0px; padding: 0px;" type="button" class="close" data-dismiss="modal"
                            aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div dir="rtl" class="modal-body p-0 m-0 table-responsive">
                        <table class="table table-sm table-hover text-center p-0 m-0">
                            <thead>
                                <tr>
                                    <th>رقم الطلب</th>
                                    <th>اسم المقرر</th>
                                    <th>رمز المقرر</th>
                                    <th>نوع المقرر</th>
                                    <th>عدد المتدربين</th>
                                    <th>رقم الشعبة</th>
                                    <th>الساعات المعتمدة</th>
                                    <th>ساعات الاتصال</th>
                                    <th>عدد الاسابيع</th>
                                    <th>ساعات الاختبار</th>
                                    <th>ساعات الاتصال الفصلية</th>
                                    <th>اجمالي الساعات الفصلية</th>
                                    <th>أجر الساعة</th>
                                    <th>المبلغ المستحق</th>
                                    <th></th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody id="tblOrders">
                            </tbody>
                        </table>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary mr-auto" data-dismiss="modal">الغاء</button>
                        <button type="button" class="btn btn-success" onclick="acceptTrainerCourseOrder()">قبول</button>
                    </div>
                </div>
            </div>
        </div>

        <div dir="ltr" class="modal fade" id="rejectModal" data-backdrop="true" tabindex="-1" role="dialog" aria-labelledby="rejectModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-md" role="document">
                <div class="modal-content">
                    <div dir="rtl" class="modal-header">
                        <h5 class="modal-title" id="editModalLabel">تعديل</h5>
                        <button style="margin:0px; padding: 0px;" type="button" class="close" data-dismiss="modal"
                            aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div dir="rtl" class="modal-body">
                        <div class="row">
                            <div class="form-group col-md-12">
                                <label for="divisionNumber">سبب الرفض</label>
                                <textarea required type="number" class="form-control" id="rejectReason"
                                    aria-describedby="rejectReason"></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary mr-auto" data-dismiss="modal">الغاء</button>
                        <button onclick="rejectTrainerCourseOrder()" class="btn btn-danger btn-md">رفض</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="table-responsive p-0 bg-white rounded border">
            <table id="trainersInfo" class="table nowrap display cell-border">
                <thead>
                    <tr>
                        <th class="text-center">#</th>
                        <th class="text-center">رقم الهوية</th>
                        <th>اسم المدرب </th>
                        <th class="text-center">رقم الحاسب</th>
                        <th class="text-center">القسم</th>
                        <th class="text-center">المؤهل</th>
                        <th class="text-center"></th>
                    </tr>
                    <tr>
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
                            <tr id="{{$user->trainer->id}}">
                                <th scope="row">{{ $loop->index + 1 ?? '' }}</th>
                                <td>{{ $user->national_id ?? 'لا يوجد' }} </td>
                                <td>{{ $user->name ?? 'لا يوجد' }} </td>
                                <td>{{ $user->trainer->bct_id ?? 'لا يوجد' }} </td>
                                <td>{{ $user->trainer->department->name ?? 'لا يوجد' }} </td>
                                <td>{{ __($user->trainer->qualification) ?? 'لا يوجد' }} </td>
                                <td><i class="fa fa-list fa-lg text-primary btn"
                                        onclick="showTrainerOrders({{ $user->trainer->id }})"></i></td>
                            </tr>
                        @empty
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

            if (document.getElementById("trainersInfo") != undefined && document.getElementById(
                    "trainersInfo") != null) {
                let trainersInfo = $('#trainersInfo').DataTable({
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

                trainersInfo.on('order.dt search.dt', function() {
                    trainersInfo.column(0, {
                        search: 'applied',
                        order: 'applied'
                    }).nodes().each(function(cell, i) {
                        cell.innerHTML = i + 1;
                    });
                }).draw();
            }

            $('#rejectModal').on('hidden.bs.modal', function(){
                window.rejectReason.value = '';
            });
        });

        function showTrainerOrders(trainer_id) {

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
            
            tblOrders.innerHTML = '';
            axios.get(`/api/community/get-courses/${trainer_id}`)
                .then((response) => {
                    window.trainer_id = trainer_id;
                    Swal.close();
                    response.data.orders.forEach(order => {
                        var row = tblOrders.insertRow(0);
                        row.id = order.id;

                        var index=0;
                        var orderId = row.insertCell(index++);
                        orderId.innerHTML = order.id;

                        var courseName = row.insertCell(index++);
                        courseName.innerHTML = order.course.name;

                        var courseCode = row.insertCell(index++);
                        courseCode.innerHTML = order.course.code;

                        var courseType = row.insertCell(index++);
                        courseType.innerHTML = order.course_type;

                        var count_of_students = row.insertCell(index++);
                        count_of_students.innerHTML = order.count_of_students;

                        var division_number = row.insertCell(index++);
                        division_number.innerHTML = order.division_number;

                        var credit_hours = row.insertCell(index++);
                        credit_hours.innerHTML = order.course.credit_hours;

                        var cth = order.course_type == 'نظري' ? order.course.theoretical_hours : order.course.practical_hours;
                        var contact_hours = row.insertCell(index++);
                        contact_hours.innerHTML = order.semester.isSummer == true ? cth*2 : cth;

                        var count_of_weeks = row.insertCell(index++);
                        count_of_weeks.innerHTML = order.semester.count_of_weeks;

                        var exam_hours = row.insertCell(index++);
                        exam_hours.innerHTML = order.course_type == 'نظري' ? order.course.exam_theoretical_hours : order.course.exam_practical_hours;

                        var count_of_hours_weeks = row.insertCell(index++);
                        count_of_hours_weeks.innerHTML = contact_hours.innerHTML*order.semester.count_of_weeks;

                        var total_hours = row.insertCell(index++);
                        total_hours.innerHTML = parseInt(count_of_hours_weeks.innerHTML)+parseInt(exam_hours.innerHTML);

                        var hour_cost = row.insertCell(index++);
                        hour_cost.innerHTML = order.trainer.qualification == 'doctoral' ? 200 : 150;

                        var deserved_amount = row.insertCell(index++);
                        deserved_amount.innerHTML = total_hours.innerHTML*hour_cost.innerHTML;

                        var reject = row.insertCell(index++);
                        reject.innerHTML =
                            `<p data-target="#rejectModal" data-toggle="modal" class="btn btn-outline-danger btn-sm" onclick="window.order_id = ${order.id}">رفض</p>`;
                        // reject.innerHTML =
                        //     `<p class="btn btn-outline-danger btn-sm" onclick="rejectTrainerCourseOrder(${order.id})">رفض</p>`;
                    });
                    $('#ordersModal').modal();
                })
                .catch((error) => {
                    console.log(error);
                    Swal.fire({
                        position: "center",
                        html: "<h4>" + error.response + "</h4>",
                        icon: "error",
                        showConfirmButton: true,
                    });
                });
        }

        function acceptTrainerCourseOrder() {

            var orders = [];

            Array.from(tblOrders.children).forEach(row => {
                orders.push({
                    order_id: row.id,
                    // count_of_students: row.children[4].firstChild.data,
                    // division_number: row.children[5].firstChild.data,
                });
            });
            
            axios.post('{{ route('communityAcceptTrainerCourseOrder') }}', {orders:orders})
                .then((response) => {
                    Swal.fire({
                        position: "center",
                        html: "<h4>" + response.data.message + "</h4>",
                        icon: "success",
                        showConfirmButton: false,
                        timer: 500,
                    });
                    document.getElementById(window.trainer_id).remove();
                    $('#ordersModal').modal('hide');
                })
                .catch((error) => {
                    console.log(error.response);
                    Swal.fire({
                        position: "center",
                        html: "<h4>" + error.response.data.error + "</h4>",
                        icon: "error",
                        showConfirmButton: true,
                    });
                });
        }

        function rejectTrainerCourseOrder() {
            var order_id = window.order_id;
            Swal.fire({
                title: ' هل انت متأكد ؟',
                // text: " لا يمكن التراجع عن هذا الاجراء",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'نعم',
                cancelButtonText: 'الغاء',
            }).then((result) => {
                if (result.isConfirmed) {
                    axios.post('{{ route('communityRejectTrainerCourseOrder') }}', {
                            order_id: order_id,
                            note: window.rejectReason.value
                        })
                        .then((response) => {
                            document.getElementById(order_id).remove();
                            Swal.fire({
                                position: "center",
                                html: "<h4>" + response.data.message + "</h4>",
                                icon: "success",
                                showConfirmButton: false,
                                timer: 1000,
                            });
                            $('#rejectModal').modal('hide');
                        })
                        .catch((error) => {
                            Swal.fire({
                                position: "center",
                                html: "<h4>" + error.response.data.message + "</h4>",
                                icon: "error",
                                showConfirmButton: true,
                            });
                        });
                }
            });
        }

    </script>
@stop
