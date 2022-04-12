<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\GrabBestDeal;
use Validator;
use Storage;

class GrabBestDealController extends Controller
{
    public function create(Request $request){
        $validator = Validator::make($request->all(), 
        [
            'shop_id'=>'required',
            'deal_name'=>'required',
            'description'=>'required',
            'thumbnail_img'=>'required|mimes:jpeg,jpg,bmp,png,gif,svg,pdf',
            'banner_img'=>'required|mimes:jpeg,jpg,bmp,png,gif,svg,pdf',
            'price'=>'required',
        ]);  
        if ($validator->fails()) {
            return  response()->json([
                'data' => $validator->messages(), 
                'message' => 'please add valid data.', 
                'status' => false
            ]);
        } else {
            if ($request->hasFile('thumbnail_img') && $request->hasFile('banner_img')) {
                $thumbnail_image = $request->file('thumbnail_img');
                $banner_image = $request->file('banner_img');
            } 
            $thumbnail_file_extension = $thumbnail_image->getClientOriginalExtension();
            $banner_file_extension = $banner_image->getClientOriginalExtension();
            $thumbnail_filename = time() . '.' . $thumbnail_file_extension;
            $banner_filename = time() . '.' . $banner_file_extension;

            # upload original image
            Storage::put('public/grab-best-deal/thumb/GBD-' . $thumbnail_filename, (string) file_get_contents($thumbnail_image), 'public');
            Storage::put('public/grab-best-deal/banner/GBD-' . $banner_filename, (string) file_get_contents($banner_image), 'public');
            $thumbnailFileUrl =  Storage::url('public/grab-best-deal/thumb/GBD-' . $thumbnail_filename);
            $bannerFileUrl =  Storage::url('public/grab-best-deal/banner/GBD-' . $banner_filename);
            
            $grabBestDeal = new GrabBestDeal();
            $grabBestDeal->shop_id = $request->shop_id;
            $grabBestDeal->deal_name = $request->deal_name;
            $grabBestDeal->description = $request->description;
            $grabBestDeal->thumbnail_img_url = $thumbnailFileUrl;
            $grabBestDeal->banner_img_url = $bannerFileUrl;
            $grabBestDeal->price = $request->price;
            $grabBestDeal->save();
        }
        return response()->json(['data' => $grabBestDeal,'message' => 'Grab Best Deal Created Successfully.','status' => true]);
    }

    public function list(Request $request){
        $validator = Validator::make($request->all(), 
        [
            'shop_id'=>'required',
        ]);  
        if ($validator->fails()) {
            return  response()->json([
                'data' => $validator->messages(), 
                'message' => 'please add valid data.', 
                'status' => false
            ]);
        } else {
            $grabBestDeal = GrabBestDeal::where('shop_id',$request->shop_id)->get();
        }
        return response()->json(['data' => $grabBestDeal,'message' => 'Grab Best Deal get Successfully.','status' => true]);
    }

    public function get_one(Request $request){
        $grabBestDeal = GrabBestDeal::where('id',$request->id)->first();
        if(!$grabBestDeal){
            return response()->json(['data' => NUll,'message' => 'Grab Best Deal not found.','status' => false]);
        }
        return response()->json(['data' => $grabBestDeal,'message' => 'Grab Best Deal get Successfully.','status' => true]);
    }

    public function update(Request $request){
        $grabBestDeal = GrabBestDeal::where('id',$request->id)->first();
        if(!$grabBestDeal){
            return response()->json(['data' => NUll,'message' => 'Grab Best Deal not found.','status' => false]);
        }
        $validator = Validator::make($request->all(), 
        [
            'shop_id'=>'required',
            'deal_name'=>'required',
            'description'=>'required',
            'thumbnail_img'=>'required|mimes:jpeg,jpg,bmp,png,gif,svg,pdf',
            'banner_img'=>'required|mimes:jpeg,jpg,bmp,png,gif,svg,pdf',
            'price'=>'required',
        ]);  
        if ($validator->fails()) {
            return  response()->json([
                'data' => $validator->messages(), 
                'message' => 'please add valid data.', 
                'status' => false
            ]);
        } else {
            if ($request->hasFile('thumbnail_img') && $request->hasFile('banner_img')) {
                $thumbnail_image = $request->file('thumbnail_img');
                $banner_image = $request->file('banner_img');
            } 
            $thumbnail_file_extension = $thumbnail_image->getClientOriginalExtension();
            $banner_file_extension = $banner_image->getClientOriginalExtension();
            $thumbnail_filename = time() . '.' . $thumbnail_file_extension;
            $banner_filename = time() . '.' . $banner_file_extension;

            # upload original image
            Storage::put('public/grab-best-deal/thumb/GBD-' . $thumbnail_filename, (string) file_get_contents($thumbnail_image), 'public');
            Storage::put('public/grab-best-deal/banner/GBD-' . $banner_filename, (string) file_get_contents($banner_image), 'public');
            $thumbnailFileUrl =  Storage::url('public/grab-best-deal/thumb/GBD-' . $thumbnail_filename);
            $bannerFileUrl =  Storage::url('public/grab-best-deal/banner/GBD-' . $banner_filename);
            
            $grabBestDeal->shop_id = $request->shop_id;
            $grabBestDeal->deal_name = $request->deal_name;
            $grabBestDeal->description = $request->description;
            $grabBestDeal->thumbnail_img_url = $thumbnailFileUrl;
            $grabBestDeal->banner_img_url = $bannerFileUrl;
            $grabBestDeal->price = $request->price;
            $grabBestDeal->save();
        }
        return response()->json(['data' => $grabBestDeal,'message' => 'Grab Best Deal Created Successfully.','status' => true]);
    }

    public function delete(Request $request){
        $grabBestDeal = GrabBestDeal::where('id',$request->id)->delete();
        if(!$grabBestDeal){
            return response()->json(['data' => NUll,'message' => 'Grab Best Deal not found.','status' => false]);
        }
        return response()->json(['data' => $grabBestDeal,'message' => 'Grab Best Deal deleted Successfully.','status' => true]);
    }
}

