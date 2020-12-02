// Inicialización dataTables
const $dt = $('.table').DataTable({
    ajax: '/jingles/datatables',
    language: {
        url: '/localisation/datatables.json'
    },
    serverSide: true,
    columns: [
        { name: 'id', data: 'id' },
        { name: 'product_name', data: 'product_name' },
        { name: 'work_title', data: 'work_title' },
        { name: 'status_id', data: 'status.name' },
        {
            orderable: false,
            data:      null,
            class:     'text-center',
            render: function(data, type) {
                if (type == 'display') {
                    return `<a href="/jingles/${ data.id }">Ver</a>`;
                }

                return null;
            }
        },
    ],
    searchCols: [
        null,
        null,
        null,
        { search: 1 }, // Status "Nuevo"
        null
    ],
    order: [[1, 'asc']], // Orden por defecto: id ascendente
    initComplete: () => {
        // Reemplazamos la búsqueda por defecto por un select con los estados de los trámites
        const statusSelect = `<select id="statusFilter">${
            statusOptions.map(opt => `<option value="${ opt.id }">${ opt.name }</option>`).join()
        }</select>`;
        $('.dataTables_filter').html(statusSelect);
        $('#statusFilter').on('change', (event) => {
            $dt.column('status_id:name')
                .search(event.target.value)
                .draw();
        });
    }
});
