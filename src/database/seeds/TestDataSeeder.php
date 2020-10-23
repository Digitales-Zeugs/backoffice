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

        $user_1 = DB::connection('sadaic')->table('usuarios')
        ->insertGetId([
            'email'      => 'roberto.vaccaro@qkstudio.com',
            'usuarioid'  => 'rvaccaro',
            'clave'      => md5('pruebas'),
            'activacion' => now(),
            'status'     => 0
        ]);

        $user_2 = DB::connection('sadaic')->table('usuarios')
        ->insertGetId([
            'email'      => 'leo.miaton@qkstudio.com',
            'usuarioid'  => 'lmiaton',
            'clave'      => md5('pruebas'),
            'activacion' => now(),
            'status'     => 0
        ]);

        DB::connection('sadaic')->table('usuarios_privilegios')
        ->insert([
            [ 'recid_usuario' => 51, 'capitulo' => 'nb_login', 'privilegios'   => 'lee' ],
            [ 'recid_usuario' => 51, 'capitulo' => 'nb_socios', 'privilegios'   => 'lee' ],
            [ 'recid_usuario' => 51, 'capitulo' => 'nb_obras', 'privilegios'   => 'sin permisos' ],
            [ 'recid_usuario' => 51, 'capitulo' => 'nb_jingles', 'privilegios'   => 'lee' ],
            [ 'recid_usuario' => $user_1, 'capitulo' => 'nb_login', 'privilegios'   => 'lee' ],
            [ 'recid_usuario' => $user_1, 'capitulo' => 'nb_socios', 'privilegios'   => 'carga' ],
            [ 'recid_usuario' => $user_1, 'capitulo' => 'nb_obras', 'privilegios'   => 'carga' ],
            [ 'recid_usuario' => $user_1, 'capitulo' => 'nb_jingles', 'privilegios'   => 'carga' ],
            [ 'recid_usuario' => $user_2, 'capitulo' => 'nb_login', 'privilegios'   => 'lee' ],
            [ 'recid_usuario' => $user_2, 'capitulo' => 'nb_socios', 'privilegios'   => 'homologa' ],
            [ 'recid_usuario' => $user_2, 'capitulo' => 'nb_obras', 'privilegios'   => 'homologa' ],
            [ 'recid_usuario' => $user_2, 'capitulo' => 'nb_jingles', 'privilegios'   => 'homologa' ],
        ]);

    }
}
