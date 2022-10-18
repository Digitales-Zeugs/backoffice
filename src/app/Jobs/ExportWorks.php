<?php

namespace App\Jobs;

use \Exception;
use App\Models\Work\Distribution;
use App\Models\Work\Registration;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;

class ExportWorks implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct()
    {
        //
    }

    public function handle()
    {
        Log::channel('sync')->debug("Iniciando exportación de las solicitudes de registro de obra para pasar a gestión interna");

        $works = Registration::where('status_id', 6)->get();

        if (!$works->count()) {
            Log::channel('sync')->debug("No hay solicitudes de registro de obra para exportar");
            return 0;
        }

        DB::beginTransaction();
        try {
            // Preparamos los datos y actualizamos el estado de la solicitud
            $works_data = $works->map(function(Registration $work) {
                $interestedParties = $work->distribution->map(function(Distribution $dist) {
                    return [
                        'nameNumber' => $dist->type == 'member' ? $dist->member->ipname : -1,
                        'name'       => $dist->type == 'member' ? ucwords(strtolower(optional($dist->member)->nombre)) : $dist->meta->name,
                        'role'       => $dist->fn,
                        'porcentPer' => str_pad($dist->public * 100, 5, '0', STR_PAD_LEFT),
                        'porcentMec' => str_pad($dist->mechanic * 100, 5, '0', STR_PAD_LEFT),
                        'porcentSyn' => str_pad($dist->sync * 100, 5, '0', STR_PAD_LEFT)
                    ];
                });

                $work->status_id = 7; // En Procesamiento Interno
                $work->save();

                $data = [
                    'submissionId'      => $work->id,
                    'agency'            => '128',
                    'originalTitle'     => $work->title,
                    'interestedParties' => $interestedParties
                ];

                return $data;
            });

            $date = new \DateTime('now');

            // Preparamos el resto del contenido del archivo
            $fileContents = [
                '$schema'    => './work_schema.json',
                'fileHeader' => [
                    'submittingAgency'     => '128',
                    'fileCreationDateTime' => $date->format('Y-m-d\TH:i:s.uT'),
                    'receivingAgency'      => '061'
                ],
                'addWorks' => $works_data
            ];

            // Parseamos el contenido del archivo
            $fileContents = json_encode($fileContents, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);

            // Transformar los strings que representan números con leading zeros a
            // "números con leading zeros". Se hace de forma manual porque el estandar
            // de JSON no soporta leading zeros para los números
            $patterns = [
                '/("nameNumber"): "(\d*)"/m',
                '/("porcentPer"): "(\d*)"/m',
                '/("porcentMec"): "(\d*)"/m',
                '/("porcentSyn"): "(\d*)"/m',
            ];

            $fileContents = preg_replace($patterns, '${1}: ${2}', $fileContents);

            // Calculamos el nombre del archivo
            $fileName = 'work-';
            $fileName .= $date->format('Y\-m\-d\TH\-i\-s');
            $fileName .= '-128-061-registros.json';

            // Guardamos el archivo en el storage
            Storage::put(
                "sadaic/output/$fileName",
                $fileContents
            );

            DB::commit();

            Log::channel('sync')->debug("Exportación de las solicitudes de registro de obra terminada");
        } catch(\Throwable $error) {
            DB::rollBack();

            Log::channel('sync')->debug("Exportación de las solicitudes de registro de obra fallida: $error");
            throw $error;
        }
    }
}
