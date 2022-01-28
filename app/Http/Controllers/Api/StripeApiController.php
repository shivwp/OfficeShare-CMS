<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Office;
use App\OfficeDesk;
use App\BlogCategory;
use App\UserBooking;
use App\BookOffice;
use App\BookingDates;
use App\Property;
use App\Space;
use App\SpaceExtraDetails;
use App\SpaceType;
use App\PropertyLocation;
use App\BookingPayment;
use App\OfficeExtraDetails;
use DB;
use App\Mail\Bookings;
use DrewM\MailChimp\MailChimp;
use Illuminate\Support\Facades\Mail;
use App\User;
use Validator;
use App\Setting;
use App\MailTemplate;
use App\PropertyAttributeValue;
use Illuminate\Support\Facades\Auth;
use Dcblogdev\Xero\Facades\Xero;
use Dcblogdev\Xero\Models\XeroToken;

use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use Log;

class StripeApiController extends Controller
{

   // public function __construct(Request $request)
   //  {

   //      //dd($request->api_key);

   //      $apitoken = $request->header('api_key');

   //      if (empty($apitoken)) {
   //          $response = json_encode(array(
   //              'status' => false,
   //              'message' => 'Please Provide Api Token',
   //          ));
   //          header("Content-Type: application/json");
   //          echo $response;
   //          exit;
   //      }
   //      if ($apitoken != 'zFLUigHPoTwMvKjLSm7YFaKpJX8M') {
   //          $response = json_encode(array(
   //              'status' => false,
   //              'message' => 'Api Token Not valid',
   //          ));
   //          header("Content-Type: application/json");
   //          echo $response;
   //          exit;
   //      }
   //  }

    public function index()
    {
        $d['title'] = "Manage Blogs";
        $d['blog'] = Blog::with('user')->orderBy('id', 'desc')->get();
        return view('admin.blog.index', $d);
    }

   public function stripConnectionToken(){

        try{

            \Stripe\Stripe::setApiKey('sk_test_51IyI6sEUI2VlKHRnMonCU5R8jWGutknpkAwcG5T513pHaEWxycYaDngoP7DKjRB5zKnAdSqTe1VURZhHNhcQX1yJ00gRqMhj8H');

            $connectionToken = \Stripe\Terminal\ConnectionToken::create();

            return response()->json([
                'status' => true, 
                'message' => "success", 
                'data' => $connectionToken
            ], 200);
        }
        catch (Exception $e){

            return response()->json([
                'status' => true, 
                'message' => $e, 'data' => null
            ], 200);

        }

    }


    public function stripeChargePayment(Request $request)
    {
        // code...
        $parameters = $request->all();
        extract($parameters);

        $booking = UserBooking::where('id', $booking_id)->first();
        $user = User::where('id', $booking->user_id)->first();
        $landload = User::where('id', $booking->landload_id)->first();


        //return $booking;
        $stripeAccount = new \Stripe\StripeClient(
            'sk_test_51IyI6sEUI2VlKHRnMonCU5R8jWGutknpkAwcG5T513pHaEWxycYaDngoP7DKjRB5zKnAdSqTe1VURZhHNhcQX1yJ00gRqMhj8H'
        );

        \Stripe\Stripe::setApiKey('sk_test_51IyI6sEUI2VlKHRnMonCU5R8jWGutknpkAwcG5T513pHaEWxycYaDngoP7DKjRB5zKnAdSqTe1VURZhHNhcQX1yJ00gRqMhj8H');

        $method = \Stripe\PaymentMethod::create([
            'type' => 'card',
            'card' => [
                'number' => '4242424242424242',
                'exp_month' => 12,
                'exp_year' => 2022,
                'cvc' => '314',
            ],
        ]);
        $paymentIntent = \Stripe\PaymentIntent::create([
            'amount' => $price * 100,
            'currency' => 'gbp',
            'payment_method_types' => ['card'],
            'payment_method' => $method->id,
            'transfer_group' => $porduct_id,
            'confirm'=>'true',
            'shipping' => [
                'name' => 'shipping name',
                'phone' => '9090909090',
                'address' => [
                    'city' => 'city',
                    'country' => 'country',
                    'line1' => 'line1',
                    'line2' => 'line2',
                    'postal_code' => 'postal_code',
                    'state' => 'state',
                ]
            ]
        ]);

        return $paymentIntent;
    }


