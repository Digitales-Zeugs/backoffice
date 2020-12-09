<?php

namespace App\Console\Commands;

use App\Mail\NotifyExpiration;
use App\Models\Jingles\Log as JingleInternalLog;
use App\Models\Jingles\Registration as JingleRegistration;
use App\Models\Work\Log as WorkInternalLog;
use App\Models\Work\Registration as WorkRegistration;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class SADAICExpirationCommand extends Command
{
    protected $signature = 'sadaic:expiration';

    protected $description = 'Marca como vencidos los trámites que no tuvieron movimientos en los últimos días';

    public function __construct()
    {
        parent::__construct();
    }


    public function handle()
    {
        Log::channel('expiration')->debug("Iniciando proceso de solicitudes vencidas");

        $startTime = microtime(true);
        $total = 0;
        $processed = 0;

        /**
         * Works
         */

        $this->line("<comment>Procesando solicitudes de registro de obras vencidas</comment>");
        Log::channel('expiration')->debug("Procesando solicitudes de registro de obras vencidas");

        // Registros de obra en proceso o en disputa por más de 15 días
        $expired = WorkRegistration::whereIn('id', function($query) {
            // Hace 21 días
            $dateBegin = date_create();
            date_sub($dateBegin, date_interval_create_from_date_string(
                env('SADAIC_REGISTRY_LIFE_DAYS', 15) + 6 . ' days'
            ));

            // Hace 16 días
            $dateEnd = date_create();
            date_sub($dateEnd, date_interval_create_from_date_string(
                env('SADAIC_REGISTRY_LIFE_DAYS', 15) + 1 . ' days'
            ));

            // Registros aceptados entre los últimos 15 y 20 días
            $query->select('registration_id')
            ->from('works_logs')
            ->where('action_id', 3)
            ->whereBetween('time', [$dateBegin, $dateEnd]);
        })
        ->whereIn('status_id', [2, 3]) // Todavía esperando todas las respuesta
        ->get();

        $total += $expired->count();

        $expired->each(function ($item, $key) {
            // Registramos el cambio en el log
            WorkInternalLog::create([
                'registration_id' => $item->id,
                'action_id'       => 13, // REQUEST_EXPIRED
                'time'            => now()
            ]);

            // Actualizamos el estado del trámite
            $item->status_id = 4; // Vencido
            $item->save();

            // Notificamos a los socios
            foreach($item->distribution as $distribution) {
                if ($distribution->type != 'member') {
                    continue;
                }

                if (trim($distribution->member->email) != "" && filter_var($distribution->member->email, FILTER_VALIDATE_EMAIL)) {
                    // Si tiene dirección válida, notificamos
                    Mail::to($distribution->member->email)->queue(new NotifyExpiration($distribution, $item->id));
                }
            }

            $processed++;
            Log::channel('expiration')->debug("Solicitud id $item->id marcada como vencida");
        });

        /**
         * Jingles
         */

        $this->line("<comment>Procesando solicitudes de inclusión de obras vencidas</comment>");
        Log::channel('expiration')->debug("Procesando solicitudes de inclusión de obras vencidas");

        // Solicitudes de inclusión de obra en proceso o en disputa por más de 15 días
        $expired = JingleRegistration::whereIn('id', function($query) {
            // Hace 21 días
            $dateBegin = date_create();
            date_sub($dateBegin, date_interval_create_from_date_string(
                env('SADAIC_REGISTRY_LIFE_DAYS', 15) + 6 . ' days'
            ));

            // Hace 16 días
            $dateEnd = date_create();
            date_sub($dateEnd, date_interval_create_from_date_string(
                env('SADAIC_REGISTRY_LIFE_DAYS', 15) + 1 . ' days'
            ));

            // Registros aceptados entre los últimos 15 y 20 días
            $query->select('registration_id')
            ->from('jingles_logs')
            ->where('action_id', 3)
            ->whereBetween('time', [$dateBegin, $dateEnd]);
        })
        ->whereIn('status_id', [2, 3]) // Todavía esperando todas las respuesta
        ->get();

        $total += $expired->count();

        $expired->each(function ($item, $key) {
            // Registramos el cambio en el log
            JingleInternalLog::create([
                'registration_id' => $item->id,
                'action_id'       => 5, // REQUEST_EXPIRED
                'time'            => now()
            ]);

            // Actualizamos el estado del trámite
            $item->status_id = 4; // Vencido
            $item->save();

            // Notificamos a los socios
            foreach($item->agreements as $agreement) {
                if ($agreement->type['name'] != 'member') {
                    continue;
                }

                if (trim($agreement->member->email) != "" && filter_var($agreement->member->email, FILTER_VALIDATE_EMAIL)) {
                    // Si tiene dirección válida, notificamos
                    Mail::to($agreement->member->email)->queue(new NotifyExpiration($agreement, $item->id));
                }
            }

            $processed++;
            Log::channel('expiration')->debug("Solicitud id $item->id marcada como vencida");
        });

        $runTime = number_format((microtime(true) - $startTime), 2);
        $this->line("<info>$processed de $total solicitudes procesadas</info> ({$runTime}s)");
        Log::channel('expiration')->debug("Proceso de solicitudes vencidas finalizado: $processed de $total solicitudes procesadas");

        return 0;
    }
}
