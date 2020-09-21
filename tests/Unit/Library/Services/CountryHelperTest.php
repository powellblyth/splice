<?php
/**
 * Created by PhpStorm.
 * User: toby
 * Date: 13/11/2018
 * Time: 18:14
 */

namespace Tests\Unit\Library\Services;

use Tests\TestCase;
use App\Library\Services\CountryHelper;


class CountryHelperTest extends Testcase
{
    public function providergetCountryCode()
    {
        return [
            ['GB', 'GB'],
            ['GB', 'United Kingdom'],
            ['GB', 'england'],
            ['GB', 'England'],
            ['GB', 'Wales'],
            ['GB', 'SCOTLAND'],
            ['GB', 'UK'],
            ['FR', 'France'],
            ['', 'Deutschland'],
        ];
    }

    /**
     * @dataProvider providergetCountryCode
     * @param $expected
     * @param $country
     */
    public function testgetCountryCode($expected, $country)
    {
        $this->assertSame($expected, CountryHelper::getCountryCode($country));

    }

}