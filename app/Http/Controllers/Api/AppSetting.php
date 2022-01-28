<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Setting;
class AppSetting extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $setting=Setting::select('id','name as setting_name','value as setting_value')->get();
        if(count($setting)>0){
            $setting[11]->setting_value=url('').'/'.$setting[11]->setting_value;  
            return response()->json(['status'=>true,'message'=>"success",'app_setting'=>$setting],200);    
        }else
        return response()->json(['status'=>false,'message'=>"Setting data not found",'app_setting'=>null],200);
    
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $setting=Setting::select('id','name as setting_name','value as setting_value')->find($id);
        if(isset($setting)){
         
            return response()->json(['status'=>true,'message'=>"success",'app_setting'=>$setting],200);    
        }else
        return response()->json(['status'=>false,'message'=>"Setting data not found",'app_setting'=>null],200);
    
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
