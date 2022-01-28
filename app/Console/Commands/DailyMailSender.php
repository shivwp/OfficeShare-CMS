<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use App\Mail\BookingSuccess;
use App\UserBooking;
use Log;
use App\Setting;
use App\MailTemplate;
use App\PropertyLocation;
use App\Space;
use App\User;

class DailyMailSender extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'daily:mail';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send a Daily email to all users';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        // 
        \Log::info('wokring');
        try {

            $st = Setting::where('options','=','site_url')->first();
            $st1 = Setting::first();
            
            $bookings = UserBooking::where('start_date', \Carbon\Carbon::now()->format('Y-m-d 00:00:00'))->get();
            Log::info('wokring 1');

            
            Log::info($bookings);
            foreach ($bookings as $key => $booking) {
                // code...
                //$this->sendmail_call($booking);

                $user = User::where('id', $booking->user_id)->first();
                // $landload = User::where('id', $booking->landload_id)->first();
                // $space = Space::where('id','=',$booking->space_id)->first();
                // $location = PropertyLocation::where('property_id','=',$booking->property_id)->first();
                // $address = [
                //     'address_1'           => $location->address,
                //     'address_2'           => $location->address_2,
                //     'city'                => $location->city,
                //     'state'               => $location->state,
                //     'country'             => $location->country,
                //     'postcode'            => $location->postcode
                // ]; 
                // $full_address = '';
                // foreach ($address as $key => $value) {
                //     // code...
                //     $full_address .= isset($value)?$value.' ':'';
                // }

                // $start_date=date_create($booking->start_date);
                // $end_date=date_create($booking->end_date);

                // $sign = [
                //   '{{name}}' => $user->name,
                //   '{{ref_id}}' => 'ref_'.$booking->id,
                //   '{{location}}' => $full_address,
                //   '{{space_name}}' => $space->space_title,
                //   // '{{number_of_desks}}' => $bookedSpace_count,
                //   '{{start_date}}' => date_format($start_date,"Y-M-d"),
                //   '{{end_date}}' => date_format($end_date,"Y-M-d"),
                //   '{{booking_days}}' => $landload->name,
                //   '{{host_name}}' => $landload->name,
                //   '{{host_email}}' => $landload->email,
                //   '{{host_phone}}' => $landload->phone,
                // ];

                // Log::info($sign);

                // $msgData = MailTemplate::where('status', trim('booking_journey_started'))->first();
                // $replMsg = MailTemplate::where('status', trim('booking_journey_started'))->pluck('message')->first();
                

                // foreach ($sign as $key => $value) {
                //     $replMsg = str_replace($key, $value, $replMsg);
                // }

                $mail_msg =  ['fromemail' => 'tapang786@gmail.com', "replyemail" => 'tapang786@gmail.com', 'msg' => 'tapang786@gmail.com', 'subject' => 'tapang786@gmail.com', 'name' => 'tapang786@gmail.com', 'status' => true];

                Log::info($mail_msg);

                // Mail::to('tapang786@gmail.com')->send(new Bookings($mail_msg));
            }

        } catch (Exception $e) {
            \Log::info($e);
        }
        
    }


    public function sendmail_call($booking)
    {
        // code...
        
    }
}
