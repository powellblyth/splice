<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrderLines extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('order_lines', function (Blueprint $table) {
            $table->increments('id');
            $table->string('order_id');
            $table->string('type')->default('product');
            $table->string('line_number');
            $table->integer('quantity')->nullable();
            $table->string('product_description')->nullable();
            $table->string('product_code')->nullable();
            $table->float('total_price')->default(0.0);
            $table->float('unit_price')->default(0.0);
            $table->string('source_status')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::dropIfExists('order_lines');
    }
}
