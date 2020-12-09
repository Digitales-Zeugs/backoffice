<?php

use Illuminate\Database\Seeder;
use App\Models\Jingles\Action;

class JinglesLogActionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Action::firstOrCreate(['name' => 'REQUEST_CREATED', 'description' => 'Solicitud creada']);
        Action::firstOrCreate(['name' => 'REQUEST_UPDATED', 'description' => 'Solicitud actualizada']);
        Action::firstOrCreate(['name' => 'REQUEST_ACCEPTED', 'description' => 'Solicitud recibida']);
        Action::firstOrCreate(['name' => 'REQUEST_REJECTED', 'description' => 'Solicitud rechazada']);
        Action::firstOrCreate(['name' => 'REQUEST_EXPIRED', 'description' => 'Venció la solicitud']);
        Action::firstOrCreate(['name' => 'REQUEST_FINISHED', 'description' => 'Se finalizó el trámite']);

        Action::firstOrCreate(['name' => 'AGREEMENT_CONFIRMED', 'description' => 'Confirmación de acuerdo']);
        Action::firstOrCreate(['name' => 'AGREEMENT_REJECTED', 'description' => 'Rechazo de acuerdo']);

        Action::firstOrCreate(['name' => 'SEND_TO_INTERNAL', 'description' => 'Enviado a Sistema Interno']);

        Action::firstOrCreate(['name' => 'REGISTER_ACCEPTED', 'description' => 'Registro aceptado']);
        Action::firstOrCreate(['name' => 'REGISTER_REJECTED', 'description' => 'Registro rechazado']);

        Action::firstOrCreate(['name' => 'NOT_NOTIFIED', 'description' => 'No se pudo notificar al socio porque el mail configurado no es válido']);
    }
}
