<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;

class NormalizeCities implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function handle()
    {
        $sql = "INSERT INTO states(state) ";
        $sql .= "SELECT provincia ";
        $sql .= "FROM source_cities ";
        $sql .= "GROUP BY provincia";
        DB::connection()->getpdo()->exec($sql);

        $sql = "INSERT INTO cities(id, state_id, city) ";
        $sql .= "SELECT source_cities.dad_id, states.id, source_cities.localidad ";
        $sql .= "FROM source_cities ";
        $sql .= "LEFT JOIN states ON states.state = source_cities.provincia";
        DB::connection()->getpdo()->exec($sql);
    }
}