<?php
/**
 * Created by PhpStorm.
 * User: toby
 * Date: 2018-11-29
 * Time: 19:12
 */

namespace Tests\Unit\App\Models;


use Tests\ModelTestBase;

//use Tests\Unit\ModelTestBase;
//use phpmock\MockBuilder;

class WarehouseStockTransferItemTest extends ModelTestBase
{


    public function testproduct_groups()
    {
        $this->runRelationshipTest('\Illuminate\Database\Eloquent\Relations\belongsTo', 'belongsTo', 'product', '\App\WarehouseStockTransferItem');
    }


}
