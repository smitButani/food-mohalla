<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnOnOrderTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('order', function($table) {
            $table->string('delivery_distance');
            $table->float('delivery_charges', 8, 2)->default(0);
            $table->float('gst_charges', 8, 2)->default(0);
            $table->float('discount_amount', 8, 2)->default(0);
            $table->string('promo_code')->nullable();
            $table->float('item_total', 8, 2)->nullable();
            $table->integer('delivery_boy_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
