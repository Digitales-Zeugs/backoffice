<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use App\Jobs\ProcessImports;
use App\Jobs\NormalizeCities;

class SADAICSyncCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sadaic:sync';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Procesa los archivos de actualización de SADAIC';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $files = Storage::disk('local')->allFiles('sadaic');

        $updateCities = false;
        foreach($files as $file) {
            $table = "";
            switch($file) {
                case 'sadaic/DIV_ADMINISTRATIVAS.csv':
                    $table = "source_cities";
                    $updateCities = true;
                break;
                case 'sadaic/DOC_MW_REF_INT_GENRE.csv':
                    $table = "source_genres";
                break;
                case 'sadaic/PAISES TIS_N.csv':
                    $table = "source_countries";
                break;
                case 'sadaic/REF_MW_WORK_ROLE.csv':
                    $table = "source_roles";
                break;
                case 'sadaic/REF_SOCIETY.csv':
                    $table = "source_societies";
                break;
                case 'sadaic/SOCIOS SGS Completo.csv':
                    $table = "source_members";
                break;
                case 'sadaic/Tipos Documentos.csv':
                    $table = "source_types";
                break;
                case 'sadaic/DATOS_USUARIOS.csv':
                    $table = "source_agencies";
                break;
            }

            // Únicamente importar archivos conocidos
            if ($table != "") {
                $startTime = microtime(true);
                $this->line("<comment>Queueing job de importación:</comment> {$file}");

                ProcessImports::dispatch($file, $table);

                $runTime = number_format((microtime(true) - $startTime), 2);
                $this->line("<info>Job de importación queued:</info>  {$file} ({$runTime}s)");
            }
        }

        if ($updateCities) {
            $startTime = microtime(true);
            $this->line("<comment>Queueing job de normalización:</comment> Localidades y provincias");

            NormalizeCities::dispatch();

            $runTime = number_format((microtime(true) - $startTime), 2);
            $this->line("<info>Job de normalización queued:</info>  Localidades y provincias ({$runTime}s)");
        }

        return 0;
    }
}
