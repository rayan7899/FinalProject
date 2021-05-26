
window.user = null;
function fillReportTable (user) {
    let national_id = user.national_id;
    let singlePaymentsReportTbl = $('#singlePaymentsReportTbl').DataTable({
        // ajax: window.paymentsReviewJson,
        // dataSrc: "data",
        data:user.student.payments,
        columnDefs: [{
            searchable: false,
            orderable: false,
            targets: 0
        }],
        columns: [{
                data: null,
                className:"text-center",
            },
            {
                data: "id",
                className: "text-center",
            },
            {
                data: function (data) {
                    if (data.accepted == 1 || data.accepted == '1' || data.accepted == true) {
                        if (data.amount != data.transaction.amount) {
                            return `<del class="text-muted">${data.amount}</del>
                            ${data.transaction.amount}`;
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
                data: "accepted",
                className: "text-center",
                render: function (data, type, row) {
                    let className = "";
                    let txt = "";
                    if(data == '1'){
                        className = "text-success";
                        txt = "مقبول";
                    }else if(data == '0'){
                        className = "text-danger";
                        txt = "مرفوض";
                    }else{
                        className = "";
                        txt = "قيد المراجعة";
                    }
                    return `<span class="${className}">${txt}</span>`
                }
            },
            {
                data: "created_at",
                className: "text-center",
                render: function (data, type, row) {
                    return data.split('T')[0];
                }
            },
            {
                data: "note"
            },
            {
                data: "receipt_file_id",
                className: "text-center",
                render: function (data, type, row) {
                    let ext = data.split('.')[1];
                    if (ext == 'pdf' || ext == 'PDF') {
                        return `<a data-toggle="modal" data-target="#pdfModal" href="#"
                        onclick="showPdf('/api/documents/show/${national_id}/${data}','pdf')">
                        <img style="width: 20px" src="/images/pdf.png" />
                    </a>`;
                    } else {
                        return `<a data-toggle="modal" data-target="#pdfModal" href="#"
                        onclick="showPdf('/api/documents/show/${national_id}/${data}','img')">
                        <img style="width: 20px" src="/images/camera_img_icon.png" />
                    </a>`;
                    }
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
    });

    singlePaymentsReportTbl.on('order.dt search.dt', function () {
        singlePaymentsReportTbl.column(0, {
            search: 'applied',
            order: 'applied'
        }).nodes().each(function (cell, i) {
            cell.innerHTML = i + 1;
        });
    }).draw();

}

















window.getStudentInfo = function () {

    let id = document.getElementById("search").value;
    Swal.fire({
        html: "<h4>جاري تحديث البيانات</h4>",
        timerProgressBar: true,
        didOpen: () => {
            Swal.showLoading();
        },
    });
    axios.get('/api/community/student/' + id)
        .then((response) => {
            fillUsetInfo(response.data);
            fillReportTable(response.data);
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
}

function fillUsetInfo(user) {
    document.getElementById("name").value = user.name;
    document.getElementById("national_id").value = user.national_id;
    document.getElementById("wallet").value = user.student.wallet;
    //for request
    document.getElementById("id").value = user.national_id;



    $("#userInfo").show();
    $("#chargeForm").show();
    $("#paymentsReportCard").show();
}

window.receiptToggle = function(action){
    if(action == 'show'){
        $('#receipt').show();
        $('#receiptImg').removeAttr('disabled');
        $('#receiptImg').attr('required', true);
    }else{
        $('#receipt').hide();
        $('#receiptImg').attr('disabled', true);
        $('#receiptImg').removeAttr('required');
    }
}