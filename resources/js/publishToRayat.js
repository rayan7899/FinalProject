jQuery(function () {
    if (document.getElementById("publishToRayatTbl") != undefined && document.getElementById("publishToRayatTbl") != null) {
        let publishToRayatTbl = $('#publishToRayatTbl').DataTable({
            ajax: window.getStudentForRayatApi,
            dataSrc: "data",
            rowId: 'id',
            columnDefs: [{
                searchable: false,
                orderable: false,
                targets: 0
            }],
            columns: [{
                    data: null
                },
                {
                    data: "student.user.national_id",
                    className: "text-center",
                },
                {
                    data: "student.rayat_id",
                    className: "text-center",
                },
                {
                    data: "student.user.name",
                },
                {
                    data: "student.user.phone",
                    className: "text-center",
                },
                {
                    data: "id",
                    className: "text-center",

                },
                {
                    data: "student.program.name",
                    className: "text-center",
                },
                {
                    data: "student.department.name",
                    className: "text-center",
                },
                {
                    data: "student.major.name",
                    className: "text-center",
                },
                {
                    data: function (data) {
                        switch (data.student.traineeState) {
                            case 'employee':
                                return "منسوب ";
                                break;

                            case 'employeeSon':
                                return "ابن منسوب";
                                break;

                            case 'privateState':
                                return "ظروف خاصة";
                                break;

                                // case 'trainee':
                            default:
                                return "متدرب";
                                break;
                        }
                    },
                    className: "text-center",

                },
                {
                    data: "student.wallet",
                    className: "text-center",

                },


                {
                    data: "requested_hours",
                    className: "text-center",
                    render: function (requested_hours, type, row) {
                        return `<input type="number" min="1" max="${row.canAddHours}" class="p-0"
                    name="requested_hours" id="requested_hours"
                    value="${requested_hours}"><smal> الحد الاعلى: ${row.canAddHours}</smal>`;
                    }
                },


                {
                    data: "student",
                    className: "text-center",
                    render: function (student, type, row) {
                        return `<button class="btn btn-primary btn-sm px-3"
                    onclick="publishToRayatStore('${row.student.user.national_id}','${row.id}',event)">تم</button>`;
                    }

                },


            ],
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
            initComplete: function () {
                Swal.close();
                var api = this.api();
                $(".filterhead", api.table().header()).each(function (i) {
                    if (i > 5 && i < 10) {
                        var column = api.column(i);
                        var select = $(
                                '<select><option value="">الكل</option></select>'
                            )
                            .appendTo($(this).empty())
                            .on("change", function () {
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
                            .each(function (d, j) {
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
        publishToRayatTbl.on('order.dt search.dt', function () {
            publishToRayatTbl.column(0, {
                search: 'applied',
                order: 'applied'
            }).nodes().each(function (cell, i) {
                cell.innerHTML = i + 1;
            });
        }).draw();
    }

    $('#publishToRayatTbl tbody').on('dblclick', 'tr', function () {
        var row = publishToRayatTbl.row(this).data();
        
            if (this.querySelector('a') == null && this.querySelector('button') == null) {
                window.open(`/community/students/report/${row.student.user.id}`, '_self');
            }
    });






    if (document.getElementById("rayatReportTbl") != undefined && document.getElementById("rayatReportTbl") != null) {
        let rayatReportTbl = $('#rayatReportTbl').DataTable({
            ajax: window.rayatReportApi,
            dataSrc: "data",
            rowId: 'student.id',
            columnDefs: [{
                searchable: false,
                orderable: false,
                targets: 0
            }],
            columns: [{
                    data: null
                },
                {
                    data: "national_id",
                },
                {
                    data: "student.rayat_id"
                },
                {
                    data: "name",
                },
                {
                    data: "phone"
                },
                {
                    data: "student.program.name"
                },
                {
                    data: "student.department.name"
                },
                {
                    data: "student.major.name"
                },
                {
                    data: "student.available_hours",
                    className: "text-center",
                },
                {
                    data: "student.credit_hours",
                    className: "text-center",
                },
                {
                    data: "student.available_hours",
                    className: "text-center",
                    render: function (student, type, row) {
                        return `<p class="text-success">مسجل في رايات</p>`
                    }

                },
                {
                    render: function (data, type, row) {
                        return `<p onclick="showOrders(${row.student.id})"><i class="fa btn fa-lg fa-edit text-primary"></i></p>`;
                    },
                },
            ],
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
            initComplete: function () {
                Swal.close();
                var api = this.api();
                $(".filterhead", api.table().header()).each(function (i) {
                    if (i > 4 && i < 8) {
                        var column = api.column(i);
                        var select = $(
                                '<select><option value="">الكل</option></select>'
                            )
                            .appendTo($(this).empty())
                            .on("change", function () {
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
                            .each(function (d, j) {
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

        rayatReportTbl.on('order.dt search.dt', function () {
            rayatReportTbl.column(0, {
                search: 'applied',
                order: 'applied'
            }).nodes().each(function (cell, i) {
                cell.innerHTML = i + 1;
            });
        }).draw();

        $('#rayatReportTbl tbody').on('dblclick', 'tr', function () {
            var row = rayatReportTbl.row(this).data();
            if (this.querySelector('a') == null && this.querySelector('button') == null) {
                window.open(`/community/students/report/${row.student.user.id}`, '_self');
            }
        });
    }
});




window.showOrders = function (student_id) {
    Swal.fire({
        html: "<h4>جاري جلب البيانات</h4>",
        timerProgressBar: true,
        didOpen: () => {
            Swal.showLoading();
        },
    });
    axios.get(`/api/community/student/orders/${student_id}`)
        .then((response) => {
            Swal.close();
            var tblOrders = document.getElementById('tblOrders');
            tblOrders.innerHTML = '';
            response.data.forEach(order => {
                if (order.requested_hours > 0) {
                    var row = tblOrders.insertRow(0);
                    row.id = order.id;
                    var orderId = row.insertCell(0);
                    var requestedHours = row.insertCell(1);
                    var cost = row.insertCell(2);
                    var editIcon = row.insertCell(3);
                    orderId.innerHTML = order.id;
                    cost.innerHTML = order.amount;
                    requestedHours.innerHTML = order.requested_hours;
                    editIcon.innerHTML = `<p data-target="#editModal" data-toggle="modal" onclick="window.order_id=${order.id};window.oldHours.value=${order.requested_hours};window.newHours.value='';window.note.value='';"><i class="fa btn fa-lg fa-edit text-primary"></i></p>`;
                }
            });
            $('#ordersModal').modal();
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

window.editHours = function () {
    axios.post('/api/community/order/edit', {
            order_id: window.order_id,
            newHours: window.newHours.value,
            note: window.note.value
        })
        .then((response) => {
            document.getElementById(window.order_id).children[1].innerHTML = window.newHours.value;
            document.getElementById(window.order_id).children[2].innerHTML = response.data.newCost;
            Swal.fire({
                position: "center",
                html: "<h4>" + response.data.message + "</h4>",
                icon: "success",
                showConfirmButton: false,
                timer: 1000,
            });
            $('#editModal').modal('hide');
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


window.deleteOrder = function (order_id) {
    Swal.fire({
        title: ' هل انت متأكد ؟',
        text: " لا يمكن التراجع عن هذا الاجراء",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'نعم',
        cancelButtonText: 'الغاء',
    }).then((result) => {
        if (result.isConfirmed) {
            axios.post('/student/order/delete', {
                    order_id: order_id
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


window.publishToRayatStore = function (national_id, order_id, event) {
    let row = event.currentTarget.parentNode.parentNode;
    // 
    // let requested_hours = document.getElementById('requested_hours').value;
    let requested_hours = row.children[11].children[0].value;

    let form = {
        national_id: national_id,
        requested_hours: requested_hours,
        order_id: order_id
    }
    axios.post(window.publishToRayat, form)
        .then((response) => {
            row.remove();
            Swal.fire({
                position: "center",
                html: "<h4>" + response.data.message + "</h4>",
                icon: "success",
                showConfirmButton: false,
                timer: 1000,
            });
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

window.changeHoursInputs = function () {
    let rows = document.getElementsByTagName("tr");
    if (rows == null || rows == undefined || rows.length <= 3) {
        return;
    }
    let counter = 0;
    let allHours = document.getElementById("allHoursValue").value;
    for (var i = 3; i < rows.length; i++) {
        if (parseInt(allHours) <= parseInt(rows[i].children[11].children[0].max)) {
            rows[i].children[11].children[0].value = allHours;
        } else {
            counter++;
        }
        if (counter > 0) {
            Swal.fire({
                position: "center",
                // html: "<h6 dir='rtl'>  " +counter+" متدربين لم يتم تغيرر ساعاتهم بسبب تجاوز الرفم المطلب للحد الاعلى </h6>",
                html: '<h6 dir="rtl">لم يتم تغيير الساعات لعدد (' + counter + ') متدربين بسبب تجاوز الحد الاعلى</h6>',
                icon: "warning",
                showConfirmButton: true,
            });
        }
    }
}
