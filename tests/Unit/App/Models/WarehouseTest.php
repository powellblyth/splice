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

class WarehouseTest extends ModelTestBase
{

    public function testcreated_products()
    {
        $this->runRelationshipTest('\Illuminate\Database\Eloquent\Relations\belongsToMany', 'belongsToMany', 'created_products', '\App\Warehouse');
    }


}
