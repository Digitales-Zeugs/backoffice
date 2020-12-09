<?php

namespace App\Jobs;

use \Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
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
        Log::channel('sync')->debug("Iniciando carga del archivo $this->file en la tabla $this->table");

        if (Storage::disk('local')->missing($this->file)) {
            Log::channel('sync')->error("Archivo $this->file no encontrado");
            throw new Exception("Archivo $this->file no encontrado");
        }

        if (!Schema::hasTable($this->table)) {
            Log::channel('sync')->error("Tabla $this->table no encontrada");
            throw new Exception("Tabla $this->table no encontrada");
        }

        // Abro el archivo a cargar y recupero la primera línea (headers)
        $prefix = Storage::disk('local')->getDriver()->getAdapter()->getPathPrefix();
        $handle = fopen($prefix . $this->file, "r");
        try {
            if (!feof($handle)) {
                fgets($handle);
            } else {
                Log::channel('sync')->error("El archivo $this->file está vacio");
                throw new Exception("El archivo $this->file está vacio");
            }

            // Si después de recuperar la primera línea me encuentro al final del
            // archivo, este no contiene registros
            if (feof($handle)) {
                Log::channel('sync')->error("El archivo $this->file no contiene registros");
                throw new Exception("El archivo $this->file no contiene registros");
            }
        } finally {
            fclose($handle);
        }

        // Vacio la tabla
        Log::channel('sync')->debug("Vaciando la tabla $this->table");
        DB::table($this->table)->truncate();
        Log::channel('sync')->debug("Tabla $this->table vaciada");

        // Cargo el archivo
        Log::channel('sync')->debug("Cargando archivo $this->file");
        $sql = "LOAD DATA LOCAL INFILE '" . addslashes($prefix . $this->file) . "' INTO TABLE $this->table CHARACTER SET LATIN1 FIELDS TERMINATED BY ';' LINES TERMINATED BY '\\r\\n' IGNORE 1 LINES";
        DB::connection()->getpdo()->exec($sql); // https://stackoverflow.com/a/44426882
        Log::channel('sync')->debug("Archivo $this->file cargado");

        // Si no estamos trabajando en local
        if (!\App::environment('local')) {
            // Muevo el archivo a done
            Log::channel('sync')->debug("Moviendo archivo $this->file");

            $date = new \DateTime('now');
            $target = pathinfo($this->file);

            $targetPath = str_replace('input', 'done', $target['dirname']);
            $targetPath .= '/' . $date->format('Y-m-d');

            $targetFile = $targetPath . '/' . $target['basename'];

            // Si el archivo ya existe, le agregamos un sufijo
            if (Storage::disk('local')->exists($targetFile)) {
                $targetFile .= $date->format('_His');
            }

            Storage::disk('local')->move($this->file, $targetFile);
            Log::channel('sync')->debug("Archivo $this->file borrado");
        }
    }
}
