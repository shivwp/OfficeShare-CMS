<?php

namespace App\Http\Controllers\Api;

use App\HomePageSetting;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\HomeSettings;
use App\User;
use Validator;
use App\UserBankDetails;
use App\Http\Controllers\Api\ProductApiController;
class OnBordingProcessApiController extends Controller
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

    public function onbordingprocess(Request $request)
    {

        // echo $request->entity_name;
        // exit;

         $validator = Validator::make($request->all(), [

            'landload_type'         => 'required',
            'entity_type'           => 'required',
            'entity_name'           => 'required',
            'first_name'            => 'required',
            'last_name'             => 'required',
            'email'                 => 'required',
            'phone'                 => 'required',
            'region'                => 'required',
        ]);
        if ($validator->fails()) {
            $er = [];
            $i = 0;
            foreach ($validator->errors() as $err) {
                $er[$i++] = $err[0];
                return $err;
            }
            return response()->json(["error" => implode("", $validator->errors()->all()), "status" => false], 403);
        }

        $user_mobile_exits = User::where("phone", $request->phone)
                                   ->orWhere("email", $request->phone)
                                   ->first();

       $password = bcrypt($request->password);

        if(!empty($user_mobile_exits)){

            return response()->json([
                'status' => false, 'message' => "Mobile number already registered", 'data' => Null
            ], 200);

        }


            $user               = new User;
            $user->name         = $request->first_name.' '.$request->last_name;
            $user->first_name   = $request->first_name;
            $user->last_name    = $request->last_name;
            $user->password     = $password;
            $user->email        = $request->email;
            $user->entity_name  = $request->entity_name;
            $user->entity_type  = $request->entity_type;
            $user->user_type    = $request->landload_type;
            $user->phone        = $request->phone;
            $user->region        = $request->region;
           $success['token'] =  $user->createToken('API Token')->accessToken;
            $user->ip_address    = "Null";
             $user_id = $user->save();

              $user->roles()->sync(3);
      


      return response()->json(['status' => true, 'message' => "success", 'user' => $user], 200);
        
    }

    
}
