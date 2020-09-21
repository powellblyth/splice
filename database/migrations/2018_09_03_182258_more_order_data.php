<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class MoreOrderData extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::table('orders', function (Blueprint $table) {
            $table->string('delivery_name')->nullable();
            $table->string('customer_telephone')->nullable();
            $table->string('customer_email')->nullable();
            $table->string('customer_guid')->nullable();
            $table->string('customer_mobile_telephone')->nullable();
        }); //
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['delivery_name']);
            $table->dropColumn(['customer_telephone']);
            $table->dropColumn(['customer_email']);
            $table->dropColumn(['customer_guid']);
            $table->dropColumn(['customer_mobile_telephone']);
        });
    }
}