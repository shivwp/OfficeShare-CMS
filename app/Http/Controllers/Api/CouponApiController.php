<?php
namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\Coupon;
use App\Setting;
use App\MailTemplate;
use App\Carts;
use App\CouponUser;
use App\CouponProduct;
use App\Mail\GiftUser;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use App\CouponUsedByUser;
use App\CurrencyExchangeRate;
class CouponApiController extends Controller
{
    public function index()
    {
        $now=Carbon::parse(Carbon::now())->format('Y-m-d h:i:s'); 
        $coupon=Coupon::whereDate('start_date','<=',$now)
        ->whereDate('expiry_date','>',$now)->where('status',1)->get();
        $couponCollection=[];$i=0;
        if(count($coupon)>0){
        foreach ($coupon as $item){
      // checking for particular user  
        $couponusr=CouponUser::where('coupon_id',$item->id)->first();
      // checking for true or false 
        if(isset($couponusr)){
        $user=Auth::guard('api')->user();
        $couponUsers=json_decode($couponusr,true);
      //checking for coupon allow for this user  
        if(in_array($user->email,$couponUsers)){
        $couponCollection[$i++]=['description'=>$item->description,
        'code'=>$item->code,
        'coupon_amount'=>$item->coupon_amount
         ];
        }
        }else{
         $couponCollection[$i++]=['description'=>$item->description,
        'code'=>$item->code,
        'coupon_amount'=>$item->coupon_amount
         ];
        }
        // end of else
        }
        // end of foreach
        if(!empty($couponCollection)){
        return response()->json(['coupon_lists'=>$couponCollection,
         'status'=>true
        ],200);
        }else{
          return response()->json(['msg'=>"No coupon applied for now",
         'status'=>false
        ],200); 

        }
        }else{
          return response()->json(['msg'=>"No coupon applied for now",
         'status'=>false
        ],200);
        }

        }
        public function couponAmount(Request $req)
        {
            date_default_timezone_set('Asia/Kolkata'); 
            $couponCollection=[];$i=0;
            $current_date=strtotime(Carbon::parse(Carbon::now())->format('Y-m-d H:i')); 

            $coupon=Coupon::with(['product','productCategory'])
           ->where('code',trim($req->code))->first();
 
            if(isset($coupon) && !empty($coupon)){
            $expiry_date=strtotime(Carbon::parse($coupon->expiry_date)->format('Y-m-d H:i'));
            $start_date=strtotime(Carbon::parse($coupon->start_date)->format('Y-m-d H:i'));
            // return $expiry_date.' stry '.($current_date-$expiry_date); 
            if($expiry_date>$current_date){              
             return response()->json(['msg'=>"coupon code expired",'status'=>false],200);
            }  
            $countCoupon=count(CouponUsedByUser::where('coupon_id',$coupon->id)->get());
            if(isset($coupon->limit_per_coupon) && $countCoupon==$coupon->limit_per_coupon){
            return response()->json(['msg'=>"coupon exceed to there limit",'status'=>false],200);
            }
            $couponUser=CouponUsedByUser::where('coupon_id',$coupon->id) 
            ->where('no_of_time',$coupon->limit_per_user)->first(); 
            if(isset($couponUser->no_of_time) && $coupon->limit_per_user==$couponUser->no_of_time){
            return response()->json(['msg'=>"You already used this code",'status'=>false],200);
            }
            $total_price=0;
            $currency_id=0;
            $p=0;

            if($req->has('cart_id')){
            foreach ($req->cart_id as  $value){
            $am=Carts::where('user_id',$req->user_id)->where('id',$value)->first()->product;
            $total_price+=$am->sum('selling_price'); 
            }
            }
             if($req->has('cart_id')){
             foreach ($req->cart_id as  $value){
                 $cartData=Carts::with(['product'])
                 ->where('user_id',$req->user_id)
                 ->where('id',$req->cart_id)->first();
             $couponProduct  = CouponProduct::where('coupon_id',$coupon->id)->first();
             if(isset($couponProduct) && !empty($couponProduct) && isset($cartData))
             {
              $couponProduct  = json_decode($couponProduct->product_id,true);
              if($couponProduct[$p++]==$cartData->product_id){
                Carts::where('user_id',$req->user_id)->update(['coupon_code'=>$coupon->code,
               'coupon_amount'=>$coupon->coupon_amount,
               'payable_amount'=>$total_price-$coupon->coupon_amount
               ]); 
               $obj=new CartApiController;
               return $obj->show($req->user_id);;
              }
             }
             if(isset($cartData)){
              $total_price +=$cartData->product['selling_price'];
              $currency_id=$cartData->currency_exchange_id;
              $cartData->coupon_code=$req->code;
              $cartData->update();
             }
             } 
            }
            // apply coupon based on specific product purchase
            if($req->has('cart_id')){
             foreach ($req->cart_id as  $value){

             }
             }
            //apply coupon code on free shipping based
            // $currency=CurrencyExchangeRate::with('currency')->where('id',$currency_id)->first();
            // $total_price=$total_price/$currency->target_rate; 
            if(($coupon->minimum_spend!="" || $coupon->maximum_spend!="") && 
              ($total_price>=$coupon->minimum_spend || $total_price<=$coupon->maximum_spend)){
             if($coupon->allow_free_shipping==1){
             Carts::where('user_id',$req->user_id)->update(['coupon_code'=>$req->code]);
               $couponCollection[$i]=['shipping_charge'=>"free"];
               return ['shipping_charge'=>"free",'status'=>true,'message'=>"Free shipping allowed"];
             }
           // apply coupon code on category based  
             if(isset($coupon->productCategory)){
               $product=json_decode($coupon->productCategory,true);
              if($req->has('cart_id')){
               $l=0;$coupon_amount=0;$product_amount=0; 
              foreach ($req->cart_id as $citem){
              $cart=Carts::with('product')->where('id',$citem->id)->first();
              if(isset($cart) && $cart->product['categories']==$product[$l++]){
               Carts::where('user_id',$req->user_id)->update(['coupon_code'=>$req->code,
               "coupon_amount"=>$coupon->coupon_amount
               ]);
              return response()->json(['coupon_amount'=>$coupon->coupon_amount,
              'coupon_code'=>$coupon->ccode
              ]); 
              }
             }
             }
            }
            }else{
              return response()->json(['msg'=>"Your checkout amount not match our criteria",
                'status'=>false],200); 
            }
           }else{
              return response()->json(['msg'=>"Invalid coupon code",'status'=>false],200); 
          }
        }

