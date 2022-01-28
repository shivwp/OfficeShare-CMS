<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Ratings;
use App\UserBooking;
use App\User;
use App\Property;
use Validator;
use Illuminate\Support\Facades\Auth;
use DB;

class RatingApiController extends Controller
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
    
    public function create(Request $request)
    {
    	$validator = Validator::make($request->all(), [
           // 'id' => 'required',
            'booking_id' => 'required',
            'rating' => 'required',
            'type' => 'required',
        ]);


        if ($validator->fails()) {
            return response()->json(['status' => false, 'message' => implode("", $validator->errors()->all())], 200);
        }

    	$booking = UserBooking::where('id','=',$request->booking_id)->first();
        $exist = Ratings::where('user_id','=',$booking->user_id)->where('type','=',$request->type)->first();
        if(!empty($exist)){

            return response()->json(['status' => false, 'message' => "User already rated",], 200);
        }
    	if(!empty($booking)){

    		$user 		= User::where('id','=',$booking->landload_id)->first();
    		if(empty($user)){

    			return response()->json(['status' => false, 'message' => "Landlord User not found",], 200);

    		}
    		$Property 	= Property::where('id','=',$booking->property_id)->first();

    		if(empty($Property)){

    			return response()->json(['status' => false, 'message' => "Property not found",], 200);

    		}

    		Ratings::insert([

    					'type' 		=> $request->type,
    					'rating' 	=> $request->rating,
    					'type_id' 	=> (!empty($request->type) && ($request->type == 'host') ? $user->id : $Property->id),
                        'rating'    => $request->rating,
    					'user_id' 	=> $booking->user_id,

    				]);
            if($request->type == 'property'){

                 $property_average_rating    = Ratings::where('type','=','property')
                                                  ->where('type_id','=',$Property->id)
                                                  ->avg('rating');

                 Property::where('id','=',$Property->id)->update([

                                                            'avg_rating' => $property_average_rating

                                                            ]);

            }
            else{
                $host_average_rating    = Ratings::where('type','=','host')
                                                  ->where('type_id','=',$user->id)
                                                  ->avg('rating');

                 User::where('id','=',$user->id)->update([

                                                            'avg_rating' => $host_average_rating

                                                            ]);

            }
    		return response()->json(['status' => true, 'message' => "success",], 200);

    	}
    	else{

    		return response()->json(['status' => false, 'message' => "booking not found",], 200);
    	}
    }

   
}
