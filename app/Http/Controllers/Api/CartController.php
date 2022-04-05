<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Categories;
use Validator;

class CartController extends Controller
{
    public function productTotalCount(){
        
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

