<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderLine extends Model {
    //
    public function isShipping(): bool {
        return ('shipping' == strtolower($this->type));
    }

    public function order(): \Illuminate\Database\Eloquent\Relations\BelongsTo {
        return $this->belongsTo(Order::class, 'order_id');
    }

    public function shipments(): \Illuminate\Database\Eloquent\Relations\BelongsToMany {
        return $this->belongsToMany(Shipment::class);
    }
}
