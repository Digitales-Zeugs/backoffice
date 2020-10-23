<?php

namespace App\Console\Commands;

use App\Models\Work\Log as InternalLog;
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
        date_sub($date, date_interval_create_from_date_string("15 days"));

        $expired = DB::table('works_registration')
        ->where([
            ['status_id', 3],
            ['updated_at', '<=', date_format($date, "Y-m-d")]
        ])->get();

        $expired->each(function ($item, $key) {
            InternalLog::create([
                'registration_id' => $item->id,
                'action_id'       => 13, // REQUEST_EXPIRED
                'time'            => now()
            ]);

            DB::table('works_registration')
            ->where('id', $item->id)
            ->update([
                'status_id' => 4 // Vencido
            ]);
        });

        return 0;
    }
}
