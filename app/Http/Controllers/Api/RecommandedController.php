<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Recommanded;
use App\Models\Products;
use Validator;
use Storage;

$name = 'Recommanded';

class RecommandedController extends Controller
{
    public function create(Request $request){
        $validator = Validator::make($request->all(), 
        [
            'combo_name'=>'required',
            'description'=>'required',
            'thumbnail_img'=>'required|mimes:jpeg,jpg,bmp,png,gif,svg,pdf',
            'banner_img'=>'required|mimes:jpeg,jpg,bmp,png,gif,svg,pdf',
            'price'=>'required',
            'shop_id'=>'required',
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
            Storage::put('public/recommanded/thumb/RD-' . $thumbnail_filename, (string) file_get_contents($thumbnail_image), 'public');
            Storage::put('public/recommanded/banner/RD-' . $banner_filename, (string) file_get_contents($banner_image), 'public');
            $thumbnailFileUrl =  Storage::url('public/recommanded/thumb/RD-' . $thumbnail_filename);
            $bannerFileUrl =  Storage::url('public/recommanded/banner/RD-' . $banner_filename);
            
            $recommanded = new Recommanded();
            $recommanded->combo_name = $request->combo_name;
            $recommanded->description = $request->description;
            $recommanded->thumbnail_img_url = $thumbnailFileUrl;
            $recommanded->banner_img_url = $bannerFileUrl;
            $recommanded->price = $request->price;
            $recommanded->product_id = 0;
            $recommanded->shop_id = $request->shop_id;
            $recommanded->save();
            if($recommanded){
                # upload original image
                Storage::put('public/products/RD-' . $thumbnail_filename, (string) file_get_contents($thumbnail_image), 'public');
                $productFileUrl =  Storage::url('public/products/RD-' . $thumbnail_filename);
    
                $products = new Products();
                $products->shop_id = $request->shop_id;
                $products->category_id = 0;
                $products->name = $request->combo_name;
                $products->description = $request->description;
                $products->image_url = $productFileUrl;
                $products->price = $request->price;
                $products->is_recommended = 1;
                $products->save();

                $recommanded->product_id = $products->id;
                $recommanded->save();
            }
        }
        return response()->json(['data' => $recommanded,'message' => 'Recommanded Created Successfully.','status' => true]);
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
            $recommanded = Recommanded::where('shop_id',$request->shop_id)->get();
        }
        return response()->json(['data' => $recommanded,'message' => 'Recommanded get Successfully.','status' => true]);
    }

    public function get_one(Request $request){
        $recommanded = Recommanded::where('id',$request->id)->first();
        if(!$recommanded){
            return response()->json(['data' => NUll,'message' => 'Recommanded not found.','status' => false]);
        }
        return response()->json(['data' => $recommanded,'message' => 'Recommanded get Successfully.','status' => true]);
    }

    public function update(Request $request){
        $recommanded = Recommanded::where('id',$request->id)->first();
        if(!$recommanded){
            return response()->json(['data' => NUll,'message' => 'Recommanded not found.','status' => false]);
        }
        $validator = Validator::make($request->all(), 
        [
            'combo_name'=>'required',
            'description'=>'required',
            'thumbnail_img'=>'required|mimes:jpeg,jpg,bmp,png,gif,svg,pdf',
            'banner_img'=>'required|mimes:jpeg,jpg,bmp,png,gif,svg,pdf',
            'price'=>'required',
            'product_id'=>'required',
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
            
            $recommanded->combo_name = $request->combo_name;
            $recommanded->description = $request->description;
            $recommanded->thumbnail_img_url = $thumbnailFileUrl;
            $recommanded->banner_img_url = $bannerFileUrl;
            $recommanded->price = $request->price;
            $recommanded->product_id = $request->product_id;
            $recommanded->save();
        }
        return response()->json(['data' => $recommanded,'message' => 'Recommanded updated Successfully.','status' => true]);
    }

    public function delete(Request $request){
        $recommanded = Recommanded::where('id',$request->id)->delete();
        if(!$recommanded){
            return response()->json(['data' => NUll,'message' => 'Recommanded not found.','status' => false]);
        }
        return response()->json(['data' => $recommanded,'message' => 'Recommanded deleted Successfully.','status' => true]);
    }
}

