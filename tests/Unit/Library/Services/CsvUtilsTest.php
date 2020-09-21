<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Tests\Unit\Library\Services;

use Tests\TestCase;
use App\Library\Services\CsvUtils;

class CsvUtilsTest extends Testcase
{
    public function providerrowToCsv(): array
    {
        return [
            ['bob,"bl\'ar%$“~+=\',ney",survivor, pot@to', ['bob', 'bl\'ar%$“~+=\',ney', 'survivor', ' pot@to']]
        ];
    }

    /**
     * @dataProvider providerrowToCSV
     * @param $expected
     * @param $row
     */
    public function testrowToCsv(string $expected, array $row)
    {
        $this->assertSame($expected, CsvUtils::rowToCsv($row));
    }

    public function providermakeHeaderRowFromRow(): array
    {
        return [
            ['bob,blarney,survivor, pot@to', ['bob', 'blarney', 'survivor', ' pot@to']]
        ];
    }

    /**
     * @dataProvider providermakeHeaderRowFromRow
     * @param $expected
     * @param $row
     */
    public function testrmakeHeaderRowFromRow(string $expected, array $row)
    {
        $this->assertSame($expected, CsvUtils::rowToCsv($row));
    }

}

