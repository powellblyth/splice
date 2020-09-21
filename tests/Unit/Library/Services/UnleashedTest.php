<?php
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Tests\Unit\Library\Services;

use App\Library\Services\FtpUtils;
use Tests\TestCase;
use App\Library\Services\Unleashed;

class UnleashedTest extends Testcase
{
    public function providergetCourierIdFromProductDescription(): array
    {
        return [
            ['royalmail', 'Shipping Cost (Royal Mail 1st Class)'],
            ['standard', 'Shipping Cost (Standard Delivery (2-3 Days))'],
            //            ['ups', 'Shipping Cost (UPS Standard)'],
            //            [null, 'Royal Mail 1st Class'],
            //            [null, 'something else']
        ];
    }

    /**
     * @dataProvider providergetCourierIdFromProductDescription
     * @param $expected
     * @param $input
     */
    public function testgetCourierIdFromProductDescription($expected, $input)
    {
        $this->assertSame($expected, Unleashed::getCourierIdFromProductDescription($input));
    }


    public function providergetDeliveryMethodIdFromProductDescription(): array
    {
        return [
            ['royalmail1st', 'Shipping Cost (Royal Mail 1st Class)'],
            ['royalmail2nd', 'Shipping Cost (Royal Mail 2nd Class)'],
            ['royalmail2nd', 'Shipping Cost Royal Mail 2nd Class'],
            ['royalmail2nd', 'Shipping CostRoyal Mail 2nd Class'],
            ['dhlexpress', 'Shipping Cost (DHL Express)'],
            ['standardshipping', 'Shipping Cost (Standard Delivery (2-3 Days))'],
            [null, 'Royal Mail 1st Class'],
            [null, 'something else']
        ];
    }

    /**
     * NOTE THIS TEST IS BRITTLE AS IT RELIES ON CONFIG
     * @dataProvider providergetDeliveryMethodIdFromProductDescription
     * @param $expected
     * @param $input
     */
    public function testgetDeliveryMethodIdFromProductDescription($expected, $input)
    {
        $this->assertSame($expected, Unleashed::getDeliveryMethodIdFromProductDescription($input));
    }


    public function providergetSalesOrdersModifiedSince(): array
    {
        return [
            [0, 'SalesOrders/1', ['modifiedSince' => '2018-01-01 01:01:01', 'endDate' => '2019-01-01 01:01:01'], '2018-01-01 01:01:01', '2019-01-01 01:01:01', 1, 0],
            [1, 'SalesOrders/4', ['modifiedSince' => '2018-01-01 01:01:01', 'endDate' => '2019-01-01 01:01:01'], '2018-01-01 01:01:01', '2019-01-01 01:01:01', 4, 1],
            [2, 'SalesOrders/3', ['modifiedSince' => '2017-01-01 01:01:01', 'endDate' => '2020-01-01 01:01:01', 'ob' => 'bob', 'eb' => 'beb'], '2017-01-01 01:01:01', '2020-01-01 01:01:01', 3, 2, ['ob' => 'bob', 'eb' => 'beb']],
        ];
    }

    /**
     * @param int $expected
     * @param int $getDataReturnValue
     * @dataProvider providergetSalesOrdersModifiedSince
     */
    public function testgetSalesOrdersModifiedSince(int $expected, string $expectedEndpoint, array $expectedParams, string $dateFrom, string $dateTo, int $page, int $getDataReturnValue, array $extraParams = [])
    {
        $sut = $this->getMockBuilder('Unleashed')->setMethods(['get'])->getMock();
        $sut->expects($this->once())->method('get')->will($this->returnValue($getDataReturnValue))->with(config('unleashed.api_id'), config('unleashed.api_key'), $expectedEndpoint, $expectedParams, Unleashed::FORMAT_JSON);
        $this->assertSame($expected, $sut->getSalesOrdersModifiedSince($dateFrom, $dateTo, $page, $extraParams));
    }

    public function providergetWarehouses(): array
    {
        return [
            [0, 'Warehouses/1', ['modifiedSince' => '2018-01-01 01:01:01', 'endDate' => '2019-01-01 01:01:01'], '2018-01-01 01:01:01', '2019-01-01 01:01:01', 1, 0],
            [1, 'Warehouses/4', ['modifiedSince' => '2018-01-01 01:01:01', 'endDate' => '2019-01-01 01:01:01'], '2018-01-01 01:01:01', '2019-01-01 01:01:01', 4, 1],
            [2, 'Warehouses/3', ['modifiedSince' => '2017-01-01 01:01:01', 'endDate' => '2020-01-01 01:01:01', 'ob' => 'bob', 'eb' => 'beb'], '2017-01-01 01:01:01', '2020-01-01 01:01:01', 3, 2, ['ob' => 'bob', 'eb' => 'beb']],
        ];
    }

    /**
     * @param int $expected
     * @param int $getDataReturnValue
     * @dataProvider providergetWarehouses
     */
    public function testgetWarehouses(int $expected, string $expectedEndpoint, array $expectedParams, string $dateFrom, string $dateTo, int $page, int $getDataReturnValue, array $extraParams = [])
    {
        $sut = $this->getMockBuilder('Unleashed')->setMethods(['get'])->getMock();
        $sut->expects($this->once())->method('get')->will($this->returnValue($getDataReturnValue))->with(config('unleashed.api_id'), config('unleashed.api_key'), $expectedEndpoint, $expectedParams, Unleashed::FORMAT_JSON);
        $this->assertSame($expected, $sut->getWarehouses($dateFrom, $dateTo, $page, $extraParams));
    }

