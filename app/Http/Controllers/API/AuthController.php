<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Validator;
use App\Models\User;
use App\Models\Address;
use App\Models\Customer;
use App\Models\Merchant;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Mail\VerificationMail;
use Carbon\Carbon;
use Dirape\Token\Token;
use Log;
use DB;

class AuthController extends Controller
{
    /**
     * Validations for create user
     */
    private $create_login_rules = [ 
        'email'         => 'required|email',
        'password'      => 'required',
        'device_token'  => 'required',
        'device_type'   => 'required',
    ];

        /**
     * Validations for create user
     */
    private $create_user_rules = [ 
        'name'          => 'required',
        'email'         => 'required|email|unique:users,email',
        'password'      => 'required|min:6',
        'device_token'  => 'required',
        'device_type'   => 'required',
        'user_type'     => 'required',
        
    ];

    /**
     * Validations for forgot-password
    */
    private $create_forgot_rules = [ 
    
        'email'         => 'required|email',
    ];

    /**
     * Validations for forgot-password
    */
    private $create_changepassword_rules = [ 
    
        'new_password'      => 'required|min:6',
        'confirm_password'  => 'required|same:new_password',
        'reset_token'       => 'required',
    ];

    /**
     * Validations for forgot-password
    */
    private $create_verifyOTP_rules = [ 
    
        'email' => "required|email",
        'otp'   => "required",
    ];

