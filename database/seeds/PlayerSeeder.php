<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Player;

class PlayerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(Player::class, 10)->create()->each(function($member){
            $member->save();
        });

        DB::table('players')->insert([
            'email' => 'apadula@sadaic.org.ar',
            'player_id' => 789789,
            'password' => Hash::make('pruebas'),
            'remember_token' => Str::random(10)
        ]);
    }
}
