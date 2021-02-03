@extends('mails.template')

@section('content')
<div class="kicker">Solicitud #{{ $registration_id }}</div>
<p>¡Hola {{ $nombre }}!</p>
<p>Tu obra ingresó con éxito a nuestra base de datos.</p>
<p>Para más detalles, te pedimos que te dirijas al sitio de Autogestión de SADAIC.</p>
<div class="cta">
    <a class="btn" href="{{ config('app.sitio_publico') }}">Ir a la Autogestión</a>
</div>
@endsection
