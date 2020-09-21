<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->increments('id');
            $table->string('order_number');
            $table->string('status')->default('new');
            $table->string('warehouse');
            $table->string('source_status');
            $table->dateTime('order_date')->nullable();
            $table->dateTime('required_date')->nullable();
            $table->string('customer_name');
            $table->string('customer_id');
            $table->string('source');
            $table->string('guid');
            $table->string('delivery_address_1')->nullable();
            $table->string('delivery_address_2')->nullable();
            $table->string('delivery_suburb')->nullable();
            $table->string('delivery_city')->nullable();
            $table->string('delivery_post_code')->nullable();
            $table->string('delivery_country')->nullable();
            $table->string('delivery_method')->nullable();
            $table->float('tax_amount')->default(0.0);
            $table->float('sub_total')->default(0.0);
            $table->float('total')->default(0.0);
            $table->string('weight')->nullable();
            $table->string('currency')->nullable();
            $table->text('comments')->nullable();
            $table->text('raw')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('orders');
    }
}
