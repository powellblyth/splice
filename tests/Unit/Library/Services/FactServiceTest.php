<?php
/**
 * Created by PhpStorm.
 * User: toby
 * Date: 2018-11-29
 * Time: 19:12
 */

namespace Tests\Unit\Library\Services;

use App\Library\Services\FactService;
use Tests\ModelTestBase;

//use Tests\Unit\ModelTestBase;
//use phpmock\MockBuilder;

class FactServiceTest extends ModelTestBase
{

    /**
     * Check that we only get one instance
     */
    public function testGetInstance()
    {
        $sut = FactService::getInstance();
        $this->assertSame($sut, FactService::getInstance());
    }

    public function providergetFact()
    {
        return [
            ['woof', 'woof', true, 'baa'],
            ['miaow', 'woof', false, 'miaow']
        ];
    }

    /**
     * A basic test example.
     * @dataProvider providergetFact
     * @param $expectedSaveResult
     * @param $expectedValues
     * @param bool $saveReturns
     * @param \stdClass $dataObject
     */
    public function testgetFactValue(string $expectedFactValue, $factValue, $factExists, $default)
    {
        $factName = "IdontcarebecauseIstubbtedthefile" . (int) rand(0, 1000);
        if ($factExists) {
            $mockFact        = $this->getMockBuilder('\App\Fact')->setMethods(['save'])->disableOriginalConstructor()->getMock();
            $mockFact->value = $factValue;
        } else {
            $mockFact = null;
        }

        $sut = $this->getMockBuilder('App\Library\Services\FactService')->setMethods(['getFactFromDb'])->disableOriginalConstructor()->getMock();
        $sut->method('getFactFromDb')->with($factName)->willReturn($mockFact);

        $this->assertSame($expectedFactValue, $sut->getFactValue($factName, $default));

    }

    public function providergetAndIncrementFact()
    {
        return [
            [8, 7, true, 1],
            [1, 88, false, 0]
        ];
    }

    /**
     * @dataProvider providergetAndIncrementFact
     * @param int $expectedFactValue
     * @param int $factValue
     * @param bool $factExists
     * @param int $default
     */
    public function testgetAndIncrementFact(int $expectedFactValue, int $factValue, bool $factExists, int $default)
    {
        $factName = "IdontcarebecauseIstubbtedthefile" . (int) rand(0, 1000);
        if ($factExists) {
            $mockFact        = $this->getMockBuilder('\App\Fact')->setMethods(['save'])->disableOriginalConstructor()->getMock();
            $mockFact->value = $factValue;
        } else {
            $mockFact = null;
        }

        $sut = $this->getMockBuilder('\App\\Library\\Services\\FactService')->setMethods(['getFactFromDb', 'setFact'])->disableOriginalConstructor()->getMock();
        $sut->method('getFactFromDb')->with($factName)->willReturn($mockFact);
        $sut->expects($this->once())->method('setFact')->willReturn(true);
        $this->assertSame($expectedFactValue, $sut->getAndIncrementFact($factName, $default));

    }

//    public function providersetFact() {
//        return [
//            ['woof', 'oink', true, 'tweet'],
////            ['miaow', 'oink', false, 'neigh']
//        ];
//    }
//
//    /**
//     * A basic test example.
//     * @dataProvider providersetFact
//     * @param $expectedSaveResult
//     * @param $expectedValues
//     * @param bool $saveReturns
//     * @param \stdClass $dataObject
//     */
//    public function testsetFact(string $expectedFactNewValue, string $factValue , bool $factExists, string $newFactValue) {
//        $factName = "IdontcarebecauseIstubbtedthefile".(int)rand(0,1000);
//        if ($factExists) {
//            $mockFact = $this->getMockBuilder('\App\Fact')->setMethods(['save'])->disableOriginalConstructor()->getMock();
//            $mockFact->expects($this->once())->method('save')->will($this->returnValue(true));
//            $mockFact->value = $factValue;
//        }
//        else{
//            $mockFact = null;
//        }
//
//        $sut = $this->getMockBuilder('App\\Library\\Services\\FactService')->setMethods(['getFactFromDb'])->disableOriginalConstructor()->getMock();
//        $sut->method('getFactFromDb')->with($factName)->willReturn($mockFact);
//        $sut->setFact($factName ,$newFactValue);
//        $this->assertSame($expectedFactNewValue, $mockFact->value);
//
//    }

//
//    public function testproducts() {
//        $this->runRelationshipTest('\Illuminate\Database\Eloquent\Relations\HasMany', 'hasMany', 'products', '\App\ProductGroup');
//    }


}
