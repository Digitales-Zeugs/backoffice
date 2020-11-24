$('#exportWorks').on('click', () => {
    axios.get(`/integration/works`)
    .then(({ data }) => {
        console.log(data);
    })
    .catch((err) => {
        toastr.error('Se encontró un problema mientras se realizaba la solicitud');
    });
});