<?php
/**
 * Created by PhpStorm.
 * User: toby
 * Date: 10/09/2018
 * Time: 13:45
 */

return [
    'enabled' => env('AFTERSHIP_ENABLED'),
    'api_key' => env('AFTERSHIP_API_KEY'),
    'couriers' => [
        'dhl' => 'dhl',
        'royalmail' => 'royal-mail',
        'usps' => 'usps',
        'ups' => 'ups',
        'fedex' => 'fedex']

];

