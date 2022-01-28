<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Blog;
use App\Setting;
use Illuminate\Support\Facades\Auth;

class SettingsApiController extends Controller
{
   
    public function termsconditions()
    {
        $Setting = Setting::where('id','=','17')->first();

        if(!empty($Setting)){

          return response()->json([
                'status' => true, 'message' => "success", 'data' => $Setting
            ], 200);
        }
        else{

             return response()->json([
                'status' => false, 'message' => "Something went wrong", 'data' => Null
            ], 200);
        }
    }

   

   
}
