<?php
/**
 * Created by PhpStorm.
 * User: toby
 * Date: 2018-11-29
 * Time: 19:12
 */

namespace Tests\Unit\App\Models;


use App\Models\Order;
use Mockery\Mock;
use Tests\ModelTestBase;

//use Tests\Unit\ModelTestBase;
//use phpmock\MockBuilder;

class OrderTest extends ModelTestBase
{
    public function providerFormattedAddress()
    {
        return [
            ['', '', '', '', '', '', ''],
            ['1 bishop, fish, toastville, breakfasttone, BN1 1NB, FR', '1 bishop', 'fish', 'toastville', 'breakfasttone', 'BN1 1NB', 'FR'],
            ['1 bishop, fish, breakfasttone, BN1 1NB, GB', '1 bishop', 'fish', '', 'breakfasttone', 'BN1 1NB', 'GB'],
            ['1 bishop, toast-SEPARATOR-fish-SEPARATOR-breakfasttone-SEPARATOR-BN1 1NB-SEPARATOR-GB', '1 bishop, toast', 'fish', '', 'breakfasttone', 'BN1 1NB', 'GB', '-SEPARATOR-'],
        ];
    }

    /**
     * @param string $expected
     * @param string $delivery_address_1
     * @param string $delivery_address_2
     * @param string $delivery_suburb
     * @param string $delivery_city
     * @param string $delivery_post_code
     * @param string $delivery_country
     * @param string $separator
     * @group orders
     * @dataProvider providerFormattedAddress
     */
    public function testFormattedAddress(string $expected, string $delivery_address_1, string $delivery_address_2, string $delivery_suburb, string $delivery_city, string $delivery_post_code, string $delivery_country, string $separator = ', ')
    {
        /**
         * @var Order $order
         */
        $order = Order::factory(['delivery_address_1' => $delivery_address_1,
                                 'delivery_address_2' => $delivery_address_2,
                                 'delivery_suburb'    => $delivery_suburb,
                                 'delivery_city'      => $delivery_city,
                                 'delivery_post_code' => $delivery_post_code,
                                 'delivery_country'   => $delivery_country])->make();
        $this->assertSame($expected, $order->formattedAddress($separator));
    }


}
