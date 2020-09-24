<?php

namespace App\Console\Commands;

use App\Exceptions\OrderNotReadyForShippingException;
use App\Library\Services\FactService;
use App\Library\Services\WalkerUtils;
use App\Notifications\OrderSuccessfullyExportedNotification;
use App\Notifications\OrderFailedExportNotification;
use App\Models\Order;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class exportToWalker extends Command {

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'walker:exportorders';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Takes un-exported orders and sends them to Walker';

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
        $validWarehouses = ['WALKER'];
        $validStati = ['Placed'];
        $outputCsvFileName = storage_path('app/chateaurougeorderexporttowalker' . date('YmdHis') . ".csv");
        echo "output file is " . $outputCsvFileName . "\n";
        // For some VERY odd reason, \r\n doesn't seem to work for walker, and I can't render their CSVs either
        // So I follow their instructions directly
        //@TOTO make this a workflow
        $orders = Order::where('status', 'new')->whereIn('source_status', $validStati)->whereIn('warehouse', $validWarehouses)->get();
        echo "=============" . count($orders) . " Orders =======\n\n\n";

        if (0 < count($orders)) {
            $headers = [];
            $rowData = [];
            $failedOrders = [];
            $successfulOrders = [];

            // Process each order individually
            foreach ($orders as $order) {
                try {
                    $rowData = array_merge($rowData, WalkerUtils::orderToRowData($order));
                } catch (OrderNotReadyForShippingException $e) {
                    $failedOrders[$order->order_number] = $e->getMessage();

                }
            }

            if (0 < count($rowData)) {
                $headers = array_keys($rowData[0]);

                WalkerUtils::writeMsSafeCsv($outputCsvFileName, $rowData, $headers);
                $sequenceNumber = $factService->getAndIncrementFact('walkerSequenceNumber', 1);
                $remoteFileName = WalkerUtils::formatOrderFileName($sequenceNumber);
                echo "writing \n----------\n $outputCsvFileName \n-------\nto $remoteFileName\n";
                try {
                    WalkerUtils::storeOnFtp($outputCsvFileName, $remoteFileName);
                    foreach ($orders as $order) {
                        if (!array_key_exists($order->order_number, $failedOrders)) {
                            $successfulOrders[$order->order_number] = $order->order_number;
                            $order->status = 'senttoshipping';
                            $order->save();
                        }
                    }

                } catch (\Exception $e) {
                    var_dump($e->getMessage());
                    Log::error($e->getMessage());
                    $notifiableUsers = User::Where('notify_about_failed_orders', true)->get();
                    $notification = new     OrderFailedExportNotification('Walker', [0 => $e->getMessage()]);
                    foreach ($notifiableUsers as $user) {
                        try {
                            $user->notify($notification);
                        } catch (\Aws\Ses\Exception\SesException $e) {
                            ;
                        }
                    }
                }
            }
            $notifiableUsers = User::Where('notify_about_failed_orders', true)->get();
            if (0 < count($failedOrders)) {
                // Let's notifiy the users
                $notification = new OrderFailedExportNotification('Walker', $failedOrders);
                foreach ($notifiableUsers as $user) {
                    try {
                        $user->notify($notification);
                    } catch (\Aws\Ses\Exception\SesException $e) {
                        ;
                    }
                }
            }
            if (0 < count($successfulOrders)) {
                $notification = new OrderSuccessfullyExportedNotification('Walker', $successfulOrders);
                foreach ($notifiableUsers as $user) {
                    try {
                        $user->notify($notification);
                    } catch (\Aws\Ses\Exception\SesException $e) {
                        ;
                    }
                }
            }
        }
    }
}
