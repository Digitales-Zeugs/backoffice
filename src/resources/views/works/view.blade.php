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
                    <li class="breadcrumb-item"><a href="/works">Registro de Obras</a></li>
                    <li class="breadcrumb-item active">Solicitud #{{ $registration->id }}</li>
                </ol>
            </div>
        </div>
    </section>
    <section class="content">
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
            @if ($registration->dnda_in_date)
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
            @endif
            @if ($registration->dnda_ed_date)
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
            @endif
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
                    <div class="d-flex flex-row align-items-center">
                    <strong>Respuesta:</strong>&nbsp;
                    @if ($distribution->response === null)
                        @if ($distribution->type == 'member' || $registration->status_id == 1 || !Auth::user()->can('nb_obras', 'carga')) Sin respuesta
                        @else
                            <button class="btn btn-link text-success" id="acceptDistribution" data-did="{{ $distribution->id }}">Aceptar</button>&nbsp;&nbsp;
                            <button class="btn btn-link text-danger" id="rejectDistribution" data-did="{{ $distribution->id }}">Rechazar</button>
                        @endif
                    @elseif ($distribution->response === 0) Rechazado ({{ $distribution->updated_at->format('d/m/Y H:i') }})
                    @elseif ($distribution->response === 1) Aceptado ({{ $distribution->updated_at->format('d/m/Y H:i') }})
                    @endif
                    </div>
                </td>
            </tr>
            @endforeach
            <tr>
                <th colspan="2" class="table-inner-title">Archivos</th>
            </tr>
            @foreach ($registration->files as $file)
                @php
                $desc = '';
                switch($file->name) {
                    case 'lyric_file': $desc = 'Archivo Partitura'; break;
                    case 'audio_file': $desc = 'Archivo de Audio'; break;
                    case 'script_file': $desc = 'Archivo Letra'; break;
                    case 'dnda_file': $desc = 'Archivo DNDA'; break;
                    default: 
                        $name = explode('_', $file->name);

                        if ($name[1] == 'editor' || $name[1] == 'subeditor' || $name[1] == 'dnda') {
                            if ($name[2] == 'contract') {
                                $desc = 'Contrato';
                            } elseif ($name[2] == 'triage') {
                                $desc = 'Contrato de triaje';
                            }
                        } elseif ($name[1] == 'no-member') {
                            $desc = 'Documento';
                        }

                        $desc .= ' <strong>';

                        if ($name[1] != 'dnda') {
                            if ($file->distribution->member_id) {
                                $desc .= $file->distribution->member->nombre;
                            } else {
                                $desc .= $file->distribution->meta->name;
                            }
                        } else {
                            $desc .= 'DNDA';
                        }

                        $desc .= '</strong>';
                    }
                @endphp
                <tr>
                    <th>{!! $desc !!}</th>
                    <td><a href="/works/files?file={{ $file->path }}">Descargar</a></td>
                </tr>
            @endforeach
            <tr>
                <th colspan="2" class="table-inner-title">Registro</th>
            </tr>
            @foreach ($registration->logs as $log)
            <tr>
                <th>{{ $log->time->format('d/m/Y H:i') }}</th>
                @switch($log->action->name)
                    @case('REGISTRATION_ACCEPTED')
                        <td>{{ $log->action->description }} {{ isset($log->action_data['forced']) ? '(Forzado)' : '' }}</td>
                        @break
                    @case('DISTRIBUTION_CONFIRMED')
                    @case('DISTRIBUTION_REJECTED')
                        <td>{{ $log->action->description }} ({{
                            $log->distribution->member_id
                            ? $log->distribution->member->nombre
                            : $log->distribution->meta->name
                            }}{{ isset($log->action_data['operator_id']) ? ' por ' . $log->action_data['operator_id'] : '' }})</td>
                        @break
                    @case('REGISTRATION_NOT_NOTIFIED')
                        <td>{{ $log->action->description }} ({{
                            $log->distribution->member_id
                            ? $log->distribution->member->nombre
                            : $log->distribution->meta->name
                            }})</td>
                        @break
                    @default
                        <td>{{ $log->action->description }}</td>
                @endswitch
            </tr>
            @endforeach
        </table>
        <br /><br />
        @if (Auth::user()->can('nb_obras', 'carga'))
        {{-- Trámite Nuevo --}}
        @if ($registration->status_id == 1)
        <div class="row justify-content-center">
            <div>
                <button class="btn btn-success" id="beginAction">Iniciar Proceso</button>
                <button class="btn btn-warning" id="beginForceAction" style="display: none">Iniciar Proceso a pesar de los errores</button>
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
        {{-- Aprobada/Rechazada --}}
        @elseif ($registration->status_id == 7 || $registration->status_id == 8)
        <div class="row justify-content-center">
            <div>
                <button class="btn btn-primary" id="finishRequest">Finalizar</button>
            </div>
        </div>
        @endif
        @endif
    </section>
