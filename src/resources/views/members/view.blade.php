@extends('dashboard.layout')

@section('content')
<div class="container">
    <section class="content-header">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark">
                    Solicitud N° {{ $profile->id }}
                </h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="/">Inicio</a></li>
                    <li class="breadcrumb-item"><a href="/works">Registro de Socios</a></li>
                    <li class="breadcrumb-item active">Solicitud #{{ $profile->id }}</li>
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
                <th scope="row">Apellido</th>
                <td>{{ $profile->lastname }}</td>
            </tr>
            <tr>
                <th scope="row">Nombre</th>
                <td>{{ $profile->forename }}</td>
            </tr>
            <tr>
                <th scope="row">Fecha de Nacimiento</th>
                <td>{{ $profile->birth_date }}</td>
            </tr>
            <tr>
                <th scope="row">Localidad de Nacimiento</th>
                <td>{{ $profile->birth_city }}</td>
            </tr>
            <tr>
                <th scope="row">Provincia de Nacimiento</th>
                <td>{{ $profile->birth_state }}</td>
            </tr>
            <tr>
                <th scope="row">País de Nacimiento</th>
                <td>{{ $profile->birth_country }}</td>
            </tr>
            <tr>
                <th scope="row">Documento</th>
                <td>{{ $profile->doc_number }}</td>
            </tr>
            <tr>
                <th scope="row">CUIT</th>
                <td>{{ $profile->work_code }}</td>
            </tr>
            <tr>
                <th scope="row">Email</th>
                <td>{{ $profile->email }}</td>
            </tr>
            <tr>
                <th scope="row">Teléfono</th>
                <td>{{ $registration->landline }}</td>
            </tr>
            <tr>
                <th scope="row">Celular</th>
                <td>{{ $profile->mobile }}</td>
            </tr>
        </table>
    </section>
</div>
@endif
</div>
@endsection