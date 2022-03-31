<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrderTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('user_id');
            $table->integer('shop_id');
            $table->integer('order_number');
            $table->integer('order_status');
            $table->integer('user_address_id');
            $table->string('payment_method')->comment('UPI, COD, etc.');
            $table->string('payment_gateway')->default('');
            $table->string('payment_transaction_id')->default('');
            $table->string('order_type')->comment('Home delivery, office, etc.');
            $table->string('order_total');
            $table->boolean('is_ongoing_order')->default(1);
            $table->softDeletes();
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
        Schema::dropIfExists('order');
    }
}
