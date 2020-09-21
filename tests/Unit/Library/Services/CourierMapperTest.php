<?php
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Tests\Unit\Library\Services;

use Tests\TestCase;
use App\Library\Services\CourierMapper;

class CourierMapperTest extends Testcase
{
    public function setUp(): void
    {
        parent::setUp();
        \Config::set('fakeservice.couriers.royalmail', 'moo');
        \Config::set('fakeservice2.couriers.royalmail', 'woof');
        \Config::set('fakeservice3.couriers.royalmail', 'Standard Delivery 2-3 Days');

//        \Config('aftership.api_key');

    }

    public function teardown(): void
    {
        parent::tearDown();
        \Config::set('fakeservice', null);
        \Config::set('fakeservice2', null);
        \Config::set('fakeservice3', null);
//        \Config('aftership.api_key');

    }

    public function providergetExternalCode()
    {
        $this->setUp();
        return [
            [config('fakeservice.couriers.royalmail'), 'fakeservice', 'royalmail'],
            [config('fakeservice3.couriers.royalmail'), 'fakeservice3', 'royalmail'],
            [null, 'fakeservice', 'Royalmail'],
            [null, 'fakeservice', 'banana'],
            [null, 'fakeservice', 'banana'],
        ];
    }

    /**
     * A basic test example.
     * @dataProvider providergetExternalCode
     * @return void
     */
    public function testgetExternalCode($expected, string $toSystem, string $courierCode)
    {
        $this->assertSame($expected, CourierMapper::getExternalCode($toSystem, $courierCode));
//        $this->sut = $this->getMockBuilder(FtpUtils::class)->setMethods([])->disableOriginalConstructor()->getMock();
//        $this->sut->ftpHandle = null;
//        $this->assertSame(false, $this->sut->isConnected());
//        $this->sut->expects($this->once())->method('isLate')->will($this->returnValue($isLate));
    }

    public function providergetInternalCode()
    {
        $this->setUp();
        return [
            ['royalmail', 'fakeservice', config('fakeservice.couriers.royalmail')],
            // Manually magicked in bbecause of issues with ( inside orders
            ['royalmail', 'fakeservice3', 'Standard Delivery 2-3 Days'],
            [null, 'fakeservice', 'Royalmail'],
            [null, 'fakeservice', 'banana'],
            [null, 'fakeservice', 'banana'],
        ];

    }

    /**
     * @dataProvider providergetInternalCode
     * @param $expected
     * @param $toSystem
     * @param $courierCode
     */
    public function testgetInternalCode($expected, $toSystem, $courierCode)
    {
        $this->assertSame($expected, CourierMapper::getInternalCode($toSystem, $courierCode));

    }


    public function providertranslateCourier()
    {
        $this->setUp();
        return [
            [config('fakeservice2.couriers.royalmail'), 'fakeservice', 'fakeservice2', config('fakeservice.couriers.royalmail')],
            [config('fakeservice.couriers.royalmail'), 'fakeservice2', 'fakeservice', config('fakeservice2.couriers.royalmail')],
        ];

    }

    /**
     * @dataProvider providertranslateCourier
     * @param $expected
     * @param $fromSystem
     * @param $toSystem
     * @param $courierCode
     */
    public function testtranslateCourier(?string $expected, string $fromSystem, string $toSystem, string $courierCode)
    {
        $this->assertSame($expected, CourierMapper::translateCourier($fromSystem, $toSystem, $courierCode));
    }
//    public function providergetCountryCode()
//    {
//        return [
//            ['GB', 'GB'],
//            ['GB', 'United Kingdom'],
//            ['GB', 'UK'],
//            ['FR', 'France'],
//            ['', 'Deutschland'],
//        ];
//    }
//
//    /**
//     * @dataProvider providergetCountryCode
//     * @param $expected
//     * @param $country
//     */
//    public function testgetCountryCode($expected, $country){
//        $this->assertSame($expected, AfterShip::getCountryCode($country));
//
//    }


}