    public function createStripAccountCallback(Request $request) {

        // $log = ['orderId' => 10,
        // 'description' => 'Some description'];

        // //first parameter passed to Monolog\Logger sets the logging channel name
        // $orderLog = new Logger('order');
        // $orderLog->pushHandler(new StreamHandler(storage_path('logs/order.log')), Logger::INFO);
        // $orderLog->info('OrderLog', $log);

        // Log::channel('payments')->info($log);
        // echo "sdf";
        // exit;

        //$app = new \Slim\App;

        // $endpoint_secret = 'whsec_oYnsq7aRFrE8AAJ9SAC0dcZrxDKInMbI';

        // $stripeAccount = new \Stripe\StripeClient(
        //     'sk_test_51IyI6sEUI2VlKHRnMonCU5R8jWGutknpkAwcG5T513pHaEWxycYaDngoP7DKjRB5zKnAdSqTe1VURZhHNhcQX1yJ00gRqMhj8H'
        // );

        // $stripeAccount->post('/webhook', function ($request, $response, $next) {
        //     //
        //     $payload = $request->getBody();
        //     $sig_header = $request->getHeaderLine('stripe-signature');

        //     $event = null;

        //     // Verify webhook signature and extract the event.
        //     // See https://stripe.com/docs/webhooks/signatures for more information.
        //     try {
        //         $event = \Stripe\Webhook::constructEvent(
        //             $payload, $sig_header, $endpoint_secret
        //         );
        //     } catch(\UnexpectedValueException $e) {
        //         // Invalid payload.
        //         return $response->withStatus(400);
        //     } catch(\Stripe\Exception\SignatureVerificationException $e) {
        //         // Invalid Signature.
        //         return $response->withStatus(400);
        //     }

        //     if ($event->type == 'account.updated') {
        //         $account = $event->data->object;
        //         // handleAccountUpdate($account);
        //         echo $account;
        //     }

        //     return $response->withStatus(200);
        // });

        // function handleAccountUpdate($account) {
        //     // Collect more required information, e.g.
            
        // };

        //$app->run();
        \Stripe\Stripe::setApiKey('sk_test_51IyI6sEUI2VlKHRnMonCU5R8jWGutknpkAwcG5T513pHaEWxycYaDngoP7DKjRB5zKnAdSqTe1VURZhHNhcQX1yJ00gRqMhj8H');

        $payload = @file_get_contents('php://input');
        $event = null;

        try {
            $event = \Stripe\Event::constructFrom(
                json_decode($payload, true)
            );
        } catch(\UnexpectedValueException $e) {
            // Invalid payload
            http_response_code(400);
            exit();
        }

        // Handle the event
        switch ($event->type) {
            case 'payment_intent.succeeded':
                $paymentIntent = $event->data->object; // contains a \Stripe\PaymentIntent
                // handlePaymentIntentSucceeded($paymentIntent);
                break;
            case 'payment_method.attached':
                $paymentMethod = $event->data->object; // contains a \Stripe\PaymentMethod
                // handlePaymentMethodAttached($paymentMethod);
                break;
            // ... handle other event types
            default:
                echo 'Received unknown event type ' . $event->type;
        }

        http_response_code(200);


        // $payload = @file_get_contents('php://input');
        // $event = null;

        // try {
        //   $event = \Stripe\Event::constructFrom(
        //     json_decode($payload, true)
        //   );
        // } catch(\UnexpectedValueException $e) {
        //   // Invalid payload
        //   echo 'Webhook error while parsing basic request.';
        //   http_response_code(400);
        //   exit();
        // }

        Log::channel('payments')->info($event);
        
        // print_r($event);

        $data = [
          'Type'          => "ACCREC",
          'Contact'       => $data_1,
          'LineItems'     => [
            [
              "description"=>"Acme Tires",
              "quantity"=>2,
              "unitAmount"=>"20.0",
              "accountCode"=>"154225ABC",
              "taxType"=>"NONE",
              "lineAmount"=>"40.0"
            ],
          ],
        ];

        $invoice = Xero::invoices()->store($data);

        return $invoice['InvoiceID'];
        
        exit;

        // // Handle the event
        // switch ($event->type) {
        //   case 'payment_intent.succeeded':
        //     $paymentIntent = $event->data->object; // contains a \Stripe\PaymentIntent
        //     // Then define and call a method to handle the successful payment intent.
        //     // handlePaymentIntentSucceeded($paymentIntent);
        //     break;
        //   case 'payment_method.attached':
        //     $paymentMethod = $event->data->object; // contains a \Stripe\PaymentMethod
        //     // Then define and call a method to handle the successful attachment of a PaymentMethod.
        //     // handlePaymentMethodAttached($paymentMethod);
        //     break;
        //   default:
        //     // Unexpected event type
        //     echo 'Received unknown event type';
        // }

        // http_response_code(200);

        // return $request;

    }

