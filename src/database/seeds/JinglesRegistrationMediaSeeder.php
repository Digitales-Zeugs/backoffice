<?php

use Illuminate\Database\Seeder;
use App\Models\Jingles\Media;

class JinglesRegistrationMediaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Media::firstOrCreate([
            'name' => 'Uso Total',
            'description' => 'Todos los medios y modalidades sonoras'
        ]);
        Media::firstOrCreate([
            'name' => 'Restringido I',
            'description' => 'Televisión'
        ]);
        Media::firstOrCreate([
            'name' => 'Restringido II y III',
            'description' => 'Radio - Propaladoras - Cine - Circ. Cerr. TV. Com./Corp'
        ]);
        Media::firstOrCreate([
            'name' => 'Restringido II y III',
            'description' => 'Única Radioemisora'
        ]);
        Media::firstOrCreate([
            'name' => 'Restringido IV',
            'description' => 'Internet'
        ]);
        Media::firstOrCreate([
            'name' => 'Restringido IV',
            'description' => 'Única Página Web'
        ]);
        Media::firstOrCreate([
            'name' => 'Restringido V',
            'description' => 'Telefonía fija y Telefonía móvil'
        ]);
        Media::firstOrCreate([
            'name' => 'Restringido VI',
            'description' => 'Video home - Soporte Digital'
        ]);
        Media::firstOrCreate([
            'name' => 'Restringido VII',
            'description' => 'Publicaciones Gráficas'
        ]);
        Media::firstOrCreate([
            'name' => 'Restringido VIII',
            'description' => 'Vía Pública'
        ]);
        Media::firstOrCreate([
            'name' => 'VPNT',
            'description' => 'Exclusivo Internet'
        ]);
    }
}
