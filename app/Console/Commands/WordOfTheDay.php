<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use App\Mail\BookingSuccess;
use App\UserBooking;
use App\User;
use Log;
use App\Setting;
use App\MailTemplate;
use App\Helper\Helper;

use App\PropertyLocation;
use App\Property;
use App\Space;

class WordOfTheDay extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'word:day';

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
        \Log::info("Cron is working fine!");

        try {
            // 

            // Booking Journey Started
            $bookings = UserBooking::where('booking_status', 'booked')
                ->where('start_date', \Carbon\Carbon::now()->format('Y-m-d 00:00:01'))
                ->get();
            // $i=1;
            foreach ($bookings as $key => $booking) {
                // code...
                // if($i == 1) {
                $user = User::where('id', $booking->user_id)->first();
                $landload = User::where('id', $booking->landload_id)->first();
                $property = Property::where('id','=',$booking->property_id)->first();
                $space = Space::where('id','=',$booking->space_id)->first();
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

                $start_date=date_create($booking->start_date);
                $end_date=date_create($booking->end_date);

                $sign = [
                  '{{name}}' => isset($user->name)?$user->name:'',
                  '{{ref_id}}' => '#'.$booking->id,
                  '{{location}}' => $full_address,
                  '{{space_name}}' => isset($space)?$space->space_title:'',
                  '{{price}}' => $booking->price,
                  '{{start_date}}' => date_format($start_date,"Y-M-d"),
                  '{{end_date}}' => date_format($end_date,"Y-M-d"),
                  '{{booking_days}}' => isset($landload)?$landload->name:'',
                  '{{host_name}}' => isset($landload)?$landload->name:'',
                  '{{host_email}}' => isset($landload)?$landload->email:'',
                  '{{host_phone}}' => isset($landload)?$landload->phone:'',
                  '{{invoice_link}}' => '<a href="https://os.eoxysitsolution.com/" style="color:#fff; text-decoration:none;">Invoice</a>',
                  '{{package}}' => 'Package Name',
                  '{{terms}}' => '<a href="#terms" style="text-decoration: none; color: #000;">Terms</a>',
                  '{{terms_condition}}' => '<a href="#terms-condition" style="text-decoration: none; color: #fff;">View Terms & Conditions</a>',
                ];

                $msgData = MailTemplate::where('status', trim('booking_journey_started'))->first();
                $replMsg = MailTemplate::where('status', trim('booking_journey_started'))->pluck('message')->first();
                

                foreach ($sign as $key => $value) {
                    $replMsg = str_replace($key, $value, $replMsg);
                }

                $mail_msg = ['fromemail' => $msgData->from_email, "replyemail" => $msgData->reply_email, 'msg' => $replMsg, 'subject' => $msgData->subject, 'name' => $msgData->name, 'status' => true];

                // Mail::to('tapang786@gmail.com')->send(new BookingSuccess($mail_msg));
                Mail::to($user->email)->send(new BookingSuccess($mail_msg));

                $title = "Booking Journey Started";
                $msg = $user->name.', You are on your journey to sharing a space with the Office share community.';
                //$title,$msg,
                $image = ''; //,$user_id,
                $type = "booking_journey_started";
                $notification = Helper::sendNotification($title, $msg, '', $user->id, $type);

                // $i++;
                // }
            }

            $bookings_ends = UserBooking::where('booking_status', 'booked')
                            ->where('end_date', \Carbon\Carbon::now()->format('Y-m-d 00:00:00'))
                            ->get();

            // $j = 1;
            foreach ($bookings_ends as $key => $booking) {
                // code...
                // if($j==1) {

                $user = User::where('id', $booking->user_id)->first();
                $landload = User::where('id', $booking->landload_id)->first();
                $space = Space::where('id','=',$booking->space_id)->first();
                // \Log::info('---------'.$user->name);
                // exit;
                $location = PropertyLocation::where('property_id','=',$booking->property_id)->first();

                $property = Property::where('id','=',$booking->property_id)->first();

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

                $start_date=date_create($booking->start_date);
                $end_date=date_create($booking->end_date);

                $host_stars = '<div class="star-1">
    <p style="line-height: 25px;font-size:8px;font-style: italic;margin-right:5px; font-weight: 400; ">1 Star Average</p>
    <div class="str-img" style="display:flex;"><a href="'.env('frontend_url').'/#/rating/?booking='.$booking->id.'&rating=1&type=host"><img src="'.url('/').'images/star1.png" /></div>
</div>
<div class="star-2">
    <p style=" line-height: 25px;font-size:8px;font-style: italic;margin-right:5px; font-weight: 400; ">2 Star Average</p>
    <div class="str-img" style="display:flex;"><a href="'.env('frontend_url').'/#/rating/?booking='.$booking->id.'&rating=2&type=host"><img src="'.url('/').'images/star2.png" /></div>
</div>
<div class="star-3">
    <p style=" line-height: 25px;font-size:8px;font-style: italic;margin-right:5px; font-weight: 400; ">3 Star Good</p>
    <div class="str-img" style="display:flex;"><a href="'.env('frontend_url').'/#/rating/?booking='.$booking->id.'&rating=3&type=host"><img src="'.url('/').'images/star3.png" /></div>
</div>
<div class="star-4">
    <p style=" line-height: 25px;font-size:8px;font-style: italic;margin-right:5px; font-weight: 400; ">4 Star Very Good</p>
    <div class="str-img" style="display:flex;"><a href="'.env('frontend_url').'/#/rating/?booking='.$booking->id.'&rating=4&type=host"><img src="'.url('/').'images/star4.png" /></div>
</div>
<div class="star-5">
    <p style=" line-height: 25px;font-size:8px;font-style: italic;margin-right:5px; font-weight: 400; ">5 Star Excellent</p>
    <div class="str-img" style="display:flex;"><a href="'.env('frontend_url').'/#/rating/?booking='.$booking->id.'&rating=5&type=host"><img src="'.url('/').'images/star5.png" /></div>
</div>';

$property_stars = '<div class="star-1">
    <p style="line-height: 25px;font-size:8px;font-style: italic;margin-right:5px; font-weight: 400; ">1 Star Average</p>
    <div class="str-img" style="display:flex;"><a href="'.env('frontend_url').'/#/rating/?booking='.$booking->id.'&rating=1&type=property"><img src="'.url('/').'images/star1.png" /></div>
</div>
<div class="star-2">
    <p style=" line-height: 25px;font-size:8px;font-style: italic;margin-right:5px; font-weight: 400; ">2 Star Average</p>
    <div class="str-img" style="display:flex;"><a href="'.env('frontend_url').'/#/rating/?booking='.$booking->id.'&rating=2&type=property"><img src="'.url('/').'images/star2.png" /></div>
</div>
<div class="star-3">
    <p style=" line-height: 25px;font-size:8px;font-style: italic;margin-right:5px; font-weight: 400; ">3 Star Good</p>
    <div class="str-img" style="display:flex;"><a href="'.env('frontend_url').'/#/rating/?booking='.$booking->id.'&rating=3&type=property"><img src="'.url('/').'images/star3.png" /></div>
</div>
<div class="star-4">
    <p style=" line-height: 25px;font-size:8px;font-style: italic;margin-right:5px; font-weight: 400; ">4 Star Very Good</p>
    <div class="str-img" style="display:flex;"><a href="'.env('frontend_url').'/#/rating/?booking='.$booking->id.'&rating=4&type=property"><img src="'.url('/').'images/star4.png" /></div>
</div>
<div class="star-5">
    <p style=" line-height: 25px;font-size:8px;font-style: italic;margin-right:5px; font-weight: 400; ">5 Star Excellent</p>
    <div class="str-img" style="display:flex;"><a href="'.env('frontend_url').'/#/rating/?booking='.$booking->id.'&rating=5&type=property"><img src="'.url('/').'images/star5.png" /></div>
</div>';


                $sign = [
                  '{{name}}' => isset($user->name)?$user->name:'',
                  '{{username}}' => isset($user->name)?$user->name:'',
                  '{{ref_id}}' => '#'.$booking->id,
                  '{{location}}' => $full_address,
                  '{{property_name}}' => $property->property_title,
                  '{{space_name}}' => isset($space->space_title)?$space->space_title:'',
                  // '{{property_name}}' => $booking->property,
                  '{{start_date}}' => date_format($start_date,"Y-M-d"),
                  '{{end_date}}' => date_format($end_date,"Y-M-d"),
                  '{{booking_days}}' => isset($landload)?$landload->name:'',
                  '{{host_name}}' => isset($landload)?$landload->name:'',
                  '{{host_email}}' => isset($landload)?$landload->email:'',
                  '{{host_phone}}' => isset($landload)?$landload->phone:'',
                  '{{admin_id}}' => isset($landload)?$landload->id:'',
                  '{{user_id}}' => isset($user)?$user->id:'',
                  '{{approve_link}}' => $booking->id,
                  '{{decline_link}}' => $booking->id,
                  '{{rating_to_host}}' => $host_stars,
                  '{{rating_to_property}}' => $property_stars,
                ]; 

                $msgData = MailTemplate::where('status', trim('rating'))->first();
                $replMsg = MailTemplate::where('status', trim('rating'))->pluck('message')->first();
                

                foreach ($sign as $key => $value) {
                    $replMsg = str_replace($key, $value, $replMsg);
                }

                $mail_msg = ['fromemail' => $msgData->from_email, "replyemail" => $msgData->reply_email, 'msg' => $replMsg, 'subject' => $msgData->subject, 'name' => $msgData->name, 'status' => true];

                // Mail::to('tapang786@gmail.com')->send(new BookingSuccess($mail_msg));
                Mail::to($user->email)->send(new BookingSuccess($mail_msg));

                $title = "Booking Journey Ended";
                $msg = $user->name.', Your Journy is ended. Your feedback is really important, it’s the glue that sticks us together.';
                //$title,$msg,
                $image = ''; //,$user_id,
                $type = "booking_journey_ended";
                $notification = Helper::sendNotification($title, $msg, '', $user->id, $type);

                // $j++;
                // }
            }


        } catch (Exception $e) {
            \Log::info($e);
        }
        
        /*
        $users = User::all();
        foreach ($users as $user) {
            Mail::raw("{$key} -> {$value}", function ($mail) use ($user) {
                $mail->from('info@tutsforweb.com');
                $mail->to('tapang786@gmail.com')
                    ->subject('Word of the Day');
            });
        }
         
        $this->info('Word of the Day sent to All Users');
        */
    }
}
