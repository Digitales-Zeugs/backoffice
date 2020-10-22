<?php

use Illuminate\Database\Seeder;
use App\Models\Work\LogAction;

class WorkLogActionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        LogAction::firstOrCreate(['name' => 'REGISTRATION_CREATED', 'description' => 'Solicitud creada']);
        LogAction::firstOrCreate(['name' => 'REGISTRATION_UPDATED', 'description' => 'Solicitud actualizada']);
        
        LogAction::firstOrCreate(['name' => 'REGISTRATION_ACCEPTED', 'description' => 'Solicitud recibida']);
        LogAction::firstOrCreate(['name' => 'REGISTRATION_REJECTED', 'description' => 'Solicitud rechazada']);
        
        LogAction::firstOrCreate(['name' => 'DISTRIBUTION_ACCESED', 'description' => '']);
        LogAction::firstOrCreate(['name' => 'DISTRIBUTION_CONFIRMED', 'description' => 'Confirmación de distribución']);
        LogAction::firstOrCreate(['name' => 'DISTRIBUTION_REJECTED', 'description' => 'Rechazo de distribución']);

        LogAction::firstOrCreate(['name' => 'SEND_TO_INTERNAL', 'description' => 'Enviado a Sistema Interno']);

        LogAction::firstOrCreate(['name' => 'REQUEST_ACCEPTED', 'description' => 'Registro aceptado']);
        LogAction::firstOrCreate(['name' => 'REQUEST_REJECTED', 'description' => 'Registro rechazado']);

        LogAction::firstOrCreate(['name' => 'REGISTRATION_NOT_NOTIFIED', 'description' => 'No se pudo notificar al socio porque el mail configurado no es válido']);
    }
}
