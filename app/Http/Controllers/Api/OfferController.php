<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Offers;
use Validator;
use DB;
use Carbon\Carbon;

class OfferController extends Controller
{
    public function create(Request $request){
        $validator = Validator::make($request->all(), 
        [
            'name'=>'required',
            'description'=>'required',
            'coupon_code'=>'required',
            'expaire_at'=>'required',
            'valid_for_item'=>'required',
            'discount_amount'=>'required'
        ]);
        if ($validator->fails()) {
            return  response()->json([
                'data' => $validator->messages(), 
                'message' => 'please add valid data.', 
                'status' => false
            ]);
        } else {
            $offers = new Offers();
            $offers->name = $request->name;
            $offers->description = $request->description;
            $offers->coupon_code = $request->coupon_code;
            $offers->expaire_at = $request->expaire_at;
            $offers->discount_amount = $request->discount_amount;
            $offers->valid_for_item = $request->valid_for_item;
            $offers->save();
        }
        return response()->json(['data' => $offers,'message' => 'Offer Created Successfully.','status' => true]);
    }

    public function list(Request $request){
        $offers = Offers::where('is_active',1)->get();
        return response()->json(['data' => $offers,'message' => 'Offers get Successfully.','status' => true]);
    }

    public function get_one(Request $request){
        $offers = Offers::where('id',$request->id)->first();
        if(!$offers){
            return response()->json(['data' => NUll,'message' => 'offers not found.','status' => false]);
        }
        return response()->json(['data' => $offers,'message' => 'Offers get Successfully.','status' => true]);
    }

    public function update(Request $request){
        $offers = Offers::where('id',$request->id)->first();
        if(!$offers){
            return response()->json(['data' => NUll,'message' => 'Offers not found.','status' => false]);
        }
        $validator = Validator::make($request->all(), 
        [
            'name'=>'required',
            'description'=>'required',
            'coupon_code'=>'required',
            'expaire_at'=>'required',
            'valid_for_item'=>'required',
            'discount_amount'=>'required'
        ]);
        if ($validator->fails()) {
            return  response()->json([
                'data' => $validator->messages(), 
                'message' => 'please add valid data.', 
                'status' => false
            ]);
        } else {
            $offers->name = $request->name;
            $offers->description = $request->description;
            $offers->coupon_code = $request->coupon_code;
            $offers->expaire_at = $request->expaire_at;
            $offers->discount_amount = $request->discount_amount;
            $offers->valid_for_item = $request->valid_for_item;
            $offers->save();
        }
        return response()->json(['data' => $offers,'message' => 'Offers updated Successfully.','status' => true]);
    }

    public function delete(Request $request){
        $offers = Offers::where('id',$request->id)->delete();
        if(!$offers){
            return response()->json(['data' => NUll,'message' => 'Offers Not found.','status' => false]);
        }
        return response()->json(['data' => $offers,'message' => 'Offers deleted Successfully.','status' => true]);
    }
}
