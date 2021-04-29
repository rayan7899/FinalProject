jQuery(function () {
    if ($("#mainTable tr").length > 0) {
        var table = $("#mainTable").DataTable({
            orderCellsTop: true,
            deferLoading: true,
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
    }
    $.ajaxSetup({
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
    });
});

window.national_id = document.getElementById("national_id");
window.sname = document.getElementById("sname");
window.amount = document.getElementById("amount");
window.note = document.getElementById("note");
window.payment_id = 0;

window.popup = function () {
    $('[data-toggle="popover"]').popover({
        html: true,
    });
};

window.showModal = function (callFrom = "edit",national_id, payment_id, name, amount) {

    if(callFrom == "reject"){
        $("#amountFormGroup").hide();
        $("#acceptBtnModal").hide();
        $("#rejectBtnModal").show();
    }else{
        $("#amountFormGroup").show();
        $("#acceptBtnModal").show();
        $("#rejectBtnModal").hide();
    }
    window.national_id.value = national_id;
    window.sname.value = name;
    window.note.value = "";
    window.payment_id = payment_id;
    if (window.amount !== null) {
        window.amount.value = amount;
    }
};

window.sendStudentUpdate = function (decision) {
    let national_id = window.national_id.value;
    let amount      = window.amount.value;
    let payment_id  = window.payment_id;
    let note        = window.note.value;

    if (amount == "" || amount <= 0) {
        amount = 0;
    }
    let form = {
        national_id: national_id,
        amount: amount,
        decision:decision,
        payment_id: payment_id,
        note: note,
    };

    Swal.fire({
        html: "<h4>جاري تحديث البيانات</h4>",
        timerProgressBar: true,
        didOpen: () => {
            Swal.showLoading();
        },
    });


    axios.post(window.paymentWithNote, form)
    .then((response) => {
        if (document.getElementById(national_id) !== null) {
            document.getElementById(national_id).remove();
        }
        
        Swal.fire({
            position: "center",
            html: "<h4>" + response.data.message + "</h4>",
            icon: "success",
            showConfirmButton: false,
            timer: 1000,
        });
          $("#editModal").modal("hide");
    })
    .catch((error) => {
        Swal.fire({
            position: "center",
            html: "<h4>" + error.response.data.message + "</h4>",
            icon: "error",
            showConfirmButton: true,
        });
    });

Swal.close();

};

window.okClicked = function (decision,national_id, payment_id, event) {
    let row = event.currentTarget.parentNode.parentNode;
    Swal.fire({
        html: "<h4>جاري تحديث البيانات</h4>",
        timerProgressBar: true,
        didOpen: () => {
            Swal.showLoading();
        },
    });

    var form  = {
        national_id: national_id,
        payment_id: payment_id,
        decision:decision
    };

    axios.post(window.paymentVerified, form)
    .then((response) => {
        row.remove();
        Swal.fire({
            position: "center",
            html: "<h4>" + response.data.message + "</h4>",
            icon: "success",
            showConfirmButton: false,
            timer: 1000,
        });
          $("#editModal").modal("hide");
    })
    .catch((error) => {
        Swal.fire({
            position: "center",
            html: "<h4>" + error.response.data.message + "</h4>",
            icon: "error",
            showConfirmButton: true,
        });
    });

Swal.close();

};
window.showPdf = function (url, type) {
    if (type == "pdf") {
        $("#modalImage").hide();
        $("#pdfIfreme").show();
        $("#pdfIfreme").attr("src", "");
        $("#pdfIfreme").attr("src", url);
    } else {
        $("#pdfIfreme").hide();
        $("#modalImage").show();
        $("#modalImage").attr("src", "");
        $("#modalImage").attr("src", url);
    }
};
