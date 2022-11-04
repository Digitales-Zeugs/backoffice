@extends('mails.template')

@section('content')
<div class="kicker">Solicitud #{{ $registration_id }}</div>
<p>¡Hola {{ $nombre }}!</p>
<p>Has sido incluido en el registro de una obra.</p>
<p>Para dar tu aprobación, te pedimos que te dirijas al sitio de Autogestión de SADAIC.</p>
<div class="cta">
    <a class="btn" href="{{ config('app.sitio_publico') }}">Ir a la Autogestión</a>
</div>
@endsection
