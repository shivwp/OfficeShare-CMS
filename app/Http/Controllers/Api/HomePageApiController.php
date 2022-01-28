<?php

namespace App\Http\Controllers\Api;

use App\HomePageSetting;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\User;
use App\Plans;
use App\UserPlan;
use App\HomeSettings;
use App\UserAddress;
use App\UserCard;
use App\UserNotification;
use App\Http\Controllers\Api\ProductApiController;
use Carbon\Carbon;
use DB;

class HomePageApiController extends Controller
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

     public function getHomePageData()
     {
          // 
          $data = HomeSettings::get();
          if(count($data) > 0){
               return response()->json(['status' => true, 'message' => "success", 'data' =>  $data ], 200);
          }
          else{
               return response()->json(['status' => false, 'message' => "unsuccess", 'data' => Null], 200);
          }
     }

    public function headerContent(){

         $data = HomeSettings::where('id','=','1')->get();

          if(!empty($data)){
               return response()->json(['status' => true, 'message' => "success", 'data' =>  $data ], 200);
          }
          else{
               return response()->json(['status' => false, 'message' => "unsuccess", 'data' => Null], 200);
          }
     }


     public function safeworking(){

          $data = HomeSettings::where('id','=','2')->get();

          if(!empty($data)){
               return response()->json(['status' => true, 'message' => "success", 'data' =>  $data ], 200);
          }
          else{
               return response()->json(['status' => false, 'message' => "unsuccess", 'data' => Null], 200);
          }
     }

     public function tabsection(){
          // 
          $data = HomeSettings::whereIn('id',[5,6,7])->get();

          if(!empty($data)){
               return response()->json(['status' => true, 'message' => "success", 'data' =>  $data ], 200);
          }
          else{
               return response()->json(['status' => false, 'message' => "unsuccess", 'data' => Null], 200);
          }
     }


     public function allCardsList(Request $request)
     {
          // code...

          $parameters = $request->all();
          extract($parameters);

          $stripeAccount = new \Stripe\StripeClient(env('STRIPE_SECRET'));

          $cards = [];
          if(isset($customer_id)) {
               $cards = $stripeAccount->customers->allSources(
                    $customer_id,
                    ['object' => 'card', 'limit' => 10]
               );
          }

          if(!empty($cards)){
               return response()->json(['status' => true, 'message' => "success", 'data' => $cards], 200);
          } else {
               return response()->json(['status' => false, 'message' => "fail", 'data' => null], 200);
          }

    }

     public function addCard(Request $request)
     {
          // code...
          try {
               $parameters = $request->all();
               extract($parameters);

               $stripeAccount = new \Stripe\StripeClient(env('STRIPE_SECRET'));
               $cardinfo = $stripeAccount->customers->createSource(
                 $customer_id,
                 ['source' => $src_token]
               ); 


               if(!empty($cardinfo)){
                    return response()->json(['status' => true, 'message' => "Card added successfully!", 'data' => $cardinfo], 200);
               } else {
                    return response()->json(['status' => false, 'message' => "Failed to add card!"], 200);
               }
          } catch(Exception $e) {
               return response()->json(['status' => false, 'message' => "Error: ".$e, 'response' => $e], 200);
          }
          

     }

     public function deleteCard(Request $request)
     {
          // 
          try{
               $parameters = $request->all();
               extract($parameters);

               $stripeAccount = new \Stripe\StripeClient(env('STRIPE_SECRET'));

               $sts = $stripeAccount->customers->deleteSource(
                    $customer_id,
                    $card_id,
                    []
               );

               if($sts->deleted){
                    return response()->json(['status' => true, 'message' => "Card Deleted Successfully", 'response' => $sts], 200);
               } else {
                    return response()->json(['status' => false, 'message' => "Failed to delete card!", 'response' => $sts], 200);
               }
          } catch (Exception $e){
               return response()->json(['status' => false, 'message' => "Error: ".$e, 'response' => $e], 200);
          }
          
     }


     public function getUserInfo(Request $request)
     {
          // code...
          $parameters = $request->all();
          extract($parameters);
          try {
               // 
               $user = User::where('id', $user_id)->first();
               $address = UserAddress::where('user_id', $user_id)->orderBy('created_at', 'desc')->first();
               $user['address'] = $address;
               //unset($user['address']['booking_id']);
               if(!empty($address)){
               return response()->json(['status' => true, 'message' => "success", 'data' => $user], 200);
               }
               else{
                  return response()->json(['status' => false, 'message' => "unsuccess", 'data' => null], 200);   
               }

          } catch ( Exception $e) {
               return response()->json(['status' => false, 'message' => "fail", 'data' => $e], 200);
          }
     }

     public function buyPlan(Request $request)
     {
          // code...
          // return $request;
          // exit;

          $parameters = $request->all();
          extract($parameters);

          $user = User::where('id', $user_id)->first();
          $plan = Plans::where('id', $plan_id)->first();
          $stripeAccount = new \Stripe\StripeClient(env('STRIPE_SECRET'));

          \Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));

          $customer_id = '';

          if($user->customer_id == "") {
               // 
               $customer = $stripeAccount->customers->create([
                    'email' => $user->email,
                    'name' => $user->name,
                    'phone' => ($user->phone != '') ? $user->phone : '',
                    'description' => 'customer_'.$user->id,
               ]);  // -- done

               $customer_id = $customer->id;

               User::where('id', $user_id)->update([
                    'customer_id' => $customer_id,
               ]);

          } else {
               $customer_id = $user->customer_id;
          }

          if($new) {
               // 
               $card_token = '';

               try {
                    $cardinfo = $stripeAccount->customers->createSource(
                         $customer_id,
                         ['source' => $src_token]
                    );

                    $card_token = $cardinfo->id;
               } 
               catch (\Stripe\Exception\InvalidRequestException $e) {
                    // Invalid parameters were supplied to Stripe's API
                    return response()->json([
                         'status' => false, 
                         'message' => $e->getError()->message
                    ], 200);
               }

               $new_card = UserCard::insert([
                    'user_id' => $user->id, 
                    'user_customer_id' => $customer_id,
                    'card_token' => $card_token,
               ]);

          } else {
               // 
               $card_token = $src_token;
          }
          if($plan->price > 0) {


               try {
                    // 
                    $paymentIntent = \Stripe\PaymentIntent::create([
                         'amount' => $plan->price * 100,
                         'currency' => 'gbp',
                         'payment_method_types' => ['card'],
                         'customer' => $customer_id,
                         'payment_method' => $card_token, // 'card_1Jht6ZEUI2VlKHRnc5KrHBMF',
                         'transfer_group' => $plan_id,
                         'confirm'=>'true',
                    ]);
               }
               catch (\Stripe\Exception\InvalidRequestException $e) {
                    // 
                    return response()->json([
                         'status' => false, 
                         'message' => $e->getError()->message
                    ], 200);
               }

               $paymtsts = $paymentIntent->status;

          } else {
               $paymtsts = 'succeeded';
          }


          if( $paymtsts == 'succeeded') {

               //make user landload

               //$user = User::where('id', $user_id)->first();

               $userRole = $user->roles;

               $roleName = $userRole[0]->title;

               if($roleName == 'User'){

                    DB::table('role_user')->where('user_id','=',$user->id)->delete();

                    $user->roles()->sync(3);
               }
               $current_date = Carbon::now();
               switch ($plan->type) {
                    case 'Day':
                         // code...
                         $future_data = $current_date->addDays($plan->number);
                         break;

                    case 'Month':
                         // code...
                         $future_dataa  = $current_date->addMonths($plan->number);
                         $future_data  = $future_dataa->subDays(1);
                         break;

                    default:
                         // code...
                         $future_dataa = $current_date->addYears($plan->number);
                         $future_data  = $future_dataa->subDays(1);
                         break;
               }
               
               // $userr = User::where('id', $user_id)->first();
               $user_plan = UserPlan::updateOrCreate(['user_id' => $user_id],[
                    'plan_id'                => $plan_id,
                    'description'            => $plan->description,
                    'validity'               => $plan->validity, 
                    'price'                  => $plan->price, 
                    'from_date'              => Carbon::now(),
                    'to_date'                => $future_data, 
                    'status'                 => true,
                    'transaction_id'         => isset($paymentIntent->id)?$paymentIntent->id:'',
                    'payment_status'         => isset($paymentIntent->status)?$paymentIntent->status:'',
                    'charges_id'             => isset($paymentIntent->charges->data[0]->id)?$paymentIntent->charges->data[0]->id:'',
                    'balance_transaction'    => isset($paymentIntent->charges->data[0]->balance_transaction)?$paymentIntent->charges->data[0]->balance_transaction:'',
               ]);

               if($user_plan) {

                      //package details 

                       $user_plan['package_details'] = $plan;

                       foreach($user->roles as $key => $item){
                                $user_plan['user_role_type'] = $item->title;
                            }

                    return response()->json(['status' => true, 'message' => "Plan Buy Successfully!", 'data'=>$user_plan], 200);

               } else {
                    // 
                    return response()->json(['status' => false, 'message' => "Failed to buy Plan!", 'transaction_id' => ''], 200);
               }

          } else {
               // 
               return response()->json(['status' => false, 'message' => "Faild to buy plan!", 'transaction_id' => ''], 200);
          }


     }


     public function userNotification(Request $request)
     {
          // code...
          try {

               $notifications = UserNotification::where('user_id', $request->user_id)->where('status', 'new')->get();
               if(count($notifications)>0) {
                    return response()->json(['status' => true, 'message' => "Notifications!", 'data' => $notifications], 200);
               } else {
                    return response()->json(['status' => false, 'message' => 'Notifications not found!'], 200);
               }

          } catch(Exception $e) {
               // 
               return response()->json(['status' => false, 'message' => $e], 200);
          }
     }

    
}
