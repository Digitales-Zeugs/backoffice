$('#exportWorks').on('click', () => {
    window.location = '/integration/works';
});

$('#importWorks').on('click', () => {
    $('#importWorksFile').click();
});

$('#importWorksFile').on('change', (event) => {
    if (event.target.files.length == 0) {
        return;
    }

    const formData = new FormData();
    const fileInput = document.getElementById('importWorksFile');
    formData.append("file", fileInput.files[0]);

    axios.post(`/integration/works`, formData, {
        headers: {
          'Content-Type': 'multipart/form-data'
        }
    })
    .then(({ data }) => {
        if (data.events && data.events.length > 0) {
            data.events.reverse().forEach(event => {
                toastr.info(event);
            })
        }

        if (data.stats) {
            toastr.error(`${ data.stats.failure } respuestas no procesadas`);
            toastr.success(`${ data.stats.success } respuestas procesadas correctamente`);
        }
    })
    .catch((err) => {
        console.log(err);
        toastr.error('Se encontró un problema mientras se realizaba la solicitud');
    })
    .finally(() => {
        event.target.value = '';
    });
});

$('#exportJingles').on('click', () => {
    window.location = '/integration/jingles';
});

$('#exportMembers').on('click', () => {
    window.location = '/integration/members';
});