<?php
/**
 * Created by PhpStorm.
 * User: toby
 * Date: 2018-11-29
 * Time: 19:12
 */

namespace Tests\Unit\App\Models;


use Tests\ModelTestBase;

//use phpmock\MockBuilder;

class ProductTest extends ModelTestBase
{
    public function providerpopulateFromUnleashed()
    {
        $stdObject              = new \stdClass();
        $stdObject->Guid        = 'someguid';
        $stdObject->Description = 'I am a description, hear me road';
        $stdObject->Barcode     = '2938498327';
        $stdObject->PackSize    = '5';
        $stdObject->Width       = 4.2;
        $stdObject->Height      = 4.4;
        $stdObject->Depth       = 4.1;
        $stdObject->Weight      = 3.1;
        return [
            [false, ['source'    => 'unleashed', 'guid' => 'someguid', 'description' => 'I am a description, hear me road',
                     'barcode'   => '2938498327',
                     'pack_size' => '5',
                     'width'     => 4.2,
                     'height'    => 4.4,
                     'depth'     => 4.1,
                     'weight'    => 3.1,
                     ''          => '',
            ], false, $stdObject],
            [true, ['source' => 'unleashed', 'guid' => 'someguid', 'description' => 'I am a description, hear me road'], true, $stdObject],
        ];
    }

    /**
     * A basic test example.
     * @dataProvider providerpopulateFromUnleashed
     * @param $expectedSaveResult
     * @param $expectedValues
     * @param bool $saveReturns
     * @param \stdClass $dataObject
     */
    public function donottestpopulateFromUnleashed(bool $expectedSaveResult, array $expectedValues, bool $saveReturns, \stdClass $dataObject)
    {
        $this->sut = $this->getMockBuilder('\App\Product')->setMethods(['save'])->disableOriginalConstructor()->getMock();
        $this->sut->method('save')->willReturn($saveReturns);
        $this->assertSame($expectedSaveResult, $this->sut->populateFromUnleashed($dataObject));
        foreach ($expectedValues as $expectedKey => $expectedValue) {
            $this->assertSame($this->sut->{$expectedKey}, $expectedValue);
        }
    }

    public function testproduct_group()
    {
        $this->runRelationshipTest('\Illuminate\Database\Eloquent\Relations\BelongsTo', 'belongsTo', 'product_group', '\App\Product');
    }

    public function testwarehouses()
    {
        $this->runRelationshipTest('\Illuminate\Database\Eloquent\Relations\BelongsToMany', 'belongsToMany', 'warehouses', '\App\Product');
    }


}
