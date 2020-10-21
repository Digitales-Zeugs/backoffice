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
        Status::firstOrCreate(['name' => 'En evaluación']);
        Status::firstOrCreate(['name' => 'Aceptado']);
        Status::firstOrCreate(['name' => 'Rechazado']);
    }
}
