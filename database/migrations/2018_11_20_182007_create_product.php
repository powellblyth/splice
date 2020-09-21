<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProduct extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->increments('id');
            $table->string('source');
            $table->string('status')->default('new');
            $table->string('sku');
            $table->string('description');
            $table->string('guid');
            $table->string('barcode')->nullable();
            $table->float('pack_size')->nullable();
            $table->float('width')->nullable();
            $table->float('height')->nullable();
            $table->float('depth')->nullable();
            $table->float('weight')->nullable();
            $table->integer('min_stock_alert_level')->nullable();
            $table->integer('max_stock_alert_level')->nullable();
            $table->float('re_order_point')->nullable();
            $table->string('unit_of_measure')->nullable();
            $table->boolean('never_diminishing')->default(false);
            $table->float('last_cost')->nullable();
            $table->string('default_purchase_price')->nullable();
            $table->float('default_sell_price')->nullable();
            $table->float('average_land_price')->nullable();
            $table->boolean('obsolete')->default(0);
            $table->text('notes')->nullable();
            $table->float('sell_price_tier_1')->nullable();
            $table->float('sell_price_tier_2')->nullable();
            $table->float('sell_price_tier_3')->nullable();
            $table->float('sell_price_tier_4')->nullable();
            $table->float('sell_price_tier_5')->nullable();
            $table->float('sell_price_tier_6')->nullable();
            $table->float('sell_price_tier_7')->nullable();
            $table->float('sell_price_tier_8')->nullable();
            $table->float('sell_price_tier_9')->nullable();
            $table->float('sell_price_tier_10')->nullable();
            $table->string('xero_tax_code')->nullable();
            $table->float('xero_tax_rate')->nullable();
            $table->boolean('taxable_purchase')->nullable();
            $table->boolean('taxable_sales')->nullable();
            $table->string('xero_sales_tax_code')->nullable();
            $table->float('xero_sales_tax_rate')->nullable();
            $table->boolean('is_component')->default(false);
            $table->boolean('is_assembled_product')->default(false);
            $table->string('xero_sales_account')->nullable();
            $table->string('xero_cost_of_goods_account')->nullable();
            $table->string('bin_location')->nullable();
            $table->string('supplier')->nullable();
            $table->string('source_id')->nullable();
            $table->string('remote_created_by');
            $table->string('source_variant_parent_id')->nullable();
            $table->string('last_modified_on')->nullable();
            $table->text('raw');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('products');
    }
}
