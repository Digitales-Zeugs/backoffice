$('#beginAction').on('click', () => {
  axios.post(`/members/${ memberId }/status`, {
      status: 'beginAction'
  })
  .then(({ data }) => {
      if (data.errors) {
          data.errors.forEach(e => {
              toastr.warning(e);
          });
      }

      if (data.status == 'success') {
          toastr.success('Proceso iniciado');

          setTimeout(() => { location.reload() }, 1000);
      }
  })
  .catch((err) => {
      toastr.error('Se encontró un problema mientras se realizaba la solicitud');
  });
});

$('#rejectAction').on('click', () => {
  axios.post(`/members/${ memberId }/status`, {
      status: 'rejectAction'
  })
  .then(({ data }) => {
      if (data.status == 'failed') {
          toastr.error('No se pudo realizar el rechazo de la solicitud.');

          data.errors.forEach(e => {
              toastr.warning(e);
          });
      } else if (data.status == 'success') {
          toastr.success('Rechazo guardado correctamente');
          setTimeout(() => { location.reload() }, 1000);
      }
  });
});