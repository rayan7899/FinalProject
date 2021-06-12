const { default: axios } = require("axios");

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
});
window.toggleChecked = function (event) {
    let row = event.target.parentNode.parentNode;
    let cost = parseInt(row.dataset.cost);
    if (event.currentTarget.checked == true) {
        window.new_cost += cost;
    } else {
        window.new_cost -= cost;
    }

    if (event.currentTarget.checked) {
        row.dataset.selected = true;
    } else {
        row.dataset.selected = false;
    }
}



window.addToCoursesTable = function () {
    window.new_cost = 0;

    let tablePickCourses = document.getElementById("pick-courses");
    let tableCourses = document.getElementById("courses");
    let selectedCourses = tablePickCourses.querySelectorAll("[data-selected='true']");
    let newTotal = 0;
    selectedCourses.forEach((row) => {
        row.children[5].children[0].setAttribute('onclick', 'window.calcCost();')
        let id = row.children[5].children[0].value;
        row.children[5].children[0].setAttribute('id', 'course_' + id);
        // let result_row = tableCourses.children[0];
        // tableCourses.insertBefore(row, result_row);
        let cost = parseInt(row.dataset.cost);
        newTotal += cost;
        // if ((window.t_cost + newTotal) <= window.wallet) {
        //     
        tableCourses.appendChild(row);
        // }

    });
    window.calcCost();
    $('#pick-courses').modal('hide');
}


window.calcCost = function (firstLoad = false) {
    window.t_cost = 0;
    window.total_hours = 0;
    document.getElementById('totalHoursCost').value = 0;
    let coursesTable = document.getElementById("courses");
    for (let course of coursesTable.children) {
        if (course.children[5].children[0].checked == true) {
            window.t_cost += parseInt(course.dataset.cost);
            window.total_hours += parseInt(course.dataset.hours);
        }
    }
    // if (!firstLoad) {
    //     if (window.total_hours < 12 || window.total_hours > 21) {
    //         $("#courses-error").html("يجب ان يكون مجموع الساعات بين 12 و 21 ساعة");
    //         $("#courses-error").show();
    //     } else {
    //         $("#courses-error").html("");
    //         $("#courses-error").hide();

    //     }
    // }
    
     if (!firstLoad) {
        if (window.total_hours > 12 && window.isSummer == '1' ) {
            $("#courses-error").html("الحد الاعلى للفصل الصيفي هو 12 ساعة");
            $("#courses-error").show();
        } else {
            $("#courses-error").html("");
            $("#courses-error").hide();

        }
    }



    let state = 'trainee';
    if (document.getElementById("employee").checked) {
        state = 'employee';
    } else if (document.getElementById("employeeSon").checked) {
        state = 'employeeSon';
    } else if (document.getElementById("privateState").checked) {
        state = 'privateState';
    }
    switch (state) {
        case 'employee':
            // document.getElementById('totalHoursCost').value = window.t_cost * 0.25;
            window.t_cost = window.t_cost * 0.25;
            $('#pledgeSection').show();
            $('#receiptImg').prop('disabled', false);
            $('#paymentCost').prop('disabled', false);
            $('#paymentCost').prop('required', true);
            $('#paymentGroup').show();
            $('#receipt').show();
            $('#privateStateDocGroup').hide();
            $('#privateStateDoc').prop('disabled', true);
            break;

        case 'employeeSon':
            // document.getElementById('totalHoursCost').value = window.t_cost * 0.5;
            window.t_cost = window.t_cost * 0.5;
            $('#pledgeSection').show();
            $('#receiptImg').prop('disabled', false);
            $('#paymentCost').prop('disabled', false);
            $('#paymentCost').prop('required', true);
            $('#paymentGroup').show();
            $('#receipt').show();
            $('#privateStateDocGroup').hide();
            $('#privateStateDoc').prop('disabled', true);
            break;

        case 'privateState':
            // document.getElementById('totalHoursCost').value = 0;
            window.t_cost = 0;
            $('#paymentGroup').hide();
            $('#receipt').hide();
            $('#receiptImg').prop('disabled', true);
            $('#paymentCost').prop('disabled', true);
            $('#privateStateDoc').prop('disabled', false);
            $('#privateStateDocGroup').show();
            break;

        // case 'trainee':
        default:
            document.getElementById('totalHoursCost').value = window.t_cost;
            $('#pledgeSection').hide();
            $('#receiptImg').prop('disabled', false);
            $('#paymentCost').prop('disabled', false);
            $('#paymentCost').prop('required', true);
            $('#paymentGroup').show();
            $('#receipt').show();
            $('#privateStateDocGroup').hide();
            $('#privateStateDoc').prop('disabled', true);
            break;
    }
    document.getElementById('totalHoursCost').value = window.t_cost;
    let walletAfterCalc = window.wallet - window.t_cost;
    let cost = 0;
    if (walletAfterCalc >= 0) {
        document.getElementById("walletAfterCalc").value = walletAfterCalc;
        $('#costFormGroup').hide();
        $('#receiptImg').prop('disabled', true);
        $('#paymentCost').prop('disabled', true);
        $('#paymentCost').prop('required', false);
        document.getElementById("orderCost").value = 0;;
    } else {
        cost = Math.abs(walletAfterCalc);
        document.getElementById("walletAfterCalc").value = 0;
        $('#costFormGroup').show();
        $('#receiptImg').prop('disabled', false);
        $('#paymentCost').prop('disabled', false);
        $('#paymentCost').prop('required', true);
        document.getElementById("orderCost").value = cost;
    }

}

