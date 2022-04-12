<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnOnProductCustomizeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('products', function($table) {
            $table->integer('shop_id')->after('id');
        });
        Schema::table('categories', function($table) {
            $table->integer('shop_id')->after('id');
        });
        Schema::table('recommanded', function($table) {
            $table->integer('shop_id')->after('id');
        });
        Schema::table('product_customize_types', function($table) {
            $table->boolean('is_optional')->default(1);
            $table->string('control_type')->default('checkbox');
        });
        Schema::dropIfExists('shops_products');
        Schema::dropIfExists('product_variant');
        if (Schema::hasColumn('cart_items', 'product_variant_id')) {
            Schema::table('cart_items', function (Blueprint $table){
                $table->dropColumn('product_variant_id');
            });
        }
        if (Schema::hasColumn('order_item', 'product_variant_id')) {
            Schema::table('order_item', function (Blueprint $table){
                $table->dropColumn('product_variant_id');
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
