@extends('mails.template')

@section('content')
<div class="kicker">Solicitud de Registro de Obra</div>
<p>¡Hola {{ $nombre }}!</p>
<p>Una solicitud de Registro de Obra de la que sos parte fue <strong>RECHAZADA</strong>, para ver más información accedé al sitio de autogestión de SADAIC:</p>
<div class="cta">
    <a class="btn" href="{{ route('login') }}">Ir a la Autogestión</a>
</div>
@endsection