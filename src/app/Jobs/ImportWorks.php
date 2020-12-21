<?php

namespace App\Jobs;

use \Exception;
use App\Models\Work\Distribution;
use App\Models\Work\Registration;
use App\Models\Members\Registration as MemberRegistration;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;

class ImportWorks implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $file;

    public function __construct($file)
    {
        $this->file = $file;
    }

    public function handle()
    {
        Log::channel('sync')->debug("Iniciando importación de las solicitudes de registro de obra provenientes gestión interna");

        if (Storage::disk('local')->missing($this->file)) {
            Log::channel('sync')->error("Archivo $this->file no encontrado");
            throw new Exception("Archivo $this->file no encontrado");
        }

        DB::beginTransaction();
        try {
            $contents = Storage::get($this->file);
            $contents = json_decode($contents);

            $events = [];
            $stats = [
                'success' => 0,
                'failure' => 0
            ];

            if (!isset($contents->acknowledgements)) {
                Log::channel('sync')->error("Importación no iniciada: Formato no soportado (no acknowledgements)");
                return 0;
            }

            if ($contents->fileHeader->receivingAgency != '128') {
                Log::channel('sync')->error("Importación fallida: N° de agencia erróneo");
                throw new Exception("Importación fallida: N° de agencia erróneo");
            }

            foreach($contents->acknowledgements as $ack) {
                // Si no es alta, omitimos el registro
                if ($ack->originalTransactionType != 'AddWork') {
                    $events[] = "Respuesta $ack->submissionId omitida porque no es un alta";
                    $stats['failure']++;
                    continue;
                }

                $work = Registration::find($ack->originalSubmissionId);

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

                    $member = MemberRegistration::where('work_id', $work->id)->get();
                    if ($member) {
                        $member->status_id = 3; // Para procesar
                        $member->save();
                    }

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

            DB::commit();
            Log::channel('sync')->debug("Importación de las solicitudes de registro de obra terminada", [
                'events' => $events,
                'stats'  => $stats
            ]);
        } catch(\Throwable $error) {
            DB::rollBack();

            Log::channel('sync')->debug("Importación de las solicitudes de registro de obra fallida: $error");
            throw $error;
        }
    }
}
