<?php

use Illuminate\Database\Seeder;
use App\Models\ProductVariant;
use App\Models\Products;

class VariantsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $products = Products::get();
        foreach($products as $product){
            ProductVariant::create([
                'product_id' => $product->id,
                'variant_name' => 'small',
                'description' => 'size small',
                'price' => $product->price,
                'is_defualt' => 1,
            ]);
            ProductVariant::create([
                'product_id' => $product->id,
                'variant_name' => 'medium',
                'description' => 'size medium',
                'price' => $product->price+40,
                'is_defualt' => 0,
            ]);
            ProductVariant::create([
                'product_id' => $product->id,
                'variant_name' => 'large',
                'description' => 'size Large',
                'price' => $product->price+80,
                'is_defualt' => 0,
            ]);
        }
    }
}
