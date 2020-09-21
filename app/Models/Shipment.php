<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @method static where(string $string, $Carrier)
 */
class Shipment extends Model {

    public function createFromWalker(OrderLine $orderLine, array $data) {
        $this->order_line_id = $orderLine->id;
        $this->line_number = $data['DatabaseLineNumber'];
        $this->sku = $data['SKU'];
        $this->quantity = $data['Quantity'];
        $this->carrier = $data['Carrier'];
        $this->tracking_number = $data['TrackingNumber'];
        $this->serial_number = $data['SerialNumber'];
        $this->batch_number = $data['BatchNumber'];
        $this->best_before_date = $data['BestBeforeDate'];
        $this->customer_name = $orderLine->order->delivery_name;
        $this->smses = $orderLine->order->customer_mobile_telephone;
        $this->emails = $orderLine->order->customer_email;
        $this->origin_country = 'GB';
        $this->postal_code = $orderLine->order->delivery_post_code;
        $this->destination_country = $orderLine->order->delivery_country;
        $this->order_id = $orderLine->order->id;
        $this->warehouse = 'walker';

        if ($this->save()) {
            $this->order_lines()->attach($orderLine);
            return true;
        } else {
            return false;
        }

    }


    public function order(): \Illuminate\Database\Eloquent\Relations\BelongsTo {
        return $this->belongsTo('App\Order');
    }

    public function order_lines(): \Illuminate\Database\Eloquent\Relations\BelongsToMany {
        return $this->belongsToMany('App\OrderLine');
    }

}
