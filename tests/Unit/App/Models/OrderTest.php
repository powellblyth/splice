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

class ProductGroupTest extends ModelTestBase
{
    public function providerpopulateFromUnleashed()
    {
        $stdObject                 = new \stdClass();
        $stdObject->Guid           = 'someguid';
        $stdObject->GroupName      = 'someGroupName';
        $stdObject->LastModifiedOn = '\/Date(1538861748000)\/';
        return [
            [false, ['source' => 'unleashed', 'guid' => 'someguid', 'remote_last_modified' => '2018-10-06 21:35:48'], false, $stdObject],
            [true, ['source' => 'unleashed', 'guid' => 'someguid', 'remote_last_modified' => '2018-10-06 21:35:48'], true, $stdObject],
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
    public function testpopulateFromUnleashed(bool $expectedSaveResult, array $expectedValues, bool $saveReturns, \stdClass $dataObject)
    {
        $this->sut = $this->getMockBuilder('\App\ProductGroup')->setMethods(['save'])->disableOriginalConstructor()->getMock();
        $this->sut->method('save')->willReturn($saveReturns);
        $this->assertSame($expectedSaveResult, $this->sut->populateFromUnleashed($dataObject));
        foreach ($expectedValues as $expectedKey => $expectedValue) {
            $this->assertSame($this->sut->{$expectedKey}, $expectedValue);
        }
    }

    public function testproducts()
    {
        $this->runRelationshipTest('\Illuminate\Database\Eloquent\Relations\HasMany', 'hasMany', 'products', '\App\ProductGroup');
    }


}
