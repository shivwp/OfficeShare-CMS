<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Category;
use App\HomePageSetting;
use App\Property;
use App\Space;
use App\UserBooking;
use App\Plans;
use Illuminate\Http\Request;
use App\User;
use App\Order;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Carbon\CarbonPeriod;

class HomeController
{

  public function index(Request $request)
  {

    $startDate = Carbon::now()->subDay();
    $endDate = Carbon::now();
    $newYear = new Carbon('today midnight');
    $diff = 'days';
    $toloop = 1;
    $filterType = 'day';
    $totalnumBooking = UserBooking::all();

    if(isset($request->last_year)){

      $startDate = Carbon::now()->subYear();
      $toloop = 12;
      $diff = 'Year';
      $filterType = 'year';

    }
    elseif(isset($request->last_month)){

      $startDate = Carbon::now()->startOfMonth()->subMonth(1);
      $daysInMonth = $startDate->daysInMonth;
      $cusdates = [];
      for($i=1; $i < $daysInMonth+1; ++$i){
        $c_data = \Carbon\Carbon::createFromDate($startDate->year, $startDate->month, $i)->format('Y-m-d');
        $cusdates[] = $c_data;
      }
      $toloop = count($cusdates);
      $diff = 'Month';
      $filterType = 'month';
      

    }
    elseif(isset($request->last_week)){

      $startDate = Carbon::now()->subWeek();
      $toloop = 7;
      $diff = 'Days';
      $filterType = 'week';

    }

    elseif(isset($request->this_month)){

//this month
      $current = \Carbon\Carbon::now();
      $dt =  Carbon::now()->format('d');
      $cusdates = [];
      for($i=1; $i < $dt+1; ++$i){
        $c_data = \Carbon\Carbon::createFromDate($current->year, $current->month, $i)->format('Y-m-d');
        $cusdates[] = $c_data;
      }

      $startDate = Carbon::now()->month();
      $toloop = count($cusdates)-1;
      $diff = 'Month';
      $filterType = 'month';

    }
    elseif(!empty($request->to)){

      $startDate = $request->to;
      $toloop = 1;
      $diff = 'Days';
      $filterType = 'week';

    }
    elseif(!empty($request->from)){

      $startDate = $request->from;
      $toloop = 1;
      $diff = 'Days';
      $filterType = 'day';

    }
    elseif(!empty($request->to) && !empty($request->from)){

      $startDate = $request->to;
      $endDate = $request->from;
      $toloop = $endDate-$startDate;
      $diff = 'Days';
      $filterType = 'day';

    }

    $revenuesubyear = UserBooking::whereYear(
      'start_date', '=', Carbon::now()->subYear()->year
    )->get();

    //percentage of booking
    $d['bookingtotal'] = UserBooking::all();
    $booked=UserBooking::where('booking_status','=','booked')->get(); 
    $cancelled=UserBooking::where('booking_status','=','cancelled')
                ->orWhere('booking_status', 'enquiry_cancelled')
                ->orWhere('booking_status', 'refunded')
                ->get(); 
     $pending=UserBooking::where('booking_status','=','pending')
              ->orWhere('booking_status', 'enquiry_pending')
              ->orWhere('booking_status', 'enquiry_approved')
              ->get();
    $d['bookedbooking'] = (count($booked) * 100 ) / count($d['bookingtotal']);
    $d['cancelbooking'] = (count($cancelled) * 100 ) / count($d['bookingtotal']);
    $d['pendingbooking'] = (count($pending) * 100 ) / count($d['bookingtotal']);


    if(isset($request)){

      $TotalBookings = UserBooking::whereBetween('start_date', [$startDate, $endDate])
      ->get();

      $bookingComplete =  UserBooking::where('booking_status','=','booked')
      ->whereBetween('start_date', [$startDate, $endDate])
      ->get();

      $bookingCancelled = UserBooking::where('booking_status','=','cancelled')
      ->whereBetween('start_date', [$startDate, $endDate])
      ->get();
    }
    else{

      $TotalBookings =   UserBooking::all();

      $bookingComplete =  UserBooking::where('booking_status','=','booked')
      ->get();

      $bookingCancelled = UserBooking::where('booking_status','=','cancelled')
      ->get();

    }
    // dd([$startDate, $endDate]);

    $tod = Carbon::now();
    $fromd = UserBooking::orderBy('id', 'DESC')->first()->start_date;
    $diff = $tod->diffInHours($fromd);
    $bookingGraph = $cancelGraph  = $pendingGraph = $xAxes = [];
    $diff_typ = "Days";
    $numberOfDiff = 0;
    $numberOfCal = 0;
    $msgBookings = "Total Bookings";

    // Last Week Bookings
    if ($request->last_week) {
      $msgBookings = "Last Week Bookings";
      $i = 0;
      while ($i <= $toloop) 
      {

        $xAxes[] .= Carbon::today()->subDay($i)->format('d M Y ') . ' ';
        $startDate = Carbon::today()->subDay($i);

        $bookingGraph[] .= count(UserBooking::WhereIn('booking_status', ['booked'])
         ->whereBetween('start_date', [$startDate->format('Y-m-d 00:00:00'), $startDate->format('Y-m-d 23:59:59')])
         ->get()); 

        $cancelGraph[] .= count(UserBooking::WhereIn('booking_status', ['cancelled', 'enquiry_cancelled', 'refunded'])
         ->whereBetween('start_date', [$startDate->format('Y-m-d 00:00:00'), $startDate->format('Y-m-d 23:59:59')])
         ->get()); 

        $pendingGraph[] .= count(UserBooking::WhereIn('booking_status', ['pending', 'enquiry_pending', 'enquiry_approved'])
         ->whereBetween('start_date', [$startDate->format('Y-m-d 00:00:00'), $startDate->format('Y-m-d 23:59:59')])
         ->get()); 
 
        $i++;
      }

      $totalnumBooking = UserBooking::whereBetween('start_date', [$startDate->format('Y-m-d 00:00:00'), Carbon::today()->format('Y-m-d 23:59:59')])->get();

      //percentage of booking
      
      $booked=UserBooking::where('booking_status','=','booked')
      ->whereBetween('start_date', [$startDate->format('Y-m-d 00:00:00'), Carbon::today()->format('Y-m-d 23:59:59')])
      ->get(); 

      $cancelled=UserBooking::WhereIn('booking_status', ['cancelled', 'enquiry_cancelled', 'refunded'])
      ->whereBetween('start_date', [$startDate->format('Y-m-d 00:00:00'), Carbon::today()->format('Y-m-d 23:59:59')])
      ->get(); 
      
      $pending=UserBooking::WhereIn('booking_status', ['pending', 'enquiry_pending', 'enquiry_approved'])
      ->whereBetween('start_date', [$startDate->format('Y-m-d 00:00:00'), Carbon::today()->format('Y-m-d 23:59:59')])
      ->get(); 

      $total = UserBooking::whereBetween('start_date', [$startDate->format('Y-m-d 00:00:00'), $startDate->format('Y-m-d 23:59:59')])
      ->get();

      $d['bookingtotal'] = count($booked) + count($cancelled) + count($pending); //UserBooking::all();

      $d['bookedbooking'] = (count($booked) * 100 ) / $d['bookingtotal']; //count($d['bookingtotal']);
      $d['cancelbooking'] = (count($cancelled) * 100 ) / $d['bookingtotal']; //count($d['bookingtotal']);
      $d['pendingbooking'] = (count($pending) * 100 ) / $d['bookingtotal']; //count($d['bookingtotal']);


      $diff_typ = "Days";
      $tod = Carbon::now();
      $fromd = UserBooking::orderBy('id', 'DESC')->first()->start_date;
      $diff = $tod->diffInDays($fromd);
      $numberOfDiff = $i-1;
      $numberOfCal = $i-1;
    }

    // Last Month Bookings
    else if (isset($request->last_month)) {
       $msgBookings = "Last Month Bookings";
      $i = 1;

      while ($i <= $toloop) {
        $xAxes[] .= Carbon::today()->startOfMonth()->subDay($i)->format('d M') . ' ';
        $startDate = Carbon::today()->startOfMonth()->subDay($i);

        $bookingGraph[] .= count(UserBooking::WhereIn('booking_status', ['booked'])
         ->whereBetween('start_date', [$startDate->format('Y-m-d 00:00:00'), $startDate->format('Y-m-d 23:59:59')])
         ->get()); 

        $cancelGraph[] .= count(UserBooking::WhereIn('booking_status', ['cancelled', 'enquiry_cancelled', 'refunded'])
         ->whereBetween('start_date', [$startDate->format('Y-m-d 00:00:00'), $startDate->format('Y-m-d 23:59:59')])
         ->get()); 

        $pendingGraph[] .= count(UserBooking::WhereIn('booking_status', ['pending', 'enquiry_pending', 'enquiry_approved'])
         ->whereBetween('start_date', [$startDate->format('Y-m-d 00:00:00'), $startDate->format('Y-m-d 23:59:59')])
         ->get());
        
        $i++;
      }

      //percentage of booking
      $d['bookingtotal'] = UserBooking::all();
      $booked=UserBooking::where('booking_status','=','booked')
      ->whereBetween('start_date', [$startDate->format('Y-m-d 00:00:00'), Carbon::today()->subDay(1)->format('Y-m-d 23:59:59')])
      ->get(); 

      $cancelled=UserBooking::WhereIn('booking_status', ['cancelled', 'enquiry_cancelled', 'refunded'])
      ->whereBetween('start_date', [$startDate->format('Y-m-d 00:00:00'), Carbon::today()->subDay(1)->format('Y-m-d 23:59:59')])
      ->get(); 

      $pending=UserBooking::WhereIn('booking_status', ['pending', 'enquiry_pending', 'enquiry_approved'])
      ->whereBetween('start_date', [$startDate->format('Y-m-d 00:00:00'), Carbon::today()->subDay(1)->format('Y-m-d 23:59:59')])
      ->get(); 

      $total = UserBooking::where('booking_status','=','pending')
      ->whereBetween('start_date', [$startDate->format('Y-m-d 00:00:00'), $startDate->format('Y-m-d 23:59:59')])
      ->get();

      $totalnumBooking = UserBooking::whereBetween('start_date', [$startDate->format('Y-m-d 00:00:00'), Carbon::today()->subDay(1)->format('Y-m-d 23:59:59')])
                          ->get();

      $d['bookingtotal'] = count($booked) + count($cancelled) + count($pending); //UserBooking::all();

      $d['bookedbooking'] = (count($booked) * 100 ) / $d['bookingtotal']; //count($d['bookingtotal']);
      $d['cancelbooking'] = (count($cancelled) * 100 ) / $d['bookingtotal']; //count($d['bookingtotal']);
      $d['pendingbooking'] = (count($pending) * 100 ) / $d['bookingtotal']; //count($d['bookingtotal']);

      $diff_typ = "Days";
      $tod = Carbon::now();
      $fromd = UserBooking::orderBy('id', 'DESC')->first()->start_date;
      $diff = $tod->diffInDays($fromd);
      $numberOfDiff = ($diff > 0) ? $diff : 1;

      $numberOfDiff = $i-1;

      $numberOfCal = $i-1;
    }

    // This Month Bookings
    else if (isset($request->this_month)) {
      // 
      $msgBookings = "This Month Bookings";
      $i = 0;
      while ($i <= $toloop) {
        // 
        $xAxes[] .= Carbon::today()->subDay($i)->format('d M') . ' ';
        $startDate = Carbon::today()->subDay($i);

        $bookingGraph[] .= count(UserBooking::WhereIn('booking_status', ['booked'])
         ->whereBetween('start_date', [$startDate->format('Y-m-d 00:00:00'), $startDate->format('Y-m-d 23:59:59')])
         ->get()); 

        $cancelGraph[] .= count(UserBooking::WhereIn('booking_status', ['cancelled', 'enquiry_cancelled', 'refunded'])
         ->whereBetween('start_date', [$startDate->format('Y-m-d 00:00:00'), $startDate->format('Y-m-d 23:59:59')])
         ->get()); 

        $pendingGraph[] .= count(UserBooking::WhereIn('booking_status', ['pending', 'enquiry_pending', 'enquiry_approved'])
         ->whereBetween('start_date', [$startDate->format('Y-m-d 00:00:00'), $startDate->format('Y-m-d 23:59:59')])
         ->get());

        $i++;
      }

      //percentage of booking
      $d['bookingtotal'] = UserBooking::all();
      
      $totalnumBooking = UserBooking::whereBetween('start_date', [$startDate->format('Y-m-d 00:00:00'), Carbon::today()->format('Y-m-d 23:59:59')])
          ->get();

      $booked=UserBooking::where('booking_status','=','booked')
      ->whereBetween('start_date', [$startDate->format('Y-m-d 00:00:00'), Carbon::today()->format('Y-m-d 23:59:59')])
      ->get(); 
      $cancelled=UserBooking::WhereIn('booking_status', ['cancelled', 'enquiry_cancelled', 'refunded'])
      ->whereBetween('start_date', [$startDate->format('Y-m-d 00:00:00'), Carbon::today()->format('Y-m-d 23:59:59')])
      ->get(); 
      $pending=UserBooking::WhereIn('booking_status', ['pending', 'enquiry_pending', 'enquiry_approved'])
      ->whereBetween('start_date', [$startDate->format('Y-m-d 00:00:00'), Carbon::today()->format('Y-m-d 23:59:59')])
      ->get();

      $total = UserBooking::where('booking_status','=','pending')
      ->whereBetween('start_date', [$startDate->format('Y-m-d 00:00:00'), $startDate->format('Y-m-d 23:59:59')])
      ->get(); 

      $d['bookingtotal'] = count($booked) + count($cancelled) + count($pending); //UserBooking::all();
      $d['bookedbooking'] = (count($booked) * 100 ) / $d['bookingtotal']; //count($d['bookingtotal']);
      $d['cancelbooking'] = (count($cancelled) * 100 ) / $d['bookingtotal']; //count($d['bookingtotal']);
      $d['pendingbooking'] = (count($pending) * 100 ) / $d['bookingtotal']; //count($d['bookingtotal']);

      $diff_typ = "Days";
      $tod = Carbon::now();
      $fromd = UserBooking::orderBy('id', 'DESC')->first()->start_date;
      $diff = $tod->diffInDays($fromd);
      $numberOfDiff = ($diff > 0) ? $diff : 1;

      $numberOfDiff = $i-1;

      $numberOfCal = $i-1;
    }

    // Last Year Bookings
    else if (isset($request->last_year)) {
      
      $msgBookings = "Last Year Bookings";
      $i = 0;
      $date = 'Dec '. Carbon::today()->subYear()->format('Y ') . ' ';
      
      while ($i < 12) {
        $xAxes[] .= Carbon::parse($date)->subMonths($i)->format('M Y ') . ' ';

        $startDate = Carbon::parse($date)->subMonths($i)->startOfMonth()->format('Y-m-d 00:00:00');
        // echo $startDate." -- <br>";
        $endDate = Carbon::parse($date)->subMonths($i)->endOfMonth()->format('Y-m-d 23:59:59');
        
        // $totalnumBooking = UserBooking::whereBetween('start_date', [Carbon::today()->startOfMonth()->format('Y-m-d 00:00:00'), Carbon::now()])->get();

        $bookingGraph[] .= count(UserBooking::WhereIn('booking_status', ['booked'])
         ->whereBetween('start_date', [$startDate, $endDate])
         ->get()); 

        $cancelGraph[] .= count(UserBooking::WhereIn('booking_status', ['cancelled', 'enquiry_cancelled', 'refunded'])
         ->whereBetween('start_date', [$startDate, $endDate])
         ->get()); 

        $pendingGraph[] .= count(UserBooking::WhereIn('booking_status', ['pending', 'enquiry_pending', 'enquiry_approved'])
         ->whereBetween('start_date', [$startDate, $endDate])
         ->get()); 

        //   // $i++;
        // }

        $i++;
      }

      //percentage of booking
      $d['bookingtotal'] = UserBooking::all();
      
      $start = 'Jan '. Carbon::today()->subYear()->format('Y ') . ' ';
      $startDate = Carbon::parse($start)->startOfMonth()->format('Y-m-d 00:00:00');

      $end = 'Dec '. Carbon::today()->subYear()->format('Y ') . ' ';
      $endDate = Carbon::parse($end)->endOfMonth()->format('Y-m-d 23:59:59');

      $totalnumBooking = UserBooking::whereBetween('start_date', [$startDate, $endDate])->get();

      $booked=UserBooking::where('booking_status','=','booked')
      ->whereBetween('start_date', [$startDate, $endDate])
      ->get(); 

      $cancelled=UserBooking::WhereIn('booking_status', ['cancelled', 'enquiry_cancelled', 'refunded'])
      ->whereBetween('start_date', [$startDate, $endDate])
      ->get(); 
      
      $pending=UserBooking::WhereIn('booking_status', ['pending', 'enquiry_pending', 'enquiry_approved'])
      ->whereBetween('start_date', [$startDate, $endDate])
      ->get(); 

      $total = UserBooking::whereBetween('start_date', [$startDate, $endDate])
      ->get();

      $d['bookingtotal'] = count($booked) + count($cancelled) + count($pending); //UserBooking::all();
      if(!$d['bookingtotal']) {
        $d['bookingtotal'] = 1;
      }

      $d['bookedbooking'] = (count($booked) * 100 ) / $d['bookingtotal']; //count($d['bookingtotal']);
      $d['cancelbooking'] = (count($cancelled) * 100 ) / $d['bookingtotal']; //count($d['bookingtotal']);
      $d['pendingbooking'] = (count($pending) * 100 ) / $d['bookingtotal']; //count($d['bookingtotal']);


      $diff_typ = "Years";
      $tod = Carbon::now();
      $fromd = UserBooking::orderBy('id', 'DESC')->first()->start_date;
      $diff = $tod->diffInYears($fromd);
      $numberOfDiff = ($diff > 0) ? $diff : 1;
      $numberOfCal = ($diff > 0) ? $diff : 1;

    }

    // Custom Days 
    else if (isset($request->from) || isset($request->to)) {
      // 
      $i = 0;
      $from = \Carbon\Carbon::parse($request->from);
      $to = \Carbon\Carbon::parse(Carbon::now());
      if(!isset($request->from) && isset($request->to)) {
        $from = \Carbon\Carbon::parse($request->to)->startOfMonth();
      }
      if (isset($request->to)) { 
        // $endDate = Carbon::parse($request->to)->format('Y-m-d 23:59:59');
        $to = \Carbon\Carbon::parse($request->to);
      }
      $toloop = $to->diffInDays($from);
      $startDate = $from->addDay($toloop);

      while ($i <= ($toloop)) {
        // 
        $xAxes[] .= Carbon::parse($to)->subDay($i)->format('d M y ') . ' ';
        $startDate = Carbon::parse($to)->subDay($i);

        $bookingGraph[] .= count(UserBooking::WhereIn('booking_status', ['booked'])
         ->whereBetween('start_date', [$startDate->format('Y-m-d 00:00:00'), $startDate->format('Y-m-d 23:59:59')])
         ->get()); 

        $cancelGraph[] .= count(UserBooking::WhereIn('booking_status', ['cancelled', 'enquiry_cancelled', 'refunded'])
         ->whereBetween('start_date', [$startDate->format('Y-m-d 00:00:00'), $startDate->format('Y-m-d 23:59:59')])
         ->get()); 

        $pendingGraph[] .= count(UserBooking::WhereIn('booking_status', ['pending', 'enquiry_pending', 'enquiry_approved'])
         ->whereBetween('start_date', [$startDate->format('Y-m-d 00:00:00'), $startDate->format('Y-m-d 23:59:59')])
         ->get()); 

        $i++;
      }

      
      //percentage of booking

      $from = $from->subDay($toloop)->format('Y-m-d 00:00:00');
      $booked=UserBooking::where('booking_status','=','booked')
      ->whereBetween('start_date', [$from, $to->format('Y-m-d 23:59:59')])
      ->get(); 

      $cancelled=UserBooking::WhereIn('booking_status', ['cancelled', 'enquiry_cancelled', 'refunded'])
      ->whereBetween('start_date', [$from, $to->format('Y-m-d 23:59:59')])
      ->get(); 
      
      $pending=UserBooking::WhereIn('booking_status', ['pending', 'enquiry_pending', 'enquiry_approved'])
      ->whereBetween('start_date', [$from, $to->format('Y-m-d 23:59:59')])
      ->get(); 

      $total = UserBooking::whereBetween('start_date', [$from, $to->format('Y-m-d 23:59:59')])
      ->get();

      $totalnumBooking = UserBooking::whereBetween('start_date', [$from, $to->format('Y-m-d 23:59:59')])->get();

      $d['bookingtotal'] = count($booked) + count($cancelled) + count($pending); //UserBooking::all();
      if(!$d['bookingtotal']) {
        $d['bookingtotal'] = 1;
      }

      $d['bookedbooking'] = (count($booked) * 100 ) / $d['bookingtotal']; //count($d['bookingtotal']);
      $d['cancelbooking'] = (count($cancelled) * 100 ) / $d['bookingtotal']; //count($d['bookingtotal']);
      $d['pendingbooking'] = (count($pending) * 100 ) / $d['bookingtotal']; //count($d['bookingtotal']);

      $diff_typ = "Days";
      $tod = Carbon::now();
      $fromd = UserBooking::orderBy('id', 'DESC')->first()->start_date;
      $diff = $tod->diffInYears($fromd);
      $numberOfDiff = ($diff > 0) ? $diff : 1;
      $numberOfCal = ($diff > 0) ? $diff : 1;

    } 

    else {
      // 
      $i = 0;
      $from = \Carbon\Carbon::parse(Carbon::now()->startOfWeek());
      $to = \Carbon\Carbon::parse(Carbon::now());
      
      $toloop = $to->diffInDays($from);
      if($toloop<3) {
        $toloop =3;
      }
      $startDate = $from->addDay($toloop);

      while ($i <= ($toloop)) {
        // 
        $xAxes[] .= Carbon::parse($to)->subDay($i)->format('d M y ') . ' ';
        $startDate = Carbon::parse($to)->subDay($i);

        $bookingGraph[] .= count(UserBooking::WhereIn('booking_status', ['booked'])
         ->whereBetween('start_date', [$startDate->format('Y-m-d 00:00:00'), $startDate->format('Y-m-d 23:59:59')])
         ->get()); 

        $cancelGraph[] .= count(UserBooking::WhereIn('booking_status', ['cancelled', 'enquiry_cancelled', 'refunded'])
         ->whereBetween('start_date', [$startDate->format('Y-m-d 00:00:00'), $startDate->format('Y-m-d 23:59:59')])
         ->get()); 

        $pendingGraph[] .= count(UserBooking::WhereIn('booking_status', ['pending', 'enquiry_pending', 'enquiry_approved'])
         ->whereBetween('start_date', [$startDate->format('Y-m-d 00:00:00'), $startDate->format('Y-m-d 23:59:59')])
         ->get()); 

        $i++;
      }

      
      //percentage of booking

      $from = $from->subDay($toloop)->format('Y-m-d 00:00:00');
      $booked=UserBooking::where('booking_status','=','booked')
      ->whereBetween('start_date', [$from, $to->format('Y-m-d 23:59:59')])
      ->get(); 

      $cancelled=UserBooking::WhereIn('booking_status', ['cancelled', 'enquiry_cancelled', 'refunded'])
      ->whereBetween('start_date', [$from, $to->format('Y-m-d 23:59:59')])
      ->get(); 
      
      $pending=UserBooking::WhereIn('booking_status', ['pending', 'enquiry_pending', 'enquiry_approved'])
      ->whereBetween('start_date', [$from, $to->format('Y-m-d 23:59:59')])
      ->get(); 

      $total = UserBooking::whereBetween('start_date', [$from, $to->format('Y-m-d 23:59:59')])
      ->get();

      $totalnumBooking = UserBooking::whereBetween('start_date', [$from, $to->format('Y-m-d 23:59:59')])->get();

      $d['bookingtotal'] = count($booked) + count($cancelled) + count($pending); //UserBooking::all();
      if(!$d['bookingtotal']) {
        $d['bookingtotal'] = 1;
      }

      $d['bookedbooking'] = (count($booked) * 100 ) / $d['bookingtotal']; //count($d['bookingtotal']);
      $d['cancelbooking'] = (count($cancelled) * 100 ) / $d['bookingtotal']; //count($d['bookingtotal']);
      $d['pendingbooking'] = (count($pending) * 100 ) / $d['bookingtotal']; //count($d['bookingtotal']);

      $diff_typ = "Days";
      $tod = Carbon::now();
      $fromd = UserBooking::orderBy('id', 'DESC')->first()->start_date;
      $diff = $tod->diffInYears($fromd);
      $numberOfDiff = ($diff > 0) ? $diff : 1;
      $numberOfCal = ($diff > 0) ? $diff : 1;
    }
  

    $xAxesValue = '';
    $xAxes = array_reverse($xAxes);
    foreach ($xAxes as $xa) {
      $xAxesValue .= '"' . $xa . '",';
    }

    // $allbooking = UserBooking::all();
    // if(count($allbooking) == 0){
    //   $allboookingComparePer = 0;
    // } 
    // else 
    // {
    //   $allboookingComparePer = ($allbooking->count() / $allbooking->count() ) * 100;
    // }


    $d['xAxes'] = $xAxesValue;
    $d['xName'] = $diff;
    // $d['totalBookings'] = count($allbooking);
    // $d['complete_booking'] = $bookingComplete;
    // $d['cancel_booking'] = $bookingCancelled;
    // $d['diff_typ'] = $diff_typ;
    // $d['numberOfDiff'] = $numberOfDiff;
    $d['bookingGraph'] = implode(',', array_reverse($bookingGraph));
    $d['cancelGraph'] = implode(',', array_reverse($cancelGraph));
    $d['pendingGraph'] = implode(',', array_reverse($pendingGraph));
    $d['revenuesubyear'] = count($revenuesubyear);
    $d['msgBookings'] = $msgBookings;
    $d['totalnumBooking'] = $totalnumBooking;

    $dt = Carbon::now()->today();
    $d['title'] = "Dashboard";
    $d['tot_property'] = count(Property::all());
    $d['tot_space'] = count(Space::all());
    $d['tot_booking'] = count(UserBooking::all());
    $d['tot_packages'] = count(Plans::all());
    $last_five_booking = UserBooking::orderBy('id', 'DESC')->take(5)->get();

    foreach ($last_five_booking as $key => $value) {
      $property = Property::where('id','=',$value->property_id)->first();
      $last_five_booking[$key]['property_title'] = $property->property_title;
      $last_five_booking[$key]['property_rating'] = $property->avg_rating;
      $last_five_booking[$key]['property_price_from'] = $property->price_from;
      $last_five_booking[$key]['property_price_to'] = $property->price_to;
      $last_five_booking[$key]['property_image'] = $property->thumbnail;
    }
    $d['last_five_booking'] = $last_five_booking;


    return view('/home', $d);
  }




