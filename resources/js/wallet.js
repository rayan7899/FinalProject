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
console.log(user);
    document.getElementById("name").value = user.name;
    document.getElementById("national_id").value = user.national_id;
    document.getElementById("wallet").value = user.student.wallet;
    //for request
    document.getElementById("id").value = user.national_id;



    $("#userInfo").show();
    $("#chargeForm").show();


}