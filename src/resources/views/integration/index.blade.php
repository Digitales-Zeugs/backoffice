@extends('dashboard.layout')

@section('content')
<div class="container">
    <section class="content-header">
        <div class="row mb-2">
            <div class="col-sm-12">
                <h1 class="m-0 text-dark">Integración</h1>
            </div>
        </div>
    </section>
    <section class="content">
        <button type="button" class="btn btn-primary" id="exportWorks">Registros de Obra</button>
    </section>
</div>
@endsection

@push('scripts')
<script src="{{ asset('/js/integration.js') }}"></script>
@endpush