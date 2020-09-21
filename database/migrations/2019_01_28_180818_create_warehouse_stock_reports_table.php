<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWarehouseStockReportsTable extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('warehouse_stock_reports', function (Blueprint $table) {
            $table->increments('id');
            $table->string('product_code');
            $table->integer('product_id');
            $table->string('product_description');
            $table->integer('current_stock')->default(0);
            $table->integer('free_stock')->default(0);
            $table->string('status');
            $table->string('source_warehouse');
            $table->integer('source_warehouse_id');
            $table->string('destination');
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
        Schema::dropIfExists('warehouse_stock_reports');
    }
}