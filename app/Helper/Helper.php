<?php

namespace App\Helper;
use DB;
use App\BookingActivity;
use App\Property;
use App\Space;
use App\SpaceExtraDetails;
use App\SpaceDayPrice;
use App\AvailabilityDesk;
use App\Notifications;



class Helper
{
	
    public static function sendNotification($title,$msg,$img,$user_id,$type){
    	
        $firebaseToken =    DB::table('user_device_token')
                            ->where('user_id','=',$user_id)
                            ->distinct('device_token')
                            ->pluck('device_token')
                            ->all();

        if(count($firebaseToken) > 0){

            $SERVER_API_KEY = 'AAAAiAxlOvk:APA91bFd-Ml4T0dZDXv48JePKdP8a6YB6BLKe-QX52d-7MdXOcciJX8mdWcNdjRT60dgOFxieiT0g6AM3mLSZnZMG1waWuajskgsTN-wHgpmV3cM9HXA8bZGVATuDrwMzEoDm3ge2g-b';
            

            $data = [
                "registration_ids" => $firebaseToken, //['cjtS8z35QUidhSopSttiV_:APA91bHQAAxAHKxsF-vFw5zXn9wZPexhjxG5eVdOR1hsMT4eW0PYfvF9NQWT8lmMABYWjU_kGsz0XuRHtG9_QLfJsNUlUGurWl8OmodFNybJB4CL7DeVDRyT2ZjnVFAetYr-upX65vyo'],
                "notification" => [
                    "title" => $title,
                    "body" => $msg,
                    // "image" =>"https://ps.w.org/wp-notification-bell/assets/icon-256x256.png"
                ],
                "data" => [
                    "type" => $type,
                ]
            ];
            $dataString = json_encode($data);

            $headers = [
                'Authorization: key=' . $SERVER_API_KEY,
                'Content-Type: application/json',
            ];
            $ch = curl_init();

            curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $dataString);

            $response = curl_exec($ch);

            $Notifications = Notifications::insert([

                                'user_id'       => $user_id,
                                'title'         => $title,
                                'type'          => $type,
                                'body'          => $msg,
                                //'image'         => $image,
                               // 'status'        => $status,

                            ]);

            return $response;
        }
	}

    public static function saveBookingActivity($bookingId,$msg){

        $BookingActivity = BookingActivity::insert([

                                'order_id'  => $bookingId,
                                'message'   => $msg,
                           ]);

        return 'success';


    }

    public static function updatePropertySpace($spaceId){

        $getSingleSpace = Space::where('id','=',$spaceId)->first();

        $getProperty = Property::where('id','=',$getSingleSpace->property_id)->first();

        $sumOfTotalDesk = Space::where('property_id','=',$getProperty->id)->sum('total_desk');

        $getallspace_id = [];

        $availability_type = [];


        $getallPropertySpace = Space::where('property_id','=',$getProperty->id)->get();

            if(count($getallPropertySpace) > 0){

                foreach ($getallPropertySpace as $key => $value) {

                    $getallspace_id[]       = $value->id; 
                    $availability_type[]   = $value->availability_type;
                }

                if(!empty($getallspace_id)){

                    $price = [];

                    foreach ($getallspace_id as $key2 => $value2) {

                    $costspace = SpaceDayPrice::select('price')->where('space_id','=',$value2)->get();

                        foreach ($costspace as $key1 => $value1) {

                            $price[] = $value1->price;

                        }
                    }

                    $max = max($price);

                    $min = min($price);

                }

                $max1 = "";
                $stats = true;
                $final = $availability_type[0];
                foreach($availability_type as $key => $val){
                    if($stats) {
                        if($key == 0){
                            $max1 = $val;
                        }
                        else if($stats && $max1 == $val){
                            $max1 = $val;
                            $final = $val;
                        } else {
                            $stats = false;
                            $final = 3;
                        }
                    }
                }

                
            }

            $property = Property::where('id','=',$getProperty->id)
                            ->update([
                                        'price_from'           => !empty($min) ? $min : 0,
                                        'price_to'           => !empty($max) ? $max : 0,
                                        'availability_type' => !empty($final) ? $final : 1,
                                        'total_desk' => !empty($sumOfTotalDesk) ? $sumOfTotalDesk : '0'
                            ]);

        return true;

    }

    public static function updatePropertySpaceOnUpdate($spaceId,$priviousPropertyid){

        $getSingleSpace = Space::where('id','=',$spaceId)->first();

        $getProperty = Property::where('id','=',$priviousPropertyid)->first();

        $sumOfTotalDesk = Space::where('property_id','=',$priviousPropertyid)->sum('total_desk');

         $getallspace_id = [];

          $availability_type = [];


          $getallPropertySpace = Space::where('property_id','=',$priviousPropertyid)->get();

            if(count($getallPropertySpace) > 0){

              foreach ($getallPropertySpace as $key => $value) {

                $getallspace_id[]       = $value->id; 
                 $availability_type[]   = $value->availability_type;
              }

                if(!empty($getallspace_id)){

                    $price = [];

                    foreach ($getallspace_id as $key2 => $value2) {

                    $costspace = SpaceDayPrice::select('price')->where('space_id','=',$value2)->get();

                        foreach ($costspace as $key1 => $value1) {

                            $price[] = $value1->price;

                        }
                    }

                  if(!empty($price)){

                      $max = max($price);

                    $min = min($price);
                  }

                }

                $max1 = "";
                $stats = true;
                $final = $availability_type[0];
                foreach($availability_type as $key => $val){
                    if($stats) {
                        if($key == 0){
                            $max1 = $val;
                        }
                        else if($stats && $max1 == $val){
                            $max1 = $val;
                            $final = $val;
                        } else {
                            $stats = false;
                            $final = 3;
                        }
                    }
                }

                
            }

            $property = Property::where('id','=',$priviousPropertyid)
                            ->update([
                                        'price_from'           => !empty($min) ? $min : 0,
                                        'price_to'           => !empty($max) ? $max : 0,
                                        'availability_type' => !empty($final) ? $final : 1,
                                        'total_desk' => !empty($sumOfTotalDesk) ? $sumOfTotalDesk : '0'
                            ]);



        return true;

    }

    public static function updatePropertySpaceOnDelete($spaceId){

         $Space = Space::where('id','=',$spaceId)->first();

         $propertyid = $Space->property_id;

         Space::destroy($spaceId);

         SpaceExtraDetails::where('space_id','=',$spaceId)->delete();

         AvailabilityDesk::where('space_id','=',$spaceId)->delete();

         SpaceDayPrice::where('space_id','=',$spaceId)->delete();


        $getProperty = Property::where('id','=',$propertyid)->first();

        $sumOfTotalDesk = Space::where('property_id','=',$propertyid)->sum('total_desk');

         $getallspace_id = [];

          $availability_type = [];


          $getallPropertySpace = Space::where('property_id','=',$propertyid)->get();

            if(count($getallPropertySpace) > 0){

              foreach ($getallPropertySpace as $key => $value) {

                 $getallspace_id[]       = $value->id; 
                 $availability_type[]   = $value->availability_type;
              }

                if(!empty($getallspace_id)){

                    $price = [];

                    foreach ($getallspace_id as $key2 => $value2) {

                    $costspace = SpaceDayPrice::select('price')->where('space_id','=',$value2)->get();

                        foreach ($costspace as $key1 => $value1) {

                            $price[] = $value1->price;

                        }
                    }

                    $max = max($price);

                    $min = min($price);

                }

                $max1 = "";
                $stats = true;
                $final = $availability_type[0];
                foreach($availability_type as $key => $val){
                    if($stats) {
                        if($key == 0){
                            $max1 = $val;
                        }
                        else if($stats && $max1 == $val){
                            $max1 = $val;
                            $final = $val;
                        } else {
                            $stats = false;
                            $final = 3;
                        }
                    }
                }
            }

          $property = Property::where('id','=',$propertyid)
                        ->update([
                                    'price_from'           => !empty($min) ? $min : 0,
                                    'price_to'           => !empty($max) ? $max : 0,
                                    'availability_type' => !empty($final) ? $final : 1,
                                    'total_desk' => !empty($sumOfTotalDesk) ? $sumOfTotalDesk : '0'
                        ]);


}




}