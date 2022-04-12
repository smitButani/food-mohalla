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
            // is_required
            $customizeType = ProductCustomizeType::create([
                'product_id' => $product->id,
                'type_name' => 'size',
                'is_optional' => 0,
                'control_type' => 'radio',
            ]);

            ProductCustomizeOption::create([
                'product_id' => $product->id,
                'customize_type_id' => $customizeType->id,
                'option_name' => 'small',
                'customize_charges' => 0,
                'is_defult' => 1,
                'description' => 'Size Small(product price + size charges)'
            ]);
            ProductCustomizeOption::create([
                'product_id' => $product->id,
                'customize_type_id' => $customizeType->id,
                'option_name' => 'medium',
                'customize_charges' => 40,
                'is_defult' => 0,
                'description' => 'size Medium(product price + size charges)'
            ]);
            ProductCustomizeOption::create([
                'product_id' => $product->id,
                'customize_type_id' => $customizeType->id,
                'option_name' => 'large',
                'customize_charges' => 80,
                'is_defult' => 0,
                'description' => 'size large(product price + size charges)'
            ]);

            // customize 1
            $customizeType = ProductCustomizeType::create([
                'product_id' => $product->id,
                'type_name' => 'milk',
                'is_optional' => 1,
                'control_type' => 'checkbox',
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
                'is_optional' => 1,
                'control_type' => 'checkbox',
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

            // customize 3
            $customizeType_2 = ProductCustomizeType::create([
                'product_id' => $product->id,
                'type_name' => 'Extra',
                'is_optional' => 1,
                'control_type' => 'checkbox',
            ]);

            ProductCustomizeOption::create([
                'product_id' => $product->id,
                'customize_type_id' => $customizeType_2->id,
                'option_name' => 'souce',
                'customize_charges' => 5,
                'description' => 'extra souce'
            ]);
            ProductCustomizeOption::create([
                'product_id' => $product->id,
                'customize_type_id' => $customizeType_2->id,
                'option_name' => 'mayo',
                'customize_charges' => 5,
                'description' => 'extra mayonnaise'
            ]);
        }
    }
}
