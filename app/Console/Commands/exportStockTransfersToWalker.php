<?php

namespace App\Console\Commands;

use App\Library\Services\FactService;
use App\Library\Services\WalkerUtils;
use App\Models\Warehouse;
use App\WarehouseStockTransfer;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class exportStockTransfersToWalker extends Command {
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'walker:exportstocktransfers';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'exports all stock transfers to walker';

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
        $factService = FactService::getInstance();
        $factName = 'walkerStockTransferLastUpdate';
        $databaseClassName = \App\Models\WarehouseStockTransfer::Class;
        $fileSequenceFactName = 'walkerStockTransferequenceNumber';

        $outputCsvFileName = storage_path('app/chateaurougetowalkerwarehousetransferexport' . date('YmdHis') . ".csv");

        $dateFrom = $factService->getFactValue($factName, '2001-01-01T19:20+01:00');
        $dateTo = gmdate('Y-m-d\TH:i:s.000');

        // NOTE we check for a GUID because we need a primary identidier
        $destinationWarehouse = Warehouse::where('slug', 'walker')->whereNotNull('guid')->first();
        if (!$destinationWarehouse instanceof Warehouse) {
            throw new \Exception('Could not export to warehouse, since the walker warehouse does not exist');
        }
        $itemsToExport = [];
        echo "FINDIND ITEMS FOR " .  $destinationWarehouse->id."\n";
        $dbObjects = $databaseClassName::whereNull('sent_to_destination_warehouse')->where('destination_warehouse_id', $destinationWarehouse->id)->get();
        foreach ($dbObjects as $dbObject) {
            $itemsToExport = array_merge($itemsToExport, WalkerUtils::warehouseStockTransferToUpdateRowsData($dbObject));
        }


        echo "output file is " . $outputCsvFileName . "\n";
        // For some VERY odd reason, \r\n doesn't seem to work for walker, and I can't render their CSVs either
        // So I follow their instructions directly
        //@TOTO make this a workflow

        if (0 < count($itemsToExport)) {
            // No headers required
            WalkerUtils::writeMsSafeCsv($outputCsvFileName, $itemsToExport, []);

            // Incrememnt the sequence
            $sequenceNumber = $factService->getAndIncrementFact($fileSequenceFactName, 1);

            $remoteFileName = WalkerUtils::formatReceiptAdviceFileName($sequenceNumber);
            echo "writing \n----------\n $outputCsvFileName \n-------\nto $remoteFileName\n";

            try {
                WalkerUtils::storeOnFtp($outputCsvFileName, $remoteFileName);
            } catch (\Exception $e) {
                $errors[] = ['level' => 'terminal', $e->getMessage()];
                Log::error($e->getMessage());
//                var_dump($e);
            }
        }
        foreach ($dbObjects as $dbObject) {
            $dbObject->sent_to_destination_warehouse = date('Y-m-d H:i:s');
            $dbObject->save();
        }

        $factService->setFact($factName, $dateTo);
    }
}