    public function createStripAccount(Request $request){

        // $stripe = new \Stripe\StripeClient(
        //   'sk_test_51IyI6sEUI2VlKHRnMonCU5R8jWGutknpkAwcG5T513pHaEWxycYaDngoP7DKjRB5zKnAdSqTe1VURZhHNhcQX1yJ00gRqMhj8H'
        // );
        // $stripe->accounts->delete(
        //   'acct_1JeHEhRRUCTDblcr',
        //   []
        // );

        // die;


        //   $stripe = new \Stripe\StripeClient(
        //   'sk_test_51IyI6sEUI2VlKHRnMonCU5R8jWGutknpkAwcG5T513pHaEWxycYaDngoP7DKjRB5zKnAdSqTe1VURZhHNhcQX1yJ00gRqMhj8H'
        // );
        // $acc= $stripe->accountLinks->create([
        //   'account' => 'acct_1JeKMqRHv0lccXIF',
        //   'refresh_url' => "https://os.eoxysitsolution.com/",
        //   'return_url' => "https://os.eoxysitsolution.com/",
        //   'type' => 'account_onboarding',
        // ]);

        // return $acc->url;
        // die;

        $parameters = $request->all();
        extract($parameters);

        //  working code 

        // $user_id = $request->user_id;

        $user = User::where('id','=',$user_id)->first();

        $stripeAccount = new \Stripe\StripeClient(
            'sk_test_51IyI6sEUI2VlKHRnMonCU5R8jWGutknpkAwcG5T513pHaEWxycYaDngoP7DKjRB5zKnAdSqTe1VURZhHNhcQX1yJ00gRqMhj8H'
        );

        $c_date = time();
        if($new == 'true') {
            $res = $stripeAccount->accounts->create([
                'country' => 'GB',
                'type' => 'custom',
                'email' => "gunjanmanghnani5@gmail.com",
                'business_type'=>'individual',
                'individual'=> [
                    'first_name'=>"demo",
                    'last_name'=>"user1",
                    "dob" => [
                        "day" => '1',
                        "month" => '10',
                        "year" => '1997'
                    ],
                    // "ssn_last_4" => '8547',
                    "id_number" => '778-62-8547',
                    "phone" => '8005680948',
                    "email" => 'gunjanmanghnani5@gmail.com',
                    "address" => [
                        "city" => 'Abbas Combe',
                        "state" => 'England',
                        "postal_code" => 'BA8',
                        "line1" => 'demo'
                    ],
                ],
                'capabilities' => [
                    'card_payments' => [
                      'requested' => true,
                    ],
                    'transfers' => [
                      'requested' => true,
                    ],
                ],
                "external_account" => [
                    "object" => "bank_account",
                    "currency" => 'usd',
                    "country" => 'us',
                    "account_number" =>  '000123456789',
                    "routing_number" => '110000000',
                    "bank_name" => 'kotak',
                    "account_holder_name" => 'gunjan',
                    "account_holder_type" => 'individual'
                ],
                "tos_acceptance" => [
                    "date" => $c_date,
                    "ip" => $_SERVER['REMOTE_ADDR'], //"153.92.217.62"
                ]
            ]);
        }
        else {
            // working -- 
            // $res = $stripeAccount->accounts->delete(
            //     $accout_id,
            //     []
            // );

            // $request->validate([
            //     'file' => 'required|mimes:pdf,xlx,csv|max:2048',
            // ]);
      
            // $additional_document_front_image = time().'additional_document_front_image.'.$request->additional_document_front_image->extension();  
            // $additional_document_back_image = time().'additional_document_back_image.'.$request->additional_document_back_image->extension();  
       
            // $request->additional_document_front_image->move(public_path('varification_doument'), $additional_document_front_image);
            // $request->additional_document_back_image->move(public_path('varification_doument'), $additional_document_back_image);



            // $additional_document_front_image = fopen(public_path('varification_doument').'/'.$additional_document_front_image, 'r');
            // $additional_document_back_image = fopen(public_path('varification_doument').'/'.$additional_document_back_image, 'r');



            // $additional_document_front_image = $stripeAccount->files->create([
            //   'purpose' => 'additional_verification',
            //   'file' => $additional_document_front_image
            // ]);

            // $additional_document_back_image = $stripeAccount->files->create([
            //   'purpose' => 'additional_verification',
            //   'file' => $additional_document_back_image
            // ]);

            $additional_document_front_image = $this->uploadDocumentImages($request->additional_document_front_image, 'additional_verification', 'additional_document_front_image');
            $additional_document_back_image = $this->uploadDocumentImages($request->additional_document_back_image, 'additional_verification', 'additional_document_back_image');
            $document_front_image = $this->uploadDocumentImages($request->document_front_image, 'identity_document', 'document_front_image');
            $document_back_image = $this->uploadDocumentImages($request->document_back_image, 'identity_document', 'document_back_image');

            
            
            $res = $stripeAccount->accounts->update( $accout_id, [
                'business_type' => 'individual', 
                'individual'=> [
                    'first_name'=>"Jones",
                    'last_name'=>"Helen",
                    "dob" => [
                        "day" => '19',
                        "month" => '10',
                        "year" => '1997'
                    ],
                    // "ssn_last_4" => '8547',
                    // "id_number" => '778-62-8547',
                    // "phone" => '8005680948',
                    // "email" => 'gunjanmanghnani5@gmail.com',
                    "address" => [
                        "city" => 'Abbas Combe',
                        "state" => 'England',
                        "postal_code" => 'BA8',
                        "line1" => 'line 1',
                        "line2" => 'line 2'
                    ],
                    "verification" => [
                        "additional_document" => [
                            "back" => $additional_document_back_image,
                            "front" => $additional_document_front_image
                        ],
                        "document" => [
                            "back" => $document_back_image,
                            "front" => $document_front_image
                        ]
                    ]
                ],
                "business_profile" => [
                    "mcc" => "5734",
                    "name" => "EoxyIT",
                    "product_description" => "Software Development Company",
                    // "support_address" => "Mansarovar",
                    "support_email" => "support@eoxyit.com",
                    "support_phone" => "9876543210",
                    "support_url" => "https://eoxysitsolution.com/support",
                    "url" => "https://eoxysitsolution.com/"
                ],
            ]);
        }
        
        return $res; 
        exit;

        // $stripeAccountId = $account->id;
        
        // if ($stripeAccountId)
        // {

        //     $stripeAccountObj = $stripeAccount
        //         ->accounts
        //         ->retrieve($stripeAccountId);

        //     $caps = $stripeAccount
        //         ->accounts
        //         ->allCapabilities($stripeAccountId, []);

        // }

        // return $account; 

        // $saveAccId = DB::table('vendor_stripe_account_id')->insert([

        //         'landloard_id'           =>$user_id,
        //         'stripe_account_id' =>$account['id']
        // ]);

        // $account_links =  $stripeAccount->accountLinks->create([
        //   'account' => $account->id,
        //   'refresh_url' => "https://os.eoxysitsolution.com/login",
        //   'return_url' => "https://os.eoxysitsolution.com/login",
        //   'type' => 'account_onboarding',
        // ]);

        // return $account_links;
        // die;


       // $user = DB::table('vendor_stripe_account_id')->where('user_id','=',$vendorId)->first();

        /*$money = 40*100;
        $stripe = new \Stripe\StripeClient(
            'sk_test_51IyI6sEUI2VlKHRnMonCU5R8jWGutknpkAwcG5T513pHaEWxycYaDngoP7DKjRB5zKnAdSqTe1VURZhHNhcQX1yJ00gRqMhj8H'
        );

        \Stripe\Stripe::setApiKey('sk_test_51IyI6sEUI2VlKHRnMonCU5R8jWGutknpkAwcG5T513pHaEWxycYaDngoP7DKjRB5zKnAdSqTe1VURZhHNhcQX1yJ00gRqMhj8H');

        $paymentIntent = \Stripe\PaymentIntent::create([
            'amount' => $money,
            'currency' => 'usd',
            'payment_method_types' => ['card'],
            'transfer_group' => 1,
        ]);

       
        $stripe->tokens->create([
            'card' => [
            'number' => '4242424242424242',
            'exp_month' => 9,
            'exp_year' => 2022,
            'cvc' => '314',
            ],
        ]);

        $transferMoney = ($money / 100) *95;

        $transferMoney = \Stripe\Payout::create([
                'amount' => $transferMoney,
                'currency' => 'usd',
                'destination' => 'ba_1JcVzqRQnhWuidlbzg0SwwO4'
            ],
            [
                "stripe_account" => "acct_1JcVzqRQnhWuidlb"
            ]
        );

        echo 'gunjan gunjan';*/


        // Set your secret key. Remember to switch to your live secret key in production.
        // See your keys here: https://dashboard.stripe.com/apikeys

        // \Stripe\Stripe::setApiKey('sk_test_51IyI6sEUI2VlKHRnMonCU5R8jWGutknpkAwcG5T513pHaEWxycYaDngoP7DKjRB5zKnAdSqTe1VURZhHNhcQX1yJ00gRqMhj8H');

        // $method = \Stripe\PaymentMethod::create([
        //   'type' => 'card',
        //   'card' => [
        //     'number' => '4242424242424242',
        //     'exp_month' => 12,
        //     'exp_year' => 2022,
        //     'cvc' => '314',
        //   ],
        // ]);

        // $stripe = new \Stripe\StripeClient('sk_test_51IyI6sEUI2VlKHRnMonCU5R8jWGutknpkAwcG5T513pHaEWxycYaDngoP7DKjRB5zKnAdSqTe1VURZhHNhcQX1yJ00gRqMhj8H');
        // $src = $stripe->sources->create([
        //         "type" => "card",
        //         "currency" => "gbp",
        //         "owner" => [
                
        //         ],
        //         'card' => [
        //             'number' => '4242424242424242',
        //             'exp_month' => 12,
        //             'exp_year' => 2022,
        //             'cvc' => '314',
        //           ],
        //     ]);

        

       /* $charge = \Stripe\Charge::create([
          "amount" => 50000,
          "currency" => "gbp",
          'source' => $src->id,
          // 'payment_method' => $method->id,


           'amount' => 10 * 100,
          'currency' => 'gbp',
          // 'payment_method_types' => ['card'],
          // 'payment_method' => $method->id,
          'source' => $src->id,
          'transfer_group' => '10110',
          'on_behalf_of' => 'acct_1JdAFsRBEK31xziU'
        ]);*/

        // return "Sdfs";
        // exit;


        // Create a PaymentIntent:

        $paymentIntent = \Stripe\PaymentIntent::create([
          'amount' => 11 * 100,
          'currency' => 'gbp',
          'payment_method_types' => ['card'],
          'payment_method' => $method->id,
          'transfer_group' => '10110',
          'confirm'=>'true'
        ]);

        // Create a Transfer to a connected account (later):
        // $transfer = \Stripe\Transfer::create([
        //   'amount' => 1 * 100,
        //   'currency' => 'gbp',
        //   'destination' => 'acct_1JdAFsRBEK31xziU',
        //   'transfer_group' => '10110',
        // ]);


        // return $transfer;
        // die();



       // return  $accounntId = $stripe['account_id'];

   

  }


