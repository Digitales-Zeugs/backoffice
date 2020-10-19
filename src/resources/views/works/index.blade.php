@extends('dashboard.layout')

@section('content')
<section class="container">
<h1>Solicitudes de Registro de Obra</h1>
</section>
<section class="container">
@if(count($requests) > 0)
<table class="table table-hover">
    <tr>
        <th>#</th>
        <th>Título</th>
        <th>Fecha</th>
    </tr>
@foreach($requests as $request)
    <tr class="clickable" data-id="{{ $request->id }}" style="cursor: pointer">
        <td>{{ $request->id }}</td>
        <td>{{ $request->title }}</td>
        <td>{{ $request->created_at->format('d/m/Y H:i') }}</td>
    </tr>
@endforeach
</table>
@else
<div class="alert alert-warning" role="alert">
  No hay solicitudes pendientes.
</div>
@endif
</section>
@endsection

@push('scripts')
<script>
window.onload = function() {
    jQuery('.clickable').on('click', function(event) {
        window.location = `/works/${this.dataset.id}`;
    });
}
</script>
@endpush

