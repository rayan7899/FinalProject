jQuery(function () {
    if($("#mainTable")){
    var table = $("#mainTable").DataTable({
        orderCellsTop: true,
        deferLoading:true,
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
              $('.filterhead' ).each( function (i) {
                  if(i > 3 && i < 7){
                var column = api.column(i);
                  var select = $('<select><option value="">الكل</option></select>')
                      .appendTo( $(this).empty() )
                      .on( 'change', function () {
                          var val = $.fn.dataTable.util.escapeRegex(
                              $(this).val()
                          );
   
                          column
                              .search( val ? '^'+val+'$' : '', true, false )
                              .draw();
                      } );
   
                  column.data().unique().sort().each( function ( d, j ) {
                      select.append( '<option value="'+d+'">'+d+'</option>' );
                  } );
                }
              } );
              
          }
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
window.wallet = document.getElementById("wallet");
window.documents_verified = document.getElementById("documents_verified");
window.note = document.getElementById("note");

window.popup = function () {
    $('[data-toggle="popover"]').popover({
        html: true,
    });
};

window.showModal = function (national_id, name, wallet, note) {
    window.documents_verified.checked = document.getElementById(
        "check_" + national_id
    ).checked;
    window.national_id.value = national_id;
    window.sname.value       = name;
    window.wallet.value      = wallet;
    window.note.value        = "";
    window.note.value        = note;

};

window.sendStudentUpdate = function () {
    let national_id        = window.national_id.value;
    let documents_verified = window.documents_verified.checked;
    let wallet             = window.wallet.value;
    let note               = window.note.value;
    if (wallet == "" || wallet <= 0) {
        wallet = 0;
    }
    if (documents_verified) {
        documents_verified = 1;
    } else {
        documents_verified = 0;
    }
    let form = {
        national_id: national_id,
        documents_verified: documents_verified,
        wallet: wallet,
        note: note,
    };
    Swal.fire({
        html: "<h4>جاري تحديث البيانات</h4>",
        timerProgressBar: true,
        didOpen: () => {
            Swal.showLoading();
        },
    });
    $.ajax({
        type: "post",
        url: window.studentUpdate,
        data: form,
        headers: {
            Accept: "application/json",
            ContentType: "application/json",
        },
        dataType: "json",
        success: function (response) {
            document.getElementById("wallet_" + national_id).innerHTML = wallet;
            document.getElementById("note_" + national_id).innerHTML = note;
            document.getElementById(
                "check_" + national_id
            ).checked = documents_verified;

            const message = response.message;
            Swal.fire({
                position: "center",
                html: "<h4> تم تحديث البيانات بنجاح</h4>",
                icon: "success",
                showConfirmButton: false,
                timer: 1700,
            });
            $("#editModal").modal("hide");
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

window.checkChanged = function (national_id, event) {
    let documents_verified = 0;
    if (event.target.checked) {
        documents_verified = 1;
    } else {
        documents_verified = 0;
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
        url: window.docsVerified,
        data: {
            national_id: national_id,
            documents_verified: documents_verified,
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

// window.find = function (text) {
//     console.log(text)
//     window.findit = new Array();
//     let sptext = text.split(" ")
//     for (let td of all) {
//         let sp = td.innerText.split(" ")
//         for (let word of sp) {
//             for (let txt of sptext) {
//                 if (word == txt) {
//                     let parent = td.parentNode
//                     if (findit[findit.length - 1] != parent) {
//                         findit.push(parent)
//                     }
//                 }
//             }
//         }
//     }
