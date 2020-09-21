<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddExportedDateToStockTransfers extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::table('warehouse_stock_transfers', function (Blueprint $table) {
            $table->datetime('sent_to_destination_warehouse')->nullable();
            $table->integer('destination_warehouse_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn(['sent_to_destination_warehouse']);
            $table->dropColumn(['destination_warehouse_id']);
        });
    }
}
