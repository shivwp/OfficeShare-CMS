<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Plans;
use App\PlanFeature;
use Illuminate\Support\Facades\Auth;

class PlanApiController extends Controller
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
    public function planlist()
    {
        //$plan = Plans::orderBy('id', 'desc')->get();
        $plan = Plans::with(['features' => function ($q) {
            $q->orderBy('features_order', 'asc');
        }])->get();

        if(!empty($plan)){

            return response()->json(['status' => true, 'message' => "success", 'plans' => $plan], 200);

        }else{

            return response()->json(['status' => true, 'message' => "success", 'plans' => Null], 200);

        }


    }

    
}
