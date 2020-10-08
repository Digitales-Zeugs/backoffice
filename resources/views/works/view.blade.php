@extends('dashboard.layout')

@section('content')
<div class="content-header">
    <div class="container">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark">
                    Solicitud N° {{ $registration->id }}
                </h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="/">Inicio</a></li>
                    <li class="breadcrumb-item"><a href="/works">Registro de Obras</a></li>
                    <li class="breadcrumb-item active">Solicitud #{{ $registration->id }}</li>
                </ol>
            </div>
        </div>
    </div>
</div>
<section class="content container">
    <table class="table">
        <tr>
            <th colspan="2" class="table-inner-title">Datos generales</th>
        </tr>
        <tr>
            <th>Título</th>
            <td>{{ $registration->title }}</td>
        </tr>
        <tr>
            <th>Fecha</th>
            <td>{{ $registration->created_at->format('d/m/Y H:i') }}</td>
        </tr>
        <tr>
            <th colspan="2" class="table-inner-title">Letra</th>
        </tr>
        <tr>
            <th>N° Expediente DNDA</th>
            <td>{{ $registration->lyric_dnda_file }}</td>
        </tr>
        <tr>
            <th>Fecha DNDA</th>
            <td>{{ $registration->lyric_dnda_date->format('d/m/Y') }}</td>
        </tr>
        <tr>
            <th>Transcripción</th>
            <td>{!! nl2br(e($registration->lyric_text)) !!}</td>
        </tr>
        <tr>
            <th colspan="2" class="table-inner-title">Audio</th>
        </tr>
        <tr>
            <th>N° Expediente DNDA</th>
            <td>{{ $registration->audio_dnda_file }}</td>
        </tr>
        <tr>
            <th>Fecha DNDA</th>
            <td>{{ $registration->audio_dnda_date->format('d/m/Y') }}</td>
        </tr>
        <tr>
            <th colspan="2" class="table-inner-title">Distribución</th>
        </tr>
        @foreach ($registration->distribution as $distribution)
        <tr>
            <th>{{ $distribution->name }}<br><small>Socio n° {{ $distribution->member }}</small></th>
            <td>
                <strong>DNI:</strong> {{ $distribution->dni }}<br>
                <strong>Porcentaje:</strong> {{ $distribution->amount }}%<br>
                <strong>Respuesta:</strong>
                @if ($distribution->response === null) No Respondió
                @elseif ($distribution->response === 0) Rechazado ({{ $distribution->updated_at->format('d/m/Y H:i') }})
                @elseif ($distribution->response === 1) Aceptado ({{ $distribution->updated_at->format('d/m/Y H:i') }})
                @endif
            </td>
        </tr>
        @endforeach
    </table>
</section>
@endsection

@push('scripts')
@endpush
