<?php
/**
 * Created by PhpStorm.
 * User: toby
 * Date: 08/08/2018
 * Time: 17:50
 */

namespace App\Library\Services;

use App\Exceptions\OrderNotReadyForShippingException;
use App\Models\Order;
//use App\Library\Services\CourierMapper;
use App\Models\OrderLine;
use App\Models\Product;
use App\WarehouseStockTransfer;
use Illuminate\Support\Facades\Log;

class WalkerUtils {
    public static function formatPtoFileName(int $sequence): string {
        return config('walker.pto_file_code') . str_pad($sequence, 9, '0', STR_PAD_LEFT) . '.CSV';
    }

    public static function formatReceiptAdviceFileName(int $sequence): string {
        return config('walker.receipt_advice_file_code') . str_pad($sequence, 9, '0', STR_PAD_LEFT) . '.CSV';
    }

    public static function formatProductUpdateFileName(int $sequence): string {
        return config('walker.product_update_file_name') . str_pad($sequence, 9, '0', STR_PAD_LEFT) . '.CSV';
    }

    public static function formatOrderFileName(int $sequence): string {
        return config('walker.consumer_order_file_code') . str_pad($sequence, 9, '0', STR_PAD_LEFT) . '.CSV';
    }

    public static function storeOnFtp(string $fileToUpload, string $remoteFileName) {
        try {
            $ftp = new FtpUtils(config('walker.ftp.host'), config('walker.ftp.user'), config('walker.ftp.password'));
            $remoteDir = self::getOrderFilePath();

            $ftp->putToFtp($fileToUpload, $remoteDir, $remoteFileName);
            $ftp->closeConnection();

        } catch (FTPException $e) {
            var_dump($e->getMessage());
            $errors[] = ['level' => 'terminal', $e->getMessage()];
            Log::error($e->getMessage());
        }
    }

    public static function getOrderFilePath(): string {
        return 'towalker/';
    }

    public static function rowToCsv(array $row): string {
        $rowText = '';
        $badCharacters = ['#', '?', '>', '<', '~', ':', ']', '[', '{', '}', '&', '¬', '%', '$', '“', "\n", '*', '~', '+', '=', "'", ','];
        foreach ($row as $key => $item) {

            // Walker can't handle a bunch of data
            $rowText .= str_replace($badCharacters, '', $item) . ",";
        }
        $rowTextFormatted = rtrim($rowText, ',');
        return $rowTextFormatted;
    }

    public static function getOrderDueDate(string $orderDate = null) {
        if (!is_null($orderDate)) {
            $deliveryDate = date('d/m/Y', strToTime($orderDate));
        } else {
            $deliveryDate = date('d/m/Y');
        }
        return $deliveryDate;
    }

    public static function writeMsSafeCsv(string $filepath, array $data, array $header): bool {
        echo " === " . count($data) . " output items\n";
        if ($fp = fopen($filepath, 'w')) {
            $show_header = 0 < count($header);

            if ($show_header) {
                fwrite($fp, implode(',', $header));
//                fseek($fp, -1, SEEK_CUR);
                fwrite($fp, "\r\n");
            }
            foreach ($data as $line) {
                fwrite($fp, self::rowToCsv($line));
//                fseek($fp, -1, SEEK_CUR);
                fwrite($fp, "\r\n");
            }
            fclose($fp);
        } else {
            return false;
        }
        return true;
    }

    /**
     * formats 0s as blanks
     * @param $value
     * @return string
     */
    public static function getWalkerFormattedProductDimension($value): ?string {
        $result = null;
        if (!is_null($value) && ((float)$value > 0.0 || (float)$value < 0.0)) {
            $result = ($value * 1000);
        }
        return $result;
    }

    /**
     * formats 0s as blanks
     * @param $value
     * @return string
     */
    public static function getWalkerFormattedWeight($value): ?string {
        $result = null;
        if (!is_null($value) && ((float)$value > 0.0 || (float)$value < 0.0)) {
            $result = $value;
        }
        return $result;
    }

    public static function productToCreateRowData($product) {
        return [
            'customer_code' => config('walker.customer_code'),
            'STOCKCODE' => $product->sku,
            'description_1' => substr($product->description, 0, 100),
            'description_2' => '',
            'alternate_product_code' => '',
            'vat_code' => '',
            'product_group' => '',
            'supplier_code' => '',
            'supplier_reference' => '',
            '1st_level_of_configuration' => 'PCE', // piece!
            'made_up_of_configuration' => '',
            'made_up_of_quantity' => '',
            'handling_media_type' => '',
            'value_1_wholesale' => '',
            'value_2_retail' => '',
            'height_mm' => static::getWalkerFormattedProductDimension($product->height),
            'width_mm' => static::getWalkerFormattedProductDimension($product->width),
            'depth_mm' => static::getWalkerFormattedProductDimension($product->depth),
            'weight_kg' => static::getWalkerFormattedWeight($product->weight),
            'vol_cub_m' => '',
            'quantity_per_layer' => '',
            'minimum-holding' => '',
            'article_number' => $product->barcode

        ];
    }

