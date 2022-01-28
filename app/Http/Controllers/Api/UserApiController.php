<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\PasswordReset;
use App\Notifications\PasswordResetRequest;
use App\Notifications\PasswordResetSuccess;
use App\Notifications\UserRegister;
use App\PasswordReset as AppPasswordReset;
use App\Role;
use App\User;
use App\Property;
use App\ForgetOtp;
use App\BillingAddress;
use App\UserDetail;
use App\UserSignature;
use App\userDetails;
use App\B2BEnquiry;
use Carbon\Carbon;
use Validator;
use Str;
use App\Setting;
use App\UserVerifyToken;
use App\MailTemplate;
use App\Mail\Signup;
use App\Newsletter as Chimp;
use DrewM\MailChimp\MailChimp;
use Newsletter;
use Config;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use App\PhoneTemp;
use App\UserCompany;
use Twilio\Rest\Client;
use DB;
use Image as Img;
use App\Notifications;
use App\Rules\PasswordRules;

class UserApiController extends Controller
{
    public function __construct(Request $request)
    {

        //dd($request->api_key);

        $apitoken = $request->header('api_key');

        if (empty($apitoken)) {
            $response = json_encode(array(
                'status' => false,
                'message' => 'Please Provide Api Token',
            ));
            header("Content-Type: application/json");
            echo $response;
            exit;
        }
        if ($apitoken != env("api_key")) {
            $response = json_encode(array(
                'status' => false,
                'message' => 'Api Token Not valid',
            ));
            header("Content-Type: application/json");
            echo $response;
            exit;
        }
    }



