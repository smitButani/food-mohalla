<?php

use Illuminate\Database\Seeder;
use App\Models\ProductCustomizeType;
use App\Models\ProductCustomizeOption;
use App\Models\Products;

class CustomizeSeeder extends Seeder
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
            $customizeType = ProductCustomizeType::create([
                'product_id' => $product->id,
                'type_name' => 'milk',
            ]);

            ProductCustomizeOption::create([
                'product_id' => $product->id,
                'customize_type_id' => $customizeType->id,
                'option_name' => 'Steamed Milk',
                'customize_charges' => 20,
                'description' => 'extra Steamed Milk'
            ]);
            ProductCustomizeOption::create([
                'product_id' => $product->id,
                'customize_type_id' => $customizeType->id,
                'option_name' => 'hot Milk',
                'customize_charges' => 20,
                'description' => 'extra hot Milk'
            ]);
            ProductCustomizeOption::create([
                'product_id' => $product->id,
                'customize_type_id' => $customizeType->id,
                'option_name' => 'cold Milk',
                'customize_charges' => 20,
                'description' => 'extra cold Milk'
            ]);
           
            // customize 2
            $customizeType_2 = ProductCustomizeType::create([
                'product_id' => $product->id,
                'type_name' => 'Veggies',
            ]);

            ProductCustomizeOption::create([
                'product_id' => $product->id,
                'customize_type_id' => $customizeType_2->id,
                'option_name' => 'Onion',
                'customize_charges' => 20,
                'description' => 'extra Onion'
            ]);
            ProductCustomizeOption::create([
                'product_id' => $product->id,
                'customize_type_id' => $customizeType_2->id,
                'option_name' => 'tomato',
                'customize_charges' => 20,
                'description' => 'extra tomato'
            ]);
            ProductCustomizeOption::create([
                'product_id' => $product->id,
                'customize_type_id' => $customizeType_2->id,
                'option_name' => 'cabbage',
                'customize_charges' => 20,
                'description' => 'extra cabbage'
            ]);
            ProductCustomizeOption::create([
                'product_id' => $product->id,
                'customize_type_id' => $customizeType_2->id,
                'option_name' => 'cheess',
                'customize_charges' => 30,
                'description' => 'extra cheess'
            ]);
        }
    }
}
