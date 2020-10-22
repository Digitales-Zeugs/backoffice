<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TestDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::connection('sadaic')->table('socios')
            ->where('socio', ['70588', '70948', '13383', '682750', '714970', '707933', '695112'])
            ->where('heredero', 0)
            ->update([
                'clave' => '601fa408a634a5f58fdb4da801184f35f928071f066f0db88931a264e2e19e62632d762160b5d2785766f1c258a7dd41ff4bcaaec9cafb4b5a2ddf9f8661ec3f'
            ]);
    }
}
