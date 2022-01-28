<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\StripePayment;
use App\Carts;
use Illuminate\Http\Request;
use Stripe;
use Illuminate\Support\Facades\Auth;
class StripePaymentController extends Controller
{
        public function getPublisherKey()
        {
         if(Auth::guard('api')->check()){
            $user=Auth::guard('api')->user();
            $cart=Carts::where('user_id',$user->id)
            ->where('product_type','giftcard')->first();
            $payment=[];
            if(isset($cart)){
            $payment= StripePayment::select('status','payment_gateway',
                    "publishing_key",'secret_key')
            ->where('payment_gateway','!=',"COD")->get();   
            }else{
                $payment= StripePayment::select('status','payment_gateway',
                    "publishing_key",'secret_key')->get();  
            }
            if(isset($payment)){
            return response()->json(['publishing_key'=>$payment,
            'status'=>true],200);
            }else{
            return response()->json(['publishing_key'=>$payment,
            'status'=>false,'msg'=>"Not available right now"],200);  
            }
           }else{
             return response()->json([
            'status'=>false,'msg'=>"need user login token"],200);   
        }
    } 

}