</div>
@endsection

@push('scripts')
<script>
$('#beginAction').on('click', () => {
    axios.post('/works/{{ $registration->id }}/status', {
        status: 'beginAction'
    })
    .catch((err) => {
        toastr.error('Se encontró un problema mientras se realizaba la solicitud')
    })
    .then(({ data }) => {
        if (data.status == 'failed') {
            toastr.error('No se puede iniciar el proceso de la solicitud.');

            data.errors.forEach(e => {
                toastr.warning(e);
            });

            if (data.continue) {
                $('#beginAction').css('display', 'none');
                $('#beginForceAction').css('display', 'inline-block');
            }
        } else if (data.status == 'success') {
            toastr.success('Proceso iniciado correctamente');
            setTimeout(() => { location.reload() }, 1000);
        }
    });
});

$('#beginForceAction').on('click', () => {
    axios.post('/works/{{ $registration->id }}/status', {
        status: 'beginAction',
        force: true
    })
    .catch((err) => {
        toastr.error('Se encontró un problema mientras se realizaba la solicitud')
    })
    .then(({ data }) => {
        if (data.status == 'failed') {
            toastr.error('No se puede iniciar el proceso de la solicitud.');

            data.errors.forEach(e => {
                toastr.warning(e);
            });
        } else if (data.status == 'success') {
            toastr.success('Proceso iniciado correctamente');
            setTimeout(() => { location.reload() }, 1000);
        }
    });
});

$('#rejectAction').on('click', () => {
    axios.post('/works/{{ $registration->id }}/status', {
        status: 'rejectAction'
    })
    .then(({ data }) => {
        if (data.status == 'failed') {
            toastr.error('No se pudo realizar el rechazo de la solicitud.');

            data.errors.forEach(e => {
                toastr.warning(e);
            });
        } else if (data.status == 'success') {
            toastr.success('Rechazo guardado correctamente');
            setTimeout(() => { location.reload() }, 1000);
        }
    });
});

$('#sendToInternal').on('click', () => {
    axios.post('/works/{{ $registration->id }}/status', {
        status: 'sendToInternal'
    })
    .then(({ data }) => {
        if (data.status == 'failed') {
            toastr.error('No se pudo registrar el pase a sistema interno de la solicitud.');

            data.errors.forEach(e => {
                toastr.warning(e);
            });
        } else if (data.status == 'success') {
            toastr.success('Pase registrado correctamente');
            setTimeout(() => { location.reload() }, 1000);
        }
    });
});

$('#approveRequest').on('click', () => {
    axios.post('/works/{{ $registration->id }}/status', {
        status: 'approveRequest'
    })
    .then(({ data }) => {
        if (data.status == 'failed') {
            toastr.error('No se pudo registrar la aprobación de la solicitud.');

            data.errors.forEach(e => {
                toastr.warning(e);
            });
        } else if (data.status == 'success') {
            toastr.success('Aprobación registrada correctamente');
            setTimeout(() => { location.reload() }, 1000);
        }
    });
});

$('#rejectRequest').on('click', () => {
    axios.post('/works/{{ $registration->id }}/status', {
        status: 'rejectRequest'
    })
    .then(({ data }) => {
        if (data.status == 'failed') {
            toastr.error('No se pudo registrar el rechazo de la solicitud.');

            data.errors.forEach(e => {
                toastr.warning(e);
            });
        } else if (data.status == 'success') {
            toastr.success('Rechazo registrado correctamente');
            setTimeout(() => { location.reload() }, 1000);
        }
    });
});

$('#finishRequest').on('click', () => {
    axios.post('/works/{{ $registration->id }}/status', {
        status: 'finishRequest'
    })
    .then(({ data }) => {
        if (data.status == 'failed') {
            toastr.error('No se pudo registrar la finalización del trámite.');

            data.errors.forEach(e => {
                toastr.warning(e);
            });
        } else if (data.status == 'success') {
            toastr.success('Finalización registrada correctamente');
            setTimeout(() => { location.reload() }, 1000);
        }
    });
});

$('#acceptDistribution').on('click', (event) => {
    axios.post('/works/{{ $registration->id }}/response', {
        response: 'accept',
        distribution_id: $(event.target).data('did')
    })
    .catch((err) => {
        toastr.error('Se encontró un problema mientras se realizaba la solicitud')
    })
    .then(({ data }) => {
        if (data.status == 'failed') {
            toastr.error('No se puedo cambiar la respuesta a la solicitud.');

            data.errors.forEach(e => {
                toastr.warning(e);
            });
        } else if (data.status == 'success') {
            toastr.success('Respuesta cambiada correctamente');
            setTimeout(() => { location.reload() }, 1000);
        }
    });
});
</script>
@endpush
