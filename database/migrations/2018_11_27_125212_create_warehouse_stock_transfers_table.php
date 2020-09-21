<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWarehouseStockTransfersTable extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('warehouse_stock_transfers', function (Blueprint $table) {
            $table->increments('id');
            $table->string('transfer_number');
            $table->string('source');
            $table->string('comments')->nullable();
            $table->dateTime('delivery_date');
            $table->string('destination_warehouse')->nullable();
            $table->text('destination_warehouse_data');
            $table->string('guid');
            $table->string('created_by');
            $table->dateTime('created_on');
            $table->string('last_modified_by')->nullable();
            $table->dateTime('last_modified_on')->nullable();
            $table->dateTime('order_date');
            $table->string('source_warehouse')->nullable();
            $table->text('source_warehouse_data');
            $table->text('transfer_details');
            $table->string('transfer_order_number');
            $table->string('transfer_status');
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
        Schema::dropIfExists('warehouse_stock_transfers');
    }
}
