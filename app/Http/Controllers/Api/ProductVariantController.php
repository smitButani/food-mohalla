<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\ProductVariant;
use Validator;
use Storage;

class ProductVariantController extends Controller
{
    public function create(Request $request){
        $validator = Validator::make($request->all(), 
        [
            'product_id'=>'required',
            'variant_name'=>'required',
            'description'=>'required',
            'price'=>'required',
            'is_defualt'=>'boolean',
        ]);
        if ($validator->fails()) {
            return  response()->json([
                'data' => $validator->messages(), 
                'message' => 'please add valid data.', 
                'status' => false
            ]);
        } else {
            $product_variant = new ProductVariant();
            $product_variant->product_id = $request->product_id;
            $product_variant->variant_name = $request->variant_name;
            $product_variant->description = $request->description;
            $product_variant->price = $request->price;
            $product_variant->is_defualt = $request->is_defualt ?? 0;
            $product_variant->save();
        }
        return response()->json(['data' => $product_variant,'message' => 'Product Variant Created Successfully.','status' => true]);
    }

    public function list(Request $request){
        $product_variant = ProductVariant::all();
        return response()->json(['data' => $product_variant,'message' => 'Product Variant get Successfully.','status' => true]);
    }

    public function get_one(Request $request){
        $product_variant = ProductVariant::where('id',$request->id)->first();
        if(!$product_variant){
            return response()->json(['data' => NUll,'message' => 'Product Variant not found.','status' => false]);
        }
        return response()->json(['data' => $product_variant,'message' => 'Product Variant get Successfully.','status' => true]);
    }

    public function update(Request $request){
        $product_variant = ProductVariant::where('id',$request->id)->first();
        if(!$product_variant){
            return response()->json(['data' => NUll,'message' => 'Product Variant not found.','status' => false]);
        }
        $validator = Validator::make($request->all(), 
        [
            'product_id'=>'required',
            'variant_name'=>'required',
            'description'=>'required',
            'price'=>'required',
            'is_defualt'=>'boolean',
        ]);
        if ($validator->fails()) {
            return  response()->json([
                'data' => $validator->messages(), 
                'message' => 'please add valid data.', 
                'status' => false
            ]);
        } else {
            $product_variant->product_id = $request->product_id;
            $product_variant->variant_name = $request->variant_name;
            $product_variant->description = $request->description;
            $product_variant->price = $request->price;
            $product_variant->is_defualt = $request->is_defualt;
            $product_variant->save();
        }
        return response()->json(['data' => $product_variant,'message' => 'Product Variant updated Successfully.','status' => true]);
    }

    public function delete(Request $request){
        $product_variant = ProductVariant::where('id',$request->id)->delete();
        if(!$product_variant){
            return response()->json(['data' => NUll,'message' => 'Product Variant Not found.','status' => false]);
        }
        return response()->json(['data' => $product_variant,'message' => 'Product Variant deleted Successfully.','status' => true]);
    }

    public function productWiseVariants(Request $request){
        
    }
}
