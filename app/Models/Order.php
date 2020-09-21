<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Library\Services\Unleashed;
use App\Library\Services\CountryHelper;
use App\Library\Services\CourierMapper;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @method static where(string $string, $orderNumber)
 */
class Order extends Model
{
    protected static $unleashed = null;

    public function populateFromUnleashed(\stdClass $order)
    {
        if (!isset(static::$unleashed)) {
            static::$unleashed = new Unleashed();
        }
        if (!is_null($order->DeliveryCountry)) {
            $delivery_country = CountryHelper::getCountryCode($order->DeliveryCountry);
        } else {

            $delivery_country = CountryHelper::getCountryCode('United Kingdom');
        }

        // Don't reset the status on update
        // This data shouldn't ever change
        if (!$this->exists) {
            $this->status       = 'new';
            $this->order_number = $order->OrderNumber;
        }
        // Static variable allows caching of thie data
        $customer                        = static::$unleashed->getCustomer($order->Customer->Guid);
        $this->customer_name             = $order->Customer->CustomerName;
        $this->customer_email            = $customer->Email;
        $this->customer_id               = $order->Customer->CustomerCode;
        $this->customer_guid             = $order->Customer->Guid;
        $this->customer_telephone        = $customer->PhoneNumber;
        $this->customer_mobile_telephone = $customer->MobileNumber;
        $this->source                    = 'Unleashed';
        $this->url                       = 'https://au.unleashedsoftware.com/v2/SalesOrder/List#orderNumber=' . $order->OrderNumber;
        $this->guid                      = $order->Guid;
        $this->order_date                = gmdate('Y-m-d H:i:s', Unleashed::getTimestampFromUnleashedDate($order->OrderDate));
        if (!is_null($order->RequiredDate)) {
            $this->required_date = gmdate('Y-m-d H:i:s', Unleashed::getTimestampFromUnleashedDate($order->RequiredDate));
        }
        $this->delivery_name      = ((is_null($order->DeliveryName)) ? $order->Customer->CustomerName : $order->DeliveryName);
        $this->delivery_address_1 = $order->DeliveryStreetAddress;
        $this->delivery_address_2 = $order->DeliveryStreetAddress2;
        $this->delivery_suburb    = $order->DeliverySuburb;
        $this->delivery_city      = $order->DeliveryCity;
        $this->delivery_post_code = $order->DeliveryPostCode;
        // We use the derived country code, or store the junk if it was junk
        $this->delivery_country = ((empty($delivery_country)) ? $order->DeliveryCountry : $delivery_country);
        $this->delivery_method  = $order->DeliveryMethod;
        $this->weight           = $order->TotalWeight;
        $this->comments         = $order->Comments;
        $this->currency         = $order->Currency->CurrencyCode;
        $this->total            = $order->Total;
        $this->sub_total        = $order->SubTotal;
        $this->tax_amount       = $order->TaxTotal;
        $this->source_status    = $order->OrderStatus;
        $this->warehouse        = $order->Warehouse->WarehouseCode;

//        $shippingData = ['courier' => null,]

//        $this->is_test = true;

        $this->raw = json_encode($order);

        if ($this->save()) {
            $courierName    = null;
            $deliveryMethod = null;
            if (is_array($order->SalesOrderLines)) {
                foreach ($order->SalesOrderLines as $orderLine) {
                    $dbOrderLine = OrderLine::where('order_id', $this->id)->where('line_number', $orderLine->LineNumber)->first();
                    if (!$dbOrderLine instanceof OrderLine) {
                        $dbOrderLine              = new OrderLine();
                        $dbOrderLine->line_number = $orderLine->LineNumber;
                        $dbOrderLine->order_id    = $this->id;
                    }
                    $dbOrderLine->quantity            = $orderLine->OrderQuantity;
                    $dbOrderLine->unit_price          = $orderLine->UnitPrice;
                    $dbOrderLine->total_price         = $orderLine->LineTotal;
                    $dbOrderLine->source_status       = '';
                    $dbOrderLine->product_code        = $orderLine->Product->ProductCode;
                    $dbOrderLine->product_description = $orderLine->Product->ProductDescription;
                    if ('charge' == strtolower($orderLine->LineType)) {
                        $dbOrderLine->type = 'charge';
                        // Attempt to pick up shipping info
                        $courierName = Unleashed::getCourierIdFromProductDescription($orderLine->Product->ProductDescription);
                        // Overwrite the courier name if needed
                        if (!is_null($courierName)) {
                            $this->courier = $courierName;
                        }
                        $deliveryMethod = Unleashed::getDeliveryMethodIdFromProductDescription($orderLine->Product->ProductDescription);
                        if (!is_null($deliveryMethod)) {
                            $this->delivery_method = $deliveryMethod;
                        }
                    } else {
                        $dbOrderLine->type = 'product';
                    }
                    $dbOrderLine->save();

                }
                // If we have updated the delivery method or the courier, then re-save this object
                if ($this->isDirty(['courier', 'delivery_method'])) {
                    $this->save();
                }

            }
        }
    }

    public function shipments(): HasMany
    {
        return $this->hasMany(Shipment::class);
    }

    public function order_lines(): HasMany
    {
        return $this->hasMany(OrderLine::class);
    }
}