  public function getPerformance(Request $r)
  {
    $date = explode('-', $r->custom);
    $d['users'] = $this->customers($r);
    $d['orders'] = $this->orders($r);
    $d['orderItem'] = $this->orderitems($r);
    $d['totsales'] = $this->totalSales($r);
    $d['salescan'] = $this->saleCancelled($r);
    $d['totrevenue'] = $this->totalRevenue($r);
    $d['order'] = Order::orderBy('id', 'desc')->take(10)->get();
    $d['revenue'] = $this->CurrentYearRevenue('', $r);
    $d['tot_product'] = count(Product::all());
    $d['sales'] = $this->productByCategory($r->custom, '');
    $d['periods'] = CarbonPeriod::create(Carbon::parse($date[0])->format('Y-m-d'), Carbon::parse($date[1])->format('Y-m-d'));
    return view('home', $d);
  }




  public function CurrentYearRevenue($year = '', $date = "")
  {
    $month = [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0];
    $order = [];
    if (isset($date->custom) && $date->custom != "") {
      $date = explode('-', $date->custom);
      $datefrom = Carbon::parse($date[0])->format('Y');
      $dateto = Carbon::parse($date[1])->format('Y');
      $currentyear = Carbon::parse(now())->format('Y');
      if ($currentyear != $datefrom) {
        $order = OrderedProducts::whereYear('created_at', $datefrom)->get();
      } elseif ($currentyear != $dateto) {
        $order = OrderedProducts::whereYear('created_at', $dateto)->get();
      } else {
        $order = OrderedProducts::whereYear('created_at', $currentyear)->get();
      }
    } else {
      $order = OrderedProducts::whereYear('created_at', $year)->get();
    }

    if (count($order) > 0) {
      foreach ($order as $value) {
        $fDate = Carbon::parse($value->created_at)->format('m');
        switch ($fDate) {
          case '01':
          $month[0] += ($value->total_price - $value->p_price);
          break;
          case '02':
          $month[1] += ($value->total_price - $value->p_price);
          break;
          case '03':
          $month[2] += ($value->total_price - $value->p_price);
          break;
          case '04':
          $month[3] += ($value->total_price - $value->p_price);
          break;
          case '05':
          $month[4] += ($value->total_price - $value->p_price);
          break;
          case '06':
          $month[5] += ($value->total_price - $value->p_price);
          break;
          case '07':
          $month[6] += ($value->total_price - $value->p_price);
          break;
          case '08':
          $month[7] += ($value->total_price - $value->p_price);
          break;
          case '09':
          $month[8] += ($value->total_price - $value->p_price);
          break;
          case '10':
          $month[9] += ($value->total_price - $value->p_price);
          break;
          case '11':
          $month[10] += ($value->total_price - $value->p_price);
          break;
          case '12':
          $month[11] += ($value->total_price - $value->p_price);
          break;
        }
      }
    }
    $actualPrice = $order->sum('p_price');
    $salePrice = $order->sum('total_price');
    $revenue = $salePrice - $actualPrice;
    return  $month;
  }
  public function customers(Request $r)
  {
    $data = [];
    $i = 0;
    if ($r->has('custom')) {
      $date = explode('-', $r->custom);
      $users = User::whereBetween(
        'created_at',
        [
          Carbon::parse($date[0])->format('y-m-d'),
          Carbon::parse($date[1])->format('y-m-d')
        ]
      )->get();

      $dateGrp = User::select(DB::raw('Date(created_at) as date'))->whereBetween(
        'created_at',
        [
          Carbon::parse($date[0])->format('Y-m-d'),
          Carbon::parse($date[1])->format('Y-m-d')
        ]
      )->groupBy(DB::raw('Date(created_at)'))->get();
      if (count($dateGrp) > 0) {
        foreach ($dateGrp as $value) {
          $query = User::whereDate('created_at', $value->date)->get();
          $data[$i++] = ['user' => count($query), 'date' => $value->date];
        }
      }
      return ['total_users' => count($users), 'users' => $data];
    }
  }
  public function orders(Request $r)
  {

    $data = [];
    $i = 0;
    if ($r->has('custom')) {
      $date = explode('-', $r->custom);
      $orders = Order::whereBetween(
        'created_at',
        [
          Carbon::parse($date[0])->format('y-m-d'),
          Carbon::parse($date[1])->format('y-m-d')
        ]
      )->get();

      $dateGrp = Order::select(DB::raw('Date(created_at) as date'))->whereBetween(
        'created_at',
        [
          Carbon::parse($date[0])->format('Y-m-d'),
          Carbon::parse($date[1])->format('Y-m-d')
        ]
      )->groupBy(DB::raw('Date(created_at)'))->get();
      if (count($dateGrp) > 0) {
        foreach ($dateGrp as $value) {
          $query = Order::whereDate('created_at', $value->date)->get();
          $data[$i++] = ["data" => count($query), 'date' => $value->date];
        }
      }
      return ['total_orders' => count($orders), 'orders' => $data];
    }
  }
  public function orderitems(Request $r)
  {
    $data = [];
    $i = 0;
    if ($r->has('custom')) {
      $date = explode('-', $r->custom);
      $orders = OrderedProducts::whereBetween(
        'created_at',
        [
          Carbon::parse($date[0])->format('y-m-d'),
          Carbon::parse($date[1])->format('y-m-d')
        ]
      )->get();

      $dateGrp = OrderedProducts::select(DB::raw('Date(created_at) as date'))->whereBetween(
        'created_at',
        [
          Carbon::parse($date[0])->format('Y-m-d'),
          Carbon::parse($date[1])->format('Y-m-d')
        ]
      )->groupBy(DB::raw('Date(created_at)'))->get();
      if (count($dateGrp) > 0) {
        foreach ($dateGrp as $value) {
          $data[$i++] = OrderedProducts::whereDate('created_at', $value->date)->get();
        }
      }
      return ['tot_order_item' => count($orders), 'order_items' => $data];
    }
  }
  public function totalSales(Request $r)
  {
    $data = [];
    $i = 0;
    if ($r->has('custom')) {
      $date = explode('-', $r->custom);
      $orders = OrderedProducts::whereBetween(
        'created_at',
        [
          Carbon::parse($date[0])->format('y-m-d'),
          Carbon::parse($date[1])->format('y-m-d')
        ]
      )->where('status', 'delivered')->sum('quantity');

      $dateGrp = OrderedProducts::select(DB::raw('Date(created_at) as date'))->whereBetween(
        'created_at',
        [
          Carbon::parse($date[0])->format('Y-m-d'),
          Carbon::parse($date[1])->format('Y-m-d')
        ]
      )->groupBy(DB::raw('Date(created_at)'))->where('status', 'delivered')->get();
      if (count($dateGrp) > 0) {
        foreach ($dateGrp as $value) {
          $query = OrderedProducts::whereDate('created_at', $value->date)->where('status', 'delivered');
          $data[$i++] = ['data' => $query->sum('quantity'), 'date' => $value->date];
        }
      }
      return ['tot_sales' => $orders, 'sales_items' => $data];
    }
  }
  public function saleCancelled(Request $r)
  {
    $data = [];
    $i = 0;
    if ($r->has('custom')) {
      $date = explode('-', $r->custom);
      $orders = OrderedProducts::whereBetween(
        'created_at',
        [
          Carbon::parse($date[0])->format('y-m-d'),
          Carbon::parse($date[1])->format('y-m-d')
        ]
      )->where('status', 'return')->sum('quantity');

      $dateGrp = OrderedProducts::select(DB::raw('Date(created_at) as date'))->whereBetween(
        'created_at',
        [
          Carbon::parse($date[0])->format('Y-m-d'),
          Carbon::parse($date[1])->format('Y-m-d')
        ]
      )->groupBy(DB::raw('Date(created_at)'))->where('status', 'return')->get();
      if (count($dateGrp) > 0) {
        foreach ($dateGrp as $value) {
          $query = OrderedProducts::whereDate('created_at', $value->date)->where('status', 'return');
          $data[$i++] = ['data' => $query->sum('quantity'), 'date' => $value->date];
        }
      }
      return ['tot_sales' => $orders, 'sales_items' => $data];
    }
  }
  public function totalRevenue(Request $r)
  {
    $data = [];
    $i = 0;
    if ($r->has('custom')) {
      $date = explode('-', $r->custom);
      $query = OrderedProducts::whereBetween(
        'created_at',
        [
          Carbon::parse($date[0])->format('y-m-d'),
          Carbon::parse($date[1])->format('y-m-d')
        ]
      )->where('status', 'delivered');
      $sp = $query->sum('total_price');
      $pp = $query->sum('p_price');
      $dateGrp = OrderedProducts::select(DB::raw('Date(created_at) as date'))->whereBetween(
        'created_at',
        [
          Carbon::parse($date[0])->format('Y-m-d'),
          Carbon::parse($date[1])->format('Y-m-d')
        ]
      )
      ->groupBy(DB::raw('Date(created_at)'))
      ->where('status', 'delivered')->get();
      if (count($dateGrp) > 0) {
        foreach ($dateGrp as $value) {
          $query = OrderedProducts::whereDate('created_at', $value->date)
          ->where('status', 'delivered');
          $data[$i++] = ['data' => $query->sum('total_price') - $query->sum('p_price'), 'date' => $value->date];
        }
      }
      return ['tot_revenue' => $sp - $pp, 'revenue' => $data];
    }
  }

