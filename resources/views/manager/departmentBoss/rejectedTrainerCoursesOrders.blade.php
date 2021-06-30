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
                                    <th>ساعات الإختبار</th>
                                    <th>سبب الرفض</th>
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
                        <button type="button" class="btn btn-success" onclick="acceptRejectedTrainerCourseOrder()">قبول</button>
                    </div>
                </div>
            </div>
        </div>

        <div dir="ltr" class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">
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
                            <div class="form-group col-md-6" style="display:block">
                                <label for="countOfStudents">عدد المتدربين</label>
                                <input type="text" class="form-control" id="countOfStudents"
                                    aria-describedby="countOfStudents">
                            </div>
                            <div class="form-group col-md-6" style="display:block">
                                <label for="divisionNumber">رقم الشعبة</label>
                                <input required type="text" class="form-control" id="divisionNumber"
                                    aria-describedby="divisionNumber">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary mr-auto" data-dismiss="modal">الغاء</button>
                        <button onclick="editTrainerCourseOrder()" class="btn btn-primary btn-md">حفظ</button>
                    </div>
                </div>
            </div>
        </div>

        <div dir="ltr" class="modal fade" id="editExamHoursModal" tabindex="-1" role="dialog" aria-labelledby="editExamHoursModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-md" role="document">
                <div class="modal-content">
                    <div dir="rtl" class="modal-header">
                        <h5 class="modal-title" id="editModalLabel">تعديل ساعات الاختبار</h5>
                        <button style="margin:0px; padding: 0px;" type="button" class="close" data-dismiss="modal"
                            aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div dir="rtl" class="modal-body">
                        <div class="row">
                            <div class="form-group col-md-6" style="display:block">
                                <label for="oldExamHours">ساعات الاختبار الحالية</label>
                                <input disabled type="text" class="form-control" id="oldExamHours"
                                    aria-describedby="oldExamHours">
                            </div>
                            <div class="form-group col-md-6" style="display:block">
                                <label for="newExamHours">ساعات الاختبار الجديدة</label>
                                <input required type="text" class="form-control" id="newExamHours"
                                    aria-describedby="newExamHours">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary mr-auto" data-dismiss="modal">الغاء</button>
                        <button onclick="editExamHours()" class="btn btn-primary btn-md">حفظ</button>
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
                                <textarea required type="text" class="form-control" id="rejectReason"
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
                                <td>{{ $user->trainer->computer_number ?? 'لا يوجد' }} </td>
                                <td>{{ $user->trainer->department->name ?? 'لا يوجد' }} </td>
                                <td>{{ $user->trainer->qualification ?? 'لا يوجد' }} </td>
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
            axios.get(`/api/department-boss/get-rejected-courses/${trainer_id}`)
                .then((response) => {
                    Swal.close();
                    window.trainer_id = trainer_id;
                    response.data.orders.forEach(order => {
                        var row = tblOrders.insertRow(0);
                        row.id = order.id;

                        var index = 0;
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

                        var contact_hours = row.insertCell(index++);
                        contact_hours.innerHTML = order.course_type == 'نظري' ? Math.ceil(order.course.contact_hours/2) : Math.floor(order.course.contact_hours/2);

                        var exam_hours = row.insertCell(index++);
                        exam_hours.innerHTML = '<span>'+(order.course_type == 'نظري' ? order.course.exam_theoretical_hours : order.course.exam_practical_hours) + '</span>'
                            + `<i class="fa fa-edit fa-sm text-primary btn" onclick="showEditExamHoursModal(event, ${order.course.id}, '${order.course_type}', '${order.course.code}')"></i>`;

                        var reject_reason = row.insertCell(index++);
                        reject_reason.innerHTML = order.community_note;

                        var actions = row.insertCell(index++);
                        actions.innerHTML =
                            `<p data-target="#editModal" data-toggle="modal" class="btn btn-primary btn-sm" onclick="countOfStudents.value=${order.count_of_students}; divisionNumber.value=${order.division_number}; order_id=${order.id}">تعديل</p>
                             <p data-target="#rejectModal" data-toggle="modal" class="btn btn-outline-danger btn-sm" onclick="window.order_id = ${order.id}">رفض</p>`;
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

        function acceptRejectedTrainerCourseOrder() {

            var orders = [];

            Array.from(tblOrders.children).forEach(row => {
                orders.push({
                    order_id: row.id,
                    count_of_students: row.children[4].firstChild.data,
                    division_number: row.children[5].firstChild.data,
                });
            });
            
            axios.post('{{ route('acceptRejectedTrainerCourseOrder') }}', {orders:orders})
                .then((response) => {
                    Swal.fire({
                        position: "center",
                        html: "<h4>" + response.data.message + "</h4>",
                        icon: "success",
                        showConfirmButton: false,
                        timer: 500,
                    });
                    $('#ordersModal').modal('hide');
                    document.getElementById(window.trainer_id).remove();
                })
                .catch((error) => {
                    console.log(error.response);
                    Swal.fire({
                        position: "center",
                        html: "<h4>" + error.response.data + "</h4>",
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
                    axios.post('{{ route('rejectTrainerCourseOrder') }}', {order_id:order_id, note: window.rejectReason.value})
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

        function editTrainerCourseOrder() {
            
            Swal.fire({
                position: "center",
                html: "<h4>تم تعديل الطلب</h4>",
                icon: "success",
                showConfirmButton: false,
                timer: 500,
            });
            var row = document.getElementById(order_id);
            count_of_students = row.children[4];
            division_number = row.children[5];
            count_of_students.innerHTML = countOfStudents.value;
            division_number.innerHTML = divisionNumber.value;
            $('#editModal').modal('hide');
        }

        function showEditExamHoursModal(event, course_id, course_type, course_code) {
            window.oldExamHours.value = event.target.parentNode.firstChild.innerHTML;
            window.course_id = course_id;
            window.course_type = course_type;
            window.course_code = course_code;
            console.log();
            $('#editExamHoursModal').modal();
        }

        function editExamHours() {
            Swal.fire({
                title: ' هل انت متأكد ؟',
                text: " سيتم تطبيق التغيرر على هذا المقرر في جميع الطلبات",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'نعم',
                cancelButtonText: 'الغاء',
            }).then((result) => {
                if (result.isConfirmed) {

                    axios.post('{{ route('deptBossEditExamHours') }}', {
                            id:window.course_id,
                            course_type: window.course_type,
                            exam_hours: window.newExamHours.value,
                        })
                        .then((response) => {
                            //FIXME: edit course in other orders hours by javascript
                            Array.from(tblOrders.children).forEach(row => {
                                if(row.children[2].innerHTML == window.course_code){
                                    row.children[8].firstChild.innerHTML = newExamHours.value;
                                }
                            });
                            Swal.fire({
                                position: "center",
                                html: "<h4>" + response.data.message + "</h4>",
                                icon: "success",
                                showConfirmButton: false,
                                timer: 1000,
                            });
                            $('#editExamHoursModal').modal('hide');
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
