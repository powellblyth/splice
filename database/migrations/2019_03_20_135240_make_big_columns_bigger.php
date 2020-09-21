<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class MakeBigColumnsBigger extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::table('warehouse_stock_transfers', function (Blueprint $table) {
            $table->longtext('transfer_details')->change();
            $table->longtext('raw')->change();
        });
    }

        /**
         * Reverse the migrations.
         *
         * @return void
         */
        public function down() {
    }
    }