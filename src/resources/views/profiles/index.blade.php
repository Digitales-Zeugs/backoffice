@extends('dashboard.layout')

@section('content')
<div class="container">
    <section class="content-header">
        <h1>Solicitudes de Actualización de Datos</h1>
    </section>
    <section class="content">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Socio</th>
                    <th>Heredero</th>
                    <th>Nombre</th>
                    <th>Email</th>
                    <th>Estado</th>
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
    const $dt = $('.table').DataTable({
        ajax: '/profiles/datatables',
        serverSide: true,
        columns: [
            { name: 'id', data: 'id' },
            { name: 'member_id', data: 'member_id' },
            { name: 'heir', data: 'heir' },
            { name: 'name', data: 'name' },
            { name: 'email', data: 'email' },
            { name: 'status_id', data: 'status.name' },
        ],
        searchCols: [
            null,
            null,
            null,
            null,
            null,
            { search: 1 }, // Status "Pendiente"
        ],
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

