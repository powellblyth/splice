<?php

namespace App\Console\Commands;

use App\Library\Services\Unleashed;
use App\Models\WarehouseStockTransfer;
use Illuminate\Console\Command;

class importWarehouseStockTransfersFromUnleashed extends Command {

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'unleashed:importwarehousestocktransfers {--refresh}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Imports all warehouse stock transfers from Unleashed';

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
        $factName = 'unleashedWarehouseStockTransfersDateSince';
        $className =WarehouseStockTransfer::class;
        $unleashedFunctionName = 'getWarehouseStockTransfers';

        $unleashed = new Unleashed();
        $unleashed->importThing($factName, $className, $unleashedFunctionName, $this->option('refresh'));
    }
}