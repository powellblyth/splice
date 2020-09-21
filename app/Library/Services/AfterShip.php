<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Library\Services;

class AfterShip {

    public static function getCouriers() {
        var_dump(config('aftership.api_key'));
        $tracking = new \AfterShip\Couriers(config('aftership.api_key'));
        var_dump($tracking->get());
        die();

    }

    public static function makeTracking(string $trackingNumber, array $trackingData) {
        $tracking = new \AfterShip\Trackings(config('aftership.api_key'));

        return $tracking->create(
            $trackingNumber,
            $trackingData);

    }

    /**
     * @param string $telephoneNumber e.g. 0 78884 34 23432
     * @param string $country something about the country, ideally 2 letter iso code
     * @return string 3.g. +4478112223344
     */
    public static function normaliseMobileNumber(string $telephoneNumber, string $country): ?string {

        // If the country is already a two digit code then great
        // Else try to look it uip
        $phoneUtil = \libphonenumber\PhoneNumberUtil::getInstance();
        try {
            $swissNumberProto = $phoneUtil->parse($telephoneNumber, $country);
            return $phoneUtil->format($swissNumberProto, \libphonenumber\PhoneNumberFormat::E164);
        } catch (\libphonenumber\NumberParseException $e) {
            return null;
        }
    }
}

