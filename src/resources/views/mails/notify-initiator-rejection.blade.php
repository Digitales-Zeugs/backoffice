@extends('mails.template')

@section('content')
<div class="kicker">Solicitud #{{ $registration_id }}</div>
<p>¡Hola {{ $nombre }}!</p>
<p>El trámite de Registro de Obra que iniciaste fue rechazado, para ver más información accedé al sitio de autogestión de SADAIC:</p>
<div class="cta">
    <a class="btn" href="{{ config('app.sitio_publico') }}">Ir a la Autogestión</a>
</div>
@endsection
