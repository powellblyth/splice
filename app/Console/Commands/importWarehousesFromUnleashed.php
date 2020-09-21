<?php

namespace App\Console\Commands;

use App\Library\Services\Unleashed;
use Illuminate\Console\Command;

class importWarehousesFromUnleashed extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'unleashed:importwarehouses {--refresh}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Imports all the warehouses from unleashed';

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
     * @return mixed
     */
    public function handle()
    {
        $factName = 'unleashedWarehousesDateSince';
        $className = 'App\\Warehouse';
        $unleashedFunctionName = 'getWarehouses';

        $unleashed = new Unleashed();
        $unleashed->importThing($factName, $className, $unleashedFunctionName, $this->option('refresh'));
        //
    }
}
