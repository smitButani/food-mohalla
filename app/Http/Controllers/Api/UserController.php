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
use App\Models\UserSetting;

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
            if($request->phone_number == '1234567890'){
                $defultNumber = 999333; 
                $user = User::firstOrNew(array('phone_number' => $request->phone_number));
                $user->otp_token = $defultNumber;
                $user->otp_send_at = Carbon::now();
                $user->phone_number = $request->phone_number;
                $user->save();
                return response()->json(['data' => ['otp_token' => $defultNumber ],'message' => 'Otp Generated Successfully.','status' => true]);
            }else{
                try{
                    $randomNumber = rand(123219,999999);
                    $endpoint = "http://sms.mobileadz.in/api/push.json";
                    $client = new \GuzzleHttp\Client();
                    $phone_number = $request->phone_number;
                    $sms_template = 'Your OTP verification code is: '.$randomNumber.' Food Mohalla';
                    $route = 'trans_dnd';
                    $sender = 'FDMOHL';
                    $api_key = '62a69b57eaf4d';
                    $response = $client->request('GET', $endpoint, ['query' => [
                        'apikey' => $api_key,
                        'route' => $route,
                        'sender' => $sender,
                        'mobileno' => $phone_number,
                        'text' => $sms_template
                    ]]);
        
                    $statusCode = $response->getStatusCode();
                    $response = $response->getBody();
                    
                    if($response){
                        $user = User::firstOrNew(array('phone_number' => $request->phone_number));
                        $user->otp_token = $randomNumber;
                        $user->otp_send_at = Carbon::now();
                        $user->phone_number = $request->phone_number;
                        $user->save();
                    } 
                    return response()->json(['data' => ['otp_token' => $randomNumber ],'message' => 'Otp Generated Successfully.','status' => true]);
                }catch(\Exception $e){
                    return response()->json(['data' => false,'message' => 'Please enter Valid Phone Number','status' => false]);
                }
            }
            
        }
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
            
            $is_exist_setting = UserSetting::where('user_id',$user->id)->first();
            if(!$is_exist_setting){
                $user_setting = UserSetting::firstOrNew(array('user_id' => $user->id));
                $user_setting->notification_active = 1;
                $user_setting->save();
            }
        }
        return response()->json(['data' => ['user' => $user, 'access_token' => $accessToken ],'message' => 'User created successfully.','status' => true]);
    }

    public function update(Request $request){
        $user = auth()->user();
        if(!$user){
            return response()->json(['data' => NUll,'message' => 'User not found.','status' => false]);
        }

        if ($request->hasFile('profile_img')) {
            $user_image = $request->file('profile_img');
            $file_extension = $user_image->getClientOriginalExtension();
            $filename = time() . '.' . $file_extension;

            Storage::put('public/user/profile/user-' . $filename, (string) file_get_contents($user_image), 'public');
            $profileUrl =  Storage::url('public/user/profile/user-' . $filename);

            $user->image_url = $profileUrl ?? '';
        }
        
        $user->first_name = $request->first_name ?? '';
        $user->last_name = $request->last_name ?? '';
        $user->username = $request->username ?? '';
        $user->email = $request->email ?? '';
        $user->save();
        return response()->json(['data' => ['user' => $user ],'message' => 'User updated successfully.','status' => true]);
    }

    public function userDetails(Request $request){
        $user = auth()->user();
        if(!$user){
            return response()->json(['data' => NUll,'message' => 'User not found.','status' => false]);
        }
        return response()->json(['data' => $user,'message' => 'User get successfully.','status' => true]);
    }

    public function updateDeviceToken(Request $request){
        $validator = Validator::make($request->all(), 
        [
            'device_type'=>'required',
            'device_token'=>'required',
        ]);
        if ($validator->fails()) {
            return  response()->json([
                'data' => $validator->messages(), 
                'message' => 'please add valid data.', 
                'status' => false
            ]);
        } else {
            $user = User::where('id',auth()->user()->id)->first();
            $user->device_type = $request->device_type;
            $user->device_token = $request->device_token;
            $user->save();
        }
        return response()->json(['data' => $user,'message' => 'User device token set successfully.','status' => true]);
    }

    public function logout(Request $request){
        $user = Auth::user()->token();
        $user->revoke();
        return response()->json(['data' => 'Logout','message' => 'User logout successfully.','status' => true]);
    }

    public function updateSetting(Request $request){
        $userId = Auth::user()->id;
        $validator = Validator::make($request->all(), 
        [
            'notification_active'=>'required',
        ]);
        if ($validator->fails()) {
            return  response()->json([
                'data' => $validator->messages(), 
                'message' => 'please add valid data.', 
                'status' => false
            ]);
        } else {
            $user_setting = UserSetting::firstOrNew(array('user_id' => $userId));
            $user_setting->user_id = $userId;
            $user_setting->notification_active = $request['notification_active'];
            $user_setting->save();
            return response()->json(['data' => $user_setting,'message' => 'User Setting update successfully.','status' => true]);
        }
    }

    public function getUserSetting(Request $request){
        $userId = Auth::user()->id;
        $user_setting = UserSetting::where('user_id', $userId)->first();
        return response()->json(['data' => $user_setting,'message' => 'User Setting get successfully.','status' => true]);
    }
}
