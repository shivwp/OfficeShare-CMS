<?php

namespace App\Http\Controllers\Api;

use App\Feedback;
use App\Setting;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Carbon;
class FeedbackApiController extends Controller
{
    public function setFeedback(Request $request)
    {
       if(Auth::guard('api')->check()){
           $user=Auth::user();
           if(!empty($request->title) && !empty($request->comment)){
              $feed=new Feedback;
              $feed->user_id=$user->id;
              $feed->title=$request->title;
              $feed->comment=$request->comment;
              $feed->rate=$request->rate;
              $feed->product_id=$request->product_id;
              $feed->permit_status=$request->permitStatus==true?true:false;
              $feed->save();
              return response()->json(['status'=>true,'feedback'=>$feed],200);
           }else{
              return response()->json(['status'=>false,'feedback'=>null,'msg'=>"Request is empty"],200);
           }
           
       }else{
          return response()->json(['status'=>false,'feedback'=>null,'msg'=>"User is not login"],200);
       }
    }

    public function getFeedback($id)
    { 
       $feed=[];
       $i=0;
       $feed=Feedback::with('user')->where('permit_status',1)
       ->where('product_id',$id)->latest()->get();
       $star=Feedback::where('product_id',$id)->avg('rate');
       if(count($feed)>0){
        foreach($feed as $value) {
          $feed[$i++]=['userName'=>$value->user['name'],
                      'title'=>$value->title,
                      'comment'=>$value->comment,
                      'rate'=>$value->rate,
                      'date'=>Carbon::parse($value->created_at)->format('d-M-Y')];
        }
         return response()->json(['status'=>true,'reviews'=>$feed,'star'=>round($star,1)],200);
       }else{
         return response()->json(['status'=>false,'reviews'=>$feed,'star'=>null],200);
       }

    }
    public function getSettingData()
    { 
       $feed=[];
       $i=0;
       $feed=Setting::with(['phoneCode'])->first();
       if(isset($feed)){
         return response()->json(['status'=>true,'data'=>[
         	'phoneCode'=>"+".$feed->phoneCode['phonecode'],
         	'phoneNumber'=>$feed->helpline,
         	'email'=>$feed->email,
          'logo'=>$feed->logo!=null?url('storage/app').'/'.$feed->logo:'',
         	'officeDayFrom'=>$feed->office_day_from,
         	'officeDayTo'=>$feed->office_day_to,
         	'officeHourFrom'=>$feed->office_hour_from,
         	'officeHourTo'=>$feed->office_hour_to,
           ]
         ],200);
       }else{
         return response()->json(['status'=>false,'data'=>$feed,'star'=>null],200);
       }

    }  
    
}
