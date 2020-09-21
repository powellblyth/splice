<?php

namespace App\Models;

use App\Library\Services\Unleashed;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\belongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class WarehouseStockTransfer extends Model {
    protected static $unleashed = null;

    public function populateFromUnleashed(\stdClass $warehouseTransfer) {
        if (!isset(static::$unleashed)) {
            static::$unleashed = new Unleashed();
        }

        $this->source = 'unleashed';
        $this->transfer_number = $warehouseTransfer->TransferOrderNumber;
        $this->comments = $warehouseTransfer->Comments;
        $this->delivery_date = gmdate('Y-m-d H:i:s', Unleashed::getTimestampFromUnleashedDate($warehouseTransfer->DeliveryDate));
        $this->destination_warehouse_data = json_encode($warehouseTransfer->DestinationWarehouse);
        // We can't change the destination warehouse, too risky
        if ( !$this->exists && is_object($warehouseTransfer->DestinationWarehouse) && isset($warehouseTransfer->DestinationWarehouse->WarehouseCode)) {
            $this->destination_warehouse = $warehouseTransfer->DestinationWarehouse->WarehouseCode;
            $destinationWarehouse = Warehouse::where('code',  $warehouseTransfer->DestinationWarehouse->WarehouseCode)->first();
            if ($destinationWarehouse instanceof Warehouse){
                $this->destination_warehouse()->associate($destinationWarehouse);
            }
        }
        $this->guid = $warehouseTransfer->Guid;
        $this->created_by = $warehouseTransfer->CreatedBy;
        $this->created_on = gmdate('Y-m-d H:i:s', Unleashed::getTimestampFromUnleashedDate($warehouseTransfer->CreatedOn));
        $this->last_modified_by = $warehouseTransfer->LastModifiedBy;
        $this->last_modified_on = gmdate('Y-m-d H:i:s', Unleashed::getTimestampFromUnleashedDate($warehouseTransfer->LastModifiedOn));
        $this->order_date = gmdate('Y-m-d H:i:s', Unleashed::getTimestampFromUnleashedDate($warehouseTransfer->OrderDate));
        $this->source_warehouse_data = json_encode($warehouseTransfer->SourceWarehouse);

        // We can't change the source warehouse, too risky
        if ( !$this->exists && is_object($warehouseTransfer->SourceWarehouse) && isset($warehouseTransfer->SourceWarehouse->WarehouseCode)) {
            $this->source_warehouse = $warehouseTransfer->SourceWarehouse->WarehouseCode;
            $sourceWarehouse = Warehouse::where('code',  $warehouseTransfer->SourceWarehouse->WarehouseCode)->first();
//echo "ID " .$warehouseTransfer->TransferOrderNumber ." - FINDING " . $warehouseTransfer->SourceWarehouse->WarehouseCode."\n";
//var_dump(get_class($sourceWarehouse)){}
            if ($sourceWarehouse instanceof Warehouse){
                $this->source_warehouse()->associate($sourceWarehouse);
            }
        }
        $this->transfer_details = json_encode($warehouseTransfer->TransferDetails);
        $this->transfer_order_number = $warehouseTransfer->TransferOrderNumber;
        $this->transfer_status = $warehouseTransfer->TransferStatus;

        $this->raw = json_encode($warehouseTransfer);

        if ($this->save()) {
            if (isset($warehouseTransfer->TransferDetails) && count($warehouseTransfer->TransferDetails) > 0) {
                foreach ($warehouseTransfer->TransferDetails as $transferItem) {
                    $transferItemDbObject = $this->warehouse_stock_transfer_items()->where('line_number', $transferItem->LineNumber)->first();
                    if (!$transferItemDbObject instanceof WarehouseStockTransferItem) {
                        $transferItemDbObject = new WarehouseStockTransferItem();
                    }
                    if ($transferItemDbObject->populateFromUnleashed($transferItem)) {
                        try {
                            $this->warehouse_stock_transfer_items()->save($transferItemDbObject);
                        } catch (\BadMethodCallException $e) {
                            var_dump($e->getMessage());
                            die();
                        }
                    }
                }
            }
        }
    }

    /**
     * one to many
     * @return HasMany
     */
    public function warehouse_stock_transfer_items(): HasMany {
        return $this->hasMany(WarehouseStockTransferItem::class);
    }
    /**
     * one to many
     * @return HasMany
     */
    public function destination_warehouse(): belongsTo {
        return $this->belongsTo(Warehouse::class);
    }
    /**
     * one to many
     * @return HasMany
     */
    public function source_warehouse(): belongsTo {
        return $this->belongsTo(Warehouse::class);
    }
}
