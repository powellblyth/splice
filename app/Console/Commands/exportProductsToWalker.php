<?php

namespace App\Console\Commands;

Use App\Models\Fact;
use App\Library\Services\FactService;
use App\Library\Services\WalkerUtils;
use App\Models\Product;
use App\Models\ProductGroup;
use App\Models\Warehouse;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class exportProductsToWalker extends Command {

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'walker:exportproducts';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Takes un-exported products and sends them to Walker';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle() {
        // NOTE that the second is here, because I rebuilt this, and truncated the database
        $factService = FactService::getInstance();
        $factName = 'walkerProductLastUpdate';
        $databaseClassName = Product::class;
        $fileSequenceFactName = 'walkerPTOSequenceNumber';

        $outputCsvFileName = storage_path('app/chateaurougetowalkerproductexport' . date('YmdHis') . ".csv");

        $warehouse = Warehouse::where('slug', 'walker')->first();

        $dateFrom = $factService->getFactValue($factName, '2001-01-01T19:20+01:00');
        $dateTo = gmdate('Y-m-d\TH:i:s.000');
        echo "dateFrom " . $dateFrom . " to " . $dateTo . "\n";
        $itemsToExport = [];
        $itemsToUpdate = [];

        $productGroups = ProductGroup::where('is_complete_product', true)->get();
        $products = null;
        foreach ($productGroups as $productGroup) {
            echo $productGroup->group_name . " " . $productGroup->id . "\n==========\n";
            $productsToAdd = $productGroup->products()->get();

            // A record of which products we create
            $createdProducts = [];
            foreach ($productsToAdd as $product) {
                $createdProduct = $warehouse->created_products()->where('product_id', $product->id)->first();
                if (!$createdProduct instanceof Product) {
                    $itemsToExport[] = WalkerUtils::productToCreateRowData($product);
                    $warehouse->created_products()->attach($product);
                    $createdProducts[] = $product->id;
                }

            }

            $productsToUpdate = $productGroup->products()
                ->where('updated_at', '>', $dateFrom)
                ->where('updated_at', '<', $dateTo)
//->where('sku', 'RCPCLT1')
                /// NOTE walker won't accept orders that have not got this data
//                ->whereNotNull('weight')
//                ->whereNotNull('height')
//                ->whereNotNull('depth')
//                ->whereNotNull('width')
//                ->where('weight', '>', 0)
//                ->where('height', '>', 0)
//                ->where('depth', '>', 0)
//                ->where('width', '>', 0)
                ->get();
//            echo "dounf " . count($productsToUpdate) . " product groups\n";
            foreach ($productsToUpdate as $product) {
                // Ignore products that we just created
                if (!in_array($product->id, $createdProducts)) {
                    $itemsToUpdate[] = WalkerUtils::productToUpdateRowData($product);
                }
            }
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

            $remoteFileName = WalkerUtils::formatPtoFileName($sequenceNumber);
            echo "writing \n----------\n $outputCsvFileName \n-------\nto $remoteFileName\n";

            try {
                WalkerUtils::storeOnFtp($outputCsvFileName, $remoteFileName);
            } catch (\Exception $e) {
                $errors[] = ['level' => 'terminal', $e->getMessage()];
                Log::error($e->getMessage());
//                var_dump($e);
            }
        }

        $outputCsvFileName = storage_path('app/chateaurougetowalkerproductupdate' . date('YmdHis') . ".csv");
        echo "output file is " . $outputCsvFileName . "\n";
        // For some VERY odd reason, \r\n doesn't seem to work for walker, and I can't render their CSVs either
        // So I follow their instructions directly
        //@TOTO make this a workflow
        echo "=============" . count($itemsToUpdate) . " " . $databaseClassName . "s =======\n\n\n";

        if (0 < count($itemsToUpdate)) {
            // No headers required
            WalkerUtils::writeMsSafeCsv($outputCsvFileName, $itemsToUpdate, []);

            // Incrememnt the sequence
            $sequenceNumber = $factService->getAndIncrementFact('walkerProductOverwriteSequenceNumber', 1);

            $remoteFileName = WalkerUtils::formatProductUpdateFileName($sequenceNumber);
            echo "writing \n----------\n $outputCsvFileName \n-------\nto $remoteFileName\n";
            try {
                WalkerUtils::storeOnFtp($outputCsvFileName, $remoteFileName);
            } catch (\Exception $e) {
                var_dump($e->getMessage());
                $errors[] = ['level' => 'terminal', $e->getMessage()];
                Log::error($e->getMessage());
            }
        }
        $factService->setFact($factName, $dateTo);
    }
}
