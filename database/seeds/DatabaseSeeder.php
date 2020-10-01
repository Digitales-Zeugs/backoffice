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
        $this->call(UserSeeder::class);
        $this->call(MemberSeeder::class);
        $this->call(PlayerSeeder::class);
        $this->call(ProfileUpdatesStatusSeeder::class);
    }
}
