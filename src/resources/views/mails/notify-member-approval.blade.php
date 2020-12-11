@extends('mails.template')

@section('content')
<div class="kicker">Solicitud #{{ $registration_id }}</div>
<p>¡Hola {{ $nombre }}!</p>
<p>Tu solicitud de registro como responsable inscripto fue <strong>APROBADA</strong>.</p>
@endsection