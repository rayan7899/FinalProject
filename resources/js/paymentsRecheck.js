window.national_id = document.getElementById("national_id");
window.sname = document.getElementById("sname");
window.amount = document.getElementById("amount");
window.note = document.getElementById("note");
// window.isManagment = false;
window.payment_id = 0;



jQuery(function () {
    let paymentsRecheckTbl = $('#paymentsRecheckTbl').DataTable({
        ajax: window.paymentsRecheckJson,
        dataSrc: "data",
        rowId: 'id',
        columnDefs: [{
            searchable: false,
            orderable: false,
            targets: 0
        }],
        createdRow: function (row, data, dataIndex) {
            row.dataset.origAmount = data.amount;
            //    data.dataset.origAmount
        },
        columns: [{
                data: null,
                className: "text-center",
            },
            {
                data: "student.user.national_id",
                className: "text-center",
            },
            {
                data: "student.user.name",
            },
            // {
            //     data: "student.user.phone",
            //     className: "text-center",
            // },
            // {
            //     data: "student.program.name",
            //     className: "text-center",
            // },
            // {
            //     data: "student.department.name",
            //     className: "text-center",
            // },
            // {
            //     data: "student.major.name",
            //     className: "text-center",
            // },
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
                data: function (data) {
                    if (data.accepted == 1 || data.accepted == '1' || data.accepted == true) {
                        var totalAmount = 0;
                        data.transactions.forEach(transaction => {
                            if (transaction.type == 'editPayment-charge' || transaction.type == 'recharge' || transaction.type == 'manager_recharge') {
                                totalAmount += transaction.amount;
                            } else {
                                totalAmount -= transaction.amount;
                            }
                        });
                        if (data.amount != totalAmount) {
                            return `<del class="text-muted">${data.amount}</del> ${totalAmount}`;
                        } else {
                            return data.amount;
                        }
                    } else {
                        return data.amount;
                    }
                },
                className: "text-center",
            },
            
            {
                data: "note",
            },
            {
                data: function (data) {
                    if(data.checker_decision == 0 || data.checker_decision == '0' || data.checker_decision == false){
                        return `مدقق الايصالات | ${data.manager.user.name}`
                    }else if(data.management_decision == 0 || data.management_decision == '0' || data.management_decision == false){
                        return `الادارة العامة | ${data.manager.user.name}`
                    }else{
                        return 'خطأ غير معروف'
                    }
                },
            },
            {
                data: function (data) {
                    if (data.checker_decision == 1 || data.checker_decision == '1' || data.checker_decision == true) {
                        if (data.management_decision == 0 || data.management_decision == '0' || data.management_decision == false) {
                            return data.management_note;
                        }
                    }
                    return data.checker_note;
                },
            },
            {
                data: "receipt_file_id",
                className: "text-center",
                render: function (data, type, row) {
                    let ext = data.split('.')[1];
                    if (ext == 'pdf' || ext == 'PDF') {
                        return `<a data-toggle="modal" data-target="#pdfModal" href="#"
                        onclick="showPdf('/api/documents/show/${row.student.user.national_id}/${data}','pdf')">
                        <img style="width: 20px" src="/images/pdf.png" />
                    </a>`;
                    } else {
                        return `<a data-toggle="modal" data-target="#pdfModal" href="#"
                        onclick="showPdf('/api/documents/show/${row.student.user.national_id}/${data}','img')">
                        <img style="width: 20px" src="/images/camera_img_icon.png" />
                    </a>`;
                    }
                }
            },
            {
                data: "student.level",
                className: "text-center",
                render: function (data, type, row) {
                    if (row.accepted == 1 || row.accepted == '1' || row.accepted == true) {
                        var totalAmount = 0;
                        row.transactions.forEach(transaction => {
                            if (transaction.type == 'editPayment-charge' || transaction.type == 'recharge' || transaction.type == 'manager_recharge') {
                                totalAmount += transaction.amount;
                            } else {
                                totalAmount -= transaction.amount;
                            }
                        });
                    } else {
                        totalAmount = row.amount;
                    }
                    // var isManagment = false;
                    // if (row.checker_decision == 1 || row.checker_decision == '1' || row.checker_decision == true) {
                    //     if (row.management_decision == 0 || row.management_decision == '0' || row.management_decision == false) {
                    //         isManagment = true;
                    //     }
                    // }
                    return `<button data-toggle="modal" data-target="#editModal"
                class="btn btn-primary px-2 py-0"
                onclick="window.recheckShowModal('accept','${row.student.user.national_id}','${row.id}','${row.student.user.name}','${row.note}','${totalAmount}', event)">
                تعديل</button>
                
                <button 
                class="btn btn-danger px-2 py-0"
                onclick="window.recheckShowModal('reject','${row.student.user.national_id}','${row.id}','${row.student.user.name}','${row.note}','${totalAmount}', event)">
                رفض</button>
                `;
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
                if (i == 3) {
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

    paymentsRecheckTbl.on('order.dt search.dt', function () {
        paymentsRecheckTbl.column(0, {
            search: 'applied',
            order: 'applied'
        }).nodes().each(function (cell, i) {
            cell.innerHTML = i + 1;
        });
    }).draw();

    $('#paymentsRecheckTbl tbody').on('dblclick', 'tr', function () {
        var row = paymentsRecheckTbl.row(this).data();
        
            if (this.querySelector('a') == null && this.querySelector('button') == null) {
                window.open(`/community/students/report/${row.student.user.id}`, '_self');
            }
    });

});



window.recheckShowModal = function (callFrom = "edit", national_id, payment_id, name, note, amount, event) {
    window.rotation = 0;
    // console.log(event.target.parentNode.parentNode);
    // return;
    document.querySelector("#modalImage").style.transform = `rotate(${window.rotation}deg)`;
    if (callFrom == "reject") {
        Swal.fire({
            title: ' هل انت متأكد ؟',
            text: " .سيتم ارسال الطلب الى مدقق الايصالات لمراجعته مرة اخرى",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'نعم',
            cancelButtonText: 'الغاء',
        }).then((result) => {
            if (result.isConfirmed) {
                recheckRejectClicked(national_id, payment_id, event)
            }
        })
    } else {
        $("#amountFormGroup").show();
        $("#acceptBtnModal").show();
        // $("#rejectBtnModal").hide();
        $("#rejectMsgs").hide();
        $("#acceptMsgs").show();
    }
    window.national_id.value = national_id;
    window.sname.value = name;
    // window.isManagment = isManagment;
    if (note == null || note == 'null') {
        window.note.value = '';
    } else {
        window.note.value = note;
    }
    window.payment_id = payment_id;

    if (window.amount !== null) {
        window.amount.value = amount;
    }
};

window.recheckRejectClicked = function (national_id, payment_id, event) {
    let row = event.target.parentNode.parentNode;
    Swal.fire({
        html: "<h4>جاري تحديث البيانات</h4>",
        timerProgressBar: true,
        didOpen: () => {
            Swal.showLoading();
        },
    });

    var form = {
        national_id: national_id,
        payment_id: payment_id,
    };

    axios.post(window.paymentsRecheckReject, form)
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

window.recheckEditAmount = function () {
    var row = document.getElementById(window.payment_id);
    if (window.newAmount.value == null || window.newAmount.value == '') {
        Swal.fire({
            position: "center",
            html: "<h4>لا يمكن ترك حقل المبلغ الجديد فارغ</h4>",
            icon: "error",
            showConfirmButton: true,
        });
    } else {
        let form = {
            payment_id: window.payment_id,
            amount: window.newAmount.value,
            note: window.note.value,
            isRecheck: true,
            // isManagment: window.isManagment
        };
        axios.post(window.editOldPayment, form)
            .then((response) => {
                Swal.fire({
                    position: "center",
                    html: "<h4>" + response.data.message + "</h4>",
                    icon: "success",
                    showConfirmButton: false,
                    timer: 1000,
                });
                row.remove();
                $("#editModal").modal("hide");
                window.newAmount.value = '';
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
}
