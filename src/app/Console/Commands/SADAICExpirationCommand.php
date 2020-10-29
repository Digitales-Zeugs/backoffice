<?php

namespace App\Console\Commands;

use App\Mail\NotifyExpiration;
use App\Models\Work\Log as InternalLog;
use App\Models\Work\Registration;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

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
        $date = date_create();
        date_sub($date, date_interval_create_from_date_string(
            env('SADAIC_REGISTRY_LIFE_DAYS', '15') . ' days'
        ));

        // Registros en proceso o en disputa sin cambios en los últimos 15 días
        $expired = Registration::where('updated_at', '<=', date_format($date, "Y-m-d"))
        ->whereIn('status_id', [2, 3])
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
                    Mail::to($distribution->member->email)->queue(new NotifyExpiration($distribution));
                }
            }
        });

        return 0;
    }
}
