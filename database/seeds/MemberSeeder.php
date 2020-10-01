<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Member;

class MemberSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(Member::class, 10)->create()->each(function($member){
            $member->save();
        });

        DB::table('members')->insert([
            'email' => 'apadula@sadaic.org.ar',
            'member_id' => 789789,
            'heir' => 0,
            'password' => Hash::make('pruebas'),
            'remember_token' => Str::random(10)
        ]);
    }
}
