<?php

use Illuminate\Database\Seeder;
use App\Models\Members\Status;

class MemberRegistrationStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Status::firstOrCreate(['name' => 'Pendiente']);
        Status::firstOrCreate(['name' => 'En espera']);
        Status::firstOrCreate(['name' => 'Para procesar']);
        Status::firstOrCreate(['name' => 'En proceso']);
        Status::firstOrCreate(['name' => 'Aceptado']);
        Status::firstOrCreate(['name' => 'Rechazado']);
    }
}
