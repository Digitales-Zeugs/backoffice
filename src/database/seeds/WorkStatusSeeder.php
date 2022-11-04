<?php

use Illuminate\Database\Seeder;
use App\Models\Work\Status;

class WorkStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Status::firstOrCreate(['name' => 'Nuevo']);
        Status::firstOrCreate(['name' => 'En Proceso']);
        Status::firstOrCreate(['name' => 'Disputa Propietarios']);
        Status::firstOrCreate(['name' => 'Vencido']);
        Status::firstOrCreate(['name' => 'Aprobado Propietarios']);
        Status::firstOrCreate(['name' => 'Para enviar a Procesamiento Interno']);
        Status::firstOrCreate(['name' => 'En Procesamiento Interno']);
        Status::firstOrCreate(['name' => 'Aprobado SADAIC']);
        Status::firstOrCreate(['name' => 'Rechazado SADAIC']);
        Status::firstOrCreate(['name' => 'Finalizado']);
    }
}
