<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\ProductCustomizeType;
use Validator;
use Storage;

class ProductCustomizeTypeController extends Controller
{
    public function create(Request $request){
        $validator = Validator::make($request->all(), 
        [
            'product_id'=>'required',
            'type_name'=>'required',
        ]);
        if ($validator->fails()) {
            return  response()->json([
                'data' => $validator->messages(), 
                'message' => 'please add valid data.', 
                'status' => false
            ]);
        } else {
            $product_customize_type = new ProductCustomizeType();
            $product_customize_type->product_id = $request->product_id;
            $product_customize_type->type_name = $request->type_name;
            $product_customize_type->save();
        }
        return response()->json(['data' => $product_customize_type,'message' => 'Product Customize Type Created Successfully.','status' => true]);
    }

    public function list(Request $request){
        $product_customize_type = ProductCustomizeType::all();
        return response()->json(['data' => $product_customize_type,'message' => 'Product Customize Type get Successfully.','status' => true]);
    }

    public function get_one(Request $request){
        $product_customize_type = ProductCustomizeType::where('id',$request->id)->first();
        if(!$product_customize_type){
            return response()->json(['data' => NUll,'message' => 'Product Customize Type not found.','status' => false]);
        }
        return response()->json(['data' => $product_customize_type,'message' => 'Product Customize Type get Successfully.','status' => true]);
    }

    public function update(Request $request){
        $product_customize_type = ProductCustomizeType::where('id',$request->id)->first();
        if(!$product_customize_type){
            return response()->json(['data' => NUll,'message' => 'Product Customize Type not found.','status' => false]);
        }
        $validator = Validator::make($request->all(), 
        [
            'product_id'=>'required',
            'type_name'=>'required',
        ]);
        if ($validator->fails()) {
            return  response()->json([
                'data' => $validator->messages(), 
                'message' => 'please add valid data.', 
                'status' => false
            ]);
        } else {
            $product_customize_type = new ProductCustomizeType();
            $product_customize_type->product_id = $request->product_id;
            $product_customize_type->type_name = $request->type_name;
            $product_customize_type->save();
        }
        return response()->json(['data' => $product_customize_type,'message' => 'Product Customize Type updated Successfully.','status' => true]);
    }

    public function delete(Request $request){
        $product_customize_type = ProductCustomizeType::where('id',$request->id)->delete();
        if(!$product_customize_type){
            return response()->json(['data' => NUll,'message' => 'Product Customize Type Not found.','status' => false]);
        }
        return response()->json(['data' => $product_customize_type,'message' => 'Product Customize Type deleted Successfully.','status' => true]);
    }
}
