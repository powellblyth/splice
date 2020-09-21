<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Library\Services;

class CourierMapper {

    public static function getExternalShippingCode(string $toSystem, string $courier, string $internalCode) {
        return config($toSystem . '.shipping_methods.' . $courier . '.' . $internalCode);
    }

    /**
     * @param string|null $toSystem
     * @param string|null $courier
     * @return null|string
     */
    public static function getExternalCode(string $toSystem = null, string $courier = null): ?string {
        return config($toSystem . '.couriers.' . $courier);
    }

    public static function getInternalCode(string $fromSystem, string $courier): ?string {
        $result = null;
        foreach (config($fromSystem . '.couriers') as $key => $value) {
            if ($value === $courier) {
                $result = $key;
                break;
            }
        }
        return $result;
    }

    public static function translateCourier(string $fromSystem, string $toSystem, string $courier): ?string {
        $internalCode = self::getInternalCode($fromSystem, $courier);
        if (!is_null($internalCode)) {
            $result = self::getExternalCode($toSystem, $internalCode);
        } else {
            $result = null;
        }
        return $result;
    }
}

