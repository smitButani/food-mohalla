<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Categories;
use Validator;
use App\Models\ProductVariant;
use App\Models\Products;
use App\Models\ProductCustomizeOption;
use App\Models\GrabBestDeal;
use App\Models\CartItems;
use App\Models\CartItemsCustomize;
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
                $customize_price = $customize_details_data->count;
            }
            $totalCount = ($variant_data->price + $customize_price) * $request->quantity;
        }
        return response()->json(['data' => $totalCount,'message' => 'Total Count Get Successfully.','status' => true]);
    }

    public function addToCart(Request $request){
        if($request->grab_deal_id){
            $validator = Validator::make($request->all(), 
            [
                'grab_deal_id'=>'required',
                'quantity' => 'required',
            ]);
            if ($validator->fails()) {
                return  response()->json([
                    'data' => $validator->messages(), 
                    'message' => 'please add valid data.', 
                    'status' => false
                ]);
            }else{
                $user_id = auth()->user()->id;
                $grab_deal = GrabBestDeal::where('id',$request->grab_deal_id)->first();
                $totalCount = $grab_deal->price * $request->quantity;
                $cartItems = new CartItems();
                $cartItems->user_id = $user_id;
                $cartItems->shop_id = $request->shop_id;
                $cartItems->quantity = $request->quantity;
                $cartItems->item_price = $totalCount;
                $cartItems->grab_best_deal_id = $request->grab_deal_id;
                $cartItems->save();
            }
            return response()->json(['data' => $cartItems,'message' => 'Product Added into Cart Successfully.','status' => true]);
        }else{
            $validator = Validator::make($request->all(), 
            [
                'product_id'=>'required',
                'variant_id'=>'required',
                'shop_id'=>'required',
                'quantity' => 'required',
            ]);
            $customize_ids = explode(',',$request->customize_ids);
            if ($validator->fails()) {
                return  response()->json([
                    'data' => $validator->messages(), 
                    'message' => 'please add valid data.', 
                    'status' => false
                ]);
            } else {
                $user_id = auth()->user()->id;
                $customize_price = 0;
                $variant_data = ProductVariant::where('product_id',$request->product_id)->where('id',$request->variant_id)->first();
                if(!$variant_data){
                    return  response()->json([
                        'data' => 'invalid data parse.', 
                        'message' => 'please add valid data.', 
                        'status' => false
                    ]);
                }
                if(!empty($customize_ids)){
                    $customize_details_data = ProductCustomizeOption::select(DB::raw("SUM(customize_charges) as count"))->whereIn('id',$customize_ids)->first();
                    $customize_price = $customize_details_data->count; 
                }
                $totalCount = ($variant_data->price + $customize_price);
                $cartItems = new CartItems();
                $cartItems->user_id = $user_id;
                $cartItems->shop_id = $request->shop_id;
                $cartItems->product_id = $request->product_id;
                $cartItems->product_variant_id = $request->variant_id;
                $cartItems->quantity = $request->quantity;
                $cartItems->item_price = $totalCount;
                $cartItems->save();
                if(!empty($customize_ids) && $cartItems){
                    foreach($customize_ids as $id){
                        $CartItemsCustomize = new CartItemsCustomize();
                        $CartItemsCustomize->cart_item_id = $cartItems->id;
                        $CartItemsCustomize->user_id = $user_id;
                        $CartItemsCustomize->product_customize_id = $id;
                        $CartItemsCustomize->save();
                    }
                }
            }
            return response()->json(['data' => $cartItems,'message' => 'Product Added into Cart Successfully.','status' => true]);
        }
    }

    public function getCart(){
        $user_id = auth()->user()->id;
        $getCartItems = CartItems::where('user_id',$user_id)->get();
        $cartData = [];
        $total_price = 0;
        foreach($getCartItems as $item){
            if(!empty($item->grab_best_deal_id)){
                $data['cart_item_id'] = $item->id;
                $data['image_url'] = GrabBestDeal::where('id',$item->grab_best_deal_id)->first()->thumbnail_img_url;
                $data['product_name'] = GrabBestDeal::where('id',$item->grab_best_deal_id)->first()->deal_name;
                $data['quntity'] = $item->quantity;
                $data['price'] = $item->item_price;
                $total_price += $item->item_price * $item->quantity;
            }else{
                $data['cart_item_id'] = $item->id;
                $data['image_url'] = Products::where('id',$item->product_id)->first()->image_url;
                $data['product_name'] = Products::where('id',$item->product_id)->first()->name;
                $data['quntity'] = $item->quantity;
                $data['price'] = $item->item_price;
                $total_price += $item->item_price * $item->quantity;
            }
            array_push($cartData,$data);
        }
        if(!$cartData){
            return response()->json(['data' => NUll,'item_total'=> $total_price, 'message' => 'Cart Items not found.','status' => false]);
        }
        return response()->json(['data' => $cartData,'item_total'=> $total_price,'message' => 'Cart Items get Successfully.','status' => true]);
    }

    public function updateCart(Request $request){
        $validator = Validator::make($request->all(), 
        [
            'cart_item_id'=>'required',
            'quantity' => 'required',
        ]);
        if ($validator->fails()) {
            return  response()->json([
                'data' => $validator->messages(), 
                'message' => 'please add valid data.', 
                'status' => false
            ]);
        } else {
            $user_id = auth()->user()->id;
            $cart_item_id = $request->cart_item_id;
            $cartItem = CartItems::where('id',$cart_item_id)->first();
            $cartItem->quantity = $request->quantity;
            $cartItem->save();
        }

        $total_price = 0;

        $allCartItems = CartItems::where('user_id',$user_id)->get();
        foreach($allCartItems as $item){
            $total_price += $item->item_price * $item->quantity;
        }
        return response()->json(['data' => $cartItem,'item_total'=> $total_price,'message' => 'Cart Items updated Successfully.','status' => true]);
    }


    public function deleteCartItem(Request $request){
        $user_id = auth()->user()->id;
        $validator = Validator::make($request->all(), 
        [
            'cart_item_id'=>'required',
        ]);
        if ($validator->fails()) {
            return  response()->json([
                'data' => $validator->messages(), 
                'message' => 'please add valid data.', 
                'status' => false
            ]);
        } else {
            $cartItem = CartItems::where('id',$request->cart_item_id)->delete();
            CartItemsCustomize::where('cart_item_id',$request->cart_item_id)->delete();
        }

        $total_price = 0;
        $allCartItems = CartItems::where('user_id',$user_id)->get();
        foreach($allCartItems as $item){
            $total_price += $item->item_price * $item->quantity;
        }

        return response()->json(['data' => 'deleted','item_total'=> $total_price,'message' => 'Cart Items Deleted Successfully.','status' => true]);
    }
}

