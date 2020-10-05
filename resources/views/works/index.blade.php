@extends('dashboard.layout')

@section('content')
<div class="container">
    <section class="content-header">
        <h1>Solicitudes de Registro de Obra</h1>
    </section>
    <section class="content">
    @if(count($requests) > 0)
    <table class="table">
        <thead>
            <tr>
                <th></th>
                <th>#</th>
                <th>Título</th>
                <th>DNDA Letra</th>
                <th>DNDA Música</th>
                <th>Fecha</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
        </tbody>
    </table>
    @else
    <div class="alert alert-warning" role="alert">
    No hay solicitudes pendientes.
    </div>
    @endif
    </section>
</div>
@endsection

@push('scripts')
<script>
window.onload = function() {
    const data = {!! json_encode($requests) !!};
    const roles = {!! json_encode($roles) !!};

    console.log(roles);

    const format = function( register ) {
        let output = '<table cellpadding="5" cellspacing="0" border="0" style="margin-left:50px;">';
        output += '<tr>';
        output += '<th>Función</th>';
        output += '<th>Apellido y Nombre</th>';
        output += '<th>DNI</th>';
        output += '<th>Socio</th>';
        output += '<th>Consentimiento</th>';
        output += '</tr>';
        register.distribution.forEach(function(current) {
            const role = roles.find(r => r.code == current.function);

            output += '<tr>';
            output += `<td>${role.description}</td>`;
            output += `<td>${current.name}</td>`;
            output += `<td>${current.dni}</td>`;
            output += `<td>${current.member}</td>`;
            output += `<td>${current.updated_at}</td>`;
            output += '</tr>';
        })
        output += '</table>';

        return output;
    };

    const table = $('.table').DataTable({
        "data": data,
        "columns": [
            {
                "className":      'details-control',
                "orderable":      false,
                "data":           null,
                "defaultContent": ''
            },
            { "data": "id" },
            { "data": "title" },
            { "data": "lyric_dnda_file" },
            { "data": "audio_dnda_file" },
            { "data": "created_at" }
        ]
    });

    $('.table tbody').on('click', 'td.details-control', function () {
        var tr = $(this).closest('tr');
        var row = table.row( tr );
 
        if ( row.child.isShown() ) {
            row.child.hide();
            tr.removeClass('shown');
        }
        else {
            row.child( format(row.data()) ).show();
            tr.addClass('shown');
        }
    });
}
</script>
@endpush
