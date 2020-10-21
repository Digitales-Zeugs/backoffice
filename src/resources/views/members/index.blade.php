@extends('dashboard.layout')

@section('content')
<div class="container">
    <section class="content-header">
        <h1>Solicitudes de Registro de Socios</h1>
    </section>
    <section class="content">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Nombre</th>
                    <th>Documento</th>
                    <th>Email</th>
                    <th>Celular</th>
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
        ajax: '/members/datatables',
        serverSide: true,
        columns: [
            { name: 'id', data: 'id' },
            { name: 'name', data: 'name' },
            { name: 'doc_number', data: 'doc_number' },
            { name: 'email', data: 'email' },
            { name: 'mobile', data: 'mobile' },
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

