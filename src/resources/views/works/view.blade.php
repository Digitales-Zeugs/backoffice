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
            <td>{{ optional($registration->created_at)->format('d/m/Y H:i') }}</td>
        </tr>
        <tr>
            <th>Estado</th>
            <td>{{ optional($registration->status)->name }}</td>
        </tr>
        <tr>
            <th colspan="2" class="table-inner-title">DNDA</th>
        </tr>
        <tr>
            <th colspan="2" class="table-inner-subtitle">Inédito</th>
        </tr>
        <tr>
            <th>N° Expediente (Audio)</th>
            <td>{{ $registration->audio_dnda_in_file }}</td>
        </tr>
        <tr>
            <th>N° Expediente (Letra)</th>
            <td>{{ $registration->lyric_dnda_in_file }}</td>
        </tr>
        <tr>
            <th>Fecha</th>
            <td>{{ optional($registration->dnda_in_date)->format('d/m/Y') }}</td>
        </tr>
        <tr>
            <th colspan="2" class="table-inner-subtitle">Editado</th>
        </tr>
        <tr>
            <th>N° Expediente (Audio)</th>
            <td>{{ $registration->audio_dnda_ed_file }}</td>
        </tr>
        <tr>
            <th>N° Expediente (Letra)</th>
            <td>{{ $registration->lyric_dnda_ed_file }}</td>
        </tr>
        <tr>
            <th>Fecha</th>
            <td>{{ $registration->dnda_ed_date->format('d/m/Y') }}</td>
        </tr>
        <tr>
            <th>Transcripción</th>
            <td>{!! nl2br(e($registration->lyric_text)) !!}</td>
        </tr>
        <tr>
            <th colspan="2" class="table-inner-title">Distribución</th>
        </tr>
        @foreach ($registration->distribution as $distribution)
        <tr>
            @if ($distribution->type == 'member')
            <th>{{ $distribution->member->nombre }}<br><small>Socio n° {{ $distribution->member_id }}</small></th>
            @else
            <th>{{ $distribution->meta->name }}<br><small>DNI n° {{ $distribution->doc_number }}</small></th>
            @endif
            <td>
                <strong>DNI:</strong> {{ $distribution->doc_number }}<br>
                <strong>Distribución Pública:</strong> {{ $distribution->public }}%<br>
                <strong>Distribución Mecánica:</strong> {{ $distribution->mechanic }}%<br>
                <strong>Distribución Sincronización:</strong> {{ $distribution->sync }}%<br>
                <strong>Respuesta:</strong>
                @if ($distribution->response === null) No Respondió
                @elseif ($distribution->response === 0) Rechazado ({{ $distribution->updated_at->format('d/m/Y H:i') }})
                @elseif ($distribution->response === 1) Aceptado ({{ $distribution->updated_at->format('d/m/Y H:i') }})
                @endif
            </td>
        </tr>
        @endforeach
    </table>
    <br /><br />
    {{-- Trámite Nuevo --}}
    @if ($registration->status_id == 1)
    <div class="row justify-content-center">
        <div>
            <button class="btn btn-success" id="beginAction">Iniciar Proceso</button>
            <button class="btn btn-danger" id="rejectAction">Rechazar Solicitud</button>
        </div>
    </div>
    {{-- Aprobado por todos los propietarios --}}
    @elseif ($registration->status_id == 5)
    <div class="row justify-content-center">
        <div>
            <button class="btn btn-primary" id="sendToInternal">Pase a Procesamiento Interno</button>
        </div>
    </div>
    {{-- En sistema interno --}}
    @elseif ($registration->status_id == 6)
    <div class="row justify-content-center">
        <div>
            <button class="btn btn-success" id="approveRequest">Aprobar</button>
            <button class="btn btn-danger" id="rejectRequest">Rechazar</button>
        </div>
    </div>
    @endif
    <br /><br />
</section>
@endsection

@push('scripts')
<script>
$('#beginAction').on('click', () => {
    axios.post('/works/{{ $registration->id }}/status', {
        status: 'beginAction'
    })
    .then(({ data }) => {
        if (data.status == 'failed') {
            toastr.error('No se puede realizar iniciar el proceso de la solicitud.');

            data.errors.forEach(e => {
                toastr.warning(e);
            })
        }
    });
});

$('#rejectAction').on('click', () => {
    axios.post('/works/{{ $registration->id }}/status', {
        status: 'rejectAction'
    });
});

$('#sendToInternal').on('click', () => {
    axios.post('/works/{{ $registration->id }}/status', {
        status: 'sendToInternal'
    });
});

$('#approveRequest').on('click', () => {
    axios.post('/works/{{ $registration->id }}/status', {
        status: 'approveRequest'
    });
});

$('#rejectRequest').on('click', () => {
    axios.post('/works/{{ $registration->id }}/status', {
        status: 'rejectRequest'
    });
});
</script>
@endpush
