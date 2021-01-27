// Generamos el contenido a mostrar en los detalles de cada registro
const details = ( data ) => {
    output = '';
    output += `<strong>Duración:</strong> ${ data.duration }<br>`;

    return output;
};

// Inicialización dataTables
const $dt = $('.table').DataTable({
    ajax: '/works/datatables',
    language: {
        url: '/localisation/datatables.json'
    },
    serverSide: true,
    columns: [
        {
            class:          'details-control',
            orderable:      false,
            data:           null,
            defaultContent: ''
        },
        { name: 'id', data: 'id' },
        { name: 'title', data: 'title' },
        {
            orderable: false,
            data:      null,
            class:     'text-center',
            render: function(data, type) {
                if (type == 'display') {
                    return data.has_editor ? 'Si' : 'No';
                }

                return null;
            }
        },
        { name: 'status_id', data: 'status.name' },
        {
            orderable: false,
            data:      null,
            class:     'text-center',
            render: function(data, type) {
                if (type == 'display') {
                    return `<a href="/works/${ data.id }">Ver</a>`;
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
        { search: -1 }, // Status "Todos"
        null
    ],
    order: [[1, 'asc']], // Orden por defecto: id ascendente
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

// Mostrar / ocultar detalles
$('.table tbody').on( 'click', 'tr td.details-control', (event) => {
    const tr = $(event.target).closest('tr');
    const row = $dt.row( tr );

    if ( row.child.isShown() ) {
        tr.removeClass('shown');
        row.child.hide();
    } else {
        tr.addClass('shown');
        row.child( details( row.data() ) ).show();
    }
});