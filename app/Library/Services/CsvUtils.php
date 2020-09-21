<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Library\Services;

use App\Exceptions\FileNotOpenableException;
use App\Models\Order;
use App\Models\OrderLine;

class CsvUtils {

    public static function makeWalkerRowDataFromOrder(Order $order, OrderLine $orderLine) {
        $result = [
            'ORDERNUMBER' => $order->OrderNumber,
            'ORDERID' => '',
            'ORDERDETAILID' => $orderLine->LineNumber,
            'NAME' => $order->Customer->CustomerName,
            'E-MAIL' => '',
            'PHONE' => '',
            'DELIVERYLINE1' => $order->DeliveryStreetAddress,
            'DELIVERYLINE2' => $order->DeliveryStreetAddress2,
            'DELIVERYLINE3' => $order->DeliverySuburb,
            'DELIVERYTOWN' => $order->DeliveryCity,
            'DELIVERYPOSTCODE' => $order->DeliveryPostCode,
            'DELIVERYCOUNTRY' => $order->DeliveryCountry,
            'GIFTWRAP' => '',
            'GIFTWRAPMESSAGE' => '',
            'GIFTWRAPCHARGE' => '',
            'INVNAME' => '',
            'INVADDLINE1' => '',
            'INVADDLINE2' => '',
            'INVTOWN' => '',
            'INVPOSTCODE' => '',
            'INVCOUNTRY' => '',
            'QTYORDERED' => $orderLine->OrderQuantity,
            'CUSTOMERSKUCODE' => '',
            'STOCKCODE' => $orderLine->Product->ProductCode,
            'CUSTOMERSKUDESCRIPTION' => $orderLine->Product->ProductDescription,
            'TYPE' => '',
            'UNITPRICE' => $orderLine->UnitPrice,
            'TOTALPRICE' => $orderLine->LineTotal,
            'DELIVERYCHARGE' => '',
            'ORDERSTATUS' => $orderLine->OrderStatus,
            'RETURNTOSTOCK' => '',
            'REPLACEMENTREQUIRED' => '',
            'REPLACEMENTSTOCKCODE' => '',
            'QTYRETURNED' => '',
            'ALTDELADDNAME' => '',
            'ALTDELADDRESS1' => '',
            'ALTDELADDRESS2' => '',
            'ALTDELTOWN' => '',
            'ALTDELCOUNTY' => '',
            'ALTDELPOSTCODE' => '',
            'ALTDELCOUNTRY' => '',
            'ALTDELPHONE' => '',
            'DELIVERYCODE' => $order->DeliveryMethod,
            'COURIERNAME' => '',
            'TRACKINGNUMBER' => '',
            'ORDERGROSS' => $order->TaxTotal,
            'ORDERVAT' => $order->TaxTotal,
            'VOUCHER' => '',
            'CURRENCY' => $order->Currency->CurrencyCode,
            'TOTALWEIGHT' => $order->TotalWeight,
            'TOTALPACKAGES' => '',
            'ORDERCOMMENTS' => $order->Comments,
            'DELIVERYDATE' => date('Y-m-d', $order->required_date)];
        return $result;
    }

    public static function makeHeaderRowFromRow(array $rowData): string {
        $headers = implode(array_keys($rowData), ",");
        return $headers;
    }

    public static function rowToCsv(array $row): string {
        $rowText = '';
        foreach ($row as $key => $item) {

            if (false === strpos($item, ",")) {
                $delimeter = '';
            } else {
                $delimeter = '"';
            }

            $rowText .= $delimeter . $item . $delimeter . ",";
        }

        $rowTextFormatted = rtrim($rowText, ',');
        return $rowTextFormatted;
    }
}

