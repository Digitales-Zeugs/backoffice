<?php

use Illuminate\Database\Seeder;
use App\Models\Jingles\Status;

class JinglesStatusSeeder extends Seeder
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
        Status::firstOrCreate(['name' => 'Disputa Autores']);
        Status::firstOrCreate(['name' => 'Vencido']);
        Status::firstOrCreate(['name' => 'Aprobado Autores']);
        Status::firstOrCreate(['name' => 'En Procesamiento Interno']);
        Status::firstOrCreate(['name' => 'Aprobado SADAIC']);
        Status::firstOrCreate(['name' => 'Rechazado SADAIC']);
        Status::firstOrCreate(['name' => 'Finalizado']);
    }
}