    public function show($id)
    {
     $data=GiftCard::find($id); 
     if (isset($data)) {
         $data->image=url('').'/'.$data->image;
         $data->amount=explode(',',$data->amount); 
         unset($data->created_at);
         unset($data->updated_at);
     return response()->json(['giftcard'=>$data,'status'=>true],200);
    }else{
        return response()->json(['status'=>false,'message'=>"Data not found"],200); 
    } 
    }
    public function store(Request $req,Carts $cart)
    {
        $card=GiftCard::find($cart->product_id);
        $user=Auth::guard('api')->user();
        $recpData=json_decode($cart->optional_style,true);
        if(isset($card)){
         $code=Str::random(16);
         $code=substr_replace($code, '-', 4, 0);
         $code=substr_replace($code, '-', 9, 0); 
         $code=substr_replace($code, '-', 14, 0);  
         $giftuser=new GiftCardUser;
         $giftuser->card_id=$cart->product_id;
         $giftuser->user_id=$user->id;
         $giftuser->title=$card->title;
         $giftuser->description=$card->description;
         $giftuser->image=$card->image;
         $giftuser->currency_sign=$req->currency_sign;
         $giftuser->from_name=$user->name;
         $giftuser->recipient_email=$recpData['recipient_email'];
         $giftuser->recipient_name=$recpData['recipient_name'];
         $giftuser->recipient_phone=$recpData['recipient_phone'];
         $giftuser->message=$recpData['message'];
         $giftuser->amount=$recpData['amount'];
         $giftuser->quantity=$cart->quantity;
         $giftuser->gift_expiry_date=Carbon::now()->addDays($card->valid_days);
         $giftuser->code=$code;
         $giftuser->save();
         $this->sendGift($giftuser);
         return response()->json(['message'=>"Thank's your order placed",'status'=>true],200); 
        }else{
           return response()->json(['message'=>"Invalid card id",'status'=>false],200);   
        }

       }

        public function sendGift($giftuser)
        {
           $setting=Setting::first(); 
           $basicinfo=['{sender_name}'=>$giftuser->from_name,
           '{recipient_name}'=>$giftuser->recipient_name,
           '{gift_code}'=>$giftuser->code,
           '{gift_expiry_date}'=>$giftuser->gift_expiry_date,
           '{gift_card_image}'=>'<img src="'.url('').'/'.$giftuser->image.'" style="width:100%;height:250px;">',
           '{gift_title}'=>$giftuser->title,
           '{gift_sender_message}'=>$giftuser->message,
           '{gift_card_description}'=>$giftuser->description,
           '{gift_amount}'=>$giftuser->amount.$giftuser->currency_sign,
           '{gift_quantity}'=>$giftuser->quantity,
           '{site_url}'=>$setting->site_url,
           '{business_logo}'=>'<img src="'.url('storage/app').'/'.$setting->logo.'" style="width:200px;height:60px;">' ,
           '{business_name}'=>$setting->business_name ,
           ];
            $msgData=MailTemplate::where('status','gift')->first();
            $replMsg=MailTemplate::where('status','gift')->pluck('message')->first();
           foreach($basicinfo as $key=> $info){
            $replMsg=str_replace($key,$info,$replMsg);
           }
           $config=['fromemail'=>$msgData->from_email,"replyemail"=>$msgData->reply_email,'msg'=>$replMsg,'subject'=>$msgData->subject,'name'=>$msgData->name];
           Mail::to($giftuser->recipient_email)->send(new GiftUser($config)); 

        }

        public function checkoutByGiftcard(Request $request)
        {
          if($request->has('giftcard_code')){
          $checkExisting=GiftCardUser::where('code',$request->giftcard_code)->first();
          if(isset($checkExisting) && !empty($checkExisting)){
            if($request->checkout_amount<=$checkExisting->amount){
              if(Carbon::now()<=$checkExisting->gift_expiry_date){
              // $checkExisting->amount=$checkExisting->amount-$request->totPrice;
              // $checkExisting->update();
                $payable=$checkExisting->amount-$request->checkout_amount;
                 return response()->json([
                'payable_amount'=>0,
                'giftcard_used_amount'=>$request->checkout_amount,
                "status"=>true
               
               ],200); 
             }else{

             return response()->json(['msg'=>"Sorry your gift card got expired "
              ,'status'=>false],200);  
             }
             }else{
             $payable=$request->checkout_amount-$checkExisting->amount;
             return response()->json(['payable_amount'=>$payable,
              'giftcard_used_amount'=>$checkExisting->amount,'status'=>true],200);  
            }
            }else{
            return response()->json(['msg'=>"Invalid gift card code",'status'=>false],200); 
            }
          }
        }
}

