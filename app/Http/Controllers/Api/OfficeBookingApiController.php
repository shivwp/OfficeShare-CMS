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
use App\UserSignature;
use App\Space;
use App\SpaceExtraDetails;
use App\SpaceType;
use App\PropertyLocation;
use App\BookingPayment;
use App\OfficeExtraDetails;
use DB;
use App\UserCard;
use App\Mail\Bookings;
use App\Mail\BookingSuccess;
use DrewM\MailChimp\MailChimp;
use Illuminate\Support\Facades\Mail;
use App\User;
use App\UserAddress;
use Validator;
use App\Setting;
use App\MailTemplate;
use App\PropertyAttributeValue;
use Illuminate\Support\Facades\Auth;
use Dcblogdev\Xero\Facades\Xero;
use Dcblogdev\Xero\Models\XeroToken;
use App\Refund;
use App\AvailabilityDesk;
use App\Helper\Helper;
use App\SpaceDayPrice;
use Exception;

class OfficeBookingApiController extends Controller
{

  public function __construct(Request $request) {
    // 
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

  public function index()
  {
    $d['title'] = "Manage Blogs";
    $d['blog'] = Blog::with('user')->orderBy('id', 'desc')->get();
    return view('admin.blog.index', $d);
  }

  public function avaliableDate(Request $request)
  {
    $validator = Validator::make($request->all(), [
       // 'id' => 'required',
        'space_id' => 'required',
        'no_of_desk' => 'required',
        'day_availability' => 'required',
    ]);


    if ($validator->fails()) {
      return response()->json(['status' => false, 'message' => implode("", $validator->errors()->all())], 200);
    }

      $totalDesk = DB::table('property_spaces')->where('id', '=', $request->space_id)->first();
      if(empty($totalDesk)){

        return response()->json(['status' => false, 'message' => 'space not found'], 200);

      }
      $totaldesk = $totalDesk->total_desk;

      $avaliable = DB::table('book_property_space')
              ->where('id', '=', $request->space_id)
              ->get();
    
      $today = today(); 
        $dates = []; 
        $days  = [];

        $date = \Carbon\Carbon::now()->format('Y');

        $current_date = \Carbon\Carbon::now()->format('d');
        $current_month = \Carbon\Carbon::now()->format('F');

         // echo $pr;
         // die;
        
        $count = 3;
      
        for ($j=0; $j < $count; $j++) { 
          # code...
          $current = \Carbon\Carbon::now();
          if($j!=0) {
            $current->addMonths($j);
          }
          $cusdates = array();
          for($i=1; $i < $current->daysInMonth + 1; ++$i) {
            $monthName = $current->format('F');
            $monthNo = $current->format('m');
            $c_data = \Carbon\Carbon::createFromDate($current->year, $current->month, $i)->format('Y-m-d');
            $c_date_in_d = \Carbon\Carbon::createFromDate($current->year, $current->month, $i)->format('d');
            $cdata = \Carbon\Carbon::createFromDate($current->year, $current->month, $i)->format('D');

            $totalDesk = DB::table('property_spaces')
                                    ->where('id','=',$request->space_id)
                                       ->first();

           $userBooking = UserBooking::where('space_id','=',$request->space_id)
                          ->where('booking_status','=','cancelled')
                           ->get();

           $getBookingId = [];

            foreach ($userBooking as $bk => $bv) {

              $getBookingId[] = $bv->id;

            }  

            $cancel = '';

           if($totalDesk->booking_approval == '1'){
              $cancel = 'enquiry_cancelled';
           }
           else{
             $cancel = 'cancelled';
           }

          $booked = BookOffice::where('space_id','=',$request->space_id)
                                    //->whereIn('booking_id',$getBookingId)
                                    ->whereDate('booked_date','=',$c_data)
                                    ->where('period_of_day','=',$request->day_availability)
                                    ->where('booking_status','!=',$cancel)
                                    ->sum('booked_desk');

        // avaliableCheck
        $avaliableCheck = AvailabilityDesk::where('space_id','=',$request->space_id)  
                                            ->whereDate('from_date', '<=', $c_data)
                                            ->whereDate('to_date', '>=', $c_data)
                                            ->first();
        $new_total_desk = $totaldesk;
        if(!empty($avaliableCheck)) {
          $c_data = date('Y-m-d', strtotime($c_data));   
          $startDate = date('Y-m-d', strtotime($avaliableCheck->from_date));
          $endDate = date('Y-m-d', strtotime($avaliableCheck->to_date)); 

          if (($c_data >= $startDate) && ($c_data <= $endDate)){   
            $new_total_desk = $avaliableCheck->available_desk;
          } else {
            $new_total_desk = $totaldesk;
          }
        }

        // $avaliablecheck = isset($avaliableCheck->available_desk) ? $avaliableCheck->available_desk : '0';

          $avaliableDesk = $new_total_desk-$booked;
          if($avaliableDesk >= $request->no_of_desk){
            $avaliable = true;
          }
          // elseif($cdata == 'Sun' || $cdata == 'Sat'){
          //   $avaliable = false;
          // }
          else{
            $avaliable = false;  
          }
         

          if($current_month == $monthName){
            if($current_date > $c_date_in_d){
              $avaliable = false;
            }

          }
          
          $varnumber = date('w', strtotime($c_data));
          if(date('d', strtotime($c_data)) == 01){
            $arrya_new = [];
            for($n=0; $n < $varnumber; $n++ ){
            $arrya_new = ['date'=>'','total'=>'','day'=>'','is_available'=>false,'day_no'=>'','is_selected'=>false];
            array_push($cusdates, (object)$arrya_new);

            }
          }
            array_push($cusdates, ['date'=>$c_data,'total'=>$new_total_desk,'day'=>$cdata,'is_available'=>$avaliable,'day_no'=>$varnumber,'is_selected'=>false]);


            //$dates[$monthName][$c_data] = ['date'=>$c_data,'total'=>$new_total_desk,'day'=>$cdata,'avaliable'=>$new_total_desk-$booked,'booked'=>$booked];
              
          }
 
 
         // $dates[$monthName] = $cusdates;

          $dates[] = ['month'=>$monthName,'month_no'=>$monthNo,'days'=>$cusdates];
        }
      
      
        return response()->json(['status' => true, 'message' => "Avaliable dates",'data'=>$dates], 200);

  }

  public function editavaliableDate(Request $request){

    $validator = Validator::make($request->all(), [
           // 'id' => 'required',
            'booking_id' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => false, 'message' => implode("", $validator->errors()->all())], 200);
        }

        $totalDesk = DB::table('property_spaces')->where('id', '=', $request->space_id)->first();
        if(empty($totalDesk)){

          return response()->json(['status' => false, 'message' => 'space not found'], 200);

        }
        $totaldesk = $totalDesk->total_desk;

      $avaliable = DB::table('book_property_space')
              ->where('id', '=', $request->space_id)
              ->get();

      $totalDesk = DB::table('property_spaces')
                                    ->where('id','=',$request->space_id)
                                       ->first();

          $totaldesk = $totalDesk->total_desk;
    
    
        $today = today(); 
        $dates = []; 
        $days  = [];

        $date = \Carbon\Carbon::now()->format('Y');

        $current_date = \Carbon\Carbon::now()->format('d');
        $current_month = \Carbon\Carbon::now()->format('F');

         // echo $pr;
         // die;

        //Get booked date
        $getBookedDate = [];

          $bookedDates =  DB::table('book_property_space')
                            ->where('booking_id','=',$request->booking_id)
                            ->get();


            foreach ($bookedDates as $dk => $dv) {

              $getBookedDate[] = $dv->booked_date;

            }

        $count = 3;
      
        for ($j=0; $j < $count; $j++) { 
          # code...
          $current = \Carbon\Carbon::now();
          if($j!=0) {
            $current->addMonths($j);
          }
          $cusdates = array();
          for($i=1; $i < $current->daysInMonth + 1; ++$i) {
            $monthName = $current->format('F');
            $monthNo = $current->format('m');
            $c_data = \Carbon\Carbon::createFromDate($current->year, $current->month, $i)->format('Y-m-d');
            $c_date_in_d = \Carbon\Carbon::createFromDate($current->year, $current->month, $i)->format('d');
            $cdata = \Carbon\Carbon::createFromDate($current->year, $current->month, $i)->format('D');

            $totalDesk = DB::table('property_spaces')
                                    ->where('id','=',$request->space_id)
                                       ->first();

                   //  $userBooking = UserBooking::where('space_id','=',$request->space_id)
                   //                ->where('booking_status','=','cancelled')
                   //                 ->get();

                   // $getBookingId = [];

                   //   foreach ($userBooking as $bk => $bv) {

                   //      $getBookingId[] = $bv->id;

                   //   }

          $booked = BookOffice::where('space_id','=',$request->space_id)
                                    //->whereIn('booking_id',$getBookingId)
                                    ->whereDate('booked_date','=',$c_data)
                                    ->where('period_of_day','=',$request->day_availability)
                                    ->sum('booked_desk');

          $is_recurring =UserBooking::where('id','=',$request->booking_id)->first();

          if($is_recurring->is_recurring == 1){

            $recurring = true;
          }else{
            $recurring = false;

          }

            // avaliableCheck
          $avaliableCheck = AvailabilityDesk::where('space_id','=',$request->space_id)  
                                              ->whereDate('from_date', '<=', $c_data)
                                              ->whereDate('to_date', '>=', $c_data)
                                              ->first();
          $new_total_desk = $totaldesk;
          if(!empty($avaliableCheck)) {
            $c_data = date('Y-m-d', strtotime($c_data));   
            $startDate = date('Y-m-d', strtotime($avaliableCheck->from_date));
            $endDate = date('Y-m-d', strtotime($avaliableCheck->to_date)); 

            if (($c_data >= $startDate) && ($c_data <= $endDate)){   
              $new_total_desk = $avaliableCheck->available_desk;
            } else {
              $new_total_desk = $totaldesk;
            }
          }

            $count_booked_date = count($getBookedDate);


            $avaliableDesk = $new_total_desk-$booked;
            if($avaliableDesk >= $request->no_of_desk){
              $avaliable = true;
            }
            // elseif($cdata == 'Sun' || $cdata == 'Sat'){
            //   $avaliable = false;
            // }
            else{
              $avaliable = false;  
            }

            if($current_month == $monthName){
              if($current_date > $c_date_in_d){
                $avaliable = false;
              }

            }
                    
          
              $varnumber = date('w', strtotime($c_data));
          if(date('d', strtotime($c_data)) == 01){
            $arrya_new = [];
            for($n=0; $n < $varnumber; $n++ ){
            $arrya_new = ['date'=>'','total'=>'','day'=>'','is_available'=>false,'day_no'=>'','is_selected'=>false,'is_recurring'=>$recurring];
            array_push($cusdates, (object)$arrya_new);

            }
          }
          
                    array_push($cusdates, ['date'=>$c_data,'total'=>$new_total_desk,'day'=>$cdata,'is_available'=>$avaliable,'day_no'=>$varnumber,'is_selected'=>(in_array($c_data, $getBookedDate)?true:false),'is_recurring'=>$recurring]);


            //$dates[$monthName][$c_data] = ['date'=>$c_data,'total'=>$totaldesk,'day'=>$cdata,'avaliable'=>$totaldesk-$booked,'booked'=>$booked];
              
          }
 
 
         // $dates[$monthName] = $cusdates;

          $dates[] = ['month'=>$monthName,'month_no'=>$monthNo,'days'=>$cusdates];
        }
      
      
        return response()->json(['status' => true, 'message' => "Avaliable dates",'data'=>$dates], 200);


  }

