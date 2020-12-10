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
        <div class="row mb-4">
            <h3 class="col-3">Registros de Obras</h3>
            <div id="exportWorksWrapper" class="col-2">
                <button type="button" class="btn btn-danger w-100" id="exportWorks">Exportar</button>
            </div>
            <div class="col-1"></div>
            <div id="importWorksWrapper" class="col-2">
                <button type="button" class="btn btn-warning w-100" id="importWorks">Importar</button>
                <input type="file" class="d-none" name="file" id="importWorksFile" accept="application/json">
            </div>
        </div>
        <div class="row mb-4">
            <h3 class="col-3">Inclusiones de Obras</h3>
            <div id="exportJinglesWrapper" class="col-2">
                <button type="button" class="btn btn-danger w-100" id="exportJingles">Exportar</button>
            </div>
        </div>
        <div class="row">
            <h3 class="col-3">Registros de Socios</h3>
            <div id="exportMembersWrapper" class="col-2">
                <button type="button" class="btn btn-danger w-100" id="exportMembers">Exportar</button>
            </div>
        </div>
    </section>
</div>
@endsection

@push('scripts')
<script src="{{ asset('/js/integration.js') }}"></script>
@endpush