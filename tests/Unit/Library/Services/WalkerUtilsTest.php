<?php
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Tests\Unit\Library\Services;

use Tests\TestCase;
use App\Library\Services\WalkerUtils;

class WalkerUtilsTest extends Testcase {
    public function providerformatOrderFileName() {
        return [
            [config('walker.consumer_order_file_code') . '000054543.CSV', 54543],
            [config('walker.consumer_order_file_code') . '000000999.CSV', 999],
            [config('walker.consumer_order_file_code') . '000000005.CSV', 5],
        ];
    }

    /**
     * A basic test example.
     * @dataProvider providerformatOrderFileName
     * @return void
     */
    public function testformatOrderFileName($expected, int $sequence) {
        $this->assertSame($expected, WalkerUtils::formatOrderFileName($sequence));
    }

    public function providerformatReceiptAdviceFileName() {
        return [
            [config('walker.receipt_advice_file_code') . '000054543.CSV', 54543],
            [config('walker.receipt_advice_file_code') . '000000999.CSV', 999],
            [config('walker.receipt_advice_file_code') . '000000005.CSV', 5],
        ];
    }

    /**
     * A basic test example.
     * @dataProvider providerformatReceiptAdviceFileName
     * @return void
     */
    public function testformatReceiptAdviceFileName($expected, int $sequence) {
        $this->assertSame($expected, WalkerUtils::formatReceiptAdviceFileName($sequence));
    }

    public function providerformatPtoFileName() {
        return [
            [config('walker.pto_file_code') . '000054543.CSV', 54543],
            [config('walker.pto_file_code') . '000000999.CSV', 999],
            [config('walker.pto_file_code') . '000000005.CSV', 5],
        ];
    }

    /**
     * A basic test example.
     * @dataProvider providerformatPtoFileName
     * @return void
     */
    public function testformatPtoFileName($expected, int $sequence) {
        $this->assertSame($expected, WalkerUtils::formatPtoFileName($sequence));
    }


    public function providerformatProductUpdateFileName() {
        return [
            [config('walker.product_update_file_name') . '000054543.CSV', 54543],
            [config('walker.product_update_file_name') . '000000999.CSV', 999],
            [config('walker.product_update_file_name') . '000000005.CSV', 5],
        ];
    }

    /**
     * A basic test example.
     * @dataProvider providerformatProductUpdateFileName
     * @return void
     */
    public function testformatProductUpdateFileName($expected, int $sequence) {
        $this->assertSame($expected, WalkerUtils::formatProductUpdateFileName($sequence));
    }


    public function testgetOrderPath() {
        $this->assertSame('towalker/', WalkerUtils::getOrderFilePath());
    }


    public function providerrowToCsv(): array {
        return [
            ['bob,blarney,survivor, pot@to,The quick brown fox jumps over the lazy dog', ['bob', 'bl\'ar%$“~+=\',ney', 'survivor', ' pot@to', 'The quick brown fox jumps over the lazy dog']]
        ];
    }

    /**
     * @dataProvider providerrowToCSV
     * @param $expected
     * @param $row
     */
    public function testrowToCsv(string $expected, array $row) {
        $this->assertSame($expected, WalkerUtils::rowToCsv($row));
    }

    public function providergetOrderDueDate(): array {
        return [
            [date('d/m/Y'), null],
            [date('05/11/2018'), '2018-11-05 13:19:31'],
            [date('05/02/2018'), '2018-02-05 13:19:31'],

        ];
    }

    /**
     * @dataProvider providergetOrderDueDate
     * @param string $expected
     * @param string|null $dateValue
     */
    public function testgetOrderDueDate(string $expected, string $dateValue = null) {
        $this->assertSame($expected, WalkerUtils::getOrderDueDate($dateValue));
    }

    public function providergetWalkerFormattedProductDimension(): array{
        return [
            ['3000', 3],
            ['3000', '3'],
            ['3400', 3.4],
            [null, 0.0],
            [null, 0],
            [null,'0'],
            ['-1000', -1],
            [null,null],
            [null,'']
        ];
    }

    /**
     * @param $expected
     * @param $input
     * @dataProvider providergetWalkerFormattedProductDimension
     */
    public function testgetWalkerFormattedProductDimension($expected, $input){
        $this->assertSame($expected, WalkerUtils::getWalkerFormattedProductDimension($input));
    }

    public function providergetWalkerFormattedWeight(): array{
        return [
            ['3', 3],
            ['3', '3'],
            ['3.4', 3.4],
            [null, 0.0],
            [null, 0],
            [null,'0'],
            ['-1', -1],
            [null,null]
        ];
    }

    /**
     * @param $expected
     * @param $input
     * @dataProvider providergetWalkerFormattedWeight
     */
    public function testgetWalkerFormattedWeight($expected, $input){
        $this->assertSame($expected, WalkerUtils::getWalkerFormattedWeight($input));
    }


}