  public function editBooking(Request $request){


    $validator = Validator::make($request->all(), [
            'booking_id' => 'required',
            'space_id' => 'required',
            'user_id' => 'required',
            'date' => 'required',
            'number_of_desk' => 'required',
            'desk_price' => 'required',
            //'is_recurring' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => false, 'message' => implode("", $validator->errors()->all())], 200);
        }

        $book_date_count = count($request->date);

        $totalDeskPrice  = $request->desk_price * $request->number_of_desk; 

        $userBooking = BookOffice::where('booking_id','=',$request->booking_id)
                    ->delete();

        $book = UserBooking::where('id','=',$request->booking_id)->update([

                    "start_date"          =>      $request->date[0],

                    ]);



        for ($i = 0; $i <= $book_date_count; $i++) {

      if (!empty($request->date[$i])) {

        BookOffice::create([

          "user_id"           =>      $request->user_id,
          "booking_id"        =>      $request->booking_id,
          "space_id"          =>      $request->space_id,
          "booked_desk"         =>      $request->number_of_desk,
          "booked_date"       =>      $request->date[$i],
          "booking_price"        =>       $totalDeskPrice

        ]);
      }
    }

  return response()->json(['status' => true, 'message' => "booking updated successfully"], 200);


  }

  public function create(Request $request)
  {
      $validator = Validator::make($request->all(), [
           // 'id' => 'required',
            'space_id' => 'required',
            'user_id' => 'required',
            'date' => 'required',
            'number_of_desk' => 'required',
            //'is_recurring' => 'required',
        ]);

      if ($validator->fails()) {
        //
        return response()->json(['status' => false, 'message' => implode("", $validator->errors()->all()),"data"=>null,], 200);
      }

      try {
      
      $date_arr_for_booking = $request->date;

      //days

      $days = [];

      foreach ($request->date as $key => $value) {

        $cdata = \Carbon\Carbon::createFromDate($value)->format('D');

        $days[] = $cdata;
        
        }
        $costperday = [];

        $SpaceDayPrice = SpaceDayPrice::where('space_id','=',$request->space_id)->get();

        foreach ($SpaceDayPrice as $key => $value) {
          // 
          $costperday[$value->day] = $value->price;
        }

        // 

        $totalDeskPrice  = $request->desk_price * $request->number_of_desk; 


        $totalDesk = DB::table('property_spaces')
                                    ->where('id','=',$request->space_id)
                                       ->first();

        if(empty($totalDesk)){
          // 
          return response()->json(['status' => false, 'message' => "space not found","data"=>null,], 200);

        }

           $totaldesk = $totalDesk->total_desk;

           $gettype =  DB::table('property_type')
                                    ->where('id','=',$totalDesk->property_type_id)
                                       ->first();
           $type = $gettype->title;

           $book_date = $request->date;

           $book_date_count = count($request->date);

           //property & space details

           $propertyData      = Property::where('id','=',$request->property_id)
                                ->first();

           $property_Extra_details = OfficeExtraDetails::where('property_id','=',$request->property_id)->first();

           $propertyAddress     = PropertyLocation::where('property_id','=',$request->property_id)
                                  ->first();

           $spaceData         = Space::where('id','=',$request->space_id)->first();

           $space_Extra_details = SpaceExtraDetails::where('space_id','=',$request->space_id)->first();
           $landload_name       = User::where('id','=',$propertyData->user_id)->first();
           $user_name           = User::where('id','=',$request->user_id)->first();




            $booking_data['property'] = [

                    'property_title'        => $propertyData->property_title,
                    'property_id'          => $request->property_id,
                    'short_description'     => $propertyData->short_description,
                    'property_type'       => $type,
                    'description'         => $propertyData->description,
                    'property_total_desk'     => $propertyData->total_desk,
                    'property_featured_image'   => url('media/thumbnail/'.$propertyData->thumbnail),
                    'property_approved_status'  => $propertyData->is_approved,
                    'avg_rating'        => $propertyData->avg_rating,
                    'disability_access'     => isset($property_Extra_details->disability_access) && ($property_Extra_details->disability_access == 1) ? true : false,
                    'describe_your_space'     => isset($property_Extra_details->describe_your_space) ? $property_Extra_details->describe_your_space : '',
                    'how_to_find_us'      => isset($property_Extra_details->how_to_find_us) ? $property_Extra_details->how_to_find_us : '',
                    'insurance'         => isset($property_Extra_details->insurance) ? $property_Extra_details->insurance : '',
                    'covid_19_secure'       => isset($property_Extra_details->covid_19_secure) ? $property_Extra_details->covid_19_secure : '',
                    'londload_name'       => !empty($landload_name->name) ? $landload_name->name : '',
                    'user_name'         => isset($user_name->name) ? $user_name->name : '',
                    'postcode'          => isset($propertyAddress->postcode) ? $propertyAddress->postcode : '',
                    'address'           => isset($propertyAddress->address) ? $propertyAddress->address :'',
                    'city'            => $propertyAddress->city,
                    'state'           => $propertyAddress->state,
                    'country'           => $propertyAddress->country,
                    'longitude'         => $propertyAddress->longitude,
                    'latitude'          => $propertyAddress->latitude,
                    
                    ];

            $booking_data['space'] = [

                  'space_title'         => $spaceData->space_title,
                  'space_id'         => $spaceData->id,
                  'booking_approval'     => ($spaceData->booking_approval == 1) ? true : true,
                    'key_feature'         => $spaceData->key_feature,
                    'space_featured_image'    => url('media/thumbnail/'.$spaceData->thumb),
                    'price'             => $spaceData->cost,
                    'space_type'        => $type,
                    'sapce_total_desk'      => $spaceData->total_desk,
                    'space_approved_status'   => $spaceData->is_approved,
                    'min_term'          => isset($space_Extra_details->min_term) ? $space_Extra_details->min_term : '',
                    'max_term'          => isset($space_Extra_details->max_term) ? $space_Extra_details->max_term : '',
                    'things_not_included'       => isset($space_Extra_details->things_not_included) ? $space_Extra_details->things_not_included : '',

            ];

            

            $all_booking_dates = [];

              for ($i = 0; $i <= $book_date_count; $i++) {

                if (!empty($request->date[$i])) {

                $all_booking_dates[] = $request->date[$i];

              }

            }

            // 
            // dd($all_booking_dates);

            $booking_data['booking_details'] = [
              // 
              'start_date'          => $request->date[0],
              'booked_desk'           => $request->number_of_desk,
              'desk_price'          => $request->desk_price,
              'total_price'           => count($all_booking_dates)*$request->desk_price*$request->number_of_desk,
              'booking_dated'         => json_encode($all_booking_dates),

            ]; 

            // dd($booking_data);

        if($type == "Dedicated") {
          

          $bookedSpace = DB::table('book_property_space')
                                        ->where('space_id','=',$request->space_id)
                                        ->whereIn('booked_date',$request->date)
                                           ->get();

          if(count($bookedSpace) > 0){
            // 
            return response()->json(['status' => true, 'message' => "Space Already Booked","data"=>null], 200);

          } else {

            if ($totaldesk >= $request->number_of_desk) {

              $book = UserBooking::updateOrCreate(['id' => $request->booking_id],[

              "user_id"           =>      $request->user_id,
              "property_id"           =>      $request->property_id,
              "user_name"           =>      $user_name->name,
              "landload_id"         =>      $landload_name->id,
              "space_id"          =>      $request->space_id,
              "is_recurring"        =>  ($request->is_recurring == "true") ? 1 : 0,
              "request_together"        =>  ($request->request_together == "true") ? 1 : 0,
              "price"           =>  $costperday[$days[0]],
              "period_of_day"           =>  $request->period_of_day,
              "start_date"        =>    $request->date[0],
              "end_date"        =>    end($date_arr_for_booking),
              "data"            =>  json_encode($booking_data)

              ]);

                  

              $book_id = $book->id;

              if(!empty($request->booking_id)) {

                  BookOffice::where('booking_id','=',$request->booking_id)->delete();

                  for ($i = 0; $i <= $book_date_count; $i++) {

                        if (!empty($request->date[$i])) {

                          BookOffice::create([

                            "user_id"           =>      $request->user_id,
                            "booking_id"        =>      $book_id,
                            "space_id"          =>      $request->space_id,
                            "booked_desk"         =>      $request->number_of_desk,
                            "booked_date"       =>      $request->date[$i],
                            "day"               =>      $days[$i],
                            "booking_price"        =>       $costperday[$days[$i]],
                             "period_of_day"           =>  $request->period_of_day

                          ]);
                        }
                  }

              } else {

                for ($i = 0; $i <= $book_date_count; $i++) {

                  if (!empty($request->date[$i])) {

                    

                    BookOffice::create([

                      "user_id"           =>      $request->user_id,
                      "booking_id"        =>      $book_id,
                      "space_id"          =>      $request->space_id,
                      "booked_desk"         =>      $request->number_of_desk,
                      "booked_date"       =>      $request->date[$i],
                      "booking_price"        =>       $totalDeskPrice,
                       "day"               =>      $costperday[$days[$i]],
                       "period_of_day"           =>  $request->period_of_day

                    ]);
                  }
                }

              }

              // $user = User::where('id','=',$request->user_id)->first();
              // $m=$this->sendBookingMessageToUser($user,$request->number_of_desk,$request->space_id,$request->date);

              // if($m['status']==true){
              //   Mail::to($user->email)->send(new Bookings($m));   
              // }

              $bookres = UserBooking::where('id','=',$book->id)->first();
              unset($bookres->data);
              unset($bookres->landload_id);
              unset($bookres->is_recurring);
              unset($bookres->price);
              unset($bookres->booking_status);
              unset($bookres->start_date);
              $bookres->user_id =  (string)$bookres->user_id;
              $bookres->space_id =  (string)$bookres->space_id;

            

              return response()->json(['status' => true, 'message' => "Space booked","data"=>$bookres], 200);
              exit;
            } 

            if ($totaldesk <= $request->number_of_desk){

              return response()->json(['status' => true, 'message' => $request->number_of_desk . "desk are not avaliable in this Space","data"=>null], 200);

            }
          }

        }


        if( 
          $type == "Flexi desk" || 
          $type == "Shared" || 
          $type == "Short-Term" || 
          $type == "Long-Term" || 
          $type="Exclusive Occupancy" ||
          $type != "Dedicated"
        ) {

            $bookedSpace = DB::table('book_property_space')
                                        ->where('space_id','=',$request->space_id)
                                        ->whereIn('booked_date',$request->date)
                                           ->get();


            $book = UserBooking::updateOrCreate(['id' => $request->booking_id],[

              "user_id"           =>      $request->user_id,
              "user_name"           =>      $user_name->name,
               "property_id"           =>      $request->property_id,
              "landload_id"         =>      !empty($landload_name->id) ? $landload_name->id : 0,
              "space_id"          =>      $request->space_id,
              "is_recurring"        => ($request->is_recurring == "true") ? 1 : 0,
               "request_together"        =>  ($request->request_together == "true") ? 1 : 0,
              "price"           =>  $costperday[$days[0]],
              "period_of_day"           =>  $request->period_of_day,
              "start_date"        =>    $request->date[0],
              "end_date"        =>    end($date_arr_for_booking),
              "data"            =>  json_encode($booking_data)

            ]);

            $book_id = $book->id;

            if(!empty($request->booking_id)){

                BookOffice::where('booking_id','=',$request->booking_id)->delete();

                for ($i = 0; $i <= $book_date_count; $i++) {

                      if (!empty($request->date[$i])) {

                        BookOffice::create([

                        "user_id"           =>      $request->user_id,
                        "booking_id"        =>      $book_id,
                        "space_id"          =>      $request->space_id,
                        "booked_desk"         =>      $request->number_of_desk,
                        "booked_date"       =>      $request->date[$i],
                        "booking_price"        =>       $costperday[$days[$i]],
                         "day"               =>      $days[$i],
                          "period_of_day"           =>  $request->period_of_day

                        ]);
                      }
                }
            }
            else{

              for ($i = 0; $i <= $book_date_count; $i++) {

                if (!empty($request->date[$i])) {

                  BookOffice::create([

                  "user_id"           =>      $request->user_id,
                  "booking_id"        =>      $book_id,
                  "space_id"          =>      $request->space_id,
                  "booked_desk"         =>      $request->number_of_desk,
                  "booked_date"       =>      $request->date[$i],
                  "booking_price"        =>       $costperday[$days[$i]],
                  "day"               =>      $days[$i],
                   "period_of_day"           =>  $request->period_of_day

                  ]);
                }
              }

            }

                    

            // $user = User::where('id','=',$request->user_id)->first();

            // $m=$this->sendBookingMessageToUser($user,$request->number_of_desk,$request->space_id,$request->date);

            //             if($m['status']==true){
            //               Mail::to($user->email)->send(new Bookings($m));   
            //             }


            $bookres = UserBooking::where('id','=',$book->id)->first();
            unset($bookres->data);
            unset($bookres->landload_id);
            unset($bookres->is_recurring);
            unset($bookres->price);
            unset($bookres->booking_status);
            unset($bookres->start_date);
            $bookres->user_id =  (string)$bookres->user_id;
            $bookres->space_id =  (string)$bookres->space_id;

            return response()->json(['status' => true, 'message' => $request->number_of_desk ."Number of desk booked ",'data'=>$bookres],   200);
            exit;

        }



        if ($totaldesk <= $request->number_of_desk){

          return response()->json(['status' => true, 'message' => $request->number_of_desk . "desk are not avaliable in this Space","data"=>null,], 200);

        }
    
      }
      catch (Exception $e) {
        return response()->json(['status' => false, 'message' => "Error: ".$e, "data"=>null,], 200);
      }
  }

  public function confirmAndPayDetails(Request $request){


    $validator = Validator::make($request->all(), [
           // 'id' => 'required',
            'booking_id' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => false, 'message' => implode("", $validator->errors()->all())], 200);
        }

        $userBooking = UserBooking::where('id','=',$request->booking_id)->first();

        $allbbokings = BookOffice::where('booking_id','=',$request->booking_id)->get();

        $cost = BookOffice::where('booking_id','=',$request->booking_id)->sum('booking_price');

        $totaldesk  = BookOffice::where('booking_id','=',$request->booking_id)->sum('booked_desk');

        //$totalcost  = $totaldesk*$cost;

        $total_no_of_days = count($allbbokings);


        $allDates = [];

        $dates = [];
        $costtotal = [];

         $singledaydesk  = BookOffice::where('booking_id','=',$request->booking_id)->first();

        foreach ($allbbokings as $key => $value) {

          $allDates[] = $value->booked_date;
        }

        foreach ($allDates as $date_key => $date_val) {

           $singleDate = BookOffice::where('booking_id','=',$request->booking_id)
                        ->where('booked_date','=',$date_val)
                        ->first();

       $desk  = BookOffice::where('booking_id','=',$request->booking_id)->sum('booked_desk');

       $dates[$date_key] = ['day'=> date("d-m-Y", strtotime($singleDate->booked_date) ),'desk'=>$singleDate->booked_desk,'cost'=>$singleDate->booked_desk*$singleDate->booking_price];

        $costtotal[] = $singleDate->booked_desk*$singleDate->booking_price;


        }

        $totalcost = array_sum($costtotal);
        $data  = [
          
          'total_desk'    =>!empty($singledaydesk) ? $singledaydesk->booked_desk : 0,
          'total_days'    =>!empty($total_no_of_days) ? $total_no_of_days : 0,
          'total_cost'    =>!empty($totalcost) ? $totalcost : 0,
          'dates'       =>(!empty($dates) && is_array($dates)) ? $dates : 0
        ];

        if(!empty($data) && !empty($userBooking)){

          return response()->json(['status' => true, 'message' => "success",'data'=>$data], 200);

        }
        else{

          return response()->json(['status' => false, 'message' => "unsuccess",'data'=>[]], 200);

        }

  }

