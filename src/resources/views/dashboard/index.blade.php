@extends('dashboard.layout')

@section('content')
<div class="container">
    <br>
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Solicitudes de actualización de datos</h3>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-12 col-md-4">
                    <canvas id="dataUpdateCanvasBars" width="400" height="400"></canvas>
                </div>
                <div class="col-12 col-md-4">
                    <canvas id="dataUpdateCanvasSemiCircle" width="400" height="400"></canvas>
                </div>
            </div>
        </div>
    </div>
    <br>
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Solicitudes de registro de socios</h3>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-12 col-md-4">
                    
                </div>
            </div>
        </div>
    </div>
    <br>
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Solicitudes de registro de obras</h3>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-12 col-md-4">
                    
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="{{ asset('/js/dashboard.js') }}"></script>
@endpush