const $dt = $('.table').DataTable({
  ajax: '/members/datatables',
  serverSide: true,
  language: {
      url: '/localisation/datatables.json'
  },
  columns: [
      { name: 'id', data: 'id' },
      { name: 'name', data: 'name' },
      { name: 'doc_number', data: 'doc_number' },
      { name: 'email', data: 'email' },
      { name: 'mobile', data: 'mobile' },
      { name: 'status_id', data: 'status.name' },
      {
        orderable: false,
        data:      null,
        class:     'text-center',
        render: function(data, type) {
            if (type == 'display') {
                return `<a href="/members/${ data.id }">Ver</a>`;
            }

            return null;
        }
    },
  ],
  searchCols: [
      null,
      null,
      null,
      null,
      null,
      { search: -1 }, // Status "Todos"
      null
  ],
  initComplete: () => {
      // Reemplazamos la búsqueda por defecto por un select con los estados de los trámites
      const statusSelect = `<select id="statusFilter">
        <option value="-1">Todos</option>
        ${ statusOptions.map(opt => `<option value="${ opt.id }">${ opt.name }</option>`).join() }
      </select>`;
      $('.dataTables_filter').html(statusSelect);
      $('#statusFilter').on('change', (event) => {
          $dt.column('status_id:name')
              .search(event.target.value)
              .draw();
      });
  }
});