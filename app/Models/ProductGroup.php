<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Library\Services\Unleashed;

class ProductGroup extends Model {
    public function populateFromUnleashed(\stdClass $productGroup) {
        $this->source = 'unleashed';
        $this->group_name = $productGroup->GroupName;
        $this->guid = $productGroup->Guid;
        $this->remote_last_modified = gmdate('Y-m-d H:i:s', Unleashed::getTimestampFromUnleashedDate($productGroup->LastModifiedOn));;
        return $this->save();
    }

    public function products(): \Illuminate\Database\Eloquent\Relations\HasMany {
        return $this->hasMany(Product::class);
    }
}