   function sendBookingMessageToUser($user,$desk,$spaceid,$date)
    {
      //date("d-m-Y", strtotime($singleDate->booked_date)
      $date_arr = [];
      foreach ($date as $key => $value) {
        $date_arr[] = date("d-F-Y", strtotime($value));
      }

      $booking_dates = implode(" , ",$date_arr);

      // $user = User::where('id','=',$userid)->first();
      $space = Space::where('id','=',$spaceid)->first();
      $property = Property::where('id','=',$space->property_id)->first();
      $landload = User::where('id','=',$property->user_id)->first(); 

    
      try {
        $st = Setting::where('options','=','site_url')->first();
        $st1 = Setting::first();
        $sign = [
          '{name}' => $user->name,
          '{date}' => $booking_dates,
          '{space_name}' => $space->space_title,
          '{landload}' => $landload->name,
        ];
        $msgData = MailTemplate::where('status', trim('placed'))->first();
        $replMsg = MailTemplate::where('status', trim('placed'))->pluck('message')->first();
        //dd($replMsg);
        foreach ($sign as $key => $value) {
            $replMsg = str_replace($key, $value, $replMsg);
        }
        if (isset($msgData)) {
            return ['fromemail' => $msgData->from_email, "replyemail" => $msgData->reply_email, 'msg' => $replMsg, 'subject' => $msgData->subject, 'name' => $msgData->name, 'status' => true];
        } else {
            return ['status' => false];
        }
      } 
      catch (Exception $e) {
      
      }
    }

