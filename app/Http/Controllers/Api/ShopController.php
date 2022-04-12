<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Shops;
use Validator;
use Storage;

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
            'contact_number'=>'required',
            'shop_icon'=>'required|mimes:jpeg,jpg,bmp,png,gif,svg,pdf',
        ]);  
        if ($validator->fails()) {
            return  response()->json([
                'data' => $validator->messages(), 
                'message' => 'please add valid data.', 
                'status' => false
            ]);
        } else {

            if ($request->hasFile('shop_icon')) {
                $shop_image = $request->file('shop_icon');
            } 
            $file_extension= $shop_image->getClientOriginalExtension();
            $filename = time() . '.' . $file_extension;

            # upload original image
            Storage::put('public/shop_image/shop-icon' . $filename, (string) file_get_contents($shop_image), 'public');
            $shopFileUrl =  Storage::url('public/shop_image/shop-icon' . $filename);

            $shop = new Shops();
            $shop->shop_name = $request->shop_name;
            $shop->address = $request->address;
            $shop->city = $request->city;
            $shop->latitude = $request->latitude;
            $shop->longitude = $request->longitude;
            $shop->contact_number = $request->contact_number;
            $shop->shop_icon_url = $shopFileUrl;
            $shop->save();
        }
        return response()->json(['data' => $shop,'message' => 'Shop Created Successfully.','status' => true]);
    }

    public function list(Request $request){
        $lat = $request->current_lat;
        $long = $request->current_long;
        if($lat && $long && !$request->search){
                $shops = \DB::table("shops")
                ->select("shops.*", \DB::raw("6371 * acos(cos(radians(" . $lat . "))
                * cos(radians(shops.latitude)) 
                * cos(radians(shops.longitude) - radians(" . $long . ")) 
                + sin(radians(" .$lat. ")) 
                * sin(radians(shops.latitude))) AS distance"))
                ->having('distance', '<', 10)
                ->orderBy('distance', 'asc')
                ->get();
        }elseif($lat && $long && $request->search){
            $shops = \DB::table("shops")
            ->select("shops.*", \DB::raw("6371 * acos(cos(radians(" . $lat . "))
            * cos(radians(shops.latitude)) 
            * cos(radians(shops.longitude) - radians(" . $long . ")) 
            + sin(radians(" .$lat. ")) 
            * sin(radians(shops.latitude))) AS distance"))
            ->where('city', 'like', '%' . $request->search . '%')
            ->having('distance', '<', 3)
            ->orderBy('distance', 'asc')
            ->get();
        }elseif($request->search && !$lat && !$long){
            $shops = Shops::where('city', 'like', '%' . $request->search . '%')->get();
        }else{
            $shops = Shops::all();
        }
        return response()->json(['data' => $shops,'message' => 'Shops get successfully.','status' => true]);
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
            'shop_icon'=>'required|mimes:jpeg,jpg,bmp,png,gif,svg,pdf',
        ]);  
        if ($validator->fails()) {
            return  response()->json([
                'data' => $validator->messages(), 
                'message' => 'please add valid data.', 
                'status' => false
            ]);
        } else {
           
            if ($request->hasFile('shop_icon')) {
                $shop_image = $request->file('shop_icon');
            } 
            $file_extension= $shop_image->getClientOriginalExtension();
            $filename = time() . '.' . $file_extension;

            # upload original image
            Storage::put('public/shop_image/shop-icon' . $filename, (string) file_get_contents($shop_image), 'public');
            $shopFileUrl =  Storage::url('public/shop_image/shop-icon' . $filename);

            $shop->shop_name = $request->shop_name;
            $shop->address = $request->address;
            $shop->city = $request->city;
            $shop->latitude = $request->latitude;
            $shop->longitude = $request->longitude;
            $shop->contact_number = $request->contact_number;
            $shop->shop_icon_url = $shopFileUrl;
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

