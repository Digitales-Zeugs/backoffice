<?php

namespace App\Jobs;

use \Exception;
use App\Models\Members\Registration;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;

class ExportMembers implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct()
    {
        //
    }

    public function handle()
    {
        Log::channel('sync')->debug("Iniciando exportación de las solicitudes de registro de socio para pasar a gestión interna");

        $members = Registration::where('status_id', 3)->get();

        if (!$members->count()) {
            Log::channel('sync')->debug("No hay solicitudes de registro de socio para exportar");
            return 0;
        }

        DB::beginTransaction();
        try {
            // Preparamos los datos y actualizamos el estado de la solicitud
            $members_data = $members->map(function(Registration $member) {
                $member->status_id = 4; // En Proceso
                $member->save();

                $data['id'] = $member->id;
                $data['nombre'] = $member->name;
                $data['fecha_nacimiento'] = $member->birth_date->format('Y-m-d');
                $data['pais_nacimiento'] = $member->birth_country;
                $data['provincia_nacimiento'] = $member->birth_state;
                $data['localidad_nacimiento'] = $member->birth_city;
                $data['nro_doc'] = $member->doc_number;
                $data['cuit'] = $member->work_code;

                $data['calle'] = $member->address_street;
                $data['numero'] = $member->address_number;
                $data['piso'] = $member->address_floor ?? '';
                $data['departamento'] = $member->address_apt ?? '';
                $data['pais'] = $member->address_country;
                $data['provincia'] = $member->address_state;
                $data['localidad'] = $member->address_city;
                $data['codigo_postal'] = $member->address_zip ?? '';
                $data['telefono'] = $member->landline;
                $data['celular'] = $member->mobile;
                $data['correo'] = $member->email;

                $data['pseudonimo'] = $member->pseudonym;
                $data['banda'] = $member->band;
                $data['obra'] = $member->entrance_work;
                $data['genero'] = $member->genre_id	;

                return $data;
            });

            $date = new \DateTime('now');

            // Parseamos el contenido del archivo
            $fileContents = json_encode($members_data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);

            // Calculamos el nombre del archivo
            $fileName = 'socios-';
            $fileName .= $date->format('Y\-m\-d\TH\-i\-s');
            $fileName .= '-registros.json';

            // Guardamos el archivo en el storage
            Storage::put(
                "sadaic/output/$fileName",
                $fileContents
            );

            DB::commit();

            Log::channel('sync')->debug("Exportación de las solicitudes de registro de socio terminada");
        } catch(\Throwable $error) {
            DB::rollBack();

            Log::channel('sync')->debug("Exportación de las solicitudes de registro de socio fallida: $error");
            throw $error;
        }
    }
}