    public function uploadDocumentImages($file, $porpose, $name)
    {
        // code...

        $stripeAccount = new \Stripe\StripeClient(
            'sk_test_51IyI6sEUI2VlKHRnMonCU5R8jWGutknpkAwcG5T513pHaEWxycYaDngoP7DKjRB5zKnAdSqTe1VURZhHNhcQX1yJ00gRqMhj8H'
        );

        $file_name = time().$name.'.'.$file->extension();  
        
        $file->move(public_path('varification_doument'), $file_name);
        
        $file_url = fopen(public_path('varification_doument').'/'.$file_name, 'r');

        $stripe_file = $stripeAccount->files->create([
          'purpose' => $porpose,
          'file' => $file_url
        ]);

        return $stripe_file->id;
    }

  public function verifySuccess(Request $request){

    

    return $request;


  }




    // ewt

    public function createInvoice(Request $request)
    {
      // code...


        $user = User::where('id','=',$request->user_id)->first();
        // $space = Space::where('id','=',$request->space_id)->first();

        $bookedSpaceData['description']       =  'description';
        $bookedSpaceData['quantity']          =  10;
        $bookedSpaceData['unitAmount']        =  100;
        $bookedSpaceData['date']              =  "20-10-2021";
        $bookedSpaceData['DueDate']          =  "2021-10";
        $bookedSpaceData['TaxAmount']         =  2;
        $bookedSpaceData['AccountCode']       =  "154225ABC";
        $bookedSpaceData['Status']            =  "AUTHORISED";

        $data = [
            'Name'          => 'ACCPAY',
            'ContactNumber' => $user->phone,
            //'AccountNumber' => 'dfgdf',
            'FirstName'     => $user->first_name,
            'LastName'      => $user->last_name,
            'EmailAddress'  => $user->email,

        ]; 

        
        $contact = Xero::contacts()->store($data);

        return $contact['ContactID'];
        // $contactData = $this->contactCreate($data);

        //invoice create

        $data_1 = $data;

        $datainvoice = [
          'Type'          => "ACCREC",
          'Contact'       => $contact,
          'ItemCode'       =>
              [
              "Name" =>'Space Name',
              "Code" => "Item-1",
              "Description" => "Kuch or",
              "InventoryAssetAccountCode" =>[
                [
                  "AccountID" => "297c2dc5-cc47-4afd-8ec8-74990b8761e9",
                  "Code" => "200",
                  "Name" => "Sales account",
                  "Type" => "INVENTORY",
                  "TaxType" => "OUTPUT2",

                  "EnablePaymentsToAccount" => "false",
                  "ShowInExpenseClaims" => "false"
                ],
              ],

            ],
          'LineItems'     => [

            [
            "description" => "adsfsdaf",
            "quantity"    => 10,
            "unitAmount" => 1000,
            "date" => "10-12-2021",

            ]
          ],
        ];

        $invoice = Xero::invoices()->store($datainvoice);
        return $invoiceid =  $invoice['InvoiceID'];
        $invoice_url = Xero::invoices()->onlineUrl('380f2da9-c953-47c5-9762-3463293bd052');


    }




}
