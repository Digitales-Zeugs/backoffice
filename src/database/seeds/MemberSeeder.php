<?php

use Illuminate\Database\Seeder;
use App\Models\Member;

class MemberSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Member::firstOrCreate([
            'email'     => 'apadula@sadaic.org.ar',
            'member_id' => '789789',
            'heir'      => 0
        ]);
    }
}