    public function bookingpayment(Request $request) {


        $validator = Validator::make($request->all(), [
            // 'id' => 'required',
            'booking_id' => 'required',
        ]);


        if ($validator->fails()) {
            return response()->json(['status' => false, 'message' => implode("", $validator->errors()->all())], 200);
        }

        $parameters = $request->all();
        extract($parameters);

        $booking = UserBooking::where('id', $booking_id)->first();
        $user = User::where('id', $booking->user_id)->first();
        $landload = User::where('id', $booking->landload_id)->first();
        $space = Space::where('id','=',$booking->space_id)->first();
        $userid   = $booking->user_id;
        $landloadid = $booking->landload_id;

        $landload = User::where('id', $booking->landload_id)->first();

        $stripeAccount = new \Stripe\StripeClient(
            'sk_test_51IyI6sEUI2VlKHRnMonCU5R8jWGutknpkAwcG5T513pHaEWxycYaDngoP7DKjRB5zKnAdSqTe1VURZhHNhcQX1yJ00gRqMhj8H'
        );

        \Stripe\Stripe::setApiKey('sk_test_51IyI6sEUI2VlKHRnMonCU5R8jWGutknpkAwcG5T513pHaEWxycYaDngoP7DKjRB5zKnAdSqTe1VURZhHNhcQX1yJ00gRqMhj8H');


        $customer_id = '';
    

        if($user->customer_id == "") {
          // 
          $customer = $stripeAccount->customers->create([
            'email' => $user->email,
            'name' => $user->name,
            'phone' => ($user->phone != '') ? $user->phone : '',
            'description' => 'customer_'.$user->id,
            //"source" => $src_token, 
          ]);  // -- done

          $customer_id = $customer->id;

          User::where('id', $user->id)->update([
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
            );  //-- done

            $card_token = $cardinfo->id;

          } 
          catch (\Stripe\Exception\InvalidRequestException $e) {
            return response()->json(['status' => false, 'message' => $e->getError()->message], 200);
          }
          
          $new_card = UserCard::insert([
            'user_id' => $user->id, 
            'user_customer_id' => $customer_id,
            'card_token' => $card_token,
            // 'last4' => ($last4 !="")?$last4:'',
            // 'expiry_month' =>($expiry_month !="")?$expiry_month:'',
            // 'expiry_year' =>($expiry_year !="")?$expiry_year:'',
            // 'card_type' => ($card_type !="")?$card_type:'',
          ]);
          

        } else {
          //$card = UserCard::where('id', $card_id)->first();
          $card_token = $src_token;
        }

        try {

          $paymentIntent = \Stripe\PaymentIntent::create([
              'amount' => $booking->price * 100,
              'currency' => 'gbp',
              'payment_method_types' => ['card'],
              // 'payment_method' => $src->id,
              'customer' => $customer_id,
              'payment_method' => $card_token, // 'card_1Jht6ZEUI2VlKHRnc5KrHBMF',
              'transfer_group' => $booking_id,
              'confirm'=>'true',
              // 'shipping' => [
              //     'name' => $request->first_name.' '.$request->last_name,
              //     'phone' => $request->phone,
              //     'address' => [
              //         'city' => $request->city,
              //         'country' => $request->country,
              //         'line1' => $request->address_1,
              //         'line2' => $request->address_2,
              //         'postal_code' => $request->postal_code,
              //         'state' => $request->state,
              //     ]
              // ]
          ]);
        }  catch (\Stripe\Exception\InvalidRequestException $e) {
          // return $e;
           // Invalid parameters were supplied to Stripe's API
           return response()->json(['status' => false, 'message' => $e->getError()->message], 200);
         }
       


        //return $paymentIntent;

        if($paymentIntent->status == 'succeeded') {

          $booked = UserBooking::where('space_id','=',$booking->space_id)
                                        ->where('user_id','=',$booking->user_id)
                                        ->where('id','=',$booking_id)
                                        ->update(['booking_status'=> 'booked']);
          $booked_dates = BookOffice::where('booking_id','=',$booking_id)
                                      ->update(['booking_status'=> 'booked']);

          $bookingPaymentStatus = BookingPayment::create([

            'user_id'       => $booking->user_id,
            'booking_id'    => $booking_id,
            'space_id'      => $booking->space_id,
            'status'        => $paymentIntent->status,
            'message'       => $paymentIntent->status,
            'trans_id'      => $paymentIntent->id,
            'charges_id'    => $paymentIntent->charges->data[0]->id,
            'balance_transaction' => $paymentIntent->charges->data[0]->balance_transaction,
            'trans_status'  => $paymentIntent->status,

          ]);

          $user = User::where('id','=',$booking->user_id)->first();

          $property = Property::where('id','=',$booking->property_id)->first();

          $space = Space::where('id','=',$booking->space_id)->first();

          $location = PropertyLocation::where('property_id','=',$booking->property_id)->first();

          // $booking = UserBooking::where('id','=',$booking->booking_id)->first();

          $bookedSpace = BookOffice::where('booking_id','=',$booking_id)->get();

          $bookedSpaceData = [];
          $bookedSpace_count = 0;
          foreach ($bookedSpace as $key_space => $value_space) {
              $bookedSpaceData[] = $value_space->booked_date;
              $bookedSpace_count = $bookedSpace_count + $value_space->booked_desk;
          }

          // $bookedSpace_count = $bookedSpace[0]->booked_desk;

          // if(isset($directpayment)) {
          //   // 
          //   $userDetails = UserAddress::create([

          //     'first_name'       => $request->first_name,
          //     'last_name'    => $request->last_name,
          //     'dob'         => $request->dob,
          //     'email'        => $request->email,
          //     'address_1'       => $request->address_1,
          //     'address_2'       => $request->address_2,
          //     'city'       => $request->city,
          //     'state'       => $request->state,
          //     'country'       => $request->country,
          //     'user_id'       => $user->id,
          //     'phone'       => $request->phone,
          //     'postcode'       => $request->postal_code,
          //     'booking_id' => $booking_id
          //   ]);
          // }

           $data = [

            'property_title'      => $property->property_title,
            'space_title'         => $space->space_title,
            'address_1'           => $location->address,
            'address_2'           => $location->address_2,
            'city'                => $location->city,
            'state'               => $location->state,
            'country'             => $location->country,
            'postcode'            => $location->postcode,
            'transaction_id'      => $paymentIntent->id,
            'booked_dates'        => $bookedSpaceData,
            'customer_id'         => $customer_id,
            'user'                => $user,
            'landlord_email'      => $landload->email,
           ];


           //Attach a PaymentMethod to a Customer

          $stripe = new \Stripe\StripeClient(
            'sk_test_51IyI6sEUI2VlKHRnMonCU5R8jWGutknpkAwcG5T513pHaEWxycYaDngoP7DKjRB5zKnAdSqTe1VURZhHNhcQX1yJ00gRqMhj8H'
          );


          // $date_arr = [];
          // foreach ($date as $key => $value) {
          //   $date_arr[] = date("d-F-Y", strtotime($value));
          // }

          // $booking_dates = implode(" , ",$date_arr);

          // $user = User::where('id','=',$userid)->first();
          // $space = Space::where('id','=',$spaceid)->first();
          $property = Property::where('id','=',$space->property_id)->first();
          $landload = User::where('id','=',$property->user_id)->first(); 

          $address = [
            'address_1'           => $location->address,
            'address_2'           => $location->address_2,
            'city'                => $location->city,
            'state'               => $location->state,
            'country'             => $location->country,
            //'postcode'            => $location->postcode
          ]; 
          $full_address = '';
          foreach ($address as $key => $value) {
            // code...
            $full_address .= isset($value)?$value.' ':'';
          }
          try {

            $st = Setting::where('options','=','site_url')->first();
            $st1 = Setting::first();
            $start_date=date_create($booking->start_date);
            $end_date=date_create($booking->end_date);

            $booking_dates_days = '';

            $dateofbooking = BookOffice::where('booking_id','=',$request->booking_id)->get();
            $image = '<img src="'.url('/').'/images/day.png" />';
            foreach ($dateofbooking as $key => $value) {
                $period_of_day = '';
                if($value->period_of_day == 1){
                  // 
                  $period_of_day = 'day';
                  $image = '<img src="'.url('/').'/images/day.png" />';

                }
                if($value->period_of_day == 2){
                  // 
                  $period_of_day = 'night';
                  $image = '<img src="'.url('/').'/images/night.png" />';

                }

                $day = \Carbon\Carbon::createFromDate($value->booked_date)->format('D');

                $booking_dates_days .= $day.'('.$image.' '.$period_of_day.'),';

            } 

            $get_directions = "http://maps.google.com/maps?q=".$location->latitude.",".$location->longitude;

            if($space->booking_payment_refund == 1){

              $refundmsg = "This booking is non-refundable after 24 Hours";
            }
            elseif($space->booking_payment_refund == 2){
              // 
              $refundmsg = "This booking is non-refundable after 48 Hours";
            }
            else{
              // 
              $refundmsg = "This booking is non-refundable.";
            }

            $rating = $landload->avg_rating??0;
            $ra = 'Bad';
            if($rating == 1)
            {
              $ra = 'Normal';
            } else if($rating >1 && $rating <3 ) {
              $ra = 'good';
            } else if($rating > 3 && $rating <= 5) {
              $ra  = 'Awesome';
            }
            $invoice = 'invoice'.$request->booking_id; // need to get invoice from xero
            $sign = [
              '{{name}}' => $user->name,
              '{{ref_id}}' => '#'.$request->booking_id,
              '{{location}}' => $full_address,
              '{{space_name}}' => $space->space_title,
              '{{number_of_desks}}' => $bookedSpace_count.' Desks',
              '{{start_date}}' => date_format($start_date,"Y-M-d"),
              '{{end_date}}' => date_format($end_date,"Y-M-d"),
              '{{booking_days}}' => $booking_dates_days,
              '{{host_name}}' => $landload->name,
              '{{host_email}}' => $landload->email,
              '{{host_phone}}' => $landload->phone,
              '{{get_directions}}' => $get_directions,
              '{{refund_msg}}' => $refundmsg,
              '{{rating}}' => ($landload->avg_rating)??'0'.' Rating',
              '{{rating_status}}' => $ra, //'Good',
              '{{profile_pic}}' => '<img src="'.$landload->profile_pic.'" />',
              '{{invoice}}' => $invoice,
              '{{cancel_policy}}' => '<a href="#cancel_policy" style="text-decoration: none; color: #fff;">Cancel Policy</a>',
              '{{chat_with_host_name}}' => '<a href="'.env('frontend_url').'/#/chat/380"  style="text-decoration: none; color: #fff;">Chat with '.$landload->name.' on the OfficeShare App</a>',
            ];
            
            $msgData = MailTemplate::where('status', trim('booking_success'))->first();
           

            // $replMsg = MailTemplate::where('status', trim('booking_success'))->pluck('message')->first();
            $replMsg = $msgData->message;
            foreach ($sign as $key => $value) {
                $replMsg = str_replace($key, $value, $replMsg);
            }
            
            $mail_msg = ['fromemail' => $msgData->from_email, "replyemail" => $msgData->reply_email, 'msg' => $replMsg, 'subject' => $msgData->subject, 'name' => $msgData->name, 'status' => true];

            Mail::to($user->email)->send(new BookingSuccess($mail_msg));

             $msg = "Booking successfull";

             Helper::saveBookingActivity($request->booking_id,$msg);
          } 
          catch (Exception $e) {
            
          }

          //return $replMsg; 
          
          //send notificaion to user
          // $user = User::where('id','=', $userid)->first();
          // $title = "Space Booking";

          // $msg = $user->name." you booked a space ".$space->space_title;
          // $image ="sdf";
          // $user_id = $user->id;
          // $type = "space_booking_user";         
          // $notification = Helper::sendNotification($title,$msg,$image,$user_id,$type);
          

          //send notification to vendor
          $user = User::where('id','=', $landloadid)->first();
          $title = "Space Booked";

          $msg = $landload->name." your space ".$space->space_title. ' is booked.';
          $image ="sdf";
          $landload_id = $landload->id;
          $type = "space_booking_admin";         
          $notification = Helper::sendNotification($title,$msg,$image,$landload_id,$type);


          return response()->json(['status' => true, 'message' => "Your Space Booked Successfully", 'data'=>$data], 200);
        } else {

            $msg = "Booking failed";

            Helper::saveBookingActivity($request->booking_id,$msg);
          return response()->json(['status' => false, 'message' => "Your Booking Failed", 'transaction_id' => ''], 200);
        }


   }
  

