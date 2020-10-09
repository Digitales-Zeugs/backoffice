<?php

use Illuminate\Database\Seeder;
use App\Models\ProfileUpdatesStatus;

class ProfileUpdatesStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        ProfileUpdatesStatus::firstOrCreate(['name' => 'Pendiente']);
        ProfileUpdatesStatus::firstOrCreate(['name' => 'En evaluación']);
        ProfileUpdatesStatus::firstOrCreate(['name' => 'Aceptado']);
        ProfileUpdatesStatus::firstOrCreate(['name' => 'Rechazado']);
    }
}
