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
                    <th></th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
    </section>
</div>
@endsection

@push('scripts')
<script>const statusOptions = @json($status);</script>
<script src="{{ asset('/js/members.index.js') }}"></script>
@endpush
