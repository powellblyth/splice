<?php
/**
 * Created by PhpStorm.
 * User: toby
 * Date: 2018-11-29
 * Time: 19:12
 */

namespace Tests\Unit\App\Models;


use Tests\ModelTestBase;

class WarehouseStockTransferTest extends ModelTestBase
{
    public function testsource_warehouse()
    {
        $this->runRelationshipTest('\Illuminate\Database\Eloquent\Relations\belongsTo', 'belongsTo', 'source_warehouse', '\App\WarehouseStockTransfer');
    }

    public function testdestination_warehouse()
    {
        $this->runRelationshipTest('\Illuminate\Database\Eloquent\Relations\belongsTo', 'belongsTo', 'destination_warehouse', '\App\WarehouseStockTransfer');
    }

    public function testwarehouse_stock_transfer_items()
    {
        $this->runRelationshipTest('\Illuminate\Database\Eloquent\Relations\HasMany', 'hasMany', 'warehouse_stock_transfer_items', '\App\WarehouseStockTransfer');
    }
}
