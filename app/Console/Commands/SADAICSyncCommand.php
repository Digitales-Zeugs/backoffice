<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use App\Jobs\ProcessImports;

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

        foreach($files as $file) {
            $table = "";
            switch($file) {
                case 'sadaic/DIV_ADMINISTRATIVAS.csv':
                    $table = "source_cities";
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
            }

            ProcessImports::dispatch($file, $table);
        }

        return 0;
    }
}