  public function mybooking(Request $request)
  {

    //dd($request->month);
    
    DB::enableQueryLog();

    
    $validator = Validator::make($request->all(), [
           'user_id' => 'required'
        ]);

         if ($validator->fails()) {
             return response()->json(['status' => false, 'message' => implode("", $validator->errors()->all())], 200);
         }

         $req = [

          'user_id'   => $request->user_id,
          'page'    => $request->page,
          'limit'   => $request->limit,
          'date'    => $request->date,
          'month'   => $request->month,
          'year'    => $request->month,
          'week'    => $request->month,

         ];



         $current_date = \Carbon\Carbon::now()->format('Y-m-d');
         $bookingPrev = [];
         $bookingNext = [];
     
     $userAllBookings = UserBooking::where('user_id','=',$request->user_id)->get();
     
     if(count($userAllBookings)>0){

      $page   = !empty($request->page) ? $request->page : 1;
      $limit  = !empty($request->limit) ? $request->limit : 5;
      $month  = $request->month;
      $year   = $request->year;

      $previousBooking = UserBooking::where('user_id','=',$request->user_id)
                        ->where('start_date','<',$current_date)
                        ->orderBy('start_date', 'DESC');


        if(!empty($month)){

          $previousBooking->whereMonth('start_date','=',$month);
        }

        if(!empty($year)){

          $previousBooking->whereYear('start_date','=',$year);

        }
        if(!empty($request->from_date) && !empty($request->to_date)){

              if($request->from_date == $request->to_date){

                $previousBooking->whereDate('start_date','=',$request->from_date);

              }
              else{

                $previousBooking->whereBetween('start_date',[$request->from_date,$request->to_date]);
              }

        }

        // echo  $dataa1 = $previousBooking->toSql();
        // exit;

          //  $dataa = $previousBooking->limit($limit)->offset(($page - 1) * $limit)->get();

            $dataa = $previousBooking->get();

            // dd($previousBooking);

      if(count($dataa)>0){

        foreach ($dataa as $key => $value) {

            $booked_dates = BookOffice::select('booked_date','booked_desk','booking_price')->where('booking_id','=',$value->id)->get();
             $total_days        = BookOffice::where('booking_id','=',$value->id)->get();
            $total_amount        = BookOffice::where('booking_id','=',$value->id)->sum('booking_price');

            //refund message

            $space = Space::where('id','=',$value->space_id)->first();

            $refund = Refund::where('id','=',$space->booking_payment_refund)->first();

            $refund_message = $refund->message;

            if(!empty($value->data)){

              //$dataa[$key]['booing_details'] = json_decode($value->data);

              $a = json_decode($value->data);

               $booking_status = 'pending';

              if($value->booking_status == 'pending'){

                $booking_status = 'pending';

              }

              if($value->booking_status == 'booked'){

                $booking_status = 'booked';
              }

              if($value->booking_status == 'cancelled'){

                $booking_status = 'cancelled';
              }

              if($value->booking_status == 'enquiry_pending'){

                $booking_status = 'enquiry pending';
              }

                if($value->booking_status == 'enquiry_approved'){

                $booking_status = 'enquiry approved';
              }

               if($value->booking_status == 'enquiry_cancelled'){

                $booking_status = 'enquiry cancelled';
              }


              //booking details
              $booking_date['booking_id'] = $value->id;
              $booking_date['start_date'] = $a->booking_details->start_date;
              $booking_date['booked_desk'] = json_decode($a->booking_details->booked_desk);
              $booking_date['desk_price'] = json_decode($a->booking_details->desk_price);
              $booking_date['total_price'] = json_decode($a->booking_details->total_price);
              $booking_date['booking_status'] = $booking_status;
              $booking_date['total_days'] = count($total_days);
              $booking_date['total_amount'] = $total_amount;
              $booking_date['period_of_day'] = $value->period_of_day;
              $booking_date['booking_payment_refund'] = $refund_message;
              
              $booking_date['booking_cancel_message'] = ($space->booking_payment_refund == 1) ? "Sorry, this booking amount is in 24 hours refundable . Are you sure want to cancel booking ?" : ( ($space->booking_payment_refund == 1) ? "Sorry, this booking amount is in 48 hours refundable . Are you sure want to cancel booking ?" : "this booking is non refundable");
              $booking_date['booked_dates'] = json_decode($a->booking_details->booking_dated);
          


              //landoad details

              $landloadetals = User::where('id','=',$value->landload_id)->first();

              // $landload['name'] = $landloadetals->name;
              // $landload['phone'] = $landloadetals->phone;
              // $landload['email'] = $landloadetals->email;


              //user details 

              $userDeails = User::where('id','=',$value->user_id)->first();

              // $user['user'] = $userDeails->name;
              // $user['phone'] = $userDeails->phone;
              // $user['email'] = $userDeails->email;


              $bookingPrev[$key]['property']    = $a->property;
              $bookingPrev[$key]['space']       = $a->space;
              $bookingPrev[$key]['landload']      = $landloadetals;
              $bookingPrev[$key]['user']      = $userDeails;

              $bookingPrev[$key]['booking_details']         = $booking_date;
            }

        }

      }

      $upcommingBookings  = UserBooking::where('user_id','=',$request->user_id)
                            ->where('start_date','>=',$current_date)
                            ->orderBy('start_date', 'DESC');

      

        if(!empty($month)){

          $upcommingBookings->whereMonth('start_date','=',$month);
        }

        if(!empty($year)){

          $upcommingBookings->whereYear('start_date','=',$year);

        }

         if(!empty($request->from_date) && !empty($request->to_date)){

              if($request->from_date == $request->to_date){

                $upcommingBookings->whereDate('start_date','=',$request->from_date);

              }
              else{

                $upcommingBookings->whereBetween('start_date',[$request->from_date,$request->to_date]);
              }

        }

    

      //$dataa1 = $upcommingBookings->limit($limit)->offset(($page - 1) * $limit)->get();
      $dataa1 = $upcommingBookings->get();

      if(count($dataa1)>0){

        foreach ($dataa1 as $k => $v) {

          $booked_dates1 = BookOffice::select('booked_date')->where('booking_id','=',$v->id)->get();
          $total_days        = BookOffice::where('booking_id','=',$v->id)->get();
          $total_amount        = BookOffice::where('booking_id','=',$v->id)->sum('booking_price');

          //refund message

          $space = Space::where('id','=',$v->space_id)->first();

          $refund = Refund::where('id','=',$space->booking_payment_refund)->first();

          $refund_message = $refund->message;
        

            if(!empty($v->data)){

              $a = json_decode($v['data']);


              //landoad details

              $landloadetals = User::where('id','=',$v->landload_id)->first();

              //user details 

              $userDeails = User::where('id','=',$v->user_id)->first();

              $booking_status = 'pending';

              if($v->booking_status == 'pending'){

                $booking_status = 'pending';

              }

              if($v->booking_status == 'booked'){

                $booking_status = 'booked';
              }

              if($v->booking_status == 'cancelled'){

                $booking_status = 'cancelled';
              }

              if($v->booking_status == 'enquiry_pending'){

                $booking_status = 'enquiry pending';
              }

                if($v->booking_status == 'enquiry_approved'){

                $booking_status = 'enquiry approved';
              }

               if($v->booking_status == 'enquiry_cancelled'){

                $booking_status = 'enquiry cancelled';
              }

            //  dd(DB::getQueryLog());




              //dd($a->booking_details);
              $booking_date['booking_id'] = $v->id;
              $booking_date['start_date'] = $a->booking_details->start_date;
              $booking_date['booked_desk'] = json_decode($a->booking_details->booked_desk);
              $booking_date['desk_price'] = json_decode($a->booking_details->desk_price);
              $booking_date['total_price'] = json_decode($a->booking_details->total_price);
              $booking_date['period_of_day'] = $v->period_of_day;
              $booking_date['booking_status'] = $booking_status;
                $booking_date['total_days'] = count($total_days);
              $booking_date['total_amount'] = $total_amount;
               $booking_date['booking_payment_refund'] = $refund_message;
                 $booking_date['booking_cancel_message'] = ($space->booking_payment_refund == 1) ? "Sorry, this booking amount is in 24 hours refundable . Are you sure want to cancel booking ?" : ( ($space->booking_payment_refund == 1) ? "Sorry, this booking amount is in 48 hours refundable . Are you sure want to cancel booking ?" : "this booking is non refundable");
              $booking_date['booked_dates'] = json_decode($a->booking_details->booking_dated);
            

              $bookingNext[$k]['property']    = $a->property;
              $bookingNext[$k]['space']       = $a->space;
              $bookingNext[$k]['landload']      = $landloadetals;
              $bookingNext[$k]['user']      = $userDeails;
              $bookingNext[$k]['booking_details']   = $booking_date;
              
            }
        }

      }
      
      $data = ['previous'=>$bookingPrev,'upcomming'=>$bookingNext];



      return response()->json(['status' => true, 'request'=>$req, 'message' => "success",'data'=>$data], 200);

    }
    else{

       $data = ['previous'=>[],'upcomming'=>[]];
      return response()->json(['status' => true, 'request'=>$req,'message' => "unsuccess",'data'=>$data],200);

      }
    
    
    
  }
  public function landloadbooking(Request $request){


    $validator = Validator::make($request->all(), [
           'landload_id' => 'required'
        ]);

         if ($validator->fails()) {
             return response()->json(['status' => false, 'message' => implode("", $validator->errors()->all())], 200);
         }

          $req = [

          'landload_id'         => $request->user_id,
          'from_date'           => $request->from_date,
          'to_date'             => $request->to_date,

         ];

         $current_date = \Carbon\Carbon::now()->format('Y-m-d');

         if(!empty($request->from_date) && !empty($request->to_date)){

              if($request->from_date == $request->to_date){

                $booking = UserBooking::where('landload_id','=',$request->landload_id)
                ->whereDate('start_date','=',$request->from_date)
                ->orWhereDate('start_date','=',$request->to_date)
                ->get();

              }
              else{

                $booking = UserBooking::where('landload_id','=',$request->landload_id)
                ->whereBetween('start_date',[$request->from_date,$request->to_date])
                ->get();
              }

         }
         else{

          $booking = UserBooking::where('landload_id','=',$request->landload_id)
                        ->get();

         }
    

    $allLandloadBookings = [];

    //dd($booking);

     if(count($booking)>0){

        foreach ($booking as $key => $value) {

          $booked_dates1 = BookOffice::select('booked_date')->where('booking_id','=',$value->id)->get();
          $total_days        = BookOffice::where('booking_id','=',$value->id)->get();
          $total_amount        = BookOffice::where('booking_id','=',$value->id)->sum('booking_price');

          
          //refund message

          $space = Space::where('id','=',$value->space_id)->first();
          if(isset($space)) { 
            $refund = Refund::where('id','=',$space->booking_payment_refund)->first();
          }

          if(isset($refund)) {
            $refund_message = $refund->message;
          } else{
            $refund_message = '';
          }


          $a = json_decode($value['data']);

         // Date time

               $timestamp = strtotime($value->created_at);

               $day = date('D', $timestamp);

               $time = date("H:i:s",$timestamp);

               $timeNew = date("h:i A", strtotime($time));

                 // booking status

                $booking_status = 'pending';
                if($value->booking_status == 'pending'){

                      $booking_status = 'pending';

                }

                if($value->booking_status == 'booked'){

                    $booking_status = 'booked';
                }

                if($value->booking_status == 'cancelled'){

                  $booking_status = 'cancelled';
                }

                if($value->booking_status == 'enquiry_pending'){

                  $booking_status = 'enquiry pending';
                }

                  if($value->booking_status == 'enquiry_approved'){

                  $booking_status = 'enquiry approved';
                }

                 if($value->booking_status == 'enquiry_cancelled'){

                  $booking_status = 'enquiry cancelled';
                }

          //booking details
              $booking_date['booking_id'] = $value->id;
              $booking_date['start_date'] = $a->booking_details->start_date;
              $booking_date['booked_desk'] = json_decode($a->booking_details->booked_desk);
              $booking_date['desk_price'] = json_decode($a->booking_details->desk_price);
              $booking_date['period_of_day'] = $value->period_of_day;
              $booking_date['booking_status'] = $booking_status;
              $booking_date['total_price'] = json_decode($a->booking_details->total_price);
              $booking_date['booking_status'] = $value->booking_status;
              $booking_date['total_days'] = count($total_days);
              $booking_date['total_amount'] = $total_amount;
              $booking_date['booking_payment_refund'] = $refund_message;
              $booking_date['booking_cancel_message'] = ($space->booking_payment_refund == 1) ? "Sorry, this booking amount is in 24 hours refundable . Are you sure want to cancel booking ?" : ( ($space->booking_payment_refund == 1) ? "Sorry, this booking amount is in 48 hours refundable . Are you sure want to cancel booking ?" : "this booking is non refundable");
              $booking_date['booked_dates'] = json_decode($a->booking_details->booking_dated);
              $booking_date['booking_day'] = $day;
              $booking_date['booking_time'] = $timeNew;

          //landoad details

              $landloadetals = User::where('id','=',$value->landload_id)->first();


          //user details 

              $userDeails = User::where('id','=',$value->user_id)->first();


          $allLandloadBookings[$key]['property']          = $a->property;
          $allLandloadBookings[$key]['space']             = $a->space;
          $allLandloadBookings[$key]['landload']          = $landloadetals;
          $allLandloadBookings[$key]['user']              = $userDeails;
          $allLandloadBookings[$key]['booking_details']   = $booking_date;

          

        }

    return response()->json(['status' => true, 'request'=>$req, 'message' => "success",'data'=>$allLandloadBookings], 200);

    }
    else{
      return response()->json(['status' => false, 'request'=>$req, 'message' => "unsuccess",'data'=>[]], 200);

      }

  }

