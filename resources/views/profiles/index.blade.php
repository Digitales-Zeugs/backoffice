@extends('dashboard.layout')

@section('content')
<section class="content-header">
<h1>Solicitudes de Actualización de Datos</h1>
</section>
<section class="content">
@if(count($updates) > 0)
<table class="table table-hover">
    <tr>
        <th>#</th>
        <th>Socio</th>
        <th>Heredero</th>
        <th>Nombre</th>
        <th>Email</th>
        <th>Fecha</th>
    </tr>
@foreach($updates as $update)
    <tr class="clickable" data-id="{{ $update->id }}" style="cursor: pointer">
        <td>{{ $update->id }}</td>
        <td>{{ $update->member_id }}</td>
        <td>{{ $update->heir }}</td>
        <td>{{ $update->name }}</td>
        <td>{{ $update->email }}</td>
        <td>{{ $update->created_at->format('d/m/Y H:i') }}</td>
    </tr>
@endforeach
</table>
@endif
</section>
@endsection

@push('scripts')
<script>
window.onload = function() {
    jQuery('.clickable').on('click', function(event) {
        window.location = `/profiles/${this.dataset.id}`;
    });
}
</script>
@endpush

