window.publishStudentHours = function (national_id, event) {
    axios.post(window.publishToRayat, {
        national_id: national_id,
        state: event.target.checked,
    })
        .then((response) => {
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
                html: "<h4>" + error.response + "</h4>",
                icon: "error",
                showConfirmButton: true,
            });
        });
}