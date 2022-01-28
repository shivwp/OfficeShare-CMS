<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\UserBooking;
use App\User;
use App\Property;
use App\UserSignature;
use App\BookOffice;
use DB;
use Illuminate\Support\Facades\Auth;
use App\Helper\Helper;

class BookingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        $d['title'] = "Bookings";

        $bookings =  UserBooking::orderBy('id', 'DESC');

        if(!empty($request->search)){

        	$bookings->where('user_name', 'like', "%$request->search%");

        }
        if(!empty($request->filter)){

            $bookings->where('booking_status','=',$request->filter);
        }
        if(!empty($request->property_id)){

            $bookings->where('property_id','=',$request->property_id);
        }
        if(!empty($request->date)){

        	 $bookings->whereDate('created_at','=',$request->date);
        }
        $d['Bookings'] = $bookings->paginate(10)->withQueryString();


        foreach($d['Bookings'] as $key => $val){

            $user = User::where('id','=',$val->user_id)->first();

            $d['Bookings'][$key]['user_name'] = !empty($user->name) ? $user->name :null;
            $data = json_decode($val->data);
             $d['Bookings'][$key]['property_title'] = $data->property->property_title;
             $d['Bookings'][$key]['space_title']    = $data->space->space_title;

             $total_booked_desk = [];
            $total_booked_desk_price = []; 


             //price total
            $sum_booked_desk = BookOffice::where('booking_id','=',$val->id)->get();
                foreach ($sum_booked_desk as $key1 => $value1) {

                $total_booked_desk[] = $value1->booked_desk;

                $total_booked_desk_price[] = $value1->booking_price*$value1->booked_desk;

                }
            $d['Bookings'][$key]['total_price_sum']    = array_sum($total_booked_desk_price);

        }

     	$d['properties'] = Property::all();


        return view('admin.bookings.index', $d);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

      
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
       
    }

    public function singleBooking($id)
    {
        // code...

        $booking = UserBooking::where('id','=',$id)->first();

        $user = User::where('id','=',$booking->user_id)->first();
        $userAddress = DB::table('user_addresses')->where('user_id','=',$user->id)->first();
        $landload = User::where('id','=',$booking->landload_id)->first();

        $booking_data = json_decode($booking->data);
        $signature = UserSignature::where('user_id',$booking->user_id)->where('booking_id', $booking->id)->first();

        $sum_booked_desk = BookOffice::where('booking_id','=',$booking->id)->get();

        $total_booked_desk = [];
        $total_booked_desk_price = []; 

        foreach ($sum_booked_desk as $key => $value) {

            $total_booked_desk[] = $value->booked_desk;

            $total_booked_desk_price[] = $value->booking_price*$value->booked_desk;

        }

        //booking status

        $booking_status = "";

             if($booking->booking_status == 'pending'){

                $booking_status = 'pending';

              }

              if($booking->booking_status == 'booked'){

                $booking_status = 'booked';
              }

              if($booking->booking_status == 'cancelled'){

                $booking_status = 'cancelled';
              }

              if($booking->booking_status == 'enquiry_pending'){

                $booking_status = 'enquiry pending';
              }

                if($booking->booking_status == 'enquiry_approved'){

                $booking_status = 'enquiry approved';
              }

               if($booking->booking_status == 'enquiry_cancelled'){

                $booking_status = 'enquiry cancelled';
              }



        $booking['property'] = $booking_data->property;
        $booking['space'] = $booking_data->space;
        $booking['booking_details'] = $booking_data->booking_details;
        $booking['user'] = $user;
        $booking['userAddress'] = $userAddress;
        $booking['landload'] = $landload;
        $booking['signature'] = (!empty($signature))? $signature:'';
        $data['title'] = 'Booking';
        $data['data'] = $booking;
        $data['total_booked_desk'] = array_sum($total_booked_desk);
        $data['total_booking_price'] = array_sum($total_booked_desk_price);
        $data['status'] = $booking_status;

        return view('admin.bookings.single',$data);

    }

    public function invoice($id){

        $booking = UserBooking::where('id','=',$id)->first();

        $user = User::where('id','=',$booking->user_id)->first();
        $userAddress = DB::table('user_addresses')->where('user_id','=',$user->id)->first();
        $landload = User::where('id','=',$booking->landload_id)->first();

        $booking_data = json_decode($booking->data);

        $booking['property'] = $booking_data->property;
        $booking['space'] = $booking_data->space;
        $booking['booking_details'] = $booking_data->booking_details;
        $booking['user'] = $user;
        $booking['userAddress'] = $userAddress;
        $booking['landload'] = $landload;

        // dd( $booking['booking_details']);



        $d['title'] = 'invoice';

        $d['data'] = $booking;





        return view('admin.bookings.invoice',$d);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {

       UserBooking::where('id','=',$id)->delete();
       BookOffice::where('booking_id','=',$id)->delete();

       
    }

    public function changeBookingStatus(Request $request){

        $UserBooking = UserBooking::where('id','=',$request->id)->first();
        $userid   = $UserBooking->user_id;
        $landloadid = $UserBooking->landload_id;

        if(isset($request->enquiry_pending)){

            $title = "Booking enquiry";

            $msg = "Your enquiry has been approved successfully.";

            $image ="sdf";

            $UserBooking = UserBooking::where('id','=',$request->id)->first();

            $user_id = $UserBooking->user_id;

            $type = "my_booking";

            $notification = Helper::sendNotification($title,$msg,$image,$user_id,$type);
           
            UserBooking::where('id','=',$request->id)->update([

                'booking_status' => 'enquiry_approved'
            ]);


            BookOffice::where('booking_id','=',$request->id)
                                      ->update(['booking_status'=> 'enquiry_approved']);

            $msg = "Booking approved";

            Helper::saveBookingActivity($request->id,$msg);

            return redirect('dashboard/bookings/')->with('msg', 'enquiry approved');
        }
        if(isset($request->enquiry_cancelled)){

            UserBooking::where('id','=',$request->id)->update([
                // 
                'booking_status' => 'enquiry_cancelled'
            ]);

            $title = "Booking enquiry";

            $msgnotification = "Your enquiry has been rejected.";

            $image ="sdf";

            $UserBooking = UserBooking::where('id','=',$request->id)->first();

            $user_id = $UserBooking->user_id;

            $type = "cancelled_enquiry";

            $notification = Helper::sendNotification($title,$msgnotification,$image,$user_id,$type);

            BookOffice::where('booking_id','=',$request->id)
                                      ->update(['booking_status'=> 'enquiry_cancelled']);

            $msg = "Booking rejected";

            Helper::saveBookingActivity($request->id,$msg);

            return redirect('dashboard/bookings/')->with('msg', 'enquiry rejected');

        }

        UserBooking::where('id','=',$request->id)->update([

            'booking_status' => $request->change_status
        ]);


          $title = "Booking status change";

          $msgnotification = "Your space has been ".$request->change_status ;

          $image ="sdf";

          $UserBooking = UserBooking::where('id','=',$request->id)->first();

          $user_id = $UserBooking->user_id;

          $type = "cancelled_booking";

          $notification = Helper::sendNotification($title,$msgnotification,$image,$user_id,$type);

          BookOffice::where('booking_id','=',$request->id)
                                      ->update(['booking_status'=> $request->change_status]);

         $msg = "Booking".$request->change_status;

         Helper::saveBookingActivity($request->id,$msg);

        return redirect('dashboard/bookings/')->with('msg', 'Status updated successfully');



     }
}
