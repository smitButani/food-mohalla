<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\BestOffers;
use Validator;
use Storage;

class BestOfferController extends Controller
{
    public function create(Request $request){
        $validator = Validator::make($request->all(), 
        [
            'offer_id'=>'required',
            'banner_name'=>'required',
            'banner_detail'=>'required',
            'banner_image'=>'required|mimes:jpeg,jpg,bmp,png,gif,svg,pdf',
        ]);
        if ($validator->fails()) {
            return  response()->json([
                'data' => $validator->messages(), 
                'message' => 'please add valid data.', 
                'status' => false
            ]);
        } else {
            if ($request->hasFile('banner_image')) {
                $banner_image = $request->file('banner_image');
            } 
            $file_extension= $banner_image->getClientOriginalExtension();
            $filename = time() . '.' . $file_extension;

            # upload original image
            Storage::put('public/best-offer/banner-' . $filename, (string) file_get_contents($banner_image), 'public');
            $productFileUrl =  Storage::url('public/best-offer/banner-' . $filename);

            $bestOffers = new BestOffers();
            $bestOffers->offer_id = $request->offer_id;
            $bestOffers->banner_name = $request->banner_name;
            $bestOffers->banner_detail = $request->banner_detail;
            $bestOffers->image_url = $productFileUrl;
            $bestOffers->save();
        }
        return response()->json(['data' => $bestOffers,'message' => 'Best offer Created Successfully.','status' => true]);
    }

    public function list(Request $request){
        $BestOffers = BestOffers::all();
        return response()->json(['data' => $BestOffers,'message' => 'Best offer get Successfully.','status' => true]);
    }

    public function get_one(Request $request){
        $BestOffers = BestOffers::where('id',$request->id)->first();
        if(!$BestOffers){
            return response()->json(['data' => NUll,'message' => 'Best offer not found.','status' => false]);
        }
        return response()->json(['data' => $BestOffers,'message' => 'Best offer get Successfully.','status' => true]);
    }

    public function update(Request $request){
        $BestOffers = BestOffers::where('id',$request->id)->first();
        if(!$BestOffers){
            return response()->json(['data' => NUll,'message' => 'Best offer not found.','status' => false]);
        }
        $validator = Validator::make($request->all(), 
        [
            'offer_id'=>'required',
            'banner_name'=>'required',
            'banner_detail'=>'required',
            'banner_image'=>'required|mimes:jpeg,jpg,bmp,png,gif,svg,pdf',
        ]);
        if ($validator->fails()) {
            return  response()->json([
                'data' => $validator->messages(), 
                'message' => 'please add valid data.', 
                'status' => false
            ]);
        } else {
           
            if ($request->hasFile('banner_image')) {
                $banner_image = $request->file('banner_image');
            } 
            $file_extension= $banner_image->getClientOriginalExtension();
            $filename = time() . '.' . $file_extension;

            # upload original image
            Storage::put('public/best-offer/banner-' . $filename, (string) file_get_contents($banner_image), 'public');
            $productFileUrl =  Storage::url('public/best-offer/banner-' . $filename);
           
            $BestOffers->offer_id = $request->offer_id;
            $bestOffers->banner_name = $request->banner_name;
            $bestOffers->banner_detail = $request->banner_detail;
            $bestOffers->image_url = $productFileUrl;
            $bestOffers->update();

        }
        return response()->json(['data' => $bestOffers,'message' => 'Best offer updated Successfully.','status' => true]);
    }

    public function delete(Request $request){
        $BestOffers = BestOffers::where('id',$request->id)->delete();
        if(!$BestOffers){
            return response()->json(['data' => NUll,'message' => 'Best offer Not found.','status' => false]);
        }
        return response()->json(['data' => $BestOffers,'message' => 'Best offer deleted Successfully.','status' => true]);
    }
}
