@extends('dashboard.layout')

@section('content')
<div class="container">
    <section class="content-header">
        <div class="row mb-2">
            <div class="col-sm-12">
                <h1 class="m-0 text-dark">Solicitudes de Registro de Obra</h1>
            </div>
        </div>
    </section>
    <section class="content">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th></th>
                    <th>#</th>
                    <th>Título</th>
                    <th>Estado</th>
                    <th></th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
    </section>
</div>
@endsection

@push('scripts')
<script>
window.onload = function() {
    // Generamos el contenido a mostrar en los detalles de cada registro
    const details = ( data ) => {
        output = '';
        output += `<strong>Duración:</strong> ${ data.duration }<br>`;

        return output;
    };

    // Inicialización dataTables
    const $dt = $('.table').DataTable({
        ajax: '/works/datatables',
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
            { name: 'status_id', data: 'status.name' },
            {
                class:          'view-control',
                orderable:      false,
                data:           null,
                defaultContent: ''
            },
        ],
        searchCols: [
            null,
            null,
            null,
            { search: 1 }, // Status "Nuevo"
            null
        ],
        order: [[1, 'asc']] // Orden por defecto: id ascendente
    });

    window.dt = $dt;

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

    // Click en ver lleva a la vista del trámite
    $('.table tbody').on( 'click', 'tr td.view-control', (event) => {
        const tr = $(event.target).closest('tr');
        const row = $dt.row( tr );

        window.location = `/works/${row.data()['id']}`;
    });

    // Reemplazamos la búsqueda por defecto por un select con los estados de los trámites
    const statusOptions = @json($status);
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
</script>
@endpush

