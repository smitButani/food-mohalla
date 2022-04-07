<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Categories;
use Validator;
use App\Models\ProductVariant;
use App\Models\Products;
use App\Models\ProductCustomizeOption;
use DB;

class CartController extends Controller
{
    public function productTotalCount(Request $request){
        $validator = Validator::make($request->all(), 
        [
            'variant_id'=>'required',
            'product_id'=>'required',
            'quantity' => 'required',
        ]);
        $customize_ids = explode(',',$request->customize_ids);
        print_r($customize_ids);
        if ($validator->fails()) {
            return  response()->json([
                'data' => $validator->messages(), 
                'message' => 'please add valid data.', 
                'status' => false
            ]);
        } else {
           
            // $variant_data = Products::where('id',$request->variant)->first();
            $variant_data = ProductVariant::where('product_id',$request->product_id)->where('id',$request->variant_id)->first();
            $customize_price = 0;
            if(!empty($customize_ids)){
                $customize_details_data = ProductCustomizeOption::select(DB::raw("SUM(customize_charges) as count"))->whereIn('id',$customize_ids)->first();
                print_r($customize_details_data);
                $customize_price = $customize_details_data->count;
            }
            $totalCount = ($variant_data->price + $customize_price) * $request->quantity;
        }
        return response()->json(['data' => $totalCount,'message' => 'Total Count Get Successfully.','status' => true]);
    }

    // public function create(Request $request){
    //     $validator = Validator::make($request->all(), 
    //     [
    //         'name'=>'required',
    //     ]);  
    //     if ($validator->fails()) {
    //         return  response()->json([
    //             'data' => $validator->messages(), 
    //             'message' => 'please add valid data.', 
    //             'status' => false
    //         ]);
    //     } else {
    //         $categories = new Categories();
    //         $categories->name = $request->name;
    //         $categories->save();
    //     }
    //     return response()->json(['data' => $categories,'message' => 'Categories Created Successfully.','status' => true]);
    // }

    // public function list(Request $request){
    //     $categories = Categories::all();
    //     return response()->json(['data' => $categories,'message' => 'Categories get Successfully.','status' => true]);
    // }

    // public function get_one(Request $request){
    //     $categories = Categories::where('id',$request->id)->first();
    //     if(!$categories){
    //         return response()->json(['data' => NUll,'message' => 'Categories not found.','status' => false]);
    //     }
    //     return response()->json(['data' => $categories,'message' => 'Categories get Successfully.','status' => true]);
    // }

    // public function update(Request $request){
    //     $categories = Categories::where('id',$request->id)->first();
    //     if(!$categories){
    //         return response()->json(['data' => NUll,'message' => 'Categories not found.','status' => false]);
    //     }
    //     $validator = Validator::make($request->all(), 
    //     [
    //         'name'=>'required',
    //     ]);  
    //     if ($validator->fails()) {
    //         return  response()->json([
    //             'data' => $validator->messages(), 
    //             'message' => 'please add valid data.', 
    //             'status' => false
    //         ]);
    //     } else {
           
    //         $categories->name = $request->name;
    //         $categories->save();
    //         return response()->json(['data' => $categories,'message' => 'Categories update Successfully.','status' => true]);
    //     }
    // }

    // public function delete(Request $request){
    //     $categories = Categories::where('id',$request->id)->delete();
    //     if(!$categories){
    //         return response()->json(['data' => NUll,'message' => 'Categories not found.','status' => false]);
    //     }
    //     return response()->json(['data' => $categories,'message' => 'Categories deleted Successfully.','status' => true]);
    // }
}

