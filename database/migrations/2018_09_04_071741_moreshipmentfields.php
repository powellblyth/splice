<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Moreshipmentfields extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::table('shipments', function (Blueprint $table) {
            $table->string('customer_name')->nullable();
            $table->string('smses')->nullable();
            $table->string('emails')->nullable();
            $table->string('origin_country')->nullable();
            $table->string('destination_country')->nullable();
            $table->string('status')->default('new');
            $table->string('postal_code')->nullable();
        });
        }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::table('shipments', function (Blueprint $table) {
            $table->dropColumn(['customer_name']);
            $table->dropColumn(['smses']);
            $table->dropColumn(['emails']);
            $table->dropColumn(['destination_country']);
            $table->dropColumn(['origin_country']);
            $table->dropColumn(['status']);
            $table->dropColumn(['postal_Code']);
        });
    }
}
