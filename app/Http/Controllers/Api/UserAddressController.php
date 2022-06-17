<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\UserAddress;
use App\Models\Charges;
use Validator;
use DB;
use Carbon\Carbon;

class UserAddressController extends Controller
{
    public function create(Request $request){
        $validator = Validator::make($request->all(), 
        [
            'user_id'=>'required',
            'address'=>'required',
            'zipcode'=>'required',
            'landmark'=>'required',
            'city'=>'required',
            'state'=>'required',
            'country'=>'required',
            'type'=>'required',
            'user_name'=>'required',
        ]);
        if ($validator->fails()) {
            return  response()->json([
                'data' => $validator->messages(), 
                'message' => 'please add valid data.', 
                'status' => false
            ]);
        } else {
            $userAddress = new UserAddress();
            $userAddress->user_name = $request->user_name;
            $userAddress->user_id = auth()->user()->id;
            $userAddress->address = $request->address;
            $userAddress->zipcode = $request->zipcode;
            $userAddress->landmark = $request->landmark;
            $userAddress->city = $request->city;
            $userAddress->state = $request->state;
            $userAddress->country = $request->country;
            $userAddress->type = $request->type;
            $userAddress->save();
        }
        return response()->json(['data' => $userAddress,'message' => 'User Address Created Successfully.','status' => true]);
    }

    public function list(Request $request){
        $userAddress = UserAddress::where('user_id',auth()->user()->id)->get();
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
        $validator = Validator::make($request->all(), 
        [
            'address_id' => 'required',
            'user_id'=>'required',
            'address'=>'required',
            'zipcode'=>'required',
            'landmark'=>'required',
            'city'=>'required',
            'state'=>'required',
            'country'=>'required',
            'type'=>'required',
            'user_name'=>'required',
        ]);
        if ($validator->fails()) {
            return  response()->json([
                'data' => $validator->messages(), 
                'message' => 'please add valid data.', 
                'status' => false
            ]);
        } else {
            $userAddress = UserAddress::where('id',$request->address_id)->first();
            if(!$userAddress){
                return response()->json(['data' => NUll,'message' => 'User Address not found.','status' => false]);
            }
            $userAddress->user_name = $request->user_name;
            $userAddress->user_id = auth()->user()->id;
            $userAddress->address = $request->address;
            $userAddress->zipcode = $request->zipcode;
            $userAddress->landmark = $request->landmark;
            $userAddress->city = $request->city;
            $userAddress->state = $request->state;
            $userAddress->country = $request->country;
            $userAddress->type = $request->type;
            $userAddress->save();
        }
        return response()->json(['data' => $userAddress,'message' => 'User Address updated Successfully.','status' => true]);
    }

    public function delete(Request $request){
        $validator = Validator::make($request->all(),[
            'address_id' => 'required',
        ]);
        if ($validator->fails()) {
            return  response()->json([
                'data' => $validator->messages(), 
                'message' => 'please add valid data.', 
                'status' => false
            ]);
        } else {
            $userAddress = UserAddress::where('id',$request->address_id)->delete();
            if(!$userAddress){
                return response()->json(['data' => NUll,'message' => 'User Address Not found.','status' => false]);
            }
            return response()->json(['data' => $userAddress,'message' => 'User Address deleted Successfully.','status' => true]);
        }
    }
}
