<?php
/**
 * Created by PhpStorm.
 * User: toby
 * Date: 2018-11-29
 * Time: 19:12
 */

namespace Tests\Unit\App\Models;

use Tests\ModelTestBase;

class ShipmentTest extends ModelTestBase
{

    public function testorder_lines()
    {
        $this->runRelationshipTest('\Illuminate\Database\Eloquent\Relations\belongsToMany', 'belongsToMany', 'order_lines', '\App\Shipment');
    }

    public function testorder()
    {
        $this->runRelationshipTest('\Illuminate\Database\Eloquent\Relations\belongsTo', 'belongsTo', 'order', '\App\Shipment');
    }

}
