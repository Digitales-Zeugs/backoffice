@if ($registration->initiator->member_id)
<p>¡Hola {{ $registration->initiator->nombre }}!</p>
@else
<p>¡Hola {{ $registration->initiator->name }}!</p>
@endif

Notificación de Finalización de Solicitud de Registro de Obra