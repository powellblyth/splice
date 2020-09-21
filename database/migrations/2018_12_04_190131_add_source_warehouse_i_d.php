<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSourceWarehouseID extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::table('warehouse_stock_transfers', function (Blueprint $table) {
            $table->integer('source_warehouse_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::table('warehouse_stock_transfers', function (Blueprint $table) {
            $table->dropColumn(['source_warehouse_id']);
        });
    }
}
