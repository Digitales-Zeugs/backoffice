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
        $this->call(WorkLogActionSeeder::class);
        $this->call(MemberSeeder::class);
        $this->call(UserSeeder::class);
        $this->call(WorkStatusSeeder::class);
        $this->call(MemberRegistrationStatusSeeder::class);
    }
}
