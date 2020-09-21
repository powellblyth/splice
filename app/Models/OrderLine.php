<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * @method static where(string $string, $id)
 */
class OrderLine extends Model {
    //
    public function isShipping(): bool {
        return ('shipping' == strtolower($this->type));
    }

    public function order(): BelongsTo {
        return $this->belongsTo(Order::class);
    }

    public function shipments(): BelongsToMany {
        return $this->belongsToMany(Shipment::class);
    }
}