    public $successStatus = 200;
    public function sendOTP(Request $req)
    {

        $validator = Validator::make(
            $req->all(),
            ['phone' => 'required']
        );
        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => implode("", $validator->errors()->all()),
            ], 200);
        }

        $user_mobile_exits = User::where("phone", $req->phone)->first();

        if(!empty($user_mobile_exits)){

            return response()->json([
                'status' => false, 'message' => "Mobile number already registered", 'data' => Null
            ], 200);

        }

        try {


        $sid = env("TWILIO_SID");
        $token = env("TWILIO_TOKEN");
        $from = env("TWILIO_FROM");

        $twilio = new Client($sid, $token);

         $verification = $twilio->verify->v2->services("VA6a865598f21b1f8f5cdfc331c37fdce6")
                                   ->verifications
                                   ->create($req->phone, "sms", 
                        ["statusCallback" => "https://champagne-otter-3626.twil.io/status-callback"]);

        return response()->json([
            'status' => true, 'message' =>" OTP successfully send to user registered mobile number", 'data' => Null
        ], 200);
       

        } catch (\Twilio\Exceptions\RestException $exception) {

            return response()->json([
            'status' => false, 'message' =>"Enterd mobile number is invalid", 'data' => Null
                ], 200);
        }

       

        
    }
    public function verifyOTP(Request $req)
    {
        $validator = Validator::make($req->all(), [
           // 'id' => 'required',
            'otp' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => false, 'message' => implode("", $validator->errors()->all())], 200);
        }

        try{

            $sid = env("TWILIO_SID");
            $token = env("TWILIO_TOKEN");
            $from = env("TWILIO_FROM");


            $twilio = new Client($sid, $token);

            $verification_check = $twilio->verify->v2->services("VA6a865598f21b1f8f5cdfc331c37fdce6")
                                             ->verificationChecks
                                             ->create($req->otp, // code
                                                      ["to" => $req->phone],
                                                       ["statusCallback" => "https://champagne-otter-3626.twil.io/status-callback"]
                                             );
            // dd($verification_check->status);
            // exit;


         if($verification_check->status == 'approved'){

             return response()->json([
                'status' => true, 
                'message' => " Your mobile number verified successfully", 'data' => Null
            ], 200);
         }
         else{

            return response()->json([
                'status' => false, 
                'message' => " Invalid OTP, Please enter valid OTP or Resend OTP", 'data' => Null
            ], 200);

         }

        }catch(\Twilio\Exceptions\RestException $e){

           return response()->json([
                'status' => false, 
                'message' => " Invalid OTP, Please enter valid OTP or Resend OTP", 'data' => Null
            ], 200);


        }


    }


    public function userInfo(Request $req)
    {
        $req->identity_info = json_encode($req->identity_info);
        $validator = Validator::make($req->all(), [
            'phone' => 'required',
            'entity' => 'required',
            'entity_type' => 'required',
            'indentity_type' => 'required',
            'identity_info' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json(['status' => false, 'message' => implode("", $validator->errors()->all())], 200);
        }
        // $ph=PhoneTemp::find($req->phone_verified_id);
        // if(empty($ph)){
        //  return response()->json(['status'=>false,'message'=>"Invalid phone verified id"],200);
        // }
        $user = new UserCompany;
        $user->phone = $req->phone;
        $user->region = $req->region;
        $user->entity = $req->entity;
        $user->entity_type = $req->entity_type;
        $user->company_name = $req->company_name;
        $user->address = $req->company_address;
        $user->indentity_type = $req->identity_type;
        $user->identity_info = json_encode($req->identity_info);
        $user->save();
        if ($user) {
            return response()->json(['status' => true, "message" => "added successfully"], 200);
        }
    }
    public function unsetdate(Object $pt)
    {
        unset($pt->created_at);
        unset($pt->updated_at);
        unset($pt->deleted_at);
    }
    public function login(Request $req)
    {

        $errorCode =  Config::get('constants.code.error');
        $succcessCode =  Config::get('constants.code.success');

        $validator = Validator::make($req->all(), [
            'email' => 'required',
            'password' => ['required'],
        ]);
        if ($validator->fails()) {
            return response()->json(['status' => false,'code'=>$succcessCode, 'message' => implode("", $validator->errors()->all())], 200);
        }

        $user = User::where('email', '=', $req->email)->first();

        if (!isset($user->email)) {

              return response()->json(['status' => false,'code'=>$succcessCode, 'message' => 'Your given email is not registered', 'user' => Null], 200);
        }

        if(!empty($user)){

            if($user->active_status == "active"){


                if (!empty($user->password) || $user->password != null) {

                    if(Auth::attempt(['email' => request('email'), 'password' => request('password')])){

                        $user = Auth::user();
                        $user_id = $user->id;

                        if($user->roles[0]->id == 4 || $user->roles[0]->title == 'Staff') {
                            // 
                            $user['id'] = $user->parent_id; 
                            foreach($user->roles as $key => $item){
                                $user['user_role_type'] = $item->title;
                            }
                            unset($user->roles);

                            $property = Property::where('user_id','=',$user->parent_id)->first();
                            $user['property_name'] = !empty($property->property_title) ? $property->property_title : '';
                            return response()->json(['status' => true,'code'=>$succcessCode, 'message' => "Your account logged in successfully", 'user' => $user], 200);
                        }
                        if($user->remember_token != null){

                            if(isset($req->firebase_token)){
                                $location = DB::table('user_device_token')->insert([
                                    'device_token'              => $req->firebase_token,
                                    'user_id'                   => $user_id,
                                    'platform_type'             =>  $req->platform_type,
                                    'device_id'                 =>  $req->device_id,
                                ]);
                            }

                            foreach($user->roles as $key => $item){
                                $user['user_role_type'] = $item->title;
                            }
                            unset($user->roles);

                            //get user as landlord propety name

                            $property = Property::where('user_id','=',$user_id)->first();
                            $user['property_name'] = !empty($property->property_title) ? $property->property_title : '';


                            return response()->json(['status' => true,'code'=>$succcessCode, 'message' => "Your account logged in successfully", 'user' => $user], 200);
                        }
                        else {

                            return response()->json(['status' => false,'code'=>$errorCode, 'message' => "Email not verified", 'user' => null], 200);

                        }

                    }
                    else{

                        return response()->json(['status' => false,'code'=>$succcessCode, 'message' => ' Invalid login credentails..!, Please enter valid details', 'user' => Null], 200);
                    }

                } else {
                    return response()->json(['status' => false,'code'=>$succcessCode, 'message' => 'Social login', 'user' => Null], 200);
                }

            }
            else{

                 return response()->json(['status' => false, 'message' => "The email has already been registred. currently in inactive mode.to active your account mail on  customerservices@office-share.io", 'user' => Null], 200);
            }


        }

    }


    
    public function verifyUserByToken(Request $req)
    {

        $errorCode =  Config::get('constants.code.error');
        $succcessCode =  Config::get('constants.code.success');

        $validator = Validator::make($req->all(), [
            'token' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json(['status' => false,'code'=>$succcessCode, 'message' => implode("", $validator->errors()->all())], 200);
        }

        $user = User::where('register_token', '=', $req->token)->first();
        $user_id = $user->id;

        User::where('id', $user_id)->update([

            'remember_token' =>Str::random(40)
        ]);

        $user = User::where('id', $user_id)->first();

        if(!empty($user)){

            if($user->active_status == "active") {


                // if (!empty($user->password) || $user->password != null) {

                    // if(Auth::attempt(['email' => request('email'), 'password' => request('password')])) {

                        // $user = Auth::user();
                        

                        

                        if($user->remember_token != null){

                            User::where('id', $user_id)->update([

                                'register_token' => null
                            ]);

                            if(isset($req->firebase_token)) {
                                $location = DB::table('user_device_token')->insert([
                                    'device_token'              => $req->firebase_token,
                                    'user_id'                   => $user_id,
                                    'platform_type'             =>  $req->platform_type,
                                    'device_id'                 =>  $req->device_id,
                                ]);
                            }

                            foreach($user->roles as $key => $item){
                                $user['user_role_type'] = $item->title;
                            }
                            unset($user->roles);

                            //get user as landlord propety name

                            $property = Property::where('user_id','=',$user_id)->first();
                            $user['property_name'] = !empty($property->property_title) ? $property->property_title : '';


                            return response()->json(['status' => true,'code'=>$succcessCode, 'message' => "Your account logged in successfully", 'user' => $user], 200);
                     }
                     else{

                        return response()->json(['status' => false,'code'=>$errorCode, 'message' => "Email not verified", 'user' => null], 200);

                     }

                    // }
                    // else{

                    //     return response()->json(['status' => false,'code'=>$succcessCode, 'message' => ' Invalid login credentails..!, Please enter valid details', 'user' => Null], 200);
                    // }

                // } 

            }
            else{

                 return response()->json(['status' => false, 'message' => "The email has already been registred. currently in inactive mode.to active your account mail on  customerservices@office-share.io", 'user' => Null], 200);
            }


        }

    }
    /**
     * Register api
     *
     * @return \Illuminate\Http\Response
     */
    public function register(Request $request)
    {
      

        $validator = Validator::make($request->all(), [
            'first_name' => 'required',
            'last_name' => 'required',
            'dob' => 'required',
            // 'email' => 'required|email|unique:users',
            'email' => 'required',
            'domestic_postcode' => 'required',
            'password' => ['required',  new PasswordRules()],
            'region' => 'required',
            'phone' => 'required',
            'newsletter' => 'required',
            'social_type' => 'required',
            'social_id' => 'required',
            // 'newsletter'=>'required'
        ]);


        $newsletter = $request->newsletter;


        if ($validator->fails()) {
            $er = [];
            $i = 0;
            foreach ($validator->errors() as $err) {
                $er[$i++] = $err[0];
                return $err;
            }
         
             return response()->json(['status' => false, 'message' => implode("", $validator->errors()->all()), 'user' => Null], 200);
        }

        $us = User::where("email", $request->email)->first();


        if (isset($us)) {
            if($us->active_status == "active"){

                return response()->json(['status' => false, 'message' => "The email has already been taken.", 'user' => Null], 200);

            }
            else{

                return response()->json(['status' => false, 'message' => "The email has already been registred. currently in inactive mode.to active your account mail on customerservices@office-share.io", 'user' => Null], 200);

            }
            
        } else {

            // create stripe customer 

            $stripe = new \Stripe\StripeClient(
                'sk_test_51IyI6sEUI2VlKHRnMonCU5R8jWGutknpkAwcG5T513pHaEWxycYaDngoP7DKjRB5zKnAdSqTe1VURZhHNhcQX1yJ00gRqMhj8H'
            );
            $customer = $stripe->customers->create([
                'description' => 'My First Test Customer (created for API docs)',
                'email' => $request->email,
                'name' => $request->first_name.' '.$request->last_name
            ]);

            $customer_id = $customer->id;


            $input = $request->toArray();
            //$pass = $input['password'];

            // $user = User::create($input);
            $user = new User;
            $user->name = $request->name;
            $user->first_name = $request->first_name;
            $user->last_name = $request->last_name;
            $user->name = $request->first_name.' '.$request->last_name;
            if($request->password){
                 $pass = Hash::make($input['password']);
                $user->password = $pass;
            }
            $user->email = $request->email;
            $user->phone = $request->phone;
            $user->dob = $request->dob;
            $user->customer_id = $customer_id;
            $user->domestic_postcode = $request->domestic_postcode;
            $user->region = $request->region;
            $user->social_type = $request->social_type;
            $user->profile_pic = 'http://officeshare-cms.ewtlive.in/user-profile/user-default.png';
            $user->social_id = $request->social_id;
            $user->newsletter = $request->newsletter;
            $user->ip_address = $request->ip();
            $user->save();
            $success['token'] =  $user->createToken('API Token')->accessToken;
            $success['name'] =  $user->name;

            $user->roles()->sync(2);

            $user_id = $user->id;

               $userr = User::where('id', '=', $user_id)
                        ->first();
                 foreach($userr->roles as $key => $item){
                                $userr['user_role_type'] = $item->title;
                            }
                  unset($userr->roles);

           //  $verifyUser = UserVerifyToken::create([
           //  'user_id' => $user_id,
           //  'token' => Str::random(40)
           //  ]);

           // $token =  $verifyUser->token;

            $m=$this->sendMessageToUser($user,$request->password);
 
                if($m['status']==true){
                    Mail::to($user->email)->send(new Signup($m));   
                }

           
        }
           

          return response()->json(['status' => true, 'message' => "Your account registerd successfully.", 'user' => $userr], 200);
    }

    public function socialLogin(Request $request){

         $errorCode =  Config::get('constants.code.error');
        $succcessCode =  Config::get('constants.code.success');

        $validator = Validator::make($request->all(), [
            'social_id' => 'required',
            'social_type' => 'required'
        ]);

        if ($validator->fails()) {
            $er = [];
            $i = 0;
            foreach ($validator->errors() as $err) {
                $er[$i++] = $err[0];
                return $err;
            }
         
             return response()->json(['status' => false,'code'=>$succcessCode, 'message' => implode("", $validator->errors()->all()), 'user' => Null], 200);
        }

        $user = User::where('social_id', '=', $request->social_id)
                        ->where('social_type','=',$request->social_type)
                        ->where('social_type','!=','manual')
                        ->first();

        if(!empty($user)){

         if($user->active_status == "inactive"){

            return response()->json(['status' => false, 'message' => "The email has already been registred. currently in inactive mode.to active your account mail on  customerservices@office-share.io", 'user' => Null], 200);

        }
        else{

             if($user->remember_token != null){

                        if(isset($request->firebase_token)){

                           $location = DB::table('user_device_token')->insert([
                            'device_token'           => $request->firebase_token,
                            'user_id'               => $user->id,
                             'platform_type'         =>  $request->platform_type,
                             'device_id'                 =>  $request->device_id,
                            ]);

                        }

                foreach($user->roles as $key => $item){
                    $user['user_role_type'] = $item->title;
                }
                unset($user->roles);

                        return response()->json(['status' => true, 'code'=>$succcessCode, 'message' => " Your account login with ".$request->social_type." sucessfully", 'user' => $user], 200);

                 }
               else{

                        return response()->json(["status" => false,'code'=>$errorCode,'message' => "Email not verified",'user' => null], 200);  
                }
        }

               

        }
        else{

        return response()->json(["status" => false,'code'=>$succcessCode,'message' => "user not found",'user' => null], 200);

        }

    }


        public function changepassword(Request $request){

            $validator = Validator::make($request->all(), [
            'user_id'           => 'required',
            'current_password'  => 'required',
            'change_password'   => 'required|min:8'
            ]);

            if ($validator->fails()) {
                $er = [];
                $i = 0;
                foreach ($validator->errors() as $err) {
                    $er[$i++] = $err[0];
                    return $err;
                }

                return response()->json(['status' => false, 'message' => implode("", $validator->errors()->all()), 'user' => Null], 200);
            }

            $pass =   Hash::make($request->change_password);

            $user = User::where('id', '=', $request->user_id)
            ->first();

            $check_pass = Hash::check($pass, $user->password);

            if($user->active_status == 'active'){

                if (!empty($user->password)) {

                        if(Auth::attempt(['id' => request('user_id'), 'password' => request('current_password')])){

                            User::where('id', '=', $request->user_id)
                            ->update(['password'=> $pass]);

                            return response()->json(['status' => true, 'message' => "Your password changed successfully"], 200);

                        }
                        else{
                            return response()->json(['status' => false, 'message' => "Your given Current password is wrong"], 200); 
                        }


                }else{

                    return response()->json(["status" => false,'message' => "Social Login"], 200);

                }


            }
            else{
                return response()->json(['status' => false, 'message' => "This account is currently in inactive mode.to active your account mail on  customerservices@office-share.io", 'user' => Null], 200);

            }

        }

        public function sendforgetotp(Request $request){

                $validator = Validator::make($request->all(), [
                    'mobile_email'           => 'required'
                ]);

                if ($validator->fails()) {
                    $er = [];
                    $i = 0;
                    foreach ($validator->errors() as $err) {
                        $er[$i++] = $err[0];
                        return $err;
                    }

                    return response()->json(['status' => false, 'message' => implode("", $validator->errors()->all()), 'user' => Null], 200);
                }

                $user = User::where('email', '=', $request->mobile_email)
                ->orWhere('phone', '=', $request->mobile_email)
                ->first();

                if($user->active_status == "active"){

                    if(!empty($user->email) && $user->remember_token !=null){

                        $otp = mt_rand(100000, 999999);

                        $userotp = ForgetOtp::create([

                        'user_id' => $user->id,
                        'otp' => $otp,

                        ]);

                        //send sms

                        $sid = env("TWILIO_SID");
                        $token = env("TWILIO_TOKEN");
                        $from = env("TWILIO_FROM");

                        $twilio = new Client($sid, $token);

                        $message = $twilio->messages
                        ->create($user->phone, // to
                        ["body" => $otp." is your code to reset your OfficeShare password. Don't reply to this message with your code.", "from" => $from]
                        );


                        // send mail

                        $m=$this->forgetPasswordEmail($user,$otp);

                        if($m['status']==true){
                            Mail::to($user->email)->send(new Signup($m));   
                        }

                        return response()->json(['status' => true, 'message' => "otp send succcessfully", 'otp' => $userotp], 200);

                    }
                    else{

                        return response()->json(['status' => false, 'message' => "user not registered or email not verified", 'otp' =>null], 200);

                    }

                }
                else{

                    return response()->json(['status' => false, 'message' => "The email has already been registred. currently in inactive mode.to active your account mail on  customerservices@office-share.io", 'user' => Null], 200);
                }
               
        }

    public function verifyforgetotp(Request $request){

          $validator = Validator::make($request->all(), [
            'otp'                   => 'required',
            'user_id'                 => 'required'
            ]);

        if ($validator->fails()) {
            $er = [];
            $i = 0;
            foreach ($validator->errors() as $err) {
                $er[$i++] = $err[0];
                return $err;
            }
         
             return response()->json(['status' => false, 'message' => implode("", $validator->errors()->all()), 'user' => Null], 200);
        }

       $userotp = ForgetOtp::where('otp','=',$request->otp)
                            ->where('user_id','=',$request->user_id)
                                ->first();

        if(!empty($userotp)){

         return response()->json(['status' => true, 'message' => "OTP verified"], 200);


        }
        else{

          return response()->json(['status' => false, 'message' => "Invalid otp"], 200);


        }



    }

    public function forgetpassword(Request $request){


         $validator = Validator::make($request->all(), [
            'mobile_email'                 => 'required',
            'password'            => ['required',  new PasswordRules()],
            ]);

        if ($validator->fails()) {
            $er = [];
            $i = 0;
            foreach ($validator->errors() as $err) {
                $er[$i++] = $err[0];
                return $err;
            }
         
             return response()->json(['status' => false, 'message' => implode("", $validator->errors()->all()), 'user' => Null], 200);
        }

         $user = User::where('email', '=', $request->mobile_email)
                    ->orWhere('phone', '=', $request->mobile_email)
                        ->first();


        $hash =Hash::make($request->password);

            if(!empty($user)){

                if($user->active_status ==  'active'){
                    User::where('id', '=', $user->id)
                        ->update([

                            'password' => $hash
                        ]);
                return response()->json(['status' => true, 'message' => "Your Password Updated Successfully", 'user' =>$user], 200);

                }
                else{

                return response()->json(['status' => false, 'message' => "The email has already been registred. currently in inactive mode.to active your account mail on  customerservices@office-share.io", 'user' => Null], 200);   

                }

            }else{

                return response()->json(['status' => true, 'message' => "user not found", 'user' => Null], 200);

            }

    }



    public function myaccount(Request $request){

         $validator = Validator::make($request->all(), [
            'user_id' => 'required'
        ]);

        $user = User::where('id', '=', $request->user_id)->first();

        if(!empty($user)){

            return response()->json(['status' => true, 'message' => "success", 'user' => $user], 200);

        }else{

              return response()->json(['status' => false, 'message' => "success", 'user' => Null], 200);

        }

    }

    public function useronlinestatus(Request $request){

          $validator = Validator::make($request->all(), [
           'user_id'            => 'required',
           'online_status'      => 'required'

            ]);

            if ($validator->fails()) {
             return response()->json(['status' => false, 'message' => implode("", $validator->errors()->all())], 200);
         }

          $online_status = 0;

          if($request->online_status == "true"){

            $online_status = 1;
          }else{

             $online_status = 0;
          }

       

         User::where('id','=',$request->user_id)->update([

            'online_status' => $online_status

         ]);

         $user =  User::where('id','=',$request->user_id)->first();

         if($user->online_status == 1){
            $user['online_status'] = true;
         }else{
             $user['online_status'] = false;

         }

        return response()->json(['status' => true, 'message' => "success"], 200);


    }
    public function getonlinestatus(Request $request){

         $validator = Validator::make($request->all(), [
           'property_id'            => 'required'
            ]);

            if ($validator->fails()) {
             return response()->json(['status' => false, 'message' => implode("", $validator->errors()->all())], 200);
         }

        $property = Property::where('id','=',$request->property_id)->first();

        if(!empty($property)){

            $user = User::where('id','=',$property->user_id)->first();

            if(!empty($user)){

                if($user->online_status == 1){

                     return response()->json(['status' => true, 'online_status'=>true, 'message' => "success",'user'=>$user], 200);

                }
                else{

                    return response()->json(['status' => false, 'online_status'=> false, 'message' => "unsuccess",'user'=>$user], 200);

                }

            }
            else{

                 return response()->json(['status' => true, 'online_status'=> false, 'message' => "user not found"], 200);

            }
        }
        else{

          return response()->json(['status' => false, 'online_status'=> false, 'message' => "property not found"], 200);  

        }
    }

   


     function sendMessageToUser(User $user, $token)
    {
    
        try {
            $st = Setting::where('options','=','site_url')->first();
            $st1 = Setting::first();

            $token = uniqid();
            $user = User::updateOrCreate(['id' => $user->id], [
                'register_token' => $token,
            ]);

            $sign = [
                '{first_name}' => $user->first_name,
                '{email}' => $user->email,
                '{token}' => 'http://officeshare.eoxysitsolution.com/#/verify/'.$token,
                // '{password}' => $pass,
                 '{site_url}' => $st->value,
                 '{business_name}' => $st1->value
            ];
            $msgData = MailTemplate::where('status', trim('signup'))->first();
            $replMsg = MailTemplate::where('status', trim('signup'))->pluck('message')->first();

            

            foreach ($sign as $key => $value) {
                $replMsg = str_replace($key, $value, $replMsg);
            }
            if (isset($msgData)) {
                return ['fromemail' => $msgData->from_email, "replyemail" => $msgData->reply_email, 'msg' => $replMsg, 'subject' => $msgData->subject, 'name' => $msgData->name, 'status' => true];
            } else {
                return ['status' => false];
            }
        } catch (Exception $e) {
        }
    }

    function forgetPasswordEmail($user,$otp){

         try {
            $st = Setting::where('options','=','site_url')->first();
            $st1 = Setting::first();
            $sign = [
                '{name}' => $user->name,
                '{otp}' => $otp,
            ];
            $msgData = MailTemplate::where('status', trim('forgot_password'))->first();
            $replMsg = MailTemplate::where('status', trim('forgot_password'))->pluck('message')->first();

            foreach ($sign as $key => $value) {
                $replMsg = str_replace($key, $value, $replMsg);
            }
            if (isset($msgData)) {
                return ['fromemail' => $msgData->from_email, "replyemail" => $msgData->reply_email, 'msg' => $replMsg, 'subject' => $msgData->subject, 'name' => $msgData->name, 'status' => true];
            } else {
                return ['status' => false];
            }
        } catch (Exception $e) {
        }

    }

     function sendMessageToUser_bk(User $user, $pass)
    {
        try {
            $st = Setting::where('options','=','site_url')->first();
            $st1 = Setting::first();
            $sign = [
                '{name}' => $user->name,
                '{email}' => $user->email,
                // '{password}' => $pass,
                 '{site_url}' => $st->value,
                 '{business_name}' => $st1->value
            ];
            $msgData = MailTemplate::where('status', trim('signup'))->first();
            $replMsg = MailTemplate::where('status', trim('signup'))->pluck('message')->first();
            foreach ($sign as $key => $value) {
                $replMsg = str_replace($key, $value, $replMsg);
            }
            if (isset($msgData)) {
                return ['fromemail' => $msgData->from_email, "replyemail" => $msgData->reply_email, 'msg' => $replMsg, 'subject' => $msgData->subject, 'name' => $msgData->name, 'status' => true];
            } else {
                return ['status' => false];
            }
        } catch (Exception $e) {
        }
    }

   
    /**
     * details api
     *
     * @return \Illuminate\Http\Response
     */
    public function details()
    {
        $user = Auth::user();
        return response()->json(['success' => $user], $this->successStatus);
    }

    public function userDetails(Request $r)
    {
        if (Auth::guard('api')->check()) {
            $user = Auth::guard('api')->user();
            if ($user->id == $r->user_id) {
                if (isset($r)) {
                    $user = UserDetail::updateOrCreate(['id' => $r->id], [
                        'name' => $r->name,
                        'phone' => $r->phone,
                        'alternate_phone' => $r->alternate_phone,
                        'address' => $r->address,
                        'address2' => $r->address2,
                        'address_type' => $r->address_type,
                        'state' => $r->state,
                        'city' => $r->city,
                        'country' => $r->country,
                        'zip_code' => $r->zip_code,
                        'landmark' => $r->landmark,
                        'user_id' => $r->user_id,
                    ]);
                    return response()->json([
                        "user_detail" => $user,
                        'success' => [
                            'status' => true,
                            'msg' => "Address saved successfully"
                        ]
                    ], 200);
                }
            } else {
                return response()->json(['msg' => "Invalid user id", "status" => false], 200);
            }
        }
    }

    public function billingAddress(Request $r)
    {
        if (Auth::guard('api')->check()) {
            $user = Auth::guard('api')->user();
            if ($user->id == $r->user_id) {
                if (isset($r)) {
                    $user = BillingAddress::updateOrCreate(['id' => $r->id], [
                        'name' => $r->name,
                        'phone' => $r->phone,
                        'address' => $r->address,
                        'address2' => $r->address2,
                        'address_type' => $r->address_type,
                        'state' => $r->state,
                        'city' => $r->city,
                        'country' => $r->country,
                        'pincode' => $r->zip_code,
                        'landmark' => $r->landmark,
                        'user_id' => $r->user_id,
                    ]);
                    return response()->json([
                        "billingAddress" => $user,
                        'success' => [
                            'status' => true,
                            'msg' => "Address saved successfully"
                        ]
                    ], 200);
                }
            } else {
                return response()->json(['msg' => "Invalid user id", "status" => false], 200);
            }
        }
    }

    public function removeUserDetail($id)
    {
        $ud = UserDetail::findOrFail($id);
        $ud->delete();
        return response()->json("Address Removed", 200);
    }

    public function logout(Request $request)
    {
        $user = User::where('id', '=', $request->user_id)->first();



        if(!empty($user))
        {

               $location = DB::table('user_device_token')
               ->where('user_id','=',$request->user_id)
               ->where('device_token','=',$request->firebase_token)
               ->where('device_id','=',$request->device_id)
               ->delete();

            return response()->json([
                'status'  => true,
                'message' => 'Your account logout successfully
'
                
            ]);

        }
        else{

                return response()->json([
                'status'  => false,
                'message' => 'user not found'
                
                ]);

        }
    }

    // Password reset or forget
    public function create(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email',
        ]);

        $user = User::where('email', $request->email)->first();
        if (!$user) {
            return response()->json([
                'message'   => "We can't find a user with that e-mail address.",
                'status'    => false
            ], 200);
        }

        $passwordReset = AppPasswordReset::updateOrCreate(
            ['email' => $user->email],
            [
                'email' => $user->email,
                'token' => Str::random(60)
            ]
        );
        if ($user && $passwordReset) {
            $user->notify(
                new PasswordResetRequest($passwordReset->token)
            );
            return response()->json([
                'message' => 'We have e-mailed your password reset link!'
            ]);
        }
    }
    /**
     * Find token password reset
     *
     * @param  [string] $token
     * @return [string] message
     * @return [json] passwordReset object
     */
    public function find($token)
    {
        $passwordReset = AppPasswordReset::where('token', $token)
            ->first();
        if (!$passwordReset)
            return response()->json([
                'message' => 'This password reset token is invalid.'
            ], 404);
        if (Carbon::parse($passwordReset->updated_at)->addMinutes(720)->isPast()) {
            $passwordReset->delete();
            return response()->json([
                'message' => 'This password reset token is invalid. expire'
            ], 404);
        }
        $d['passwordReset'] = $passwordReset;
        return view("front.reset-password", $d);
        // return response()->json($passwordReset);
    }
    /**
     * Reset password
     *
     * @param  [string] email
     * @param  [string] password
     * @param  [string] password_confirmation
     * @param  [string] token
     * @return [string] message
     * @return [json] user object
     */
    public function passwordReset(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email',
            'password' => ['required',  new PasswordRules()],
            'cpassword' => 'required|string|same:password',
            'token' => 'required|string'
        ]);
        if ($validator->fails()) {
            //return $validator->errors()->all();
            session()->flash('error', implode("", $validator->errors()->all()));
            return back();
        }
        $passwordReset = AppPasswordReset::where([
            ['token', $request->token],
            ['email', $request->email]
        ])->first();
        if (!$passwordReset)
            return back()->with("msg", "This password reset token is invalid.");
        $user = User::where('email', $passwordReset->email)->first();
        if (!$user) {
            return back()->with("msg", "We can't find a user with that e-mail address.");
        }
        $user->password = bcrypt($request->password);
        $user->save();
        $passwordReset->delete();
        $user->notify(new PasswordResetSuccess($passwordReset));
        return back()->with("msg", "Your password changed successfully");
    }



    // generate OTP 
    public function generateOTP()
    {
        $otp = mt_rand(1000, 9999);
        return $otp;
    }

    // verifyOtp
    // public function verifyOtp(Request $request) {
    //     //
    //     $otp = trim($request->otp);
    //     $user_id = $request->user_id;

    //     /* DB::table('user_otp')
    //         ->where('user_id', '=', $user_id)
    //         ->where('otp', '=', $otp)
    //         ->first(); */

    //     $user = User::where('id', $user_id)
    //                 ->where('otp',$otp)
    //                 ->first();

    //     if (!$user) {
    //         return response()->json([
    //             'message'   =>  'This activation token is invalid.',
    //             'status'    => false,
    //         ], 404);
    //     }
    //     //return $user;

    //     $user->isVerified = true;
    //     $user->otp = null;
    //     $user->save();

    //     return response()->json([
    //         'message'   =>  'Your Otp is verified.',
    //         'status'    => true,
    //     ], 200);

    //     return $user;
    // }
    public function changeAccount(Request $r)
    {
        $user = User::findOrFail($r->id);
        $user->name = $r->name;
        $user->phone = $r->phone;
        $user->country = $r->country;
        if (!empty($r->password) && !empty($r->new_password)) {
            if (!Hash::check($r['password'], $user->password)) {
                return response()
                    ->json(['success' => ["msg" => 'Sorry your current password does not match our record', "status" => false]], 200);
            } else {
                $user->password = bcrypt($r->new_password);
            }
        }
        $user->update();
        return response()->json(['success' => ["status" => true, "msg" => "Account detail changed successfully"], "user" => $user], 200);
    }
    public function getUserDetail($id)
    {
        if (Auth::guard('api')->check()) {
            $user = Auth::guard('api')->user();
            if ($user->id == $id) {
                $ud = User::findOrFail($id)->userDetails;
                if (isset($ud)) {
                    return response()->json(['shipping_address' => $ud], 200);
                } else {
                    return response()->json(['shipping_address' => "", "msg" => 'shipping address not added', "status" => false], 200);
                }
            } else {
                return response()->json('Invalid Token', 200);
            }
        }
    }
    public function getBillingAddress($id)
    {
        if (Auth::guard('api')->check()) {
            $user = Auth::guard('api')->user();
            if ($user->id == $id) {
                $ud = User::findOrFail($id)->billingAddress;
                if (isset($ud)) {
                    return response()->json(['billingAddress' => $ud], 200);
                } else {
                    return response()->json(['billingAddress' => "", "msg" => 'Billing Address not added', "status" => false], 200);
                }
            } else {
                return response()->json('Invalid Token', 200);
            }
        }
    }
    public function newsletter(Request $r)
    {

        $chmp = Chimp::where("status", 1)->first();
        config([
            'newsletter.apiKey' => $chmp->api,
            'newsletter.lists.subscribers.id' => $chmp->audience_id
        ]);
        // Newsletter::delete($r->email);
        if (!Newsletter::isSubscribed($r->email)) {
            Newsletter::subscribePending($r->email);
            return response()->json(['success' => ['status' => true, 'msg' => "Thank's for subscribe"]]);
        } else {
            return response()->json(['error' => ['status' => false, 'msg' => "You have already subscribed"]]);
        }
    }

    public function getAccountDetail($id)
    {
        $ud = User::findOrFail($id);

        return response()->json([
            'user' => $ud,
            'profile_pic_url' => $ud->profile_pic != null ? url('') . '/' . $ud->profile_pic : ''
        ]);
    }
    public function uploadProfilePicture_bk(Request $r)
    {
        $validator = Validator::make($r->all(), [
            'image' => 'required|image:jpeg,png,jpg,gif,svg|max:2048'
        ]);


        if ($validator->fails()) {
            return response()->json([$validator->errors(), 'error'], 500);
        }
        if (Auth::guard('api')->check()) {
            $user = Auth::guard('api')->user();
            if ($user->id == $r->id) {
                $us = User::findOrFail($r->id);
                if ($r->has('image')) {
                    $us->profile_pic = $r->file('image')->move('user/profile', $r->file('image')->getClientOriginalName());
                    $us->update();
                    if (Storage::exists(url('') . '/' . $us->profile_pic)) {
                        Storage::delete(url('') . '/' . $us->profile_pic);
                    }
                    return response()->json([
                        'success'
                        => ['msg' => "Image uploaded", 'status' => true],
                        'profile_pic_url' => url('') . '/' . $us->profile_pic
                    ], 200);
                } else {
                    return response()->json(['error' => "Something wrong", "status" => true], 400);
                }
            } else {
                return response()->json(['msg' => "Invalid User id", "status" => false], 200);
            }
        }
    }

       public function uploadProfilePicture(Request $r)
    {


        $validator = Validator::make($r->all(), [
            'image' => 'required|image:jpeg,png,jpg,gif,svg'
        ]);


        if ($validator->fails()) {
            return response()->json(["status" => false,'message' => implode("", $validator->errors()->all())],200);
        }


        $user = User::where('id','=',$r->user_id)->first();

        if(!empty($user)){

            
                if ($files    =    $r->file('image')) 
                {
                        //compress user profile image
                       $name    =    uniqid() . $files->getClientOriginalName();
                        $img = Img::make($files);
                        $img->resize(1024, null, function ($constraint) {
                                $constraint->aspectRatio();
                        });
                        $img->save('user-profile/'.$name, 50);

                        $location = url('user-profile',$name);

                        //$files->move('user-profile', $name);

                        User::where('id','=',$r->user_id)->update([
                        'profile_pic'           => $location
                        ]);

                         $user_data = User::where('id','=',$r->user_id)->first();

                    return response()->json(['status' => true,'message' => "Your profile picture uploaded successfully",'user'=>$user_data], 200);
                        
                }
                else
                {

                return response()->json(['status' => true,'message' => "Unable to upload profile picture",'data'=>null], 200);

                }

            }
            else{

            return response()->json(['status' => false, 'message' => "user not found",'user'=>null], 200); 

            }



    }


    public function updateuser(Request $request){

        $validator = Validator::make($request->all(), [
            'user_id'           => 'required',
            'first_name'        => 'required',
            'last_name'         => 'required',
            'dob'               => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(["status" => false,'message' => $validator->errors()],200);
        }

        $user = User::where('id','=',$request->user_id)->first();

        if(!empty($user)){

            User::where('id','=',$request->user_id)
                ->update([
                    'first_name'     => $request->first_name,
                    'name'           => $request->first_name.' '.$request->last_name,
                    'last_name'      => $request->last_name,
                    'dob'            => $request->dob,
                    'domestic_postcode' => $request->domestic_postcode,
                ]);

            $user_data = User::where('id','=',$request->user_id)->first();

            return response()->json(['status' => true, 'message' => "Your profile details updated successfully",'user'=>$user_data], 200);  

        } else {
            return response()->json(['status' => false, 'message' => "user not found",'user'=>null], 200); 
        }
    }

     public function saveToken(Request $request)
    {
        auth()->user()->update(['device_token'=>$request->token]);
        return response()->json(['token saved successfully.']);
    }
  
    /**
     * Write code on Method
     *
     * @return response()
     */
    public function sendNotification(Request $request)
    {
        //$firebaseToken = CarUser::whereNotNull('device_token')->distinct('device_token')->pluck('device_token')->all();
        
        // $SERVER_API_KEY = 'AAAAucd0rRk:APA91bFclCmJ7XHqrSBs7uvAVauSNt5_-XHUzjIbcYq4QsBrr4QQL51TK1o_ncQ9pSIHzQNzWvNBfX_JsoRB0dRMK4dSYSCTlRu9bh5uJlQtZJoJjNLhY69b6kW_CgTZzkqyYoyzvhGA';
        $validator = Validator::make($request->all(), [
            'title'                     => 'required',
            'message'                   => 'required',
            'sender_id'                 => 'required',
            'receiver_id'               => 'required',
            'type'                      => 'required',
            'image'                      => 'required',
        ]);

        if ($validator->fails()) {
        return response()->json(["status" => false,'message' => implode("", $validator->errors()->all())],200);
        }

        $title = $request->title;
        $mess = $request->message;
        $receiver_id = $request->receiver_id;
        $sender_id = $request->sender_id;
        $image = $request->image;

        $firebaseToken =DB::table('user_device_token')
                        ->where('user_id','=',$receiver_id)
                        ->whereNotNull('device_token')
                        ->pluck('device_token')
                        ->all();

        if(!empty($firebaseToken)){

            $SERVER_API_KEY = 'AAAAiAxlOvk:APA91bFd-Ml4T0dZDXv48JePKdP8a6YB6BLKe-QX52d-7MdXOcciJX8mdWcNdjRT60dgOFxieiT0g6AM3mLSZnZMG1waWuajskgsTN-wHgpmV3cM9HXA8bZGVATuDrwMzEoDm3ge2g-b';
      
            $data = [
                "registration_ids" => $firebaseToken,
                "notification" => [
                    "title" => $title,
                    "body" => $mess,
                    "sound" => 1,
                    "vibrate" => 1
                ],
                "data" => [
                    "type" => $request->type,
                    "sender_id" => $sender_id,
                    "receiver_id" => $receiver_id,
                ]
            ];
            $dataString = json_encode($data);
            

            $headers = [
                'Authorization: key=' . $SERVER_API_KEY,
                'Content-Type: application/json',
            ];
        
            $ch = curl_init();
          
            curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $dataString);

              // $Notifications = Notifications::insert([

              //                   'user_id'       => $receiver_id,
              //                   'title'         => $title,
              //                   'type'          => $request->type,
              //                   'body'          => $mess,
              //                   'image'         => $image,
              //                  // 'status'        => $status,

              //               ]);

            $response = curl_exec($ch);

            //return $response;
     
            return response()->json(['status' => true, 'message' => "success"], 200);

        }
        else{

            return response()->json(['status' => false, 'message' => "token not found"], 200);

        }
    
    }

    public function getNotification(Request $request){

        $validator = Validator::make($request->all(), [
          
            'user_id'                      => 'required',
        ]);

        if ($validator->fails()) {

            return response()->json(['status' => false, 'message' => implode("", $validator->errors()->all()), 'data' => null], 200);
        }

        $Notification = Notifications::where('user_id','=',$request->user_id)
                                        ->orWhere('user_id','=',0)
                                        ->orderBy('id', 'DESC')
                                        ->get()
                                        ->toArray();

        if(!empty($Notification)){

        return response()->json(['status' => true, 'message' => "success", 'data' => $Notification], 200);

        }

        else{

        return response()->json(['status' => false, 'message' => "Result not found", 'data' => null], 200);

        }



    }


    public function B2B(Request $req)
    {
        $b = new B2BEnquiry;
        $b->person = $req->name;
        $b->store_name = $req->store_name;
        $b->annual_revenue = $req->annual_revenue;
        $b->address = $req->address;
        $b->email = $req->email;
        $b->phone = $req->phone;
        $b->description = $req->description;
        $b->save();
        return response()->json(['status' => true, 'msg' => "Thank's"], 200);
    }

    public function deleteUser(Request $request){

        $validator = Validator::make($request->all(), [
            'user_id'           => 'required',
        ]);

          if ($validator->fails()) {
                return response()->json(["status" => false,'message' => $validator->errors()],200);
            }

        $user = User::where('id','=',$request->user_id)->where('active_status','=',"active")->first();

        if(!empty($user)){

            User::where('id','=',$user->id)->update(['active_status'=>'inactive']);

              return response()->json(['status' => true, 'message' => "user inactive successfully"], 200); 


        }
        else{

            return response()->json(['status' => false, 'message' => "user not found"], 200); 
        }


    }

    





}