  public function singlebooking(Request $request){



    $validator = Validator::make($request->all(), [
           'booking_id' => 'required'
        ]);

         if ($validator->fails()) {
             return response()->json(['status' => false, 'message' => implode("", $validator->errors()->all())], 200);
         }


    $booking = UserBooking::where('id','=',$request->booking_id)
                        ->get();

    if(!empty($booking)){

    foreach ($booking as $key => $value) {



        $booked_dates = BookOffice::select('booked_date','booked_desk','booking_price')->where('booking_id','=',$value->id)->get();

        if(!empty($value->data)){

          $booking[$key]['booing_details'] = json_decode($value->data);

          $booking[$key]['booing_dates']   = $booked_dates;
          
          unset($value->data);
        }

        }

    return response()->json(['status' => true, 'message' => "success",'data'=>$booking], 200);

    }
    else{
      return response()->json(['status' => false, 'message' => "unsuccess",'data'=>[]], 200);

    }
    

  }


  public function canclebooking(Request $request){

    $validator = Validator::make($request->all(), [
           'booking_id' => 'required'
        ]);

         if ($validator->fails()) {
             return response()->json(['status' => false, 'message' => implode("", $validator->errors()->all())], 200);
         }

         $booking = UserBooking::where('id','=',$request->booking_id)
                        ->get();

    if(!empty($booking)){

    UserBooking::where('id','=',$request->booking_id)
                        ->update([

                          'booking_status' => 'cancelled'
                        ]);

      $msg = "Booking cancelled";

      Helper::saveBookingActivity($request->booking_id,$msg);

    return response()->json(['status' => true, 'message' => "booking cancelled"], 200);

    }
    else{
      return response()->json(['status' => false, 'message' => "unsuccess"], 200);

    }


  }

