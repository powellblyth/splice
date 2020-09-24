<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Exceptions\BadFileException;
use App\Library\Services\FileUtils;
use App\Notifications\OrdersDespatchUpdateFailureNotification;
use App\Notifications\OrdersDespatchedNotification;
use App\Models\User;
use App\Models\Order;
use App\Models\OrderLine;
use App\Models\Shipment;
use Illuminate\Support\Facades\Log;

class processWalkerDespatchConfirms extends Command {

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'walker:processDespatchConfirms';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Process all the confirmations that Walker have despatched';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct() {
        parent::__construct();
    }

    private function fudgeData(string $workingPath, string $pendingPath, string $rejectedPath, string $processedPath) {
        // Move working data back to pending
        $files = scandir($workingPath);
        foreach ($files as $fileName) {
            if (0 === strpos($fileName, 'DESCON')) {
                rename($workingPath . $fileName, $pendingPath . $fileName);
            }
        }
        unset($files);
        // move rejected data back
        $files2 = scandir($rejectedPath);
        foreach ($files2 as $fileName) {
            if (0 === strpos($fileName, 'DESCON')) {
                rename($rejectedPath . $fileName, $pendingPath . $fileName);
            }
        }
        unset($files2);
        // move rejected data back
        $files3 = scandir($processedPath);
        foreach ($files3 as $fileName) {
            if (0 === strpos($fileName, 'DESCON')) {
                rename($processedPath . $fileName, $pendingPath . $fileName);
            }
        }
        unset($files2);
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle() {
        $fudgeData = false;

        $pendingPath = storage_path('app/walker/received/pending/');
        $workingPath = storage_path('app/walker/received/processing/');
        $rejectedPath = storage_path('app/walker/received/rejected/');
        $processedPath = storage_path('app/walker/received/processed/');
        if ($fudgeData) {
            $this->fudgeData($workingPath, $pendingPath, $rejectedPath, $processedPath);
        }
        $successes = [];
        $errors = [];

        $files = scandir($pendingPath);
        foreach ($files as $fileName) {
            if (0 === strpos($fileName, 'DESCON')) {
                rename($pendingPath . $fileName, $workingPath . $fileName);

                echo "====================\n" . $workingPath . $fileName . "\n";
                $fileHandle = FileUtils::openFileForReading($workingPath . $fileName);
                $successes[$fileName] = [];

                $headers = [];
                try {

                    while (($data = fgetcsv($fileHandle, 10000, ",")) !== FALSE) {
                        // Process first row
                        if (0 === count($headers)) {
                            echo 'DOING HEADERS\n';
                            for ($c = 0; $c < count($data); $c++) {
                                $headers[$c] = $data[$c];
                            }
                        } else {
                            echo json_encode($headers);
                            echo "\n";
                            // Disregard blank lines
                            echo "COUNT IS " . count($data) . "\n";
                            if (1 < count($data)) {
                                if (!in_array('OrderNumber', $headers)) {
                                    throw new BadFileException();
                                    break;
                                } else {
                                    $indexedData = [];
                                    // Convert the numerically indexed array into a text indexed array
                                    foreach ($headers as $index => $value) {
                                        echo "a indexing $value at $index\n";
                                        $indexedData[$value] = $data[$index];
                                    }

                                    $orderNumber = $indexedData['OrderNumber'];
                                    echo "Order number '$orderNumber'\n";
                                    $order = Order::where('order_number', $orderNumber)->first();
                                    if (!$order instanceof Order) {
                                        echo "Not found :( \n";
                                        Log:error("$orderNumber found not found importing despatch confirm");
                                        $errors[$orderNumber] = $fileName . ' - could not find order when marking despatched';
                                    } else {
                                        $orderLine = $order->order_lines()->where('line_number', $indexedData['DatabaseLineNumber'])->first();
//                                        $orderLine = OrderLine::where('order_id', $order->id)->where('line_number', $indexedData['DatabaseLineNumber'])->first();
                                        if ($orderLine instanceof OrderLine) {
                                            //    $guzzle = new GuzzleHttp\Client();
                                            // Check this isn't a dupe
                                            $shipment = Shipment::where('carrier', $indexedData['Carrier'])->where('tracking_number', $indexedData['TrackingNumber'])->first();
                                            if (!$shipment instanceof Shipment) {
                                                $shipment = new Shipment();
                                                if (!$shipment->createFromWalker($orderLine, $indexedData)) {
                                                    $errors[$orderNumber] = $fileName . ' - could not save status of shipment order line ' . $orderLine->line_number . ' (' . $orderLine->product_code . ') in database when marking despatched! Panic! ' . print_r($indexedData, true);
                                                }
                                            } else {
                                                $shipment->order_lines()->attach($orderLine);
                                            }
                                            $successes[$fileName][$orderNumber] = 'Line ' . $orderLine->line_number . ' (' . $orderLine->product_code . ') Batch ' . $shipment->batch_number . ", courier=" . $shipment->carrier . ", (#" . $shipment->tracking_number . ')';
                                        } else {
                                            $errors[$orderNumber] = $fileName . ' - order line ' . $indexedData['DatabaseLineNumber'] . ' does not exist for order ' . $order->id;
                                        }
                                    }
                                }
                            }
                        }
                    }
                    rename($workingPath . $fileName, $processedPath . $fileName);
//                    rename($workingPath . $fileName, $processedPath . $fileName);
                    FileUtils::closeFile($fileHandle);
                } catch (BadFileException $ex) {
                    echo "NO GOOD";
//                    $errors[$fileName] = 'File was corrupt';
                    Log::error("$fileName File was corrupt");
                    FileUtils::closeFile($fileHandle);

                    rename($workingPath . $fileName, $rejectedPath . $fileName);
                }
            }
        }

        $this->notifyUsers($errors, $successes);
    }

    protected function getNotifiableUsers() {
        return User::Where('notify_about_failed_orders', true)->get();
    }

    protected function notifyUsers(array $errors, array $successes) {
        if (0 < count($errors) || (0 < count($successes) && 0 < $successes[array_keys($successes)[0]])) {
            $notifiableUsers = $this->getNotifiableUsers();
            if (0 < count($errors)) {
                $notification = new OrdersDespatchUpdateFailureNotification('Walker', $errors);
                foreach ($notifiableUsers as $user) {
                    try {
                        $user->notify($notification);
                    } catch (\Aws\Ses\Exception\SesException $e) {
                        Log::error('Could not notify' . $user->email);
                    }
                }
            }
            if (0 < count($successes) && 0 < $successes[array_keys($successes)[0]]) {
                $notification = new OrdersDespatchedNotification('Walker', $successes);
                foreach ($notifiableUsers as $user) {
                    try {
                        $user->notify($notification);
                    } catch (\Aws\Ses\Exception\SesException $e) {
                        Log::error('Could not notify' . $user->email);
                    }
                }
            }
        }
    }

}
