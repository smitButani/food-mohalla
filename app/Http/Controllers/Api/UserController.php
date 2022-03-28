<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;
use Validator;
use DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Storage;

class UserController extends Controller
{
    public function tokenGenerate(Request $request){
        $validator = Validator::make($request->all(), 
        [
            'phone_number'=>'required|min:11|numeric',
        ]);
        if ($validator->fails()) {
            return  response()->json([
                'data' => $validator->messages(), 
                'message' => 'please add valid data.', 
                'status' => false
            ]);
        } else {
            $randomNumber = rand(1231,7879);
            $user = User::firstOrNew(array('phone_number' => $request->phone_number));
            $user->otp_token = $randomNumber;
            $user->otp_send_at = Carbon::now();
            $user->phone_number = $request->phone_number;
            $user->save();
            
        }
        return response()->json(['data' => ['otp_token' => $randomNumber ],'message' => 'Otp Generated Successfully.','status' => true]);
    }

    public function verifyOtp(Request $request){
        $validator = Validator::make($request->all(), 
        [
            'phone_number'=>'required',
            'otp_token'=>'required',
        ]);
        if ($validator->fails()) {
            return  response()->json([
                'data' => $validator->messages(), 
                'message' => 'please add valid data.', 
                'status' => false
            ]);
        } else {
            $user = User::where('phone_number', $request->phone_number)->where('otp_token', $request->otp_token)->first();
            if(!$user){
                return response()->json(['data' => NUll,'message' => 'Please enter Valid Otp.','status' => false]);
            }

            Auth::login($user);

            $accessToken = $user->createToken('authToken')->accessToken;
            $user = User::firstOrNew(array('phone_number' => $request->phone_number));
            $user->phone_verified_at = Carbon::now();
            $user->token = $accessToken;
            $user->phone_number = $request->phone_number;
            $user->save();
        }
        return response()->json(['data' => ['user' => $user, 'access_token' => $accessToken ],'message' => 'Otp verify Successfully.','status' => true]);
    }

    public function userCreate(Request $request){
        $validator = Validator::make($request->all(), 
        [
            'phone_number'=>'required',
        ]);
        if ($validator->fails()) {
            return  response()->json([
                'data' => $validator->messages(), 
                'message' => 'please add valid data.', 
                'status' => false
            ]);
        } else {
            $user = User::firstOrNew(array('phone_number' => $request->phone_number));
            $user->phone_number = $request->phone_number;
            $user->phone_verified_at = Carbon::now();
            $accessToken = $user->createToken('authToken')->accessToken;
            $user->token = $accessToken;
            $user->save();
        }
        return response()->json(['data' => ['user' => $user, 'access_token' => $accessToken ],'message' => 'Otp verify Successfully.','status' => true]);
    }

    public function update(Request $request){
        $validator = Validator::make($request->all(), 
        [
            'phone_number'=>'required',
            'first_name'=>'required',
            'last_name' => 'required',
            'profile_img' => 'mimes:jpeg,jpg,bmp,png,gif,svg,pdf'
        ]);
        if ($validator->fails()) {
            return  response()->json([
                'data' => $validator->messages(), 
                'message' => 'please add valid data.', 
                'status' => false
            ]);
        } else {
            // $user = User::where('phone_number', $request->phone_number)->where('otp_token', $request->otp_token)->first();
            $user = auth()->user();
            if(!$user){
                return response()->json(['data' => NUll,'message' => 'User not Found.','status' => false]);
            }

            if ($request->hasFile('profile_img')) {
                $user_image = $request->file('profile_img');
                $file_extension = $user_image->getClientOriginalExtension();
                $filename = time() . '.' . $file_extension;

                Storage::put('public/user/profile/user-' . $filename, (string) file_get_contents($user_image), 'public');
                $profileUrl =  Storage::url('public/user/profile/user-' . $filename);

                $user->image_url = $profileUrl;
            }
            
            $user->first_name = $request->first_name ?? null;
            $user->last_name = $request->last_name ?? null;
            $user->username = $request->username ?? null;
            $user->phone_number = $request->phone_number ?? null;
            $user->save();
        }
        return response()->json(['data' => ['user' => $user ],'message' => 'Otp verify Successfully.','status' => true]);
    }

    public function userDetails(Request $request){
        $user = auth()->user();
        if(!$user){
            return response()->json(['data' => NUll,'message' => 'User not found.','status' => false]);
        }
        return response()->json(['data' => $user,'message' => 'User get Successfully.','status' => true]);
    }

    public function shopListByLocation(Request $request){
        return 'pending api';
    }
}
