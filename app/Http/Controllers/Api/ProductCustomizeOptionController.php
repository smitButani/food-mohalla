<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\ProductCustomizeOption;
use Validator;
use Storage;

class ProductCustomizeOptionController extends Controller
{
    public function create(Request $request){
        $validator = Validator::make($request->all(), 
        [
            'product_id'=>'required',
            'customize_type_id'=>'required',
            'option_name'=>'required',
            'customize_charges'=>'required',
            'description'=>'required',
        ]);
        if ($validator->fails()) {
            return  response()->json([
                'data' => $validator->messages(), 
                'message' => 'please add valid data.', 
                'status' => false
            ]);
        } else {
            $product_customize_option = new ProductCustomizeOption();
            $product_customize_option->product_id = $request->product_id;
            $product_customize_option->customize_type_id = $request->customize_type_id;
            $product_customize_option->option_name = $request->option_name;
            $product_customize_option->customize_charges = $request->customize_charges;
            $product_customize_option->description = $request->description;
            $product_customize_option->save();
        }
        return response()->json(['data' => $product_customize_option,'message' => 'Product Customize options Created Successfully.','status' => true]);
    }

    public function list(Request $request){
        $product_customize_option = ProductCustomizeOption::all();
        return response()->json(['data' => $product_customize_option,'message' => 'Product Customize options get Successfully.','status' => true]);
    }

    public function get_one(Request $request){
        $product_customize_option = ProductCustomizeOption::where('id',$request->id)->first();
        if(!$product_customize_option){
            return response()->json(['data' => NUll,'message' => 'Product Customize Type not found.','status' => false]);
        }
        return response()->json(['data' => $product_customize_option,'message' => 'Product Customize options get Successfully.','status' => true]);
    }

    public function update(Request $request){
        $product_customize_option = ProductCustomizeOption::where('id',$request->id)->first();
        if(!$product_customize_option){
            return response()->json(['data' => NUll,'message' => 'Product Customize options not found.','status' => false]);
        }
        $validator = Validator::make($request->all(), 
        [
            'product_id'=>'required',
            'customize_type_id'=>'required',
            'option_name'=>'required',
            'customize_charges'=>'required',
            'description'=>'required',
        ]);
        if ($validator->fails()) {
            return  response()->json([
                'data' => $validator->messages(), 
                'message' => 'please add valid data.', 
                'status' => false
            ]);
        } else {
            $product_customize_option->product_id = $request->product_id;
            $product_customize_option->customize_type_id = $request->customize_type_id;
            $product_customize_option->option_name = $request->option_name;
            $product_customize_option->customize_charges = $request->customize_charges;
            $product_customize_option->description = $request->description;
            $product_customize_option->save();
        }
        return response()->json(['data' => $product_customize_option,'message' => 'Product Customize options updated Successfully.','status' => true]);
    }

    public function delete(Request $request){
        $product_customize_option = ProductCustomizeOption::where('id',$request->id)->delete();
        if(!$product_customize_option){
            return response()->json(['data' => NUll,'message' => 'Product Customize options Not found.','status' => false]);
        }
        return response()->json(['data' => $product_customize_option,'message' => 'Product Customize options deleted Successfully.','status' => true]);
    }
}
