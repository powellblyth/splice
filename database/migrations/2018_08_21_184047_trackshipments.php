<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Trackshipments extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(){
        Schema::create('shipments', function (Blueprint $table) {
            $table->increments('id');
            $table->string('order_line_id');
            $table->string('line_number');
            $table->string('sku');
            $table->integer('quantity')->nullable();
            $table->string('carrier')->nullable();
            $table->string('tracking_number')->nullable();
            $table->float('serial_number')->nullable();
            $table->float('batch_number')->nullable();
            $table->string('best_before_date')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::dropIfExists('shipments');
    }
}
