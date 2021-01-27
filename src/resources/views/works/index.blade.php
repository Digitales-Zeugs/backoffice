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
                    <th>Editor</th>
                    <th>Jingle</th>
                    <th>M. Película</th>
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
<script src="{{ asset('/js/works.index.js') }}"></script>
@endpush

