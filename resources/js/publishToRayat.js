window.publishStudentHours = function (national_id, event) {
    let row = event.currentTarget.parentNode.parentNode;
    axios.post(window.publishToRayat, {
        national_id: national_id,
        hours: document.getElementById('hours').value,
    })
        .then((response) => {
            console.log(response);
            row.remove();
            Swal.fire({
                position: "center",
                html: "<h4>"+response.data.message+"</h4>",
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