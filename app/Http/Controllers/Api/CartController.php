<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Categories;
use Validator;
use App\Models\Products;
use App\Models\ProductCustomizeOption;
use App\Models\GrabBestDeal;
use App\Models\CartItems;
use App\Models\CartItemsCustomize;
use App\Models\OrderItem;
use App\Models\Order;
use App\Models\OrderItemsCustomize;
use App\Models\Shops;
use App\Models\UserAddress;
use App\Models\DeliveryBoy;
use App\Models\Offers;
use DB;
use Carbon\Carbon;

class CartController extends Controller
{
    public function productTotalCount(Request $request){
        $validator = Validator::make($request->all(), 
        [
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
            $product_data = Products::where('id',$request->product_id)->first();
            $customize_price = 0;
            if(!empty($customize_ids)){
                $customize_details_data = ProductCustomizeOption::select(DB::raw("SUM(customize_charges) as count"))->whereIn('id',$customize_ids)->first();
                $customize_price = $customize_details_data->count;
            }
            $totalCount = ($product_data->price + $customize_price) * $request->quantity;
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
                $cartItems = CartItems::where('grab_best_deal_id',$request->grab_deal_id)->first();
                if($cartItems){
                    $cartItems->quantity = $cartItems->quantity + $request->quantity;
                    $cartItems->save();
                }else{
                    $grab_deal = GrabBestDeal::where('id',$request->grab_deal_id)->first();
                    $totalCount = $grab_deal->price;
                    $cartItems = new CartItems();
                    $cartItems->user_id = $user_id;
                    $cartItems->shop_id = $request->shop_id;
                    $cartItems->quantity = $request->quantity;
                    $cartItems->item_price = $totalCount;
                    $cartItems->grab_best_deal_id = $request->grab_deal_id;
                    $cartItems->save();
                }
            }
            return response()->json(['data' => $cartItems,'message' => 'Product Added into Cart Successfully.','status' => true]);
        }else{
            $validator = Validator::make($request->all(), 
            [
                'product_id'=>'required',
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
                $product_data = Products::where('id',$request->product_id)->first();
                if(!$product_data){
                    return  response()->json([
                        'data' => 'invalid data parse.', 
                        'message' => 'please add valid data.', 
                        'status' => false
                    ]);
                }
                if(!empty($request->customize_ids)){
                    $customize_details_data = ProductCustomizeOption::select(DB::raw("SUM(customize_charges) as count"))->whereIn('id',$customize_ids)->first();
                    $customize_price = $customize_details_data->count; 
                }
                $totalCount = ($product_data->price + $customize_price);
                $cartItems = new CartItems();
                $cartItems->user_id = $user_id;
                $cartItems->shop_id = $request->shop_id;
                $cartItems->product_id = $request->product_id;
                $cartItems->quantity = $request->quantity;
                $cartItems->item_price = $totalCount;
                $cartItems->save();
                if(!empty($request->customize_ids) && $cartItems){
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
    
    //orders 
    public function paymentPage(Request $request){
        $validator = Validator::make($request->all(), 
        [
            'current_lat'=>'required',
            'current_long'=>'required',
            'shop_id'=> 'required'
        ]);  
        if ($validator->fails()) {
            return  response()->json([
                'data' => $validator->messages(), 
                'message' => 'please add valid data.', 
                'status' => false
            ]);
        } else {
            $user_lat = $request->current_lat;
            $user_long = $request->current_long;
            // 'promoCode';
            $user_id = auth()->user()->id;
            $allCartItems = CartItems::where('user_id',$user_id)->get();
            $total_price = 0;
            foreach($allCartItems as $item){
                $total_price += $item->item_price * $item->quantity;
            }
            $data = [];
            $shop = Shops::where('id',$request->shop_id)->first();
            $latitudeFrom = $shop->latitude;
            $longitudeFrom =  $shop->longitude;
            $latitudeTo = $user_lat;
            $longitudeTo = $user_long;
            $GST = $total_price * 18/100;
            $data['purchase_price'] = $total_price;
            $data['GST'] = $GST;
            $charges = 15;
            $data['delivery_distance'] = round($this->checkDisctranceCharges($latitudeFrom, $longitudeFrom, $latitudeTo, $longitudeTo),2);
            $data['per_km_charges'] = $charges;
            $data['delivery_charges'] = round($this->checkDisctranceCharges($latitudeFrom, $longitudeFrom, $latitudeTo, $longitudeTo)*$charges);
            $total_payable_amount = round($GST + $data['delivery_charges'] +($total_price - ($discount_amount ?? 0)));
            $data['total_payable_amount'] = $total_payable_amount;
        }
        return response()->json(['data' => $data,'message' => 'Payment Page Details Successfully.','status' => true]);
    }

    public function checkDisctranceCharges($latitudeFrom, $longitudeFrom, $latitudeTo, $longitudeTo, $earthRadius = 6371000)
    {
        // convert from degrees to radians
        $theta = $longitudeFrom - $longitudeTo;
        $dist = sin(deg2rad($latitudeFrom)) * sin(deg2rad($latitudeTo)) +  cos(deg2rad($latitudeFrom)) * cos(deg2rad($latitudeTo)) * cos(deg2rad($theta));
        $dist = acos($dist);
        $dist = rad2deg($dist);
        $miles = $dist * 60 * 1.1515;
        
        $distance = ($miles * 1.609344);
        return $distance;
    }

    public function checkPromocode(Request $request){
        $validator = Validator::make($request->all(), 
        [
            'promo_code'=>'required',
        ]);
        if ($validator->fails()) {
            return  response()->json([
                'data' => $validator->messages(), 
                'message' => 'please add valid data.', 
                'status' => false
            ]);
        } else {
            $user_id = auth()->user()->id;

            $allCartItems = CartItems::where('user_id',$user_id)->get();

            $total_price = 0;
            foreach($allCartItems as $item){
                $total_price += $item->item_price * $item->quantity;
            }
            $data = [];
            $discount_amount = 0;
            $offer =  Offers::where('coupon_code',$request->promo_code)->where('is_active',1)->first();
            if($offer){
                    if($offer->min_cart_price > 0){
                        if($offer->min_cart_price < $total_price){
                            // check cart total
                            if($offer->discount_type == 'p'){
                                $discount_amount = $total_price * $offer->discount_amount / 100;
                                $discount_amount = ($discount_amount > $offer->max_discount_amount) ? $offer->max_discount_amount : $dicount_amount;
                            }
                            if($offer->discount_type == 'f'){
                                $discount_amount = $offer->discount_amount;
                            }
                        }else{
                            $data['promo_code_error_message'] = 'Your cart Items price Less then offer amount.';
                        }
                    }else{
                        if($offer->discount_type == 'p'){
                            $discount_amount = $total_price * $offer->discount_amount /100 ;
                        }
                        if($offer->discount_type == 'f'){
                            $discount_amount = $offer->discount_amount;
                        }
                    }
            }else{
                $data['promo_code_error_message'] = 'Invalid Promo code.';
            }
        }
        $data['discount_amount'] = $discount_amount;
        return response()->json(['data' => $data,'message' => 'Promo code check Successfully.','status' => true]);
    }

    public function createOrder(Request $request){
        $user_id = auth()->user()->id;
        $validator = Validator::make($request->all(), 
        [
            'shop_id'=>'required',
            'order_status'=>'required',
            'user_address_id'=>'required',
            'payment_method'=>'required',
            'order_type'=>'required',
            'current_lat' => 'required',
            'current_long' => 'required',
            'discount_amount' => 'required',
        ]);
        if ($validator->fails()) {
            return  response()->json([
                'data' => $validator->messages(), 
                'message' => 'please add valid data.', 
                'status' => false
            ]);
        }

        if($request->payment_method == 'UPI'){
            $validator = Validator::make($request->all(), 
            [
                'payment_gateway'=>'required',
                'payment_transaction_id'=>'required',
            ]);
        }

        if ($validator->fails()) {
            return  response()->json([
                'data' => $validator->messages(), 
                'message' => 'please add valid data.', 
                'status' => false
            ]);
        }

        $allCartItems = CartItems::where('user_id',$user_id)->get();
        if(!count($allCartItems))
        {
            return response()->json(['data' => [], 'message' => 'Cart item not found.','status' => true]);
        }

        $shop = Shops::where('id',$request->shop_id)->first();
        $latitudeFrom = $shop->latitude;
        $longitudeFrom =  $shop->longitude;
        $latitudeTo = $request->current_lat;
        $longitudeTo = $request->current_long;

        $order = new Order();
        $order->shop_id = $request->shop_id;
        $order->user_id = $user_id;
        $order->order_number = $this->random_str(8, '1234567890');
        $order->order_status = $request->order_status;
        $order->user_address_id = $request->user_address_id;
        $order->payment_method = $request->payment_method;
        $order->order_type = $request->order_type;
        $order->discount_amount = $request->discount_amount;
        $order->promo_code = $request->promo_code;
        if($order->payment_method == 'UPI'){
            $order->payment_gateway = $request->payment_gateway;
            $order->payment_transaction_id = $request->payment_transaction_id;
        }

        //discoun amount
        $dicount_amount = round($request->discount_amount);

        //delivery charges 
        $charges = 15;
        $order->delivery_distance = round($this->checkDisctranceCharges($latitudeFrom, $longitudeFrom, $latitudeTo, $longitudeTo),2);
        $order->delivery_charges = round($this->checkDisctranceCharges($latitudeFrom, $longitudeFrom, $latitudeTo, $longitudeTo),2)*$charges;

        //GST charges 
        $allCartItems = CartItems::where('user_id',$user_id)->get();
        $total_price = 0;
        foreach($allCartItems as $item){
            $total_price += $item->item_price * $item->quantity;
        }

        $GST = $total_price * 18/100;
        $order->gst_charges = $GST;

        $order->item_total = $total_price;
        $order->order_total = ($total_price + $GST + $order->delivery_charges - $dicount_amount);
        $order->save();

        if($order){
            $total_price = 0;
            $allCartItems = CartItems::where('user_id',$user_id)->get();
            foreach($allCartItems as $item){
                $orderItem = new OrderItem();
                $orderItem->order_id = $order->id;
                $orderItem->user_id = $user_id;
                $orderItem->shop_id = $item->shop_id;
                if($item->product_id){
                    $orderItem->product_id = $item->product_id;
                }
                if($item->grab_best_deal_id){
                    $orderItem->grab_best_deal_id = $item->grab_best_deal_id;
                }
                $orderItem->item_price = $item->item_price;
                $orderItem->quantity = $item->quantity;
                $orderItem->save();
                // $total_price += $item->item_price * $item->quantity;
                
                $cartItemsCustomizes = CartItemsCustomize::where('cart_item_id',$item->id)->get();
                if($cartItemsCustomizes){
                    foreach($cartItemsCustomizes as $cartCustomize){
                        $orderItemsCustomize = new OrderItemsCustomize();
                        $orderItemsCustomize->order_item_id = $orderItem->id;
                        $orderItemsCustomize->user_id = $user_id;
                        $orderItemsCustomize->product_customize_id = $cartCustomize->id;
                        $orderItemsCustomize->save();
                    }
                }
                CartItemsCustomize::where('cart_item_id',$item->id)->delete();
            }
            CartItems::where('user_id',$user_id)->delete();
            $order->is_ongoing_order = 1;
            $order->save();
        }
        return response()->json(['data' => $order, 'message' => 'Order Placed Successfully.','status' => true]);
    }

    public function orderList(Request $request){
        $user_id = auth()->user()->id;
        $orderDetails = Order::where('user_id',$user_id)->with(['order_item'])->get();
        $order = [];
        foreach($orderDetails as $orderDetails){
            $items = [];
            $item_price = 0;
            if(isset($orderDetails->order_item)){
                foreach($orderDetails->order_item as $item){
                    if($item->grab_best_deal_id){
                        $item['product'] = $item->grab_best_deal_id ?  GrabBestDeal::select('deal_name as name','description','thumbnail_img_url as image_url')->where('id',$item->grab_best_deal_id)->first() : [];
                    }else{
                        $item['product'] = $item->product_id ? Products::select('name','description','image_url')->where('id',$item->product_id)->first() : [] ;
                    }
                    $items[] = $item;
                }
            }
            $user_address = UserAddress::where('id',$orderDetails->user_address_id)->first();
            $data = [
                'shop_address' => Shops::where('id',$orderDetails->shop_id)->first()->address,
                'user_address' => $user_address,
                'order_number' => $orderDetails->order_number,
                'payment_method' => $orderDetails->payment_method,
                'payment_gateway' => $orderDetails->payment_gateway,
                'payment_transaction_id' => $orderDetails->payment_transaction_id,
                'order_type' => $orderDetails->order_type,
                'order_status' => $orderDetails->order_status,
                'order_total' => $orderDetails->order_total,
                'is_ongoing_order' => $orderDetails->is_ongoing_order,
                'id' => $orderDetails->id,
                'date_time' => Carbon::parse($orderDetails->created_at)->format('d/m/Y, g:i A'),
                'order_items' => $items
            ];
            $order[] = $data;
        }
        return response()->json(['data' => $order, 'message' => 'Orders get Successfully.','status' => true]);
    }

    public function orderDetails(Request $request){
        $validator = Validator::make($request->all(), 
        [
            'order_id'=>'required',
        ]);
         if ($validator->fails()) {
            return  response()->json([
                'data' => $validator->messages(), 
                'message' => 'please add valid data.', 
                'status' => false
            ]);
        }
        $user_id = auth()->user()->id;
        $orderDetails = Order::where('id',$request->order_id)->with(['order_item'])->first();
        
        // $order_item_name = [];
        // $items = [];
        // $order_item_with_qty = [];
        // $item_price = 0;
        // if(isset($orderDetails->order_item)){
        //     foreach($orderDetails->order_item as $item){
        //         $order_item_name[] = $item->product->name;
        //         $order_item_with_qty[] = $item->quantity.' '.$item->product->name;
        //         $item_price += $item->item_price * $item->quantity;
        //         $items[] = $item;
        //     }
        // }
        // $order = [];
        $user_address = UserAddress::where('id',$orderDetails->user_address_id)->first();
        $items = [];
        if(isset($orderDetails->order_item)){
            foreach($orderDetails->order_item as $item){
                if($item->grab_best_deal_id){
                    $item['product'] = $item->grab_best_deal_id ?  GrabBestDeal::select('deal_name as name','description','thumbnail_img_url as image_url')->where('id',$item->grab_best_deal_id)->first() : [];
                }else{
                    $item['product'] = $item->product_id ? Products::select('name','description','image_url')->where('id',$item->product_id)->first() : [] ;
                }
                $items[] = $item;
            }
        }
        $delivery_boy = DeliveryBoy::where('id',$orderDetails->delivery_boy_id)->first();
        $order = [
            'shop_address' => Shops::where('id',$orderDetails->shop_id)->first()->address,
            'delivery_address' => $user_address,
            'order_number' => $orderDetails->order_number,
            'payment_method' => $orderDetails->payment_method,
            'payment_gateway' => $orderDetails->payment_gateway,
            'payment_transaction_id' => $orderDetails->payment_transaction_id,
            'order_type' => $orderDetails->order_type,
            'order_status' => $orderDetails->order_status,
            'is_ongoing_order' => $orderDetails->is_ongoing_order,
            'id' => $orderDetails->id,
            'order_item_count' => count($orderDetails->order_item),
            'item_total' => $orderDetails->item_total,
            'delivery_charges' => $orderDetails->delivery_charges,
            'discount_amount' => $orderDetails->discount_amount,
            'GST' => $orderDetails->gst_charges,
            'order_total' => $orderDetails->order_total,
            'order_items' => $items,
            'delivery_boy_phone' => $delivery_boy ? $delivery_boy->phone_no : null,
            'order_date' => Carbon::parse($orderDetails->created_at)->format('d/m/Y, g:i A'),
        ];
        return response()->json(['data' => $order, 'message' => 'Orders get Successfully.','status' => true]);
    }

    public function orderStatusChange(Request $request){
        $validator = Validator::make($request->all(), 
        [
            'order_id'=>'required',
            'order_status'=>'required',
            'delivery_boy_id'=>'required',
        ]);
        if ($validator->fails()) {
            return  response()->json([
                'data' => $validator->messages(), 
                'message' => 'please add valid data.', 
                'status' => false
            ]);
        }
        $orderDetails = Order::where('id',$request->order_id)->first();
        if(!$orderDetails){
            return response()->json(['data' => $orderDetails, 'message' => 'Order not found.','status' => false]);
        }
        $orderDetails->order_status = $request->order_status;
        $orderDetails->delivery_boy_id = $request->delivery_boy_id;
        if($request->order_status == "delivered"){
            $orderDetails->is_ongoing_order = 0;
        }
        $orderDetails->save();
        return response()->json(['data' => $orderDetails, 'message' => 'Order status update Successfully.','status' => true]);
    }

    function random_str(
        int $length = 64,
        string $keyspace = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ'
    ): string {
        if ($length < 1) {
            throw new \RangeException("Length must be a positive integer");
        }
        $pieces = [];
        $max = mb_strlen($keyspace, '8bit') - 1;
        for ($i = 0; $i < $length; ++$i) {
            $pieces []= $keyspace[random_int(0, $max)];
        }
        return implode('', $pieces);
    }

    
      
}