    /**
     * Validations for create user
     */
    private $create_change_number_rules = [ 
        'phone_number'  => 'required|unique:users,phone_number',
        
    ];

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), $this->create_login_rules);
     
        if ($validator->fails()) { 
            $message = $validator->errors()->first();
            return response()->json([
            'message' => $message,
        ], 400);
        }

        $user = User::where('email','=',$request->email)->first();
        if ($user) {
            if (Hash::check($request->password, $user->password)) {
                if ($user->is_admin == 1) {

                    auth()->logout();
                    session()->flush();

                    return $this->responseError("Unauthorize access", 400);
                }
                if ($user->is_active == 0) {

                    auth()->logout();
                    session()->flush();

                    return $this->responseError(trans('Sorry, your account is not verified.'), 400);
                }
                if ($user->is_active == 2) {

                    auth()->logout();
                    session()->flush();

                    return $this->responseError(trans('Sorry, your account has been inactive'), 400);

                } 
                if ($user->is_active == 3) {

                    auth()->logout();
                    session()->flush();

                    return $this->responseError(trans('Sorry, your account has been Suspended by admin'), 400);

                }

                DB::table('oauth_access_tokens')
                    ->where('user_id', $user->id)
                    ->update([
                        'revoked' => true
                    ]);

                /* check device_token*/
                $check_token = User::where('device_token', $request->device_token)->get();
                if ($check_token->isEmpty()) {
                    $accessToken = $user->createToken('authToken')->accessToken;
                    $user->api_token    = $accessToken;
                    $user->device_token = $request->device_token;
                    $user->updated_at   = time();
                    $user->save();
                } else {
                    foreach ($check_token as $key => $check_tokens) {
                        $check_tokens->device_token = null;
                        $check_tokens->save();
                    }
                    $accessToken = $user->createToken('authToken')->accessToken;
                    $user->api_token    = $accessToken;
                    $user->device_token = $request->device_token;
                    $user->updated_at   = time();
                    $user->save();
                }
                
                if($request->user_type == "1"){
                    $customer = Customer::where('user_id',$user->id)->first();
                    return response(['user' => $user,'customer' => $customer, 'access_token' => $accessToken]);
                }elseif ($request->user_type == "2") {
                    $shop = Merchant::where('id', $user->id)->first();
                    return response(['user' => $user,'shop' => $shop, 'access_token' => $accessToken]);
                } else{
                    auth()->logout();
                    session()->flush();

                    return $this->responseError(trans('Sorry, Some thing went wrong!!'), 500);
                }
                

                
            } else {
                return response(['message' => 'Your Password is Incorrect.'], 400);
            }
        } else {
            return response(['message' => 'The provided Email do not match our records.'], 400);
        }
        
    }

    /**
     * Register User
     */
    public function register(Request $request) {

        $validator = Validator::make($request->all(), $this->create_user_rules);
     
        if ($validator->fails()) { 
            $message = $validator->errors();
            return response()->json([
                'message' => $message,
            ], 400);
        }
        
        $user = $this->createUser($request);
        $accessToken = $user->createToken('authToken')->accessToken;
        $responseData = [
            'token' => $accessToken,
        ];
        return response()->json([
            'message' => 'Verification of your DeliveryKirana account has been sent to your email address. Please verify to proceed.',
        ], 200);
    }
    /**
     * Get user inputs
     */
    public function createUser($request) {
    
    /* check device_token*/
        $check_token = User::where('device_token', $request->device_token)->get();
        if ($check_token->isEmpty()) {
            $user = new User();
            $user->user_type    = $request->user_type;
            $user->is_active    = 0;
            $user->email        = $request->email;
            $user->password     = Hash::make($request->password);
            $user->name         = $request->name;
            $user->phone_number = $request->phone_number;
            $user->device_token = $request->device_token;
            $user->device_type  = $request->device_type;
            $user->save();
            // inserting user-details in user profile table..
            if ($request->user_type == 1) {
                $new_users_profile = new Customer();
                $new_users_profile->user_id = $user->id;
                $new_users_profile->name    = $request->name;
                $new_users_profile->email   = $request->email;
                $new_users_profile->save();
            } else if($request->user_type == 2){
                $new_users_profile = new Merchant();
                $new_users_profile->user_id         = $user->id;
                $new_users_profile->merchant_id     = 'MER-' . $user->id;
                $new_users_profile->name            = $request->name;
                $new_users_profile->email           = $request->email;
                $new_users_profile->shop_name       = $request->shop_name;
                $new_users_profile->shop_address    = $request->shop_address;
                $new_users_profile->latitude        = $request->latitude;
                $new_users_profile->longitude       = $request->longitude;
                $new_users_profile->save();
            }else{
                return response(['message' => 'The User Type is invalid.'], 400);
            }
            
            $accessToken = $user->createToken('authToken')->accessToken;

            $mailData = array('title' => 'Delivery Verification', 'name' => $request->get('name'), 'email' => $request->get('email'),  'url' => $user->id , 'token' => $accessToken);    
            Mail::to($request->get('email'))->send(new VerificationMail($mailData));
            return $user;
        } else {
            foreach ($check_token as $key => $check_tokens) {
                    $check_tokens->device_token = null;
                    $check_tokens->save();
                }
                $user = new User();
                $user->user_type    = $request->user_type;
                $user->is_active    = 0;
                $user->email        = $request->email;
                $user->password     = Hash::make($request->password);
                $user->name         = $request->name;
                $user->phone_number = $request->phone_number;
                $user->device_token = $request->device_token;
                $user->device_type  = $request->device_type;
                $user->save();
            // inserting user-details in user profile table..
            if ($request->user_type == 1) {
                $new_users_profile = new Customer();
                $new_users_profile->user_id = $user->id;
                $new_users_profile->name    = $request->name;
                $new_users_profile->email   = $request->email;
                $new_users_profile->save();
            } else {
                $new_users_profile = new Merchant();
                $new_users_profile->user_id         = $user->id;
                $new_users_profile->merchant_id     = 'MER-' . $user->id;
                $new_users_profile->name            = $request->name;
                $new_users_profile->email           = $request->email;
                $new_users_profile->shop_name       = $request->shop_name;
                $new_users_profile->shop_address    = $request->shop_address;
                $new_users_profile->latitude        = $request->latitude;
                $new_users_profile->longitude       = $request->longitude;
                $new_users_profile->save();
            }
            $accessToken = $user->createToken('authToken')->accessToken;

            $mailData = array('title' => 'delivery Verification', 'name' => $request->get('name'), 'email' => $request->get('email'),  'url' => $user->id , 'token' => $accessToken);    
            Mail::to($request->get('email'))->send(new VerificationMail($mailData));
            return $user;
        }
        
    }

    /**
     * Log out
     */
    public function logout(Request $request)
    {
        /* remove device token of user*/

        $user_detail = User::find(Auth::user()->id);
        $user_detail->device_token = null;
        $user_detail->save();

        return response()->json([
                'message' => 'You have logged out successfully'
            ], 200);

        // $user = auth()->user();
        // $token = $user->token();
        // $token->revoke();

        //return $this->responseSuccess('You have logged out successfully');
    }
    /**
     * forgot_password
     */
    public function forgot_password(Request $request)
    {
        $validator = Validator::make($request->all(), $this->create_forgot_rules);
     
        if ($validator->fails()) { 
            $message = $validator->errors();
            return response()->json([
            'message' => $message,
        ], 400);
        }
        else {
            if(user::where('email',  $request->email)->exists())
            {
               $otp =  rand ( 1000 , 9999);
               $new = new PasswordReset();
               $new->email  = $request->email;
               $new->otp    = $otp;
               $new->save();
               
                $data['email']          = $request->email;
                $data['otp']            = $otp;
                $data['email_title']    = 'You are receiving this email because we received a password reset request for your account..';
               
                //mail goes to user
                Mail::to($data['email'])->send(new PasswordResetNotification($data));
                // check for failed ones
                if (Mail::failures()) {
                    // return failed mails
                    return response()->json([
                        'message' => Mail::failures(),
                    ], 400);
                }
                return response(['message' => 'Password Reset OTP Sent Successfully']);
            }else{
                return response()->json([
                        'message' => 'This Email is not exists',
                    ], 400);
            }
        }
    }
    /**
     * verifyOTP
     */
    public function verifyOTP(Request $request)
    {
        $validator = Validator::make($request->all(), $this->create_verifyOTP_rules);
     
        if ($validator->fails()) { 
            $message = $validator->errors();
            return response()->json([
            'message' => $message,
        ], 400);
        }
        else {
        $verify = PasswordReset::where('email', $request->email)->where('otp', $request->otp)->first();
        if(isset($verify))
        {
            $time = $verify->created_at;
            $carbon = Carbon::now();
            $diff_in_minutes = $carbon->diffInMinutes($time);
            if ($diff_in_minutes < 100){
                $token = (new Token())->Unique('users', 'api_token', 60);
                $user = User::where('email', $request->email)->first();
                $user->api_token = $token;
                $user->save();
                return response(['message' => 'Otp Matched', 'reset_token' => $token]);
        }else
            {
                return response(["message" => 'Your OTP is Expired'], 400);
            }
        }else{
            return response(["message" => 'Entered OTP is not matched'], 400);
        }
    }
    }
    /**
     * changePasswordWithToken
     */
    public function changePasswordWithToken(Request $request)
    {
        $validator = Validator::make($request->all(), $this->create_changepassword_rules);
     
        if ($validator->fails()) { 
            $message = $validator->errors();
            return response()->json([
            'message' => $message,
        ], 400);
        }
        
            $details = User::where('api_token', $request->reset_token)->first();
            $details->password =  Hash::make($request->new_password);
            $details->save();
       
        return response(['message' => 'Password Change Successfully']);
    }

    /**
     * Verifying Mobile OTP.....
     */
    public function verifyMobileOTP(Request $request)
    {
        $users = User::where('phone_number',$request->phone_number)->first();
        if ($users == null) {
            return $this->responseError(trans('message.Sorry, This number is not matched in our records.'), 400);
        } else {
            $user = User::find($users->id);
            $user->mobile_verified = 1;
            $user->save();
            return response(['message' => 'Phone Number Successfully Verified']);
        }
        
    }

    /**
     * Change Mobile NUmber.....
     */
    public function changeMobileNumber(Request $request)
    {   
        $validator = Validator::make($request->all(), $this->create_change_number_rules);
     
        if ($validator->fails()) { 
            $message = $validator->errors();
            return response()->json([
            'message' => $message,
        ], 400);
        }

        $users = User::where('email',$request->email)->first();
        if ($users == null) {
            return $this->responseError(trans('message.Sorry, This email is not matched in our records.'), 400);
        } else {
            $user = User::find($users->id);
            $user->phone_number     = $request->phone_number;
            $user->mobile_verified  = 0;
            $user->save();
            return response(['message' => 'Phone Number Successfully Changed']);
        }
        
    }

    public function userProfile(Request $request)
    {
        $data['profile']        = User::where('id', Auth::user()->id)->first();
        $data['user_profile']   = UserProfile::where('user_id', Auth::user()->id)->first();

        $data['address']    = Address::where('id', $data['user_profile']->address_id)->first();
        $data['city']       = City::where('id', $data['user_profile']->city_id)->first();
        $data['state']      = State::where('id', $data['user_profile']->state_id)->first();
        $data['country']    = Country::where('id', $data['user_profile']->country_id)->first();

             return response()->json([
                  'data' => $data
             ], 200);
    }

    public function updateUserProfile(Request $request)
    {
        if($request->hasFile('profile_pic')){
            $file_name  =  $request->file('profile_pic')->getClientOriginalName();
            $public_path = public_path() . '/images/profile_img';
            $path = $request->profile_pic->move($public_path ,$file_name);
            $filename = basename($path);
        }
        
        $user_profile = UserProfile::where('user_id', Auth::user()->id)->first();
        if($user_profile->address_id == null) {
            $address               = new Address();
            $address->address1     = $request->address;
            $address->postal_code  = $request->post_code;
            $address->save();
        }
        else{
            $address               = Address::find($user_profile->address_id);
            $address->address1     = ($request->get('address')?$request->get('address'):$address->address1);
            $address->postal_code  = ($request->get('post_code')?$request->get('post_code'):$address->postal_code);
            $address->save();
        }
        

        try{
            $user = User::find(Auth::user()->id);
            $user->name = ($request->get('name')?$request->get('name'):$user->name);
            $user->phone_number = ($request->get('phone_number')?$request->get('phone_number'):$user->phone_number);
            $user->dob = ($request->get('dob')?$request->get('dob'):$user->dob);
            // $user->email = $request->get('email');
            $user->save();
            $user_profile = UserProfile::where('user_id', Auth::user()->id)->first();
            $user_profile->full_name = ($request->get('name')?$request->get('name'):$user_profile->full_name);
            // $user_profile->email = $user->email;
            $user_profile->age = ($request->get('age')?$request->get('age'):$user_profile->age);
            $user_profile->gender_id = ($request->get('gender')?$request->get('gender'):$user_profile->gender_id);
            if($request->hasFile('profile_pic')){
             $user_profile->profile_pic = $filename;
           }
            $user_profile->address_id = $address->id;
            $user_profile->mobile = ($request->get('phone_number')?$request->get('phone_number'):$user_profile->mobile);
            $user_profile->country_id = ($request->get('country')?$request->get('country'):$user_profile->country_id);
            $user_profile->state_id = ($request->get('state')?$request->get('state'):$user_profile->state_id);
            $user_profile->city_id = ($request->get('city')?$request->get('city'):$user_profile->city_id);
            $user_profile->blood_group = ($request->get('blood_group')?$request->get('blood_group'):$user_profile->blood_group);
            $user_profile->postal_address_id = ($request->get('post_code')?$request->get('post_code'):$user_profile->postal_address_id);
            $user_profile->save();

            $user_details = User::find(Auth::user()->id);

             return response()->json([
                  'message' => 'Profile Updated Successfully!!',
                  'user'    => $user_details
             ], 200);
        }catch(\Exception $ex){
            $message = $ex->getMessage();
            return response()->json([
                'data' => $message
            ], 400);
        }
    }

    public function userChangePassword(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'old_password' => 'sometimes',
            'new_password' => 'required',
            'password_confirmation' => 'required',
        ]);
        
           if ($validator->fails())
           {
                $errors = [];
                foreach ($validator->messages()->all() as $error)
                {
                    array_push($errors, $error);
                }

                return response()->json([
                    'message' => $errors,
                ], 400);
            }
        
        $old_password = $request->get('old_password');
        $new_password = $request->get('new_password');
        $password_confirmation = $request->get('password_confirmation');

        if($new_password != $password_confirmation){
            return response()->json([
                'message' => 'Confirm Password did not match with new password!'
            ], 400);
        }
        
        try {
            $user = User::find(Auth::user()->id);
        } catch(\Exception $e){
            $message = $e->getMessage();
            return response()->json([
                'data' => $message
            ], 400);
        }

        if(Hash::check($old_password,$user->password) == false){
            return response()->json([
                'message' => 'Old Password did not matched'
            ], 400);
        }

        $user->password = Hash::make($new_password);
        if ($user->save()) {
            return response()->json([
                  'message' => 'Password successfully changed..!!'
             ], 200);
        } else {
            return response()->json([
                'message' => 'Password incorrect'
            ], 400);
        }
    }

    public function responseFail($message, $data = null)
    {
        return response()->json([
            'message' => $message,
            'data'    => $data
        ], 400);
    }

    public function responseError($message = null, $errorCode = 400)
    {
        return response(['message' => $message ], 400);
    }
}
