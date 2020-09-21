<?php
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
return [
    'ftp' => [
        'host' => env('WALKER_FTP_SERVER'),
        'user' => env('WALKER_FTP_USER'),
        'password' => env('WALKER_FTP_PASSWORD')
    ],
    'customer_code' => 'CHRO',
    'consumer_order_file_code' => 'CHRCONS',
    'pto_file_code' => 'PTOCHRO',
    'product_update_file_name' => 'POVCHRO',
    'trade_order_file_code' => 'CHRTRAD ',
    'receipt_advice_file_code' => 'CHRORECADV',

    'couriers' => [
        'dhl' => 'dhl',
        'royalmail' => 'RoyalMail',
        'ups' => 'ups',
        'dpd' => 'DPD',
        'free' => 'RoyalMail',
        'standard' => 'Chateau Rouge Shipping'],
    'shipping_methods' => [
        'standard' =>
            [
                "freeshipping" => 'RMTRK2',
                "standardshipping" => 'RMTRK2',
                'nexdayshipping' => 'DPDND',
                'shippinginternational' => 'RMAIRSURE',
                'courier' => 'DHLSTD',
            ],
        'dhl' =>
            [
                'dhlexpress' => 'DHLEXP',
                'dhlstandard' => 'DHLST'
            ],
        'royalmail' =>
            [
                'royalmail1st' => 'RMTRK2',
                'royalmail2nd' => 'Royal Mail 2nd Class',
                'royalmailinternational' => 'RMAIRMAIL'
            ],
        'dpd' =>
            ['dpd' => 'DPDND'],
        'free' =>
            [
                'free' => 'RMTRK2',
                'freeeu' => 'RMAIRMAIL'
            ]
    ]

];