  public function bookingActions(Request $request){

      $validator = Validator::make($request->all(), [
          'booking_id'    => 'required',
          'status_update' => 'required',
          // 'user_id'       => 'required'
          // 'token'       => 'required',
      ]);
 
      $status =  $request->status_update;

      $msg = explode('_', $status);

        if ($validator->fails()) {
          return response()->json(['status' => false, 'message' => implode("", $validator->errors()->all())], 200);
        }

          $booking = UserBooking::where('id','=',$request->booking_id)
                                  // ->where('token','=',$request->token)
                                  ->first();
          // dd($booking);
          if(empty($booking)){
            // 
            return response()->json(['status' => false, 'message' => 'booking not found'], 200);
          }
          $space = Space::where('id','=',$booking->space_id)->first();

          if(empty($space)){
            // 
            return response()->json(['status' => false, 'message' => 'space not found'], 200);
          }

          $landload = User::where('id', '=', $booking->landload_id)->first();

          if($space->booking_approval == 1) { 

            $changeBookingStaus = UserBooking::where('id','=',$request->booking_id)
                                  // ->where('token','=',$request->token)
                                  ->update([
              'booking_status' => $request->status_update
            ]);

            BookOffice::where('id','=',$request->booking_id)
                        ->update([
                          'booking_status' => $request->status_update
                        ]);

            UserBooking::where('id','=',$request->booking_id)
                        ->update([
                          'token' => ''
                        ]);

            //Save activty
            $newmsg = $msg[0].' '.$msg[1];
            if($newmsg == "enquiry approved"){
              $msg = "Enquiry is Approved";
            }
            else{
               $msg = "Enquiry is Rejected";
            }

            Helper::saveBookingActivity($request->booking_id,$msg);

            //send notification
            $user = User::where('id','=', $booking->user_id)->first();
            // $landload = User::where('id','=', $booking->landload_id)->first();
            $user_id = $booking->user_id; 
            $title = "Space Enquiry Response";
            $yrmsg = $user->name." your ".$msg." for space ".$space->space_title; //. ' enquiry is Enquired.';
            $image ="sdf";
            $landload_id = $landload->id;
            $type = "space_enquiry";   
            $notification = Helper::sendNotification($title, $yrmsg, $image, $user_id, $type);
            // dd($notification);
            return response()->json(['status' => true, 'message' => $msg, 'data' => $landload], 200);
          }
          else{
            return response()->json(['status' => true, 'message' => "booking type is not enquiry", 'data' =>$landload] , 200);
          }

  }


  public function propertyActions(Request $request){

    $validator = Validator::make($request->all(), [
          'property_id'     => 'required',
          'is_approved'     => 'required',
          'user_id'         => 'required'
      ]);

      //echo $request->status_update;

        if ($validator->fails()) {
          return response()->json(['status' => false, 'message' => implode("", $validator->errors()->all())], 200);
        }

        $property = Property::where('id','=',$request->property_id)
                              ->where('user_id','=',$request->user_id)
                              ->first();

        if(!empty($property)){

          Property::where('id','=',$request->property_id)
                    ->where('user_id','=',$request->user_id)
                    ->update([

                        'is_approved' => $request->is_approved

                      ]);

            return response()->json(['status' => true, 'message' => 'success'], 200);

        }
        else{

            return response()->json(['status' => false, 'message' => 'result not found'], 200);

        }

  }


  public function spaceActions(Request $request){

        $validator = Validator::make($request->all(), [
            'space_id'        => 'required',
            'is_approved'     => 'required',
            'user_id'         => 'required'
        ]);

        //echo $request->status_update;

        if ($validator->fails()) {
            return response()->json(['status' => false, 'message' => implode("", $validator->errors()->all())], 200);
        }

        $space =  Space::where('id','=',$request->space_id)
                          ->where('user_id','=',$request->user_id)
                          ->first();

        if(!empty($space)){

          $updateStatus = Space::where('id','=',$request->space_id)
                          ->where('user_id','=',$request->user_id)
                          ->update([
                              'is_approved' => $request->is_approved
                            ]);

        return response()->json(['status' => true, 'message' => 'success'], 200);

        }
        else{

         return response()->json(['status' => true, 'message' => 'success'], 200);

        }


  }

  public function contactCreate($data){


    // $data = [
    //   'Name'          => 'ACCPAY',
    //   'ContactNumber' => '8005680948',
    //   'AccountNumber' => 'dfgdf',
    //   'FirstName'     => 'priya',
    //   'LastName'      => 'jaha',
    //   'EmailAddress'  => 'dfgdf@gmail.com',

    // ];


    $contact = Xero::contacts()->store($data);

    return $contact['ContactID'];

  }


