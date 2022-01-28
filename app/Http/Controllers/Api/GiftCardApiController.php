<?php
namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Api\CouponApiController;
use App\GiftCard;
use App\GiftCardUser;
use App\Setting;
use App\MailTemplate;
use App\Carts;
use App\Mail\GiftUser;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use App\CurrencyExchangeRate;
class GiftCardApiController extends Controller
{

    public function index()
    {
    $data=GiftCard::select('id','image')->where('status',1)
    ->orderBy('id','desc')->get();
    if(count($data)>0){
        foreach ($data as $key => $value) {
           $value->image=url('').'/'.$value->image;
        }
        return response()->json(['giftcards'=>$data,'status'=>true],200);
    }else{
        return response()->json(['status'=>false,'message'=>"Data not found"],200); 
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
         $giftuser->currency_exchange_id=$cart->currency_exchange_id;
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
                // date_default_timezone_set('Asia/Kolkata');

                $cartamount=0;$cartexchangeid="";$coupon_amount=0;
                $payable;$gift_used_amount;$crate="";
                if($request->has('code')){
                $checkExisting=GiftCardUser::where('code',$request->code)->first();
                if(isset($checkExisting) && !empty($checkExisting)){
                 $expiry_date=Carbon::parse($checkExisting->gift_expiry_date)->format('Y-m-d H:i');
                 $current_date=Carbon::parse(Carbon::now())->format('Y-m-d H:i');
                if($expiry_date>$current_date){
                if($request->has('cart_id')){
                 foreach ($request->cart_id as  $value){
                 $cart=Carts::with('product')->where('user_id',$request->user_id)
                ->where('id',$value)->first();
                 if(isset($cart)){
                 $coupon_amount=$cart->coupon_amount;   
                 $crate=CurrencyExchangeRate::where('id',$cart->currency_exchange_id)->first(); 
                 $cartamount +=round(($cart->product['selling_price']*$crate->target_rate)*$cart->quantity);
                 $cartexchangeid=$cart->currency_exchange_id;
                 $cart->giftcard_code=$request->code;
                 $cart->update();
                 }
                 }

                if($checkExisting->currency_exchange_id==$cartexchangeid){
                 if($cartamount<$checkExisting->amount){
                 $gift_used_amount=$checkExisting->amount-$cartamount;
                 $payable=0;
                 }elseif($cartamount>$checkExisting->amount){
                 $gift_used_amount=$checkExisting->amount;
                 $payable=$cartamount-$checkExisting->amount;
                 }elseif($cartamount==$checkExisting->amount){
                 $gift_used_amount=$checkExisting->amount;
                 Carts::where('user_id',$request->user_id)->update(['payable_amount'=>0]);
                 $payable=0;
                 }
                }else{

                 // $giftamount=$checkExisting->amount*$crate->target_rate;
                 // if($cartamount<$giftamount){
                 // $gift_used_amount=$giftamount-$cartamount;
                 // $payable=0;
                 // }elseif($cartamount>$giftamount){
                 // $gift_used_amount=$giftamount;
                 // $payable=$cartamount-$giftamount;
                 // }elseif($cartamount==$giftamount){
                 // $gift_used_amount=$giftamount;
                 // $payable=0;
                 // }
                return (['status'=>false,
                'message'=>"giftcard currency and cart currency doesn't match,
                Please change currency to giftcard currency"]);  
                }
                Carts::whereIn('id',$request->cart_id)->where('user_id',$request->user_id)
                ->update(['payable_amount'=>$payable]);
                return ['payable_amount'=>$payable,
                'giftcard_used_amount'=>$gift_used_amount,
                'status'=>true,
                'message'=>' giftcard amount applied'];  
                }else{
                  return (['status'=>false,
                  'message'=>"cart id required "]);  
                }
                }else{
                  $cartamount=$this->getCartAmount($request);
                return ['status'=>false,
                'message'=>"giftcard expired",
                'payable_amount'=>$cartamount['cartamount'],
                'giftcard_used_amount'=>0 ];  
                }
               }
                else{
                return ['msg'=>"Invalid gift card code",'status'=>false]; 
                }
               }else{
                return ['msg'=>"giftcard code required",'status'=>false]; 
                }
     
      }
      public function getCartAmount(Request $request)
      {
         $cartexchangeid="";$cartamount=0;$coupon_amount=0;
         foreach ($request->cart_id as  $value){
         $cart=Carts::with('product')->where('user_id',$request->user_id)
        ->where('id',$value)->first();
         if(isset($cart)){
         $crate=CurrencyExchangeRate::where('id',$cart->currency_exchange_id)->first(); 
         $cartamount +=round(($cart->product['selling_price']*$crate->target_rate)*$cart->quantity);
         $cartexchangeid=$cart->currency_exchange_id;
         $coupon_amount=$cart->coupon_amount;
         }
         }
         Carts::whereIn('id',$request->cart_id)->where('user_id',$request->user_id)
         ->update(['payable_amount'=>$cartamount-$coupon_amount]);

         return ['cartexchangeid'=>$cartexchangeid,'cartamount'=>($cartamount-$coupon_amount)];
      }

      public function verifyCode(Request $r)
      {
          if($r->type=="giftcard"){
            return $this->checkoutByGiftcard($r);
          }elseif ($r->type=="coupon") {
            $obj=new CouponApiController();
            return $obj->couponAmount($r);
          }
      }
}

