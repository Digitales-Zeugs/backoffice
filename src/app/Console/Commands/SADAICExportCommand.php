<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use App\Jobs\ExportJingles;
use App\Jobs\ExportMembers;
use App\Jobs\ExportWorks;

class SADAICExportCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sadaic:export';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Prepara los archivos para SADAIC';

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
        new ExportJingles();
        new ExportMembers();
        new ExportWorks();

        return 0;
    }
}
