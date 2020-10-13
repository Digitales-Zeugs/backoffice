<?php

use Illuminate\Database\Seeder;
use App\Models\WorkLogActions;

class WorkLogActionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        WorkLogActions::firstOrCreate(['name' => 'REGISTRATION_CREATED', 'description' => '']);
        WorkLogActions::firstOrCreate(['name' => 'REGISTRATION_UPDATED', 'description' => '']);
        
        WorkLogActions::firstOrCreate(['name' => 'REGISTRATION_ACEPTED', 'description' => '']);
        WorkLogActions::firstOrCreate(['name' => 'REGISTRATION_REJECTED', 'description' => '']);
        
        WorkLogActions::firstOrCreate(['name' => 'DISTRIBUTION_ACCESED', 'description' => '']);
        WorkLogActions::firstOrCreate(['name' => 'DISTRIBUTION_CONFIRMED', 'description' => '']);
        WorkLogActions::firstOrCreate(['name' => 'DISTRIBUTION_REJECTED', 'description' => '']);
    }
}
