<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Shops;
use Validator;

class ShopController extends Controller
{
    public function create(Request $request){
        $validator = Validator::make($request->all(), 
        [
            'shop_name'=>'required|min:8',
            'address'=>'required|min:20',
            'city'=>'required',
            'latitude'=>'required',
            'longitude'=>'required',
            'contact_number'=>'required|',
        ]);  
        if ($validator->fails()) {
            return  response()->json([
                'data' => $validator->messages(), 
                'message' => 'please add valid data.', 
                'status' => false
            ]);
        } else {
            $shop = new Shops();
            $shop->shop_name = $request->shop_name;
            $shop->address = $request->address;
            $shop->city = $request->city;
            $shop->latitude = $request->latitude;
            $shop->longitude = $request->longitude;
            $shop->contact_number = $request->contact_number;
            $shop->save();
        }
        return response()->json(['data' => $shop,'message' => 'Shop Created Successfully.','status' => true]);
    }

    public function list(Request $request){
        $validator = Validator::make($request->all(), 
        [
            'search'=>'required|min:2',
        ]);
        if(!$request->search){
            $shops = Shops::all();
        }else{
            $shops = Shops::where('address', 'like', '%' . $request->search . '%')->get();
        }
        return response()->json(['data' => $shops,'message' => 'Shops get Successfully.','status' => true]);
    }

    public function get_one(Request $request){
        $shop = Shops::where('id',$request->id)->first();
        if(!$shop){
            return response()->json(['data' => NUll,'message' => 'Shop not found.','status' => false]);
        }
        return response()->json(['data' => $shop,'message' => 'Shop get Successfully.','status' => true]);
    }

    public function update(Request $request){
        $shop = Shops::where('id',$request->id)->first();
        if(!$shop){
            return response()->json(['data' => NUll,'message' => 'Shop not found.','status' => false]);
        }
        $validator = Validator::make($request->all(), 
        [
            'shop_name'=>'required|min:8',
            'address'=>'required|min:20',
            'city'=>'required',
            'latitude'=>'required',
            'longitude'=>'required',
            'contact_number'=>'required|',
        ]);  
        if ($validator->fails()) {
            return  response()->json([
                'data' => $validator->messages(), 
                'message' => 'please add valid data.', 
                'status' => false
            ]);
        } else {
           
            $shop->shop_name = $request->shop_name;
            $shop->address = $request->address;
            $shop->city = $request->city;
            $shop->latitude = $request->latitude;
            $shop->longitude = $request->longitude;
            $shop->contact_number = $request->contact_number;
            $shop->save();
            return response()->json(['data' => $shop,'message' => 'Shop update Successfully.','status' => true]);
        }
    }

    public function delete(Request $request){
        $shop = Shops::where('id',$request->id)->delete();
        if(!$shop){
            return response()->json(['data' => NUll,'message' => 'Shop Not Ffound.','status' => false]);
        }
        return response()->json(['data' => $shop,'message' => 'Shop deleted Successfully.','status' => true]);
    }
}