 public function invoiceCreate($data){

      // $data = [
      //     'Type'          => "ACCREC",
      //     'Contact'       => $data_1,
      //     'LineItems'     => [
      //       [
      //         "description"=>"Acme Tires",
      //         "quantity"=>2,
      //         "unitAmount"=>"20.0",
      //         "accountCode"=>"154225ABC",
      //         "taxType"=>"NONE",
      //         "lineAmount"=>"40.0"
      //       ],
      //     ],
      //   ];

        $invoice = Xero::invoices()->store($data);

        return $invoice['InvoiceID'];

 } 


    public function invoice(Request $request){

        //Contact Create 

        $user = User::where('id','=',$request->user_id)->first();
        $space = Space::where('id','=',$request->space_id)->first();

        $bookedSpaceData['description']       =  $space->space_title;
        $bookedSpaceData['quantity']          =  10;
        $bookedSpaceData['unitAmount']        =  100;
        $bookedSpaceData['date']              =  "20-10-2021";
        $bookedSpaceData['DueDate']          =  "2021-10";
        $bookedSpaceData['TaxAmount']         =  $tax;
        $bookedSpaceData['AccountCode']       =  $AccountCode;
        $bookedSpaceData['Status']            =  "AUTHORISED";

        $data = [
            'Name'          => 'ACCPAY',
            'ContactNumber' => $user->phone,
            //'AccountNumber' => 'dfgdf',
            'FirstName'     => $user->first_name,
            'LastName'      => $user->last_name,
            'EmailAddress'  => $user->email,

        ]; 

        $contactData = $this->contactCreate($data);

        //invoice create

        $data_1 = $data;

        $datainvoice = [
          'Type'          => "ACCREC",
          'Contact'       => $data_1,
          'ItemCode'       =>
              [
              "Name" =>$space->space_title,
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

        $invoiceid = $this->invoiceCreate($datainvoice);
        $invoice_url = Xero::invoices()->onlineUrl('380f2da9-c953-47c5-9762-3463293bd052');


    }


  public function bookingenquiry(Request $request){


      $booking = UserBooking::where('id','=',$request->booking_id)->first();
      $space = Space::where('id','=',$booking->space_id)->first();
      $landload = User::where('id','=',$booking->landload_id)->first();
      $user = User::where('id','=',$booking->user_id)->first();

       $userid   = $booking->user_id;
       $landloadid = $booking->landload_id;

      $booking_id = $request->booking_id;

      if($space->booking_approval == "1"){

        $booked = UserBooking::where('id','=',$request->booking_id)
                                      ->update(['booking_status'=> 'enquiry_pending']);

         $booked_dates = BookOffice::where('booking_id','=',$request->booking_id)
                                      ->update(['booking_status'=> 'enquiry_pending']);
          //send notificaion to user
          // $user = User::where('id','=', $userid)->first();
          // $title = "Space enquiry";

          // $msg = $user->name." you enquire a space ".$space->space_title;
          // $image ="sdf";
          // $user_id = $user->id;
          // $type = "space_enquiry_user";         
          // $notification = Helper::sendNotification($title,$msg,$image,$user_id,$type);
          

          //send notification to vendor
          $user = User::where('id','=', $booking->user_id)->first();
          $title = "Space Booked";

          $msg = $landload->name." your space ".$space->space_title. 'is enquired.';
          $image ="sdf";
          $landload_id = $landload->id;
          $type = "space_enquiry_admin";         
          $notification = Helper::sendNotification($title,$msg,$image,$landload_id,$type);


          $userDetails = UserAddress::create([
            'first_name'       => $request->first_name,
            'last_name'    => $request->last_name,
            'dob'         => $request->dob,
            'email'        => $request->email,
            'address_1'       => $request->address_1,
            'address_2'       => $request->address_2,
            'city'       => $request->city,
            'state'       => $request->state,
            'country'       => $request->country,
            'user_id'       => $user->id,
            'phone'       => $request->phone,
            'postcode'       => $request->postal_code,
            'booking_id' => $booking_id
          ]);



          // send mail to vendor
          $property = Property::where('id','=',$space->property_id)->first();
          $landload = User::where('id','=',$property->user_id)->first(); 
          $location = PropertyLocation::where('property_id','=',$booking->property_id)->first();
          $address = [
            'address_1'           => $location->address,
            'address_2'           => $location->address_2,
            'city'                => $location->city,
            'state'               => $location->state,
            'country'             => $location->country,
            'postcode'            => $location->postcode
          ]; 
          $full_address = '';
          foreach ($address as $key => $value) {
            // code...
            $full_address .= isset($value)?$value.' ':'';
          }
          try {

            $bookedSpace = BookOffice::where('booking_id','=',$booking_id)->get();

            
            $bookedSpace_count = 0;
            foreach ($bookedSpace as $key_space => $value_space) {
              
                $bookedSpace_count = $bookedSpace_count + $value_space->booked_desk;
            }

            $st = Setting::where('options','=','site_url')->first();
            $st1 = Setting::first();
            $start_date=date_create($booking->start_date);
            $end_date=date_create($booking->end_date);

            // $to = Carbon::parse('Y-m-d', $booking->end_date);
            // $from = Carbon::parse('Y-m-d', $booking->start_date);

            // $diff_in_days = $to->diffInDays($from);

            $booking_dates_days = '';

            $dateofbooking = BookOffice::where('booking_id','=',$booking_id)->get();

            foreach ($dateofbooking as $key => $value) {

                if($value->period_of_day == 1){

                  $period_of_day = 'day';
                  $image = '<img src="http://officeshare-cms.ewtlive.in/images/day.png" />';

                }
                if($value->period_of_day == 2){

                  $period_of_day = 'night';
                   $image = '<img src="http://officeshare-cms.ewtlive.in/images/night.png" />';

                }

              $day = \Carbon\Carbon::createFromDate($value->booked_date)->format('D');

              $booking_dates_days .= $day.'('.$image.' '.$period_of_day.'),';
            } 

            $u_tok = uniqid().'_'.$booking_id.$landload->id.$user->id;

            UserBooking::where('id','=',$booking_id)
                                      ->update(['token'=> $u_tok]);
            $sign = [
              '{{name}}' => $user->name,
              '{{ref_id}}' => '#'.$booking_id,
              '{{location}}' => $full_address,
              '{{space_name}}' => $space->space_title,
              '{{number_of_desks}}' => $bookedSpace_count.' Desks',
              '{{start_date}}' => date_format($start_date,"Y-M-d"),
              '{{end_date}}' => date_format($end_date,"Y-M-d"),
              '{{booking_days}}' => $booking_dates_days,
              '{{host_name}}' => $landload->name,
              '{{host_email}}' => $landload->email,
              '{{host_phone}}' => $landload->phone,
              '{{price}}' => $booking->price,
              '{{approve_link}}' => env("frontend_url").'/#/enquery?status=enquiry_approved&token='.$u_tok.'&booking_id='.$booking_id,
              '{{decline_link}}' => env("frontend_url").'/#/enquery?status=enquiry_cancelled&token='.$u_tok.'&booking_id='.$booking_id,
              '{{admin_id}}' => isset($landload)?$landload->id:'',
              '{{user_id}}' => isset($user)?$user->id:'',
              '{{report}}' => $user->id,
            ];
            
            $msgData = MailTemplate::where('status', trim('booking_enquiry'))->first();
            // $replMsg = MailTemplate::where('status', trim('booking_enquiry '))->pluck('message')->first();
            $replMsg = $msgData->message;
            foreach ($sign as $key => $value) {
                $replMsg = str_replace($key, $value, $replMsg);
            }
            
            $mail_msg = ['fromemail' => $msgData->from_email, "replyemail" => $msgData->reply_email, 'msg' => $replMsg, 'subject' => $msgData->subject, 'name' => $msgData->name, 'status' => true];

            Mail::to($user->email)->send(new BookingSuccess($mail_msg));

            $msg = "Booking Enquiry";

            Helper::saveBookingActivity($booking_id,$msg);
          } 
          catch (Exception $e) {
            
          }

        return response()->json(['status' => true, 'message' => "success"], 200);

      }
      else{

        return response()->json(['status' => true, 'message' => "Space type is not enquiry"], 200);
      }



  }

  public function singnature(Request $request){

    $validator = Validator::make($request->all(), [
            'booking_id'        => 'required',
            'signature'     => 'required',
            'user_id'         => 'required'
        ]);

        //echo $request->status_update;

        if ($validator->fails()) {
            return response()->json(['status' => false, 'message' => implode("", $validator->errors()->all())], 200);
        }

        
        if ($files    =    $request->file('signature')) {

            $name    =    uniqid() . $files->getClientOriginalName();
            $files->move('media/signature', $name);

            $path = url('media/signature').'/'.$name;

            UserSignature::create([ 

              'user_id'   =>$request->user_id,
              'signature' =>$path,
              'ip'  =>$request->ip(),
              'booking_id'  =>$request->booking_id,

              ]);
        }

         return response()->json(['status' => true, 'message' => "Your digital signature has been saved for the booking process. "], 200);

    }


}
