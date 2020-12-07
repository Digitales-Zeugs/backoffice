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
                    <li class="breadcrumb-item"><a href="/jingles">Inclusión de Obras</a></li>
                    <li class="breadcrumb-item active">Solicitud #{{ $registration->id }}</li>
                </ol>
            </div>
        </div>
    </section>
    <section class="content">
        <table class="table">
            <tr>
                <th colspan="2" class="table-inner-title">Datos del solicitante</th>
            </tr>
            @include('components.agency-view', ['type' => 'applicant'])
            <tr>
                <th colspan="2" class="table-inner-title">Datos del anunciante</th>
            </tr>
            @include('components.agency-view', ['type' => 'advertiser'])
            <tr>
                <th colspan="2" class="table-inner-title">Tipo de Solicitud</th>
            </tr>
            <tr>
                <th>Tipo</th>
                <td>{{ $registration->is_special ? 'Especial' : 'Regular' }} - {{ $registration->request_action }}</td>
            </tr>
            <tr>
                <th>Vigencia en meses</th>
                <td>{{ $registration->validity }}</td>
            </tr>
            <tr>
                <th>Fecha de salida al aire</th>
                <td>{{ $registration->air_date->format('d/m/Y') }}</td>
            </tr>
            @if ($registration->is_special)
            <tr>
                <th colspan="2" class="table-inner-title">Campaña Especial</th>
            </tr>
            <tr>
                <th>Cantidad de avisos</th>
                <td>{{ count($registration->ads_duration) }}</td>
            </tr>
            <tr>
                <th>Duración de los avisos</th>
                <td>@foreach($registration->ads_duration as $duration)
                    Aviso {{ $loop->iteration }}: {{ $duration }}
                    @if(!$loop->last) <br> @endif
                @endforeach</td>
            </tr>
            @else
            <tr>
                <th>Duración en segundos</th>
                <td>{{ $registration->ads_duration[0] }}</td>
            </tr>
            @endif
            <tr>
                <th colspan="2" class="table-inner-title">Características del Aviso Publicitario</th>
            </tr>
            <tr>
                <th>Territorio de Difusión</th>
                <td>{{ $registration->broadcast_territory }}</td>
            </tr>
            @if ($registration->broadcast_territory_id == 2)
            <tr>
                <th>Detalle de Provincias</th>
                <td>{{ $registration->territories->implode('state', ', ') }}</td>
            </tr>
            @elseif ($registration->broadcast_territory_id == 3)
            <tr>
                <th>Detalle de Paises</th>
                <td>{{ $registration->territories->implode('name_ter', ', ') }}</td>
            </tr>
            @endif
            <tr>
                <th colspan="2" class="table-inner-title">Medios de Comunicación</th>
            </tr>
            <tr>
                <th>{{ $registration->media->name }}</th>
                <td>{{ $registration->media->description }}</td>
            </tr>
            <tr>
                <th colspan="2" class="table-inner-title">Datos de la Agencia</th>
            </tr>
            <tr>
                <th>Tipo</th>
                <td>{{ $registration->agency_type }}</td>
            </tr>
            @include('components.agency-view', ['type' => 'agency'])
            <tr>
                <th colspan="2" class="table-inner-title">Datos del Producto / Servicio</th>
            </tr>
            <tr>
                <th>Marca</th>
                <td>{{ $registration->product_brand }}</td>
            </tr>
            <tr>
                <th>Característica y Tipo</th>
                <td>{{ $registration->product_type }}</td>
            </tr>
            <tr>
                <th>Identicación y Nombre</th>
                <td>{{ $registration->product_name }}</td>
            </tr>
            <tr>
                <th colspan="2" class="table-inner-title">Datos de la Obra</th>
            </tr>
            <tr>
                <th>Título</th>
                <td>{{ $registration->work_title }}</td>
            </tr>
            <tr>
                <th>Original</th>
                <td>{{ $registration->work_original ? 'Si' : 'No' }}</td>
            </tr>
            <tr>
                <th>DNDA</th>
                <td>{{ $registration->work_dnda }}</td>
            </tr>
            <tr>
                <th>Autores</th>
                <td>{{ $registration->work_authors }}</td>
            </tr>
            <tr>
                <th>Compositores</th>
                <td>{{ $registration->work_composers }}</td>
            </tr>
            <tr>
                <th>Editores</th>
                <td>{{ $registration->work_editors }}</td>
            </tr>
            <tr>
                <th>Letra modificada</th>
                <td>{{ $registration->work_script_mod ? 'Si' : 'No' }}</td>
            </tr>
            <tr>
                <th>Música modificada</th>
                <td>{{ $registration->work_music_mod ? 'Si' : 'No' }}</td>
            </tr>
            <tr>
                <th colspan="2" class="table-inner-title">Conformidad de los Autores</th>
            </tr>
            <tr>
                <th>¿Cuenta con la conformidad de los autores?</th>
                <td>{{ $registration->authors_agreement ? 'Si' : 'No' }}</td>
            </tr>
            @if ($registration->authors_agreement)
                @foreach($registration->agreements as $person)
                    <tr>
                        @if ($person->type_id == 1)
                        <th>{{ $person->member->nombre }}</th>
                        <td>
                            <strong>N° de Documento:</strong> {{ $person->doc_number }}<br>
                            <strong>Correo electrónico:</strong> {{ $person->member->email }}<br>
                        @else
                        <th>{{ $person->meta->name }}</th>
                        <td>
                            <strong>Nacimiento:</strong> {{ $person->meta->birth_date->format('d/m/Y') }}, {{ $person->meta->birth_country->name_ter }}<br>
                            <strong>Dirección:</strong> {{ $person->meta->full_address }}<br>
                            <strong>Correo electrónico:</strong> <a href="mailto:{{ $person->meta->email }}">{{ $person->meta->email }}</a><br>
                            <strong>Teléfono:</strong> {{ $person->meta->full_phone }}<br>
                        @endif
                            <strong>Respuesta:</strong>
                            @if ($person->response === null)
                            Sin respuesta
                            @if (in_array($registration->status_id, [2, 3]) && Auth::user()->can('nb_obras', 'carga'))
                            &nbsp;<button class="btn btn-link text-success acceptDistribution" data-did="{{ $person->id }}">Aceptar</button>
                            &nbsp;<button class="btn btn-link text-danger rejectDistribution" data-did="{{ $person->id }}">Rechazar</button>
                            @endif
                            @elseif ($person->response === 0)
                                Rechazado por
                                {{ $person->liable_id ?? 'el socio' }}
                                ({{ $person->updated_at->format('d/m/Y H:i') }})
                                @if (in_array($registration->status_id, [2, 3]) && Auth::user()->can('nb_obras', 'carga'))
                                &nbsp;<button class="btn btn-link text-success acceptDistribution" data-did="{{ $person->id }}">Cambiar Respuesta</button>
                                @endif
                            @elseif ($person->response === 1)
                                Aceptado por 
                                {{ $person->liable_id ?? 'el socio' }}
                                ({{ $person->updated_at->format('d/m/Y H:i') }})
                            @endif
                        </td>
                    </tr>
                @endforeach
            <tr>
                <th colspan="2" class="table-inner-title">Arancel</th>
            </tr>
            <tr>
                <th>Monto</th>
                <td>$ {{ number_format($registration->authors_tariff, 2) }}</td>
            </tr>
            @endif
            <tr>
                <th colspan="2" class="table-inner-title">Persona física o jurídica que abona el derecho de autor</th>
            </tr>
            <tr>
                <th>Persona</th>
                <td>{{ $registration->tariff_payer }}</td>
            </tr>
            @if ($registration->tariff_payer_id == 3)
            <tr>
                <th>A cuenta y orden de</th>
                <td>{{ $registration->tariff_representation }}</td>
            </tr>
            @endif
            <tr>
                <th colspan="2" class="table-inner-title">Registro</th>
            </tr>
            @foreach ($registration->logs as $log)
            <tr>
                <th>{{ $log->time->format('d/m/Y H:i') }}</th>
                @switch($log->action->name)
                    @case('REQUEST_ACCEPTED')
                        <td>{{ $log->action->description }} {{ isset($log->action_data['forced']) ? '(Forzado)' : '' }}</td>
                        @break
                    @case('AGREEMENT_CONFIRMED')
                    @case('AGREEMENT_REJECTED')
                        <td>{{ $log->action->description }} ({{
                            $log->agreement->member_idx
                            ? $log->agreement->member->nombre
                            : $log->agreement->meta->name
                            }}{{ isset($log->action_data['operator_id']) ? ' por ' . $log->action_data['operator_id'] : '' }})</td>
                        @break
                    @case('REGISTRATION_NOT_NOTIFIED')
                        <td>{{ $log->action->description }} ({{
                            $log->agreement->member_idx
                            ? $log->agreement->member->nombre
                            : $log->agreement->meta->name
                            }})</td>
                        @break
                    @default
                        <td>{{ $log->action->description }}</td>
                @endswitch
            </tr>
            @endforeach
            <tr>
                <th colspan="2" class="table-inner-title">Observaciones</th>
            </tr>
            <tr>
                <td colspan="2" id="observationsWrapper">
                    @if (Auth::user()->can('nb_obras', 'carga'))
                    <textarea id="observations">{{ $registration->observations }}</textarea>
                    <button class="btn btn-secondary float-right" id="saveObservations">Guardar Observaciones</button>
                    @else
                    <div id="observations">{!! nl2br(e($registration->observations)) !!}</div>
                    @endif
                </td>
            </tr>
        </table>
        <br><br>
        @if (Auth::user()->can('nb_obras', 'carga'))
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
<script>const jingleId = {{ $registration->id }}</script>
<script src="{{ asset('/js/jingles.view.js') }}"></script>
@endpush

@push('styles')
<style>
.btn-link:hover {
    text-decoration: underline;
}
</style>
@endpush