@extends('dashboard.layout')

@section('content')
<section class="container">
<h1>Solicitudes de Registro de Obra</h1>
</section>
<section class="container">
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
@endsection

@push('scripts')
<script>
window.onload = function() {
    const format = ( data ) => {
        output = '';
        output += `<strong>Duración:</strong> ${ data.duration }<br>`;

        return output;
    };

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
            { name: 'status_id', data: 'status_id' },
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
        order: [[1, 'desc']]
    });

    $('.table tbody').on( 'click', 'tr td.details-control', (event) => {
        const tr = $(event.target).closest('tr');
        const row = $dt.row( tr );

        if ( row.child.isShown() ) {
            tr.removeClass('shown');
            row.child.hide();
        } else {
            tr.addClass('shown');
            row.child( format( row.data() ) ).show();
        }
    });

    $('.table tbody').on( 'click', 'tr td.view-control', (event) => {
        const tr = $(event.target).closest('tr');
        const row = $dt.row( tr );

        window.location = `/works/${row.data()['id']}`;
    });

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

