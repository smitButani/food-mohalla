<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DropColumnRecommendedId extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasColumn('order_item', 'recommanded_id')) {
            Schema::table('order_item', function (Blueprint $table){
              $table->dropColumn('recommanded_id');
           });
       }
       if (Schema::hasColumn('cart_items', 'recommanded_id')) {
            Schema::table('cart_items', function (Blueprint $table){
             $table->dropColumn('recommanded_id');
            });
        }
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