    public static function productToUpdateRowData(Product $product) {
        return [
            'customer_code' => config('walker.customer_code'),
            'STOCKCODE' => $product->sku,
            'description_1' => substr($product->description, 0, 100),
            'product_group' => '',
            '1st_level_of_configuration' => 'PCE', // piece!
            'article_number' => $product->barcode,


        ];
    }

    public static function warehouseStockTransferToUpdateRowsData(WarehouseStockTransfer $warehouseStockTransfer) {
        $rows = [];
        // first we do the crazy heading section
        $deliveryDate = strToTime($warehouseStockTransfer->delivery_date);
        $customer_code = $warehouseStockTransfer->transfer_number;
        $rows[] = [
            'H' => 'H',
            'customer_code' => 'CHRO' . $customer_code,
            'our_code' => 'CHROT' . $customer_code,
            'supplier' => '',
            'date_expected' => date('d/m/Y', $deliveryDate),
            'comments' => $warehouseStockTransfer->comments,

        ];
        foreach ($warehouseStockTransfer->warehouse_stock_transfer_items()->get() as $stock_transfer_item) {
            $product = $stock_transfer_item->product()->first();
            if ($product instanceof Product) {
                $rows[] = ['H' => 'D',
                    'description_1' => substr($product->sku, 0, 100),
                    'quantity' => $stock_transfer_item->quantity,
                    '1st_level_of_configuration' => 'PCE', // piece!
                    'database_line_number' => $stock_transfer_item->line_number,
                ];
            }
        }
        return $rows;
    }

    /**
     *
     * convert an order to a set of rows for walker
     * @param Order $order
     * @return array
     * @throws OrderNotReadyForShippingException
     */
    public static function orderToRowData(Order $order): array {
        $rowData = [];
        // now get the product lines
        $orderLines = OrderLine::where('order_id', $order->id)->where('type', 'product')->get();
        foreach ($orderLines as $orderLine) {

            $delivery_country = null;
            if (!empty($order->delivery_country)) {
                $delivery_country = CountryHelper::getCountryCode($order->delivery_country);
            }

            $shippingMethod = '';
            if (!empty($order->delivery_method) && !empty($order->courier)) {
                $shippingMethod = CourierMapper::getExternalShippingCode('walker', $order->courier, $order->delivery_method);
            }
            // Validate the order
            if (empty($delivery_country)) {
                throw new OrderNotReadyForShippingException($order->id, 'Order does not have a delivery country');
            }
            if (empty($shippingMethod)) {
                throw new OrderNotReadyForShippingException($order->id, 'Order does not have a shipping method');
            }

            if (0 >= (int)$orderLine->quantity) {
                throw new OrderNotReadyForShippingException($order->id, 'Order line ' . $orderLine->line_number . ' (' . $orderLine->sku . ') does not have a valid quantity');
            }

            $rowData[] = [
                'ORDERNUMBER' => $order->order_number,
                'ORDERID' => '',
                'ORDERDETAILID' => $orderLine->line_number,
                'NAME' => $order->customer_name,
                'E-MAIL' => '',
                'PHONE' => '',
                'DELIVERYLINE1' => $order->delivery_address_1,
                'DELIVERYLINE2' => $order->delivery_address_2,
                'DELIVERYLINE3' => $order->delivery_suburb,
                'DELIVERYTOWN' => $order->delivery_city,
                'DELIVERYPOSTCODE' => $order->delivery_post_code,
                'DELIVERYCOUNTRY' => $delivery_country,
                'GIFTWRAP' => '',
                'GIFTWRAPMESSAGE' => '',
                'GIFTWRAPCHARGE' => '',
                'INVNAME' => '',
                'INVADDLINE1' => '',
                'INVADDLINE2' => '',
                'INVTOWN' => '',
                'INVPOSTCODE' => '',
                'INVCOUNTRY' => '',
                'QTYORDERED' => $orderLine->quantity,
                'CUSTOMERSKUCODE' => '',
                'STOCKCODE' => $orderLine->product_code,
                'CUSTOMERSKUDESCRIPTION' => $orderLine->product_description,
                'TYPE' => '',
                'UNITPRICE' => $orderLine->unit_price,
                'TOTALPRICE' => $orderLine->total_price,
                'DELIVERYCHARGE' => '',
                'ORDERSTATUS' => $order->order_status,
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
                'DELIVERYCODE' => '',
                'COURIERNAME' => $shippingMethod,
                'TRACKINGNUMBER' => '',
                'ORDERGROSS' => $order->total,
                'ORDERVAT' => $order->tax_amount,
                'VOUCHER' => '',
                'CURRENCY' => $order->currency,
                'TOTALWEIGHT' => $order->weight,
                'TOTALPACKAGES' => 1,
                'ORDERCOMMENTS' => $order->comments,
                'DELIVERYDATE' => $order->required_date,
            ];
        }
        return $rowData;
    }

    public static function ordersToRowData($orders): array {
        $rowData = [];
        foreach ($orders as $order) {
            $rowData = array_merge($rowData, self::orderToRowData($order));
        }
        return $rowData;
    }
}