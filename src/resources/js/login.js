$('form').on('submit', function(event) {
    axios.post('/auth', $('form').serialize())
    .then((response) => {
        if (response.data.status == 'success') {
            window.location = response.data.intended;
        } else {
            for (const attr in response.data.errors) {
                toastr.warning(response.data.errors[attr], '', {container: 'members'});
            }
        }
    })
    .catch(({ response }) => {
        if (response.status == 422) {
            for (const attr in response.data.errors) {
                toastr.warning(response.data.errors[attr], '', {container: 'members'});
            }
        } else {
            toastr.warning('Se produjo un error mientras se intentaba realizar la acción.')
        }
    });

    event.preventDefault();
});
