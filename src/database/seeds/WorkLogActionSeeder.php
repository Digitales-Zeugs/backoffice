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
        LogAction::firstOrCreate(['name' => 'REGISTRATION_CREATED', 'description' => '']);
        LogAction::firstOrCreate(['name' => 'REGISTRATION_UPDATED', 'description' => '']);
        
        LogAction::firstOrCreate(['name' => 'REGISTRATION_ACEPTED', 'description' => '']);
        LogAction::firstOrCreate(['name' => 'REGISTRATION_REJECTED', 'description' => '']);
        
        LogAction::firstOrCreate(['name' => 'DISTRIBUTION_ACCESED', 'description' => '']);
        LogAction::firstOrCreate(['name' => 'DISTRIBUTION_CONFIRMED', 'description' => '']);
        LogAction::firstOrCreate(['name' => 'DISTRIBUTION_REJECTED', 'description' => '']);
    }
}