  public function productByCategory($custom = '', $year = '')
  {
    if ($custom != "") {
      $date = explode('-', $custom);
      $order = OrderedProducts::select('category')->groupBy('category')
      ->whereBetween('created_at', [
        Carbon::parse($date[0])->format('y-m-d'),
        Carbon::parse($date[1])->format('y-m-d')
      ])->whereNotNull('category')->take(5)->get();
      $item = [];
      $i = 0;
      $label = [];
      if (count($order) > 0) {
        foreach ($order as $key => $value) {
          $tot = count(OrderedProducts::select('category')->whereBetween('created_at', [
            Carbon::parse($date[0])->format('y-m-d'),
            Carbon::parse($date[1])->format('y-m-d')
          ])
          ->where('category', $value->category)->get());
          $item[$i] = $tot;
          $label[$i++] = $value->category;
        }
      }
      return ['label' => $label, 'data' => $item];
    } else {
      $order = OrderedProducts::select('category')->groupBy('category')
      ->whereYear('created_at', $year)->whereNotNull('category')->take(5)->get();
      $item = [];
      $i = 0;
      $label = [];
      if (count($order) > 0) {
        foreach ($order as $key => $value) {
          $tot = count(OrderedProducts::select('category')->whereYear('created_at', $year)
            ->where('category', $value->category)->get());
          $item[$i] = $tot;
          $label[$i++] = $value->category;
        }
      }
      return ['label' => $label, 'data' => $item];
    }
  }
  public function loadPage()
  {
    $d['banner'] = HomePageSetting::orderBy('id', 'desc')->get();
    $d['title'] = "Manage Page Module";
    return view('admin.home.index', $d);
  }
  public function addModule()
  {
    $d['title'] = "Add Page Content Module";
    $d['category'] = Category::where('cid', "No Parent")->get();
    return view('admin.home.add-module', $d);
  }
  public function store(Request $request)
  {
    $attribute = [];
    $i = 0;
    if ($request->has('bseller')) {
      $attribute[$i++] = $request->bseller;
    }
    if ($request->has('newA')) {
      $attribute[$i++] = $request->newA;
    }

    $hm = HomePageSetting::updateOrCreate(
      ['id' => $request->id],
      [
        'page_module' => $request->module,
        "pricing_type" => $request->pricingType,
        "show_as" => $request->showas,
        "min_pricing" => $request->minPricing,
        "max_pricing" => $request->maxPricing,
        "product_category" => json_encode($request->cat),
        "content_title" => $request->title,
        "contents" => $request->content,
        "attributes" => $request->newA,
        "content_position" => $request->content_post,
        "content_priority" => $request->position,
        "total_product_to_show" => $request->totproduct,
        "total_product_in_row" => $request->productrow,
        "meta_title" => $request->meta_title,
        "meta_description" => $request->meta_keyword,
      ]
    );
    if ($request->has('banner')) {
      $hm->images = $request->file('banner')->move('public/banner', uniqid() . $request->file('banner')->getClientOriginalName());
      $hm->update();
    }
    if ($request->has('mobile_banner')) {
      $hm->mobile_banner = $request->file('mobile_banner')->move('public/banner', uniqid() . $request->file('mobile_banner')->getClientOriginalName());
      $hm->update();
    }

    return back()->with("msg", "Added successfully");
  }

  public function edit($id)
  {
    $d['title'] = "Edit page module";
    $d['data'] = $pc = HomePageSetting::findOrFail($id);
    $d['categories'] = Category::whereIn('id', json_decode($pc->product_category, true))->get();
    $d['category'] = Category::where('cid', "No Parent")->get();
    return view('admin.home.add-module', $d);
  }
  public function destroy($id)
  {
    if (request()->ajax()) {
      HomePageSetting::destroy($id);
    }
  }
  public function changeStatus($id, $st)
  {
    $hm = HomePageSetting::findOrFail($id);
    $hm->status = $st;
    $hm->update();
    return back();
  }
}
