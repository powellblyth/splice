<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use App\Library\Services\Unleashed;


class showOrder extends Command {
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'unleashed:showorder {ordernum}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Just dumps an order to screen';

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
        $unleashed = new Unleashed();

        $orders = $unleashed->getSalesOrder($this->argument('ordernum'), false);
        var_dump($orders);
        if ($orders) {
            $order = $orders->Items[0];

            foreach ($order->SalesOrderLines as $key => $line) {
                $batches = $unleashed->getBatchesForProduct($line->Product->ProductCode, $order->Warehouse->WarehouseCode);
                if (0 < count($batches->Items)) {
                    var_dump($batches);
                    $batch = $batches->Items[0];
                    var_dump($batch);
                    $unleashed->postSerialBatch($order->Guid, false);
//                $order->SalesOrderLines[$key]->BatchNumbers = $batch->Number;
                } else {
                    echo "No batches available";
                }
//            $unleashed->postSalesOrder($order);
//            $batches = $unleashed->getBatchesForProduct($line->Product->ProductCode);
//            var_dump($line);
            }
            return true;
        }
    }
}