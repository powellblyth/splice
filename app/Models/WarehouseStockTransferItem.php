<?php

namespace App\Models;

use App\Library\Services\Unleashed;
use Illuminate\Database\Eloquent\Model;

class WarehouseStockTransferItem extends Model {
    /**
     * [{"TransferOrderId":565,
     * "LineNumber":1,
     * "SourceWarehouseId":1,
     * "WarehouseProductId":743,
     * "WarehouseLocationId":1,
     * "DestinationWarehouseId":16,
     * "DestinationWarehouseLocationId":16,
     * "ReceiptFIFODate":"\/Date(1538870400000)\/",
     * "BatchNumber":null,
     * "ExpiryDate":"\/Date(1538870400000)\/",
     * "AvailableQuantity":0,
     * "TransferQuantity":2,
     * "Comments":"FBA15C2L7N2J",
     * "AverageLandedPriceAtTimeOfTransfer":9.65466666666667,
     * "SourceWarehouseStockOnHandBeforeTransfer":2,
     * "DestinateWarehouseStockOnHandBeforeTransfer":4,
     * "WarehouseProductCode":"TGS04L"}]
     */
    protected static $unleashed = null;

    public function populateFromUnleashed(\stdClass $warehouseTransferItem) {
        if (!isset(static::$unleashed)) {
            static::$unleashed = new Unleashed();
        }
        $product = Product::where('sku', $warehouseTransferItem->WarehouseProductCode)->first();

        $this->source = 'unleashed';
        $this->receipt_fifo_date = gmdate('Y-m-d H:i:s', Unleashed::getTimestampFromUnleashedDate($warehouseTransferItem->ReceiptFIFODate));
        $this->remote_stock_transfer_id = $warehouseTransferItem->TransferOrderId;
        $this->transfer_item_remote_id = $warehouseTransferItem->WarehouseProductId;
        $this->remote_source_warehouse_id = $warehouseTransferItem->SourceWarehouseId;
        $this->remote_destination_warehouse_id = $warehouseTransferItem->DestinationWarehouseId;
        $this->batch_number = $warehouseTransferItem->BatchNumber;
        $this->remote_source_product_id = $warehouseTransferItem->WarehouseProductId;
        $this->expiry_date = gmdate('Y-m-d H:i:s', Unleashed::getTimestampFromUnleashedDate($warehouseTransferItem->ExpiryDate));
        $this->line_number = $warehouseTransferItem->LineNumber;
        $this->product_sku = $warehouseTransferItem->WarehouseProductCode;
        $this->quantity = $warehouseTransferItem->TransferQuantity;
        $this->destination_warehouse_stock_level_before = $warehouseTransferItem->DestinateWarehouseStockOnHandBeforeTransfer;
        $this->comments = $warehouseTransferItem->Comments;
        $this->raw = json_encode($warehouseTransferItem);

        if ($this->save()) {
            try {
                $this->product()->associate($product);
            } catch (\BadMethodCallException $e) {
                var_dump($e->getMessage());
                die();
            }
            return true;
        } else {
            return false;
        }

    }

    public function product(): \Illuminate\Database\Eloquent\Relations\BelongsTo {
        return $this->belongsTo(Product::class);
    }
    //
}
