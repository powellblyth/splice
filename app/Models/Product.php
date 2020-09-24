<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Library\Services\Unleashed;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Facades\Log;

/**
 * @method static where(string $string, $WarehouseProductCode)
 */
class Product extends Model
{
    protected static $unleashed = null;

    public function populateFromUnleashed(\stdClass $product)
    {
        if (!isset(static::$unleashed)) {
            static::$unleashed = new Unleashed();
        }
        var_dump($product);
        echo "-------------------------\n\n";;
        // Pull the warehouse barcode
        if (isset($product->AttributeSet)
            && $product->AttributeSet->Guid === '07b8ff2d-abbe-4537-b1ce-2803eb77d3da'
            && isset($product->AttributeSet->Attributes)) {
            $this->product_attributes = json_encode($product->AttributeSet);
            vaR_dump($product->AttributeSet->Attributes);
            foreach ($product->AttributeSet->Attributes as $object) {
                var_dump($object);
                if ($object->Guid === 'e6dcf85f-ca83-465b-b88f-46888ab61331') {
                    $this->warehouse_barcode = $object->Value;
                    break;
                }

            }
        }
        $this->source                     = 'unleashed';
        $this->status                     = 'new';
        $this->sku                        = $product->ProductCode;
        $this->sellable                   = $product->IsSellable;
        $this->description                = $product->ProductDescription;
        $this->barcode                    = $product->Barcode;
        $this->pack_size                  = $product->PackSize;
        $this->width                      = $product->Width;
        $this->height                     = $product->Height;
        $this->depth                      = $product->Depth;
        $this->weight                     = $product->Weight;
        $this->min_stock_alert_level      = $product->MinStockAlertLevel;
        $this->max_stock_alert_level      = $product->MaxStockAlertLevel;
        $this->re_order_point             = $product->ReOrderPoint;
        $this->unit_of_measure            = ((is_object($product->UnitOfMeasure)) ? $product->UnitOfMeasure->Name : null);
        $this->never_diminishing          = $product->NeverDiminishing;
        $this->last_cost                  = $product->LastCost;
        $this->default_purchase_price     = $product->DefaultPurchasePrice;
        $this->default_sell_price         = $product->DefaultSellPrice;
        $this->average_land_price         = $product->AverageLandPrice;
        $this->obsolete                   = $product->Obsolete;
        $this->notes                      = $product->Notes;
        $this->sell_price_tier_1          = $product->SellPriceTier1->Value;
        $this->sell_price_tier_2          = $product->SellPriceTier2->Value;
        $this->sell_price_tier_3          = $product->SellPriceTier3->Value;
        $this->sell_price_tier_4          = $product->SellPriceTier4->Value;
        $this->sell_price_tier_5          = $product->SellPriceTier5->Value;
        $this->sell_price_tier_6          = $product->SellPriceTier6->Value;
        $this->sell_price_tier_7          = $product->SellPriceTier7->Value;
        $this->sell_price_tier_8          = $product->SellPriceTier8->Value;
        $this->sell_price_tier_9          = $product->SellPriceTier9->Value;
        $this->sell_price_tier_10         = $product->SellPriceTier10->Value;
        $this->xero_tax_code              = $product->XeroTaxCode;
        $this->xero_tax_rate              = $product->XeroTaxRate;
        $this->taxable_purchase           = $product->TaxablePurchase;
        $this->taxable_sales              = $product->TaxableSales;
        $this->xero_sales_tax_code        = $product->XeroSalesTaxCode;
        $this->xero_sales_tax_rate        = $product->XeroSalesTaxRate;
        $this->is_component               = $product->IsComponent;
        $this->is_assembled_product       = $product->IsAssembledProduct;
        $this->xero_sales_account         = $product->XeroSalesAccount;
        $this->xero_cost_of_goods_account = $product->XeroCostOfGoodsAccount;
        $this->bin_location               = $product->BinLocation;
        $this->supplier                   = '';
        $this->source_id                  = $product->SourceId;
        $this->remote_created_by          = $product->CreatedBy;
        $this->source_variant_parent_id   = $product->SourceVariantParentId;
        $this->guid                       = $product->Guid;
        $this->remote_last_modified_on    = gmdate('Y-m-d H:i:s', Unleashed::getTimestampFromUnleashedDate($product->LastModifiedOn));
        $this->raw                        = json_encode($product);

        if (isset($product->ProductGroup)) {
            $correctProductGroup = ProductGroup::where('guid', $product->ProductGroup->Guid)->first();
            if ($correctProductGroup instanceof ProductGroup) {
                try {
                    $correctProductGroup->products()->save($this);
                } catch (\BadMethodCallException $e) {
                    Log::error($e->getMessage());
//                    var_dump($e->getMessage());
//                    die();
                }
            }
        }

        return $this->save();
    }

    public function product_group(): BelongsTo
    {
        return $this->belongsTo(ProductGroup::class);
    }

    public function warehouses(): BelongsToMany
    {
        return $this->belongsToMany(Warehouse::class);
    }
}