async function getUserInfo() {
    let id = document.getElementById("searchId").value;
    if (id == undefined || id == null || id == "") {
        console.error("searchId is undefined or null");
        return;
    }
    var data = null;
    Swal.fire({
        html: "<h4>جاري البحث </h4>",
        timerProgressBar: true,
        didOpen: () => {
            Swal.showLoading();
        },
    });
    await axios.get('/api/community/student-info/' + id)
        .then((response) => {
            data = response.data;
        })
        .catch(async (error) => {
            await Swal.fire({
                position: "center",
                html: "<h4>" + error.response.data.message + "</h4>",
                icon: "error",
                showConfirmButton: true,
            });
        });

    Swal.close();
    return data;
}

window.findStudent = async function () {

    var user = await getUserInfo();
    if (user == null) {
        console.error("user is null");
        return;
    }

    document.getElementById("editStudentForm").action = "/community/students/update/" + user.id;
    document.getElementById("resetPasswordForm").action = "/community/students/reset-password/" + user.id;
    document.getElementById("national_id").value = user.national_id;
    document.getElementById("rayat_id").value = user.student.rayat_id;
    document.getElementById("name").value = user.name;
    document.getElementById("phone").value = user.phone;
    document.getElementById("traineeState").value = user.student.traineeState;
    document.getElementById("level").value = user.student.level;


    $("#studentSection").show();
    $("#searchSection").hide();
    level
    document.getElementById("program").value = user.student.program_id;
    window.fillDepartments();
    document.getElementById("department").value = user.student.department_id;
    window.fillMajors();
    document.getElementById("major").value = user.student.major_id;
}


window.popup = function () {
    $('#info-popup').popover({
        html: true,
    });
    // $('[data-toggle="popover"]').popover();
}


window.getStudentReport = function () {

    let id = document.getElementById("search").value;
    Swal.fire({
        html: "<h4>جاري تحديث البيانات</h4>",
        timerProgressBar: true,
        didOpen: () => {
            Swal.showLoading();
        },
    });
    axios.get('/api/community/student-report/' + id)
        .then((response) => {
            document.getElementById("getReportForm").action = '/community/students/report/' + response.data.id;
            document.getElementById("getReportForm").submit();
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




window.addEventListener('DOMContentLoaded', (event) => {
    $("[data-toggle=popover]").popover();
});
