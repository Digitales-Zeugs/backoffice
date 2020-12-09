<?php

namespace App\Console\Commands;

use App\Mail\NotifyExpiration;
use App\Models\Work\Log as InternalLog;
use App\Models\Work\Registration;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
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
        // Registros en proceso o en disputa por más de 15 días
        $expired = Registration::whereIn('id', function($query) {
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

        $expired->each(function ($item, $key) {
            // Registramos el cambio en el log
            InternalLog::create([
                'registration_id' => $item->id,
                'action_id'       => 13, // REQUEST_EXPIRED
                'time'            => now()
            ]);

            // Actualizamos el estado del trámite
            $item->status_id = 4; // Vencido
            $item->save();

            foreach($item->distribution as $distribution) {
                if ($distribution->type != 'member') {
                    continue;
                }

                if (trim($distribution->member->email) != "" && filter_var($distribution->member->email, FILTER_VALIDATE_EMAIL)) {
                    // Si tiene dirección válida, notificamos
                    Mail::to($distribution->member->email)->queue(new NotifyExpiration($distribution, $item->id));
                }
            }
        });

        return 0;
    }
}
