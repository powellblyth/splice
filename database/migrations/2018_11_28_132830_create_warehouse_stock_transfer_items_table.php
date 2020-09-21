<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWarehouseStockTransferItemsTable extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('warehouse_stock_transfer_items', function (Blueprint $table) {
            $table->increments('id');
            $table->string('source');
            $table->dateTime('receipt_fifo_date');
            $table->integer('remote_stock_transfer_id');
            $table->integer('transfer_item_remote_id');
            $table->integer('remote_source_warehouse_id');
            $table->integer('remote_destination_warehouse_id');
            $table->integer('remote_source_product_id');
            $table->string('batch_number')->nullable();
            $table->dateTime('expiry_date')->nullable();
            $table->integer('line_number');
            $table->string('product_sku');
            $table->string('product_id')->nullable();
            $table->integer('quantity');
            $table->integer('destination_warehouse_stock_level_before')->nullable();
            $table->text('comments')->nullable();
            $table->integer('warehouse_stock_transfer_id')->nullable();
            $table->text('raw');
            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::dropIfExists('warehouse_stock_transfer_items');
    }
}
