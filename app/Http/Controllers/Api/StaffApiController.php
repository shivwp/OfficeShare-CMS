<?php

namespace App\Http\Controllers\Api;

use App\Office;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Attribute;
use App\AttributeValue;
use App\OfficeAttribute;
use App\OfficeAttributeValue;
use App\OfficeDesk;
use App\Country;
use App\SharerAddresses;
use App\OfficeLocation;
use App\OfficeFeaturedImage;
use App\OfficeExtraDetails;
use phpDocumentor\Reflection\Types\Null_;
use Validator;
use App\Property;
use App\Space;
use App\SpaceType;
use App\PropertyLocation;
use App\PropertyAttributeValue;
use App\Wishlist;
use App\SpaceExtraDetails;
use App\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Setting;
use App\MailTemplate;
use App\Mail\Signup;
use App\Newsletter as Chimp;
use DrewM\MailChimp\MailChimp;
use Illuminate\Support\Facades\Mail;
use DateTime;
use Carbon\Carbon;


class StaffApiController extends Controller
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
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function staffRegister (Request $request)
    {

        $validator = Validator::make($request->all(), [
            'email'       => 'required|email|unique:users',
            'password'    => 'required|min:8|regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).+$/',
            'landload_id' => 'required',
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

        $pass = Hash::make($request->password);

        $us = User::where("email", $request->email)->first();

        if(!empty($us)){

            return response()->json(['status' => false, 'message' => "The email has already been taken.", 'user' => Null], 200);

        }


        $user = new User;

        $user->email       = $request->email;
        $user->password    = $pass;
        $user->parent_id   = $request->landload_id;
        $user->name        = $request->name;
        $user->remember_token = uniqid();
        $user->save();

        $user->roles()->sync(4);

        // $m=$this->sendMessageToUser($user,$request->password);

        // if($m['status']==true){
        //     Mail::to($user->email)->send(new Signup($m));   
        // }

        return response()->json(['status' => true, 'message' => "Your account registerd successfully.", 'user' => $user], 200);

    }

    public function staffget(Request $request){

        $validator = Validator::make($request->all(), [
            'landlord_id'          => 'required',
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

              $user = User::where('parent_id','=',$request->landlord_id)->get();

              if(count($user) > 0){

                return response()->json(['status' => true, 'message' => "success" , 'data' => $user], 200);

              }
              else{

                 return response()->json(['status' => false, 'message' => "unsuccess" , 'data' => $user], 200);


              }


    }

    public function staffedit (Request $request){

         $validator = Validator::make($request->all(), [
            'email'       => 'required|email|unique:users',
            'password'    => 'required|min:8',
            'id'          => 'required',
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

            $user = User::where('id','=',$request->id)->first();

            if(!empty($user)){


              $user->update([

                  'email'     => $request->email,
                  'password'  => $request->password,

              ]);

               return response()->json(['status' => true, 'message' => "Your Account updated successfully."], 200);

            }

            else{

              return response()->json(['status' => false, 'message' => "User Not Found", 'user' => $userr], 200);

            }

    }

    public function staffLogin(Request $request){

          $validator = Validator::make($request->all(), [
            'email'       => 'required|email',
            'password'    => 'required'
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

          if(Auth::attempt(['email' => request('email'), 'password' => request('password')])){

               $user = Auth::user();

              $a = new DateTime;
              // echo $a->date;

              // dd($a); 
              // exit;

              $user->update([

                'last_login' => Carbon::now()->toDateTimeString(),

              ]);

              return response()->json(['status' => true, 'message' => "Your account logged in successfully", 'user' => $user], 200);

          }
          else{

             return response()->json(['status' => false,'message' => 'Credentials not match', 'user' => Null], 200);
          }

    }

    public function staffDelete(Request $request){

            $validator = Validator::make($request->all(), [
              'id'       => 'required'
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

           $user = User::where('id', '=', $request->id)->first();

           if(!empty($user)){

              $user->forceDelete();

              return response()->json(['status' => true, 'message' => 'User Deleted Successfully'], 200);


           }
           else{

            return response()->json(['status' => false, 'message' => 'User Not Found'], 200);


           }



    }




     function sendMessageToUser(User $user, $token)
    {
    
        try {
            $st = Setting::where('options','=','site_url')->first();
            $st1 = Setting::first();
            $sign = [
                '{name}' => $user->name,
                '{email}' => $user->email,
                '{token}' => url('email/verify/'.$user->id),
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

}
