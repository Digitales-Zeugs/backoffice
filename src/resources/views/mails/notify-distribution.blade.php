@if ($distribution->type == 'member')
<p>¡Hola {{ $distribution->member->nombre }}!</p>
@else
<p>¡Hola {{ $distribution->meta->name }}!</p>
@endif

Notificación de Solicitud de Registro de Obra