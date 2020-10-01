<?php

namespace App\Jobs;

use \Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;

class ProcessImports implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $file;
    public $table;

    public function __construct($file, $table)
    {
        $this->table = $table;
        $this->file = $file;
    }

    public function handle()
    {
        if (Storage::disk('local')->missing($this->file)) {
            throw new Exception("Archivo $this->file no encontrado");
        }

        if (!Schema::hasTable($this->table)) {
            throw new Exception("Tabla $this->table no encontrada");
        }

        // Abro el archivo a cargar y recupero la primera línea (headers)
        $prefix = Storage::disk('local')->getDriver()->getAdapter()->getPathPrefix();
        $handle = fopen($prefix . $this->file, "r");
        try {
            if (!feof($handle)) {
                fgets($handle);
            } else {
                throw new Exception("El archivo $this->file está vacio");
            }

            // Si después de recuperar la primera línea me encuentro al final del
            // archivo, este no contiene registros
            if (feof($handle)) {
                throw new Exception("El archivo $this->file no contiene registros");
            }
        } finally {
            fclose($handle);
        }

        // Vacio la tabla
        DB::table($this->table)->truncate();

        // Cargo el archivo
        $sql = "LOAD DATA LOCAL INFILE '" . addslashes($prefix . $this->file) . "' INTO TABLE $this->table FIELDS TERMINATED BY ';' IGNORE 1 LINES";
        DB::connection()->getpdo()->exec($sql); // https://stackoverflow.com/a/44426882

        // Elimino el archivo
        Storage::disk('local')->delete($this->file);
    }
}
