<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Products;
use App\Models\ProductVariant;
use App\Models\Shops;
use App\Models\ShopsProducts;
use App\Models\ProductCustomizeType;
use Validator;
use Storage;

class ProductController extends Controller
{
    public function create(Request $request){
        $validator = Validator::make($request->all(), 
        [
            'category_id'=>'required',
            'name'=>'required',
            'description'=>'required',
            'product_image'=>'required|mimes:jpeg,jpg,bmp,png,gif,svg,pdf',
            'price'=>'required|numeric'
        ]);
        if ($validator->fails()) {
            return  response()->json([
                'data' => $validator->messages(), 
                'message' => 'please add valid data.', 
                'status' => false
            ]);
        } else {
            if ($request->hasFile('product_image')) {
                $product_image = $request->file('product_image');
            } 
            $file_extension= $product_image->getClientOriginalExtension();
            $filename = time() . '.' . $file_extension;

            # upload original image
            Storage::put('public/products/products-' . $filename, (string) file_get_contents($product_image), 'public');
            $productFileUrl =  Storage::url('public/products/products-' . $filename);

            $products = new Products();
            $products->category_id = $request->category_id;
            $products->name = $request->name;
            $products->description = $request->description;
            $products->image_url = $productFileUrl;
            $products->price = $request->price;
            $products->save();
            if($products){
                $shops = Shops::all();
                foreach($shops as $shop){
                     $shop_product = new ShopsProducts();
                     $shop_product->shop_id =  $shop->id;
                     $shop_product->product_id =  $products->id;
                     $shop_product->save();
                }
            }
        }
        return response()->json(['data' => $products,'message' => 'Product Created Successfully.','status' => true]);
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
            $products = Products::where('shop_id',$request->shop_id)->where('is_recommended',0)->get();
        }
        return response()->json(['data' => $products,'message' => 'Products get Successfully.','status' => true]);
    }

    public function get_one(Request $request){
        $Products = Products::where('id',$request->id)->first();
        if(!$Products){
            return response()->json(['data' => NUll,'message' => 'Products not found.','status' => false]);
        }
        return response()->json(['data' => $Products,'message' => 'Products get Successfully.','status' => true]);
    }

    public function update(Request $request){
        $products = Products::where('id',$request->id)->first();
        if(!$products){
            return response()->json(['data' => NUll,'message' => 'Products not found.','status' => false]);
        }
        $validator = Validator::make($request->all(), 
        [
            'category_id'=>'required',
            'name'=>'required',
            'description'=>'required',
            'product_image'=>'required|mimes:jpeg,jpg,bmp,png,gif,svg,pdf',
            'price'=>'required|numeric'
        ]);
        if ($validator->fails()) {
            return  response()->json([
                'data' => $validator->messages(), 
                'message' => 'please add valid data.', 
                'status' => false
            ]);
        } else {
            if ($request->hasFile('product_image')) {
                $product_image = $request->file('product_image');
            } 
            $file_extension= $product_image->getClientOriginalExtension();
            $filename = time() . '.' . $file_extension;

            # upload original image
            Storage::put('public/products' . $filename, (string) file_get_contents($product_image), 'public');
            $productFileUrl =  Storage::url('public/products' . $filename);

            $products->category_id = $request->category_id;
            $products->name = $request->name;
            $products->description = $request->description;
            $products->image_url = $productFileUrl;
            $products->price = $request->price;
            $products->save();
        }
        return response()->json(['data' => $products,'message' => 'Product updated Successfully.','status' => true]);
    }

    public function delete(Request $request){
        $Products = Products::where('id',$request->id)->delete();
        if(!$Products){
            return response()->json(['data' => NUll,'message' => 'Products Not found.','status' => false]);
        }
        return response()->json(['data' => $Products,'message' => 'Products deleted Successfully.','status' => true]);
    }

    public function productWiseVariants(Request $request){
        $product_variant = ProductVariant::where('product_id',$request->productId)->get();
        return response()->json(['data' => $product_variant,'message' => 'Products variants get Successfully.','status' => true]);
    }

    public function productCustomizeDetails(Request $request){
        $validator = Validator::make($request->all(), 
        [
            'product_id'=>'required',
        ]);
        if ($validator->fails()) {
            return  response()->json([
                'data' => $validator->messages(), 
                'message' => 'please add valid data.', 
                'status' => false
            ]);
        } else {
            $product_customize_details = Products::where('id',$request->product_id)->with(['productCustomizeType.productCustomizeOption'])->first();
            return response()->json(['data' => $product_customize_details,'message' => 'Products variants get Successfully.','status' => true]);
        }
             
    }

    public function productDetails(Request $request){
        $product_customize = ProductCustomizeType::where('product_id',$request->productId)->with(['productCustomizeOption'])->get();
        // $product_variant = ProductVariant::where('product_id',$request->productId)->get();
        return ['data' => $product_customize,'message' => 'Products variants and Product Customize get Successfully.','status' => true];
    }

    public function productSearch(Request $request){
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
            if(!$request->search){
                $products = Products::where('shop_id',$request->shop_id)->get();
            }else{
                $products = Products::where('shop_id',$request->shop_id)->where('name', 'like', '%' . $request->search . '%')->get();
            }
        }
        return response()->json(['data' => $products,'message' => 'Products get Successfully.','status' => true]);
    }
}
