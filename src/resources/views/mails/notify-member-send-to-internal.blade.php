@extends('mails.template')

@section('content')
<div class="kicker">Solicitud #{{ $registration_id }}</div>
<p>¡Hola {{ $nombre }}!</p>
<p>Tu solicitud de registro como responsable inscripto está siendo procesada, para ver más información accedé al sitio de autogestión de SADAIC:</p>
<div class="cta">
    <a class="btn" href="{{ route('login') }}">Ir a la Autogestión</a>
</div>
@endsection