<?php

namespace App\Models;

use App\Library\Services\Unleashed;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Warehouse extends Model
{

    public function populateFromUnleashed(\stdClass $warehouse): bool
    {

        $this->source = 'unleashed';
        $this->guid   = $warehouse->Guid;
        $this->code   = $warehouse->WarehouseCode;
        $this->name   = $warehouse->WarehouseName;
        // We never update the slug, only fill it first time
        if (!$this->exists) {
            $this->slug = strtolower(str_replace([" ", "&"], "-", $warehouse->WarehouseCode));
        }
        $this->street_number           = $warehouse->StreetNo;
        $this->address_line_1          = $warehouse->AddressLine1;
        $this->address_line_2          = $warehouse->AddressLine2;
        $this->city                    = $warehouse->City;
        $this->contact_name            = $warehouse->ContactName;
        $this->country                 = $warehouse->Country;
        $this->post_code               = $warehouse->PostCode;
        $this->ddi                     = $warehouse->DDINumber;
        $this->fax_number              = $warehouse->FaxNumber;
        $this->mobile_number           = $warehouse->MobileNumber;
        $this->telephone_number        = $warehouse->PhoneNumber;
        $this->is_default              = $warehouse->IsDefault;
        $this->remote_last_modified_on = gmdate('Y-m-d H:i:s', Unleashed::getTimestampFromUnleashedDate($warehouse->LastModifiedOn));
        $this->obsolete                = $warehouse->Obsolete;
        $this->region                  = $warehouse->Region;
        return $this->save();
    }
    //
    // The list of mapped products - so we know if we need to update or edit
    // NOTE that this simply menas that a product has ever been attached
    // It is not the same as saying a product is _currently_ attached to a
    public function created_products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class);
    }

    // warehouse

}
