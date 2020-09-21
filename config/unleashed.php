<?php
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

return [
    'api_url' => env('UNLEASHED_API_URL'),
    'api_id' => env('UNLEASHED_API_ID'),
    'api_key' => env('UNLEASHED_API_KEY'),

    'warehouses' => ['WALKER', 'MAIN'],
    'couriers' => [
        'dhl' => 'DHL',
        'royalmail' => 'Royal Mail',
        'ups' => 'UPS',
        'dpd' => 'DPD',
        'standard' => 'Chateau Rouge Shipping'
    ],
    'shipping_methods' => [
        'standard' =>
            [
                "freeshipping" => 'FREE Delivery',
                // NOTE we remove all brackets because trim and ltrim suck like sucky lemons
                "standardshipping" => 'Standard Delivery 2-3 Days',
                'nexdayshipping' => 'Next Day Delivery',
                'shippinginternational' => 'International Delivery',
                'courier' => 'DHL'
            ],
        'dhl' =>
            [
                'dhlexpress' => 'DHL Express',
                'dhlstandard' => 'DHL Standard'
            ],
        'royalmail' =>
            [
                'royalmail1st' => 'Royal Mail 1st Class',
                'royalmail2nd' => 'Royal Mail 2nd Class',
                'royalmailinternational' => 'Royal Mail International'
            ],
        'dpd' =>
            ['dpd' => 'dpd'],
        'free' =>
            [
                'free' => 'free',
                'freeeu' => 'Free (EU)'
            ]
    ]

];

//Standard(Royal Mail 48 Hours)				RMTRK2
//Airmail(Royal Mail International)				RMAIRMAIL(Andrew can confirm correct for International / EU airmail)
//    Next Day Delivery(DPD)					DPDND
//DHL(Courier) ? (Andrew can confirm code and do we need one DHL code for UK and another for International ?)
//FREE									RMTRK2
//FREE(EU)