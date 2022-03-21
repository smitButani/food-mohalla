<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\UserAddress;
use Validator;
use DB;
use Carbon\Carbon;

class UserAddressController extends Controller
{
    public function create(Request $request){
        $validator = Validator::make($request->all(), 
        [
            'user_id'=>'required',
            'full_address'=>'required',
            'zipcode'=>'required',
            'landmark'=>'required',
            'type'=>'required',
        ]);
        if ($validator->fails()) {
            return  response()->json([
                'data' => $validator->messages(), 
                'message' => 'please add valid data.', 
                'status' => false
            ]);
        } else {
            $userAddress = new UserAddress();
            $userAddress->user_id = $request->user_id;
            $userAddress->full_address = $request->full_address;
            $userAddress->zipcode = $request->zipcode;
            $userAddress->landmark = $request->landmark;
            $userAddress->type = $request->type;
            $userAddress->save();
        }
        return response()->json(['data' => $userAddress,'message' => 'User Address Created Successfully.','status' => true]);
    }

    public function list(Request $request){
        $userAddress = UserAddress::all();
        return response()->json(['data' => $userAddress,'message' => 'User Address get Successfully.','status' => true]);
    }

    public function get_one(Request $request){
        $userAddress = UserAddress::where('id',$request->id)->first();
        if(!$userAddress){
            return response()->json(['data' => NUll,'message' => 'User Address not found.','status' => false]);
        }
        return response()->json(['data' => $userAddress,'message' => 'User Address get Successfully.','status' => true]);
    }

    public function update(Request $request){
        $userAddress = UserAddress::where('id',$request->id)->first();
        if(!$userAddress){
            return response()->json(['data' => NUll,'message' => 'User Address not found.','status' => false]);
        }
        $validator = Validator::make($request->all(), 
        [
            'user_id'=>'required',
            'full_address'=>'required',
            'zipcode'=>'required',
            'landmark'=>'required',
            'type'=>'required',
        ]);
        if ($validator->fails()) {
            return  response()->json([
                'data' => $validator->messages(), 
                'message' => 'please add valid data.', 
                'status' => false
            ]);
        } else {
            $userAddress->user_id = $request->user_id;
            $userAddress->full_address = $request->full_address;
            $userAddress->zipcode = $request->zipcode;
            $userAddress->landmark = $request->landmark;
            $userAddress->type = $request->type;
            $userAddress->save();
        }
        return response()->json(['data' => $userAddress,'message' => 'User Address updated Successfully.','status' => true]);
    }

    public function delete(Request $request){
        $userAddress = UserAddress::where('id',$request->id)->delete();
        if(!$userAddress){
            return response()->json(['data' => NUll,'message' => 'User Address Not found.','status' => false]);
        }
        return response()->json(['data' => $userAddress,'message' => 'User Address deleted Successfully.','status' => true]);
    }
}
