<?php

namespace App\Jobs;

use \Exception;
use App\Models\Jingles\Agreement;
use App\Models\Jingles\Registration;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;

class ExportJingles implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct()
    {
        //
    }

    public function handle()
    {
        Log::channel('sync')->debug("Iniciando exportación de las solicitudes de inclusión de obra para pasar a gestión interna");

        $jingles = Registration::where('status_id', 6)->with('agreements')->get();

        if (!$jingles->count()) {
            Log::channel('sync')->debug("No hay solicitudes de inclusión de obra para exportar");
            return 0;
        }

        DB::beginTransaction();
        try {
            // Preparamos los datos y actualizamos el estado de la solicitud
            $jingles_data = $jingles->map(function(Registration $jingle) {
                $jingle->status_id = 7; // En Procesamiento Interno
                $jingle->save();

                $data = [
                    'id'             => $jingle->id,
                    'tipo_solicitud' => $jingle->is_especial ? 'especial' : 'regular',
                    'tipo_accion'    => $jingle->request_action,
                    'vigencia'       => $jingle->validity,
                    'fecha_salida'   => $jingle->air_date->format('Y-m-d'),
                    'territorio'     => $jingle->broadcast_territory,
                ];

                $data['solicitante'] = [
                    'cuit'         => $jingle->applicant->cuit,
                    'razon_social' => $jingle->applicant->name,
                    'direccion'    => trim($jingle->applicant->address ?? ''),
                    'telefono'     => trim($jingle->applicant->phone ?? ''),
                    'correo'       => trim($jingle->applicant->cuit ?? ''),
                ];

                $data['anunciante'] = [
                    'cuit'         => $jingle->advertiser->cuit,
                    'razon_social' => $jingle->advertiser->name,
                    'direccion'    => trim($jingle->advertiser->address ?? ''),
                    'telefono'     => trim($jingle->advertiser->phone ?? ''),
                    'correo'       => trim($jingle->advertiser->cuit ?? ''),
                ];

                if ($jingle->is_especial) {
                    $data['cantidad_avisos'] = count($jingle->ads_duration);
                    $data['duracion_avisos'] = $jingle->ads_duration;
                } else {
                    $data['duracion_aviso'] = $jingle->ads_duration[0];
                }

                if ($jingle->broadcast_territory_id == 2) { // Provincial
                    $data['provincias'] = $jingle->territories->map(function($t) {
                        return $t->state;
                    });
                } else if ($jingle->broadcast_territory_id == 3) { // Internacional
                    $data['paises'] = $jingle->territories->map(function($t) {
                        return $t->tis_n;
                    });

                    $data['difusion_nacional'] = $jingle->also_national ? 'Si' : 'No';
                }

                $data['medios_de_comunicacion'] = [
                    'tipo_1' => $jingle->media->name,
                    'tipo_2' => $jingle->media->description,
                ];

                $data['agencia'] = [
                    'tipo'         => $jingle->agency_type,
                    'cuit'         => $jingle->agency->cuit,
                    'razon_social' => $jingle->agency->name,
                    'direccion'    => trim($jingle->agency->address ?? ''),
                    'telefono'     => trim($jingle->agency->phone ?? ''),
                    'correo'       => trim($jingle->agency->cuit ?? ''),
                ];

                $data['producto'] = [
                    'marca'  => $jingle->product_brand,
                    'tipo'   => $jingle->product_type,
                    'nombre' => $jingle->product_name
                ];

                $data['obra'] = [
                    'titulo'            => $jingle->work_title,
                    'original'          => $jingle->work_original ? 'Si' : 'No',
                    'dnda'              => $jingle->work_dnda ?? '',
                    'autores'           => $jingle->work_authors ?? '',
                    'compositores'      => $jingle->work_composers ?? '',
                    'editores'          => $jingle->work_editors ?? '',
                    'letra_modificada'  => $jingle->work_script_mod ? 'Si' : 'No',
                    'musica_modificada' => $jingle->work_music_mod ? 'Si' : 'No'
                ];

                $data['conformidad_autores'] = $jingle->authors_agreement ? 'Si' : 'No';

                if ($jingle->authors_agreement) {
                    $data['autores'] = [];

                    foreach($jingle->agreements as $person) {
                        $autor = [];

                        if ($person->type_id == 1) { // Socios
                            $autor['nombre'] = optional($person->member)->nombre;
                            $autor['nro_socio'] = optional($person->member)->codanita;
                            $autor['nro_doc'] = $person->doc_number;
                            $autor['correo'] = optional($person->member)->email;
                        } else { // No socios
                            $autor['nombre'] = $person->meta->name;
                            $autor['nro_doc'] = $person->doc_number;
                            $autor['correo'] = $person->meta->email;
                            $autor['pais'] = $person->meta->country;
                            $autor['nacionalidad'] = $person->meta->birth_country->name_ter;
                            $autor['provincia'] = $person->meta->state;
                            $autor['localidad'] = $person->meta->city;
                            $autor['codigo_postal'] = $person->meta->address_zip ?? '';
                            $autor['calle'] = $person->meta->street_name;
                            $autor['numero'] = $person->meta->street_number;
                            $autor['piso'] = $person->meta->floor ?? '';
                            $autor['departamento'] = $person->meta->apartment ?? '';
                            $autor['fecha_nacimiento'] = $person->meta->birth_date->format('Y-m-d');
                            $autor['tel_pais'] = $person->meta->phone_country;
                            $autor['tel_area'] = $person->meta->phone_area;
                            $autor['tel_num'] = $person->meta->phone_number;
                        }

                        array_push($data['autores'], $autor);
                    }
                }

                $data['arancel_monto'] = $jingle->authors_tariff;
                $data['arancel_responsable'] = $jingle->tariff_payer;

                if ($jingle->tariff_payer_id == 3) {
                    $data['arancel_a_cuenta'] = $jingle->tariff_representation;
                }

                return $data;
            });

            $date = new \DateTime('now');

            // Preparamos el resto del contenido del archivo
            $fileContents = $jingles_data;

            // Parseamos el contenido del archivo
            $fileContents = json_encode($fileContents, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);

            // Calculamos el nombre del archivo
            $fileName = 'work-';
            $fileName .= $date->format('Y\-m\-d\TH\-i\-s');
            $fileName .= '-inclusiones.json';

            // Guardamos el archivo en el storage
            Storage::put(
                "sadaic/output/$fileName",
                $fileContents
            );

            DB::commit();

            Log::channel('sync')->debug("Exportación de las solicitudes de inclusión de obra terminada");
        } catch(\Throwable $error) {
            DB::rollBack();

            Log::channel('sync')->debug("Exportación de las solicitudes de inclusión de obra fallida: $error");
            throw $error;
        }
    }
}