    public function providergetWarehouseStockTransfers(): array
    {
        return [
            [0, 'WarehouseStockTransfers/1', ['modifiedSince' => '2018-01-01 01:01:01', 'endDate' => '2019-01-01 01:01:01'], '2018-01-01 01:01:01', '2019-01-01 01:01:01', 1, 0],
            [1, 'WarehouseStockTransfers/4', ['modifiedSince' => '2018-01-01 01:01:01', 'endDate' => '2019-01-01 01:01:01'], '2018-01-01 01:01:01', '2019-01-01 01:01:01', 4, 1],
            [2, 'WarehouseStockTransfers/3', ['modifiedSince' => '2017-01-01 01:01:01', 'endDate' => '2020-01-01 01:01:01', 'ob' => 'bob', 'eb' => 'beb'], '2017-01-01 01:01:01', '2020-01-01 01:01:01', 3, 2, ['ob' => 'bob', 'eb' => 'beb']],
        ];
    }

    /**
     * @param int $expected
     * @param int $getDataReturnValue
     * @dataProvider providergetWarehouseStockTransfers
     */
    public function testgetWarehouseStockTransfers(int $expected, string $expectedEndpoint, array $expectedParams, string $dateFrom, string $dateTo, int $page, int $getDataReturnValue, array $extraParams = [])
    {
        $sut = $this->getMockBuilder('Unleashed')->setMethods(['get'])->getMock();
        $sut->expects($this->once())->method('get')->will($this->returnValue($getDataReturnValue))->with(config('unleashed.api_id'), config('unleashed.api_key'), $expectedEndpoint, $expectedParams, Unleashed::FORMAT_JSON);
        $this->assertSame($expected, $sut->getWarehouseStockTransfers($dateFrom, $dateTo, $page, $extraParams));
    }

    public function providergetProductsModifiedSince(): array
    {
        return [
            [0, 'Products/1', ['modifiedSince' => '2018-01-01 01:01:01', 'endDate' => '2019-01-01 01:01:01'], '2018-01-01 01:01:01', '2019-01-01 01:01:01', 1, 0],
            [1, 'Products/4', ['modifiedSince' => '2018-01-01 01:01:01', 'endDate' => '2019-01-01 01:01:01'], '2018-01-01 01:01:01', '2019-01-01 01:01:01', 4, 1],
            [2, 'Products/3', ['modifiedSince' => '2017-01-01 01:01:01', 'endDate' => '2020-01-01 01:01:01', 'ob' => 'bob', 'eb' => 'beb'], '2017-01-01 01:01:01', '2020-01-01 01:01:01', 3, 2, ['ob' => 'bob', 'eb' => 'beb']],
        ];
    }

    /**
     * @param int $expected
     * @param int $getDataReturnValue
     * @dataProvider providergetProductsModifiedSince
     */
    public function testgetProductsModifiedSince(int $expected, string $expectedEndpoint, array $expectedParams, string $dateFrom, string $dateTo, int $page, int $getDataReturnValue, array $extraParams = [])
    {
        $sut = $this->getMockBuilder('Unleashed')->setMethods(['get'])->getMock();
        $sut->expects($this->once())->method('get')->will($this->returnValue($getDataReturnValue))->with(config('unleashed.api_id'), config('unleashed.api_key'), $expectedEndpoint, $expectedParams, Unleashed::FORMAT_JSON);
        $this->assertSame($expected, $sut->getProductsModifiedSince($dateFrom, $dateTo, $page, $extraParams));
    }

//    public function providerimportThing(): array {
//        return [
//            [0, 'Products/1', ['modifiedSince' => '2018-01-01 01:01:01', 'endDate' => '2019-01-01 01:01:01'], '2018-01-01 01:01:01', '2019-01-01 01:01:01', 1, 0],
//            [1, 'Products/4', ['modifiedSince' => '2018-01-01 01:01:01', 'endDate' => '2019-01-01 01:01:01'], '2018-01-01 01:01:01', '2019-01-01 01:01:01', 4, 1],
//            [2, 'Products/3', ['modifiedSince' => '2017-01-01 01:01:01', 'endDate' => '2020-01-01 01:01:01', 'ob' => 'bob', 'eb' => 'beb'], '2017-01-01 01:01:01', '2020-01-01 01:01:01', 3, 2, ['ob' => 'bob', 'eb' => 'beb']],
//        ];
//    }
//
//    /**
//     * @param int $expected
//     * @param int $getDataReturnValue
//     * @dataProvider providerimportThing
//     */
//    public function testimportThing(int $expected, string $expectedEndpoint, array $expectedParams, string $dateFrom, string $dateTo, int $page, int $getDataReturnValue, array $extraParams = []) {
//        $sut = $this->getMockBuilder('Unleashed')->setMethods(['get'])->getMock();
//        $sut->expects($this->once())->method('get')->will($this->returnValue($getDataReturnValue))->with(config('unleashed.api_id'), config('unleashed.api_key'), $expectedEndpoint, $expectedParams, $sut::FORMAT_JSON);
////        string $factName, string $className, string $unleashedFunctionName, string $dateFrom, string $dateTo, array $extraParams = []
//        $this->assertSame($expected, $sut->importThing(
//            $factName, $className, $unleashedFunctionName, $dateFrom, $dateTo, $page, $extraParams));
//    }

}
