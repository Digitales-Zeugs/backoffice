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
                    <li class="breadcrumb-item"><a href="/works">Actualización de Datos</a></li>
                    <li class="breadcrumb-item active">Solicitud #{{ $profile->id }}</li>
                </ol>
            </div>
        </div>
    </section>
    <section class="content">
        <table class="table">
            <tr>
                <th scope="row">Id</th>
                <td>{{ $profile->id }}</td>
            </tr>
            <tr>
                <th scope="row">Socio</th>
                <td>{{ $profile->member_id }}</td>
            </tr>
            <tr>
                <th scope="row">Heredero</th>
                <td>{{ $profile->heir }}</td>
            </tr>
            <tr>
                <th scope="row">Email</th>
                <td>{{ $profile->email }}</td>
            </tr>
            <tr>
                <th scope="row">Nombre y Apellido</th>
                <td>{{ $profile->name }}</td>
            </tr>
            <tr>
                <th scope="row">Tipo de Dirección</th>
                <td>{{ $profile->address_type }}</td>
            </tr>
            <tr>
                <th scope="row">Dirección</th>
                <td>{{ $profile->address }}</td>
            </tr>
            <tr>
                <th scope="row">Código Postal</th>
                <td>{{ $profile->address_zip }}</td>
            </tr>
            <tr>
                <th scope="row">Localidad</th>
                <td>{{ $profile->address_city }}</td>
            </tr>
            <tr>
                <th scope="row">Provincia</th>
                <td>{{ $profile->address_state }}</td>
            </tr>
            <tr>
                <th scope="row">País</th>
                <td>{{ $profile->address_country }}</td>
            </tr>
            <tr>
                <th scope="row">Teléfono</th>
                <td>{{ $profile->phone_country . $profile->phone_area . $profile->phone_number }}</td>
            </tr>
            <tr>
                <th scope="row">Teléfono</th>
                <td>{{ $profile->cell_country . $profile->cell_area . $profile->cell_number }}</td>
            </tr>
            <tr>
                <th scope="row">Estado</th>
                <td>{{ $profile->status->name }}</td>
            </tr>
        </table>
        <div class="d-flex flex-column align-items-center flex-md-row justify-content-md-around">
        @if ($profile->status_id == 1)
        <form action="/profiles/{{ $profile->id }}/status" method="POST">
            @csrf
            <input type="hidden" name="status" value="2" />
            <button type="submit" class="btn bg-info">Marcar como Recibido</button>
        </form>
        @endif
        @if ($profile->status_id > 1)
        <form action="/profiles/{{ $profile->id }}/status" method="POST">
            @csrf
            <input type="hidden" name="status" value="3" />
            <button type="submit" class="btn bg-success">Aceptar</button>
        </form>
        <form action="/profiles/{{ $profile->id }}/status" method="POST">
            @csrf
            <input type="hidden" name="status" value="4" />
            <button type="submit" class="btn bg-danger">Rechazar</button>
        </form>
    </section>
</div>
@endif
</div>
@endsection