<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Library\Services\Unleashed;

class importOrdersFromUnleashed extends Command {

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'unleashed:importorders {--refresh}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Imports new and amended orders from Unleashed';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct() {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle() {
        $factName = 'salesOrderDateSince';
        $className = 'App\\Order';
        $unleashedFunctionName = 'getSalesOrdersModifiedSince';

        $unleashed = new Unleashed();
        $unleashed->importThing($factName, $className, $unleashedFunctionName,$this->option('refresh'), ['orderStatus'=>'Deleted,Placed,Completed']);
    }
}