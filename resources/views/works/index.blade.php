@extends('dashboard.layout')

@section('content')
<div class="container">
    <section class="content-header">
        <h1>Solicitudes de Registro de Obra</h1>
    </section>
    <section class="content">
        <table class="table">
            <thead>
                <tr>
                    <th></th>
                    <th>#</th>
                    <th>Título</th>
                    <th>DNDA Letra</th>
                    <th>DNDA Música</th>
                    <th>Fecha</th>
                    <th></th>
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
                    <td></td>
                </tr>
            </tbody>
            <tfoot>
                <tr>
                    <th></th>
                    <th>#</th>
                    <th>Título</th>
                    <th>DNDA Letra</th>
                    <th>DNDA Música</th>
                    <th></th>
                    <th></th>
                </tr>
            </tfoot>
        </table>
    </section>
</div>
@endsection

@push('scripts')
<script>
window.onload = function() {
    const format = function( register ) {
        let output = '<table cellpadding="5" cellspacing="0" border="0" style="margin-left:50px;">';
        output += '<tr>';
        output += '<th>Función</th>';
        output += '<th>Apellido y Nombre</th>';
        output += '<th>DNI</th>';
        output += '<th>Socio</th>';
        output += '</tr>';
        register.distribution.forEach(function(current) {
            output += '<tr>';
            output += `<td>${current.role.description}</td>`;
            output += `<td>${current.name}</td>`;
            output += `<td>${current.dni}</td>`;
            output += `<td>${current.member}</td>`;
            output += '</tr>';
        })
        output += '</table>';

        return output;
    };

    const table = $('.table').DataTable({
        ajax: '{{ URL::current() }}',
        serverSide: true,
        dom: 'lrtip',
        columns: [
            {
                className:      'details-control',
                orderable:      false,
                searchable:     false,
                data:           null,
                defaultContent: ''
            },
            {
                name:       'id',
                data:       'id',
                searchable: true
            },
            {
                name:       'title',
                data:       'title',
                searchable: true
            },
            {
                name:       'lyric_dnda_file',
                data:       'lyric_dnda_file',
                searchable: true
            },
            {
                name:       'audio_dnda_file',
                data:       'audio_dnda_file',
                searchable: true
            },
            {
                name:        'created_at',
                data:        'created_at',
                searchable:  true,
                createdCell: function (cell, cellData) {
                    const parsedDate = new Date(cellData);
                    let output = `${ parsedDate.getDate() }/${ parsedDate.getMonth() + 1 }/`; // dd/mm
                    output += `${ parsedDate.getFullYear().toString().substring(2) } `; // yy
                    output += `${ parsedDate.getHours() }:${ parsedDate.getMinutes() }`; // hh:ii
                    $(cell).text(output);
                }
            },
            {
                className:      'view-control',
                orderable:      false,
                searchable:     false,
                data:           null,
                defaultContent: ''
            },
        ],
        initComplete: function () {
            {{-- Búsqueda --}}
            this.api().columns().every( function () {
                const column = this;

                const $footer = $(this.footer());
                const title = $footer.text();

                if (title) {
                    {{-- Crear inputs en el footer para hacer search --}}
                    $footer.html(`<input type="text" class="search_${ column.dataSrc() }" placeholder="${ title }" />`);

                    {{-- Bindear el evento change de los input --}}
                    $('input', $footer).on('keyup change clear', function () {
                        if ( column.search() !== this.value ) {
                            column.search( this.value ).draw();
                        }
                    });
                }
            });
        }
    });

    {{-- Mostrar/ocultar detalles --}}
    $('.table tbody').on('click', 'td.details-control', function () {
        const tr = $(this).closest('tr');
        const row = table.row( tr );
 
        if ( row.child.isShown() ) {
            row.child.hide();
            tr.removeClass('shown');
        }
        else {
            row.child( format(row.data()) ).show();
            tr.addClass('shown');
        }
    });

    {{-- Ver solicitud --}}
    $('.table tbody').on('click', 'td.view-control', function () {
        const tr = $(this).closest('tr');
        const row = table.row(tr);
 
        window.location = `/works/${ row.data().id }`;
    });
}
</script>
@endpush
