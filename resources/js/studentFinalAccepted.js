
jQuery(function () {
    let finalAcceptedFormTbl = $('#finalAcceptedFormTbl').DataTable({
        ajax: window.finalStudentDataApi,
        dataSrc: "data",
        rowId: 'national_id',
        columnDefs: [{
            searchable: false,
            orderable: false,
            targets: 0
        }],
        columns: [
            { data: null },
            { data: "national_id" },
            { data: "name", },
            { data: "phone" },
            { data: "student.program.name" },
            { data: "student.department.name" },
            { data: "student.major.name" },
            {
                data: "student.identity",
                className: "text-center",
                render: function (identity, type, row) {
                    let ext = identity.split('.')[1];
                    if (ext == 'pdf' || ext == 'PDF') {
                        return `<a data-toggle="modal" data-target="#pdfModal" href="#"
                                onclick="showPdf('/documents/show/${identity}','pdf')">
                                <img style="width: 20px" src="/images/pdf.png" />
                                </a>`;
                    } else {
                        return `<a data-toggle="modal" data-target="#pdfModal" href="#"
                        onclick="showPdf('/documents/show/${identity}','img')">
                        <img style="width: 20px" src="/images/camera_img_icon.png" />
                        </a>`;
                    }
                }
            },
            {
                data: "student.degree",
                className: "text-center",
                render: function (degree, type, row) {
                    let ext = degree.split('.')[1];
                    if (ext == 'pdf' || ext == 'PDF') {
                        return `<a data-toggle="modal" data-target="#pdfModal" href="#"
                                onclick="showPdf('/documents/show/${degree}','pdf')">
                                <img style="width: 20px" src="/images/pdf.png" />
                                </a>`;
                    } else {
                        return `<a data-toggle="modal" data-target="#pdfModal" href="#"
                        onclick="showPdf('/documents/show/${degree}','img')">
                        <img style="width: 20px" src="/images/camera_img_icon.png" />
                        </a>`;
                    }
                }
            },
            {
                data: "student.has_imported_docs",
                className: "text-center"
            },
            {
                data: "student.student_docs_verified",
                className: "text-center",
                render: function (has_imported_docs, type, row) {
                    let isChecked = "";
                    if (has_imported_docs == 1) {
                        isChecked = "checked";
                    }
                    return `<input id="check_${row.national_id}" type="checkbox"
                    onchange="window.finalAcceptChanged('${row.national_id}',event)"
                    class="custom-checkbox" style="width: 16px; height: 16px;"
                    ${isChecked}
                    value="${has_imported_docs}" />`;
                }
            },

            {
                data: "student.final_accepted",
                className: "text-center",
                render: function (final_accepted, type, row) {
                    let isChecked = "";
                    if (final_accepted == 1) {
                        isChecked = "checked";
                    }
                    return `<input id="check_${row.national_id}" type="checkbox"
                    onchange="window.finalAcceptChanged('${row.national_id}',event)"
                    class="custom-checkbox" style="width: 16px; height: 16px;"
                    ${isChecked}
                    value="${final_accepted}" />`;
                }
            },

        ],
        order: [[0, "asc"]],
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
                copyKeys:
                    "زر <i>ctrl</i> أو <i>⌘</i> + <i>C</i> من الجدول<br>ليتم نسخها إلى الحافظة<br><br>للإلغاء اضغط على الرسالة أو اضغط على زر الخروج.",
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
            var api = this.api();
            $(".filterhead", api.table().header()).each(function (i) {
                if (i > 3 && i < 7) {
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

    finalAcceptedFormTbl.on('order.dt search.dt', function () {
        finalAcceptedFormTbl.column(0, { search: 'applied', order: 'applied' }).nodes().each(function (cell, i) {
            cell.innerHTML = i + 1;
        });
    }).draw();

























    let finalAcceptedReportTbl = $('#finalAcceptedReportTbl').DataTable({
        ajax: window.finalStudentDataReportApi,
        dataSrc: "data",
        rowId: 'national_id',
        columnDefs: [{
            searchable: false,
            orderable: false,
            targets: 0
        }],
        columns: [
            { data: null },
            { data: "national_id" },
            { data: "name", },
            { data: "phone" },
            { data: "student.program.name" },
            { data: "student.department.name" },
            { data: "student.major.name" },
        ],
        order: [[0, "asc"]],
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
                copyKeys:
                    "زر <i>ctrl</i> أو <i>⌘</i> + <i>C</i> من الجدول<br>ليتم نسخها إلى الحافظة<br><br>للإلغاء اضغط على الرسالة أو اضغط على زر الخروج.",
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
            var api = this.api();
            $(".filterhead", api.table().header()).each(function (i) {
                if (i > 3 && i < 7) {
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

    finalAcceptedReportTbl.on('order.dt search.dt', function () {
        finalAcceptedReportTbl.column(0, { search: 'applied', order: 'applied' }).nodes().each(function (cell, i) {
            cell.innerHTML = i + 1;
        });
    }).draw();




});



window.finalAcceptChanged = function (national_id, event) {
    let studentRowInputs = event.target.parentNode.parentNode.querySelectorAll("input");
    let studentDocsVerified = studentRowInputs[0].checked;
    let finalAccepted = studentRowInputs[1].checked;

    if (studentDocsVerified) {
        studentDocsVerified = 1;
    } else {
        studentDocsVerified = 0;
    }
    if (finalAccepted) {
        finalAccepted = 1;
    } else {
        finalAccepted = 0;
    }


    Swal.fire({
        html: "<h4>جاري تحديث البيانات</h4>",
        timerProgressBar: true,
        didOpen: () => {
            Swal.showLoading();
        },
    });
    $.ajax({
        type: "post",
        url: window.finalAcceptedRoute,
        data: {
            national_id: national_id,
            final_accepted: finalAccepted,
            student_docs_verified: studentDocsVerified,
        },
        headers: {
            Accept: "application/json",
            ContentType: "application/json",
        },
        dataType: "json",
        success: function (response) {
            const message = response.message;
            Swal.fire({
                position: "center",
                icon: "success",
                showConfirmButton: false,
                timer: 1000,
            });
        },
        error: function (response) {
            const message = response.responseJSON.message;
            Swal.fire({
                position: "center",
                icon: "error",
                title: message,
                showConfirmButton: true,
            });
        },
    });
    Swal.close();
};