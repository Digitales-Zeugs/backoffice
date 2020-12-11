@extends('dashboard.layout')

@section('content')
<div class="container">
    <section class="content-header">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark">
                    Solicitud N° {{ $registration->id }}
                </h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="/">Inicio</a></li>
                    <li class="breadcrumb-item"><a href="/members">Registro de Socios</a></li>
                    <li class="breadcrumb-item active">Solicitud #{{ $registration->id }}</li>
                </ol>
            </div>
        </div>
    </section>
    <section class="content">
        <table class="table">
            <tr>
                <th scope="row">Id</th>
                <td>{{ $registration->id }}</td>
            </tr>
            <tr>
                <th scope="row">Nombre y Apellido</th>
                <td>{{ $registration->name }}</td>
            </tr>
            <tr>
                <th scope="row">Fecha de Nacimiento</th>
                <td>{{ $registration->birth_date }}</td>
            </tr>
            <tr>
                <th scope="row">Localidad de Nacimiento</th>
                <td>{{ $registration->birth_city }}</td>
            </tr>
            <tr>
                <th scope="row">Provincia de Nacimiento</th>
                <td>{{ $registration->birth_state }}</td>
            </tr>
            <tr>
                <th scope="row">País de Nacimiento</th>
                <td>{{ $registration->birth_country }}</td>
            </tr>
            <tr>
                <th scope="row">Documento</th>
                <td>{{ $registration->doc_number }}</td>
            </tr>
            <tr>
                <th scope="row">CUIT</th>
                <td>{{ $registration->work_code }}</td>
            </tr>
            <tr>
                <th scope="row">Email</th>
                <td>{{ $registration->email }}</td>
            </tr>
            <tr>
                <th scope="row">Teléfono</th>
                <td>{{ $registration->landline }}</td>
            </tr>
            <tr>
                <th scope="row">Celular</th>
                <td>{{ $registration->mobile }}</td>
            </tr>
        </table>
    </section>
    @if (Auth::user()->can('nb_socios', 'carga'))
        {{-- Trámite Nuevo --}}
        @if ($registration->status_id == 1)
        <div class="row justify-content-center">
            <div>
                <button class="btn btn-success" id="beginAction">Iniciar Proceso</button>
                <button class="btn btn-danger" id="rejectAction">Rechazar Solicitud</button>
            </div>
        </div>
        {{-- Trámite en proceso interno --}}
        @elseif ($registration->status_id == 4)
        <div class="row justify-content-center">
            <div>
                <button class="btn btn-success" id="approveRequest">Aprobar</button>
                <button class="btn btn-danger" id="rejectRequest">Rechazar</button>
            </div>
        </div>
        @endif
    @endif
</div>
@endsection

@push('scripts')
<script>const memberId = {{ $registration->id }}</script>
<script src="{{ asset('/js/members.view.js') }}"></script>
@endpush