<?php

namespace App\Console\Commands;

use App\Exceptions\BadFileException;
use App\Library\Services\FileUtils;
use App\Notifications\stockReportFromSupplierErrorNotification;
use App\Models\Product;
use App\Models\Warehouse;
use App\Models\WarehouseStockReport;
use Illuminate\Console\Command;

class processWalkerStockReports extends Command {
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'walker:processStockReports';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Processes all the stock reports in the database from Walker';

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
        $errors = [];
        $fudgeData = false;
        $pendingPath = storage_path('app/walker/received/pending/');
        $workingPath = storage_path('app/walker/received/processing/');
        $rejectedPath = storage_path('app/walker/received/rejected/');
        $processedPath = storage_path('app/walker/received/processed/');
        if ($fudgeData) {
            // Move working data back to pending
            $files = scandir($workingPath);
            foreach ($files as $fileName) {
                if (0 === strpos($fileName, 'STOCK')) {
                    rename($workingPath . $fileName, $pendingPath . $fileName);
                }
            }
            unset($files);
            // move rejected data back
            $files2 = scandir($rejectedPath);
            foreach ($files2 as $fileName) {
                if (0 === strpos($fileName, 'STOCK')) {
                    rename($rejectedPath . $fileName, $pendingPath . $fileName);
                }
            }
            unset($files2);
            // move rejected data back
            $files3 = scandir($processedPath);
            foreach ($files3 as $fileName) {
                if (0 === strpos($fileName, 'STOCK')) {
                    rename($processedPath . $fileName, $pendingPath . $fileName);
                }
            }
            unset($files2);
        }

        $warehouse = Warehouse::where('slug', 'walker')->first();

        $files = scandir($pendingPath);
        foreach ($files as $fileName) {
            if (0 === strpos($fileName, 'STOCK')) {
                rename($pendingPath . $fileName, $workingPath . $fileName);

                echo $workingPath . $fileName . "\n";
                $fileHandle = FileUtils::openFileForReading($workingPath . $fileName);

                $headers = [];
                try {

                    while (($data = fgetcsv($fileHandle, 10000, ",")) !== FALSE) {
                        // Process first row
                        if (0 === count($headers)) {
                            echo "DOING HEADERS\n";
                            for ($c = 0; $c < count($data); $c++) {
                                $headers[$c] = $data[$c];
                            }
                        } else {
                            if (!in_array('ProductCode', $headers)) {
                                // some fempty rows found
                                throw new BadFileException();
                                break;
                            } else {
                                if (count($headers) == count($data)) {
                                    $indexedData = [];
                                    // Convert the numerically indexed array into a text indexed array
                                    foreach ($headers as $index => $value) {
                                        echo "a indexing $value at $index\n";
                                        if (!array_key_exists($index, $data)) {
                                            var_dump($data);
                                        }
                                        $indexedData[$value] = $data[$index];
                                    }

                                    $productCode = $indexedData['ProductCode'];
                                    echo "Product Code '$productCode'\n";
                                    $product = Product::where('sku', $productCode)->first();
                                    if (!$product instanceof Product) {
                                        echo "Not found :( \n";
                                        $errors[$productCode] = ['message' => 'could not find product when processing Stock Report'];
                                    } else {
                                        $warehouseStockReport = new WarehouseStockReport();
                                        $warehouseStockReport->source_warehouse_id = $warehouse->id;
                                        $warehouseStockReport->source_warehouse = 'walker';
                                        $warehouseStockReport->product_code = $product->sku;
                                        $warehouseStockReport->product_id = $product->id;
                                        $warehouseStockReport->product_description = $indexedData['ProductDescription'];
                                        $warehouseStockReport->current_stock = (int)$indexedData['CurrentStock'];
                                        $warehouseStockReport->free_stock = (int)$indexedData['FreeStock'];
                                        $warehouseStockReport->status = 'new';
                                        $warehouseStockReport->destination = 'unleashed';
                                        $warehouseStockReport->raw = implode(',', $indexedData);

                                        if (!$warehouseStockReport->save()) {
                                            $errors[$productCode] = ['message' => 'couldnt save warehouse stock report' . $warehouseStockReport->raw];
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
                    FileUtils::closeFile($fileHandle);

                }
            }

        }
        if (0 < count($errors))
        {
            $notifiableUsers = User::Where('notify_about_failed_orders', true)->get();
            $notification = new stockReportFromSupplierErrorNotification('Walker', $errors);
            foreach ($notifiableUsers as $user) {
                try {
                    $user->notify($notification);
                } catch (\Aws\Ses\Exception\SesException $e) {
                    ;
                }
            }

        }
        var_dump($errors);
    }
}
