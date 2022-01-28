<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Membership;
class MembershipController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
  
    public function index()
    {

        $mem=Membership::where('status',1)->get();
        
        if(isset($mem)){
         return response()->json(['status'=>true,'message'=>"Success","data"=>$mem],200);
        }else{
         return response()->json(['status'=>false,'message'=>"Not found","data"=>null],200);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($slug)
    {
       $mem= Membership::where('slug',$slug)->first();
       if(isset($mem)){
        return response()->json(['status'=>true,'message'=>"Success","data"=>$mem],200);
       }else{
        return response()->json(['status'=>false,'message'=>"Not found","data"=>null],200);
       }
    }

}
