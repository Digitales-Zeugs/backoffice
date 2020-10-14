<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            'name'              => 'Juan Prueba',
            'phone'             => '542215751688',
            'email'             => 'roberto.vaccaro@qkstudio.com',
            'email_verified_at' => now(),
            'password'          => Hash::make('pruebas'),
            'remember_token'    => Str::random(32),
            'created_at'        => now(),
            'updated_at'        => now()
        ]);
    }
}
