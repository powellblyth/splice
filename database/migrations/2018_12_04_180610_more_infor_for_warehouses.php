<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class MoreInforForWarehouses extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::table('warehouses', function (Blueprint $table) {
            $table->string('guid')->nullable();
            $table->string('code')->nullable();
            $table->string('name')->nullable();
            $table->string('source')->nullable();
            $table->string('street_number')->nullable();
            $table->string('address_line_1')->nullable();
            $table->string('address_line_2')->nullable();
            $table->string('city')->nullable();
            $table->string('contact_name')->nullable();
            $table->string('country')->nullable();
            $table->string('post_code')->nullable();
            $table->string('ddi')->nullable();
            $table->string('fax_number')->nullable();
            $table->string('mobile_number')->nullable();
            $table->string('telephone_number')->nullable();
            $table->boolean('is_default')->default(false);
            $table->dateTime('remote_last_modified_on')->nullable();
            $table->boolean('obsolete')->default(false);
            $table->string('region')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::table('warehouses', function (Blueprint $table) {
            $table->dropColumn(['guid', 'code', 'name', 'source', 'street_number', 'address_line_1', 'address_line_2', 'city',
                'contact_name', 'country', 'post_code', 'ddi', 'fax_number', 'mobile_number',
                'telephone_number', 'is_default', 'remote_last_modified_on', 'obsolete', 'region']);
        });
    }
}
