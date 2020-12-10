<?php

namespace App\Http\Controllers;

use App\Models\Work\Distribution;
use App\Models\Work\Registration as WorkRegistration;
use App\Models\Jingles\Registration as JingleRegistration;
use App\Models\Members\Registration as MemberRegistration;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class IntegrationController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        return view('integration.index');
    }

    public function exportWorks()
    {
        $works = WorkRegistration::where('status_id', 6)->get();

        $works_data = $works->map(function(WorkRegistration $work) {
            $interestedParties = $work->distribution->map(function(Distribution $dist) {
                return [
                    'nameNumber' => $dist->type == 'member' ? $dist->member->ipname : -1,
                    'name'       => $dist->type == 'member' ? ucwords(strtolower($dist->member->nombre)) : $dist->meta->name,
                    'role'       => $dist->fn,
                    'porcentPer' => $this->formatPercentage($dist->public),
                    'porcentMec' => $this->formatPercentage($dist->mechanic),
                    'porcentSyn' => $this->formatPercentage($dist->sync)
                ];
            });

            $data = [
                'submissionId'      => $work->id,
                'agency'            => '128',
                'originalTitle'     => $work->title,
                'interestedParties' => $interestedParties
            ];

            return $data;
        });

        $date = new \DateTime('now');

        $fileContents = [
            'fileHeader' => [
                '$schema'              => './work_schema.json',
                'submittingAgency'     => '128',
                'fileCreationDateTime' => $date->format('Y-m-d\TH:i:s.uT'),
                'receivingAgency'      => '061'
            ],
            'addWorks' => $works_data
        ];

        $fileName = 'work-';
        $fileName .= $date->format('Y-m-d\TH:i:s');
        $fileName .= '-128-061-registros.json';

        return response()->streamDownload(function() use ($fileContents) {
            echo json_encode($fileContents, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
        }, $fileName, [
            'Content-Encoding' => 'utf-8',
            'Content-Type'     => 'application/json'
        ]);
    }

    public function importWorks(Request $request)
    {
        if (!$request->hasFile('file')) {
            abort(400);
        }

        $contents = $request->file('file')->get();
        $contents = json_decode($contents);

        if ($contents->fileHeader->receivingAgency != '128') {
            abort(400);
        }

        $events = [];
        $stats = [
            'success' => 0,
            'failure' => 0
        ];

        foreach($contents->acknowledgements as $ack) {
            // Si no es alta, omitimos el registro
            if ($ack->originalTransactionType != 'AddWork') {
                $events[] = "Respuesta $ack->submissionId omitida porque no es un alta";
                $stats['failure']++;
                continue;
            }

            $work = WorkRegistration::find($ack->originalSubmissionId);

            // Si no encontramos la solicitud en la BBDD, omitimos el registro
            if (!$work) {
                $events[] = "Respuesta $ack->submissionId omitida porque no se encontro solicitud(id $ack->originalSubmissionId) en la BBDD";
                $stats['failure']++;
                continue;
            }

            // Si la solicitud no está a la espera de respuesta, omitimos el registro
            if ($work->status_id != 6) { // Para pasar a PI
                $events[] = "Respuesta $ack->submissionId omitida porque la solicitud(id $ack->originalSubmissionId) no está a la espera de respuesta";
                $stats['failure']++;
                continue;
            }

            if ($ack->transactionStatus == 'FullyAccepted') {
                $work->status_id = 8; // Aprobado
                $work->approved = true;
                $work->codwork = $ack->codworkSq;
                $stats['success']++;
            } else if ($ack->transactionStatus == 'Rejected') {
                $work->status_id = 9; // Rechazado
                $work->approved = true;
                $stats['success']++;
            } else {
                $events[] = "Respuesta $ack->submissionId omitida porque no está soportado el tipo";
                $stats['failure']++;
                continue;
            }

            $work->save();
        }

        return [
            'status' => 'success',
            'events' => $events,
            'stats'  => $stats
        ];
    }

    public function exportJingles()
    {
        $jingles = JingleRegistration::where('status_id', 6)->with('agreements')->get();

        // Preparamos los datos y actualizamos el estado de la solicitud
        $jingles_data = $jingles->map(function(JingleRegistration $jingle) {
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

                $data['difusion_nacional'] = $jingle->also_national;
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
                        $autor['nombre'] = $person->member->nombre;
                        $autor['nro_socio'] = $person->member->codanita;
                        $autor['nro_doc'] = $person->doc_number;
                        $autor['correo'] = $person->member->email;
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

        // Calculamos el nombre del archivo
        $fileName = 'work-';
        $fileName .= $date->format('Y\-m\-d\TH\-i\-s');
        $fileName .= '-inclusiones.json';

        return response()->streamDownload(function() use ($fileContents) {
            echo json_encode($fileContents, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
        }, $fileName, [
            'Content-Encoding' => 'utf-8',
            'Content-Type'     => 'application/json'
        ]);
    }

    public function exportMembers()
    {
        $members = MemberRegistration::where('status_id', 3)->get();

        // Preparamos los datos y actualizamos el estado de la solicitud
        $members_data = $members->map(function(MemberRegistration $member) {
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
        $fileContents = $members_data;

        // Calculamos el nombre del archivo
        $fileName = 'socios-';
        $fileName .= $date->format('Y\-m\-d\TH\-i\-s');
        $fileName .= '-registros.json';

        return response()->streamDownload(function() use ($fileContents) {
            echo json_encode($fileContents, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
        }, $fileName, [
            'Content-Encoding' => 'utf-8',
            'Content-Type'     => 'application/json'
        ]);
    }

    private function formatPercentage($percentage) {
        return $percentage * 100;
    }
}