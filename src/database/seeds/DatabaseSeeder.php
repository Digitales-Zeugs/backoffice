<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(ProfileUpdatesStatusSeeder::class);
        $this->call(WorkLogActionsSeeder::class);
        $this->call(MemberSeeder::class);
    }
}