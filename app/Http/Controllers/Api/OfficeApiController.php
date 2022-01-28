<?php

namespace App\Http\Controllers\Api;

use App\Office;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Attribute;
use App\AttributeValue;
use App\OfficeAttribute;
use App\OfficeAttributeValue;
use App\OfficeDesk;
use App\Country;
use App\SharerAddresses;
use App\OfficeLocation;
use App\OfficeFeaturedImage;
use App\OfficeExtraDetails;
use phpDocumentor\Reflection\Types\Null_;
use Validator;
use App\Property;
use App\Refund;
use App\Space;
use App\SpaceType;
use App\PropertyLocation;
use App\PropertyAttributeValue;
use App\Wishlist;
use App\SpaceExtraDetails;
use App\User;
use App\AvailabilityDesk;
use App\SpaceDayPrice;


class OfficeApiController extends Controller
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
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

    }

   

    public function searchoffice(Request $request)
    {

        $req = [
            'search'=> $request->search,
            'lat'=> $request->lat,
            'long'=> $request->long,
            'user_id'=> $request->user_id,
            'attr_id'=> $request->attr_id,
            'rating'=> $request->rating,
            'no_of_desk_min'=> $request->no_of_desk_min,
            'no_of_desk_max'=> $request->no_of_desk_max,
            'no_of_desk_greater'=> $request->no_of_desk_greater,
            'no_of_desk_below'=> $request->no_of_desk_below,
            'page'=> $request->page,
            'limit'=> $request->limit,
            // 'availability_type' => $request->availability_type
        ];

        $parameters = $request->all();
        extract($parameters);

        // $availability_type = [1, 2, 3];

        

        if(!empty($request->lat) && !empty($request->long) && !empty($request->radius)){

            $locationId = [];

            $sql = "SELECT *, ( 3959 * acos( cos( radians(".$request->lat.") ) * cos( radians( latitude ) ) * cos( radians( longitude ) - radians(".$request->long.") ) + sin( radians(".$request->lat.") ) * sin( radians( latitude ) ) ) ) AS distance FROM dbs_property_locations HAVING distance < ".$request->radius." ORDER BY distance ASC";

            
            $test_center_data = DB::select($sql);

            foreach ($test_center_data as $key => $value) {

              $locationId[] = $value->property_id;
            }
        
          $property = Property::select('property.*')
                            ->where('is_approved', "publish")
                            // ->whereIn('availability_type', $availability_type) //->whereIn('id', [1, 2, 3])
                            ->whereIn('property.id', $locationId)
                            ->join('property_locations', 'property.id', '=', 'property_locations.property_id');
        }
        else{

          $search = $request->search;

            $property = Property::select('property.*')
                            ->where('is_approved', "publish")
                            // ->whereIn('availability_type', $availability_type) //->whereIn('id', [1, 2, 3])
                            ->where(function ($query) use($search) {
                                $query->where('property_title', 'like', "%".$search."%")
                                    ->orWhere('postcode', 'like', "%".$search."%")
                                    ->orWhere('city', 'like',"%".$search."%");
                            })
                            ->join('property_locations', 'property.id', '=', 'property_locations.property_id');


        }

        if(isset($availability_type) && $availability_type == 1) {
            $property->where('availability_type', $availability_type);
            $property->whereOr('availability_type', 3);
        }
        if(isset($availability_type) && $availability_type == 2) {
            $property->where('availability_type', $availability_type);
            $property->whereOr('availability_type', 3);
        } 
        // else {
        //     $availability_type = [1, 2, 3];
        // }
         

         if(!empty($request->rating)) {


             $property->where('avg_rating','=',$request->rating);

            }

         if(!empty($request->no_of_desk_min) && !empty($request->no_of_desk_max)){

            $property->whereBetween('total_desk', [$request->no_of_desk_min, $request->no_of_desk_max]);

            }

        

            if(!empty($request->no_of_desk_greater)){

                $property->where('total_desk', '>=', $request->no_of_desk_greater);

            }

            if(!empty($request->no_of_desk_below)){

                $property->where('total_desk', '<=', $request->no_of_desk_below);

            }
          
         if(!empty($request->attr_id)){

             $property_id = [];

            $attribute_id_arr = explode(',', $request->attr_id);

           $propertySpace = DB::table('property_attributes_values')
                                    ->select('property_id')
                                    ->whereIn('attribute_value_id', $attribute_id_arr)
                                    ->get();
           foreach($propertySpace as $keySpace => $valSpace){

                $property_id[] = $valSpace->property_id;

            }
            $property_id_un = array_unique($property_id);
            $property->whereIn('property.id', $property_id_un);

        }

        if(!empty($request->page) && !empty($request->limit)){

          $page = $request->page;
          $limit = $request->limit;

        $dataaa = $property->limit($limit)->offset(($page - 1) * $limit)->get();
          
        }
        else{

          // $page = 1;
          // $limit = 11;

          $dataaa = $property->get();

          // echo $dataaa;
          // exit;

        }

         //$dataaa = $property->get();

        // return response()->json(['status' => true, 'message' => "success", 'property' => $data], 200);
        if (isset($dataaa))
         {
            foreach ($dataaa as $k => $v) 
            {

                  //Get Property type
                  $property_space_type = DB::table('property_spaces')
                                        ->where('property_id','=',$v->id)
                                        ->where('is_approved','=','publish')
                                        ->select('property_type_id')
                                        ->distinct()
                                        ->get();

                    if(!empty($property_space_type)){

                       $get_type_title = [];

                        foreach ($property_space_type as $key_type => $value_type) {

                     $propertySpace = DB::table('property_type')
                                              ->select('title')
                                              ->where('id','=',$value_type->property_type_id)->first();

                              $get_type_title[] = $propertySpace->title;
                        }

                       $dataaa[$k]['property_type'] = $get_type_title;

                    }

                     //Booking approval

                      if($v->booking_approval == 0){

                        $v->booking_approval = false;

                      }else{

                        $v->booking_approval = true;
                      }

                    //Get Addresses
                    $property_address = DB::table('property_locations')
                              ->where('property_id','=',$v->id)
                              ->first();

               if(!empty($property_address)){

                 $property_address->longitude = (double)$property_address->longitude;
                 $property_address->latitude = (double)$property_address->latitude;

                 if($property_address->address){

                 }
                  
                  $dataaa[$k]['location'] = $property_address;
                }

              //Description

              $dataaa[$k]['short_description'] =  $v->short_description;

                 //price range

                $spaceforcosttype = Space::where('property_id','=',$v->id)
                                  ->where('cost_type','=','range')
                                  ->where('is_approved','=','publish')
                                  ->first();

                if(!empty($spaceforcosttype)){

                  $cost_type = $spaceforcosttype->cost_type;

                }

                $dataaa[$k]['cost_type'] = !empty($spaceforcosttype) ? $cost_type : 'single';

              //Approved

              if($v->is_approved == 'publish'){

                             $dataaa[$k]['approved'] = true;

                        }
                        else{
                             $dataaa[$k]['approved'] = false;

                        }

                        //Number  of Desk

                        $dataaa[$k]['number_of_desk'] =  $v->total_desk;

                        //Thumbnail 

                        $dataaa[$k]['featured_image'] =  url('media/thumbnail/' . $v->thumbnail);

                        //gallery

                         $gallery = json_decode($v->gallary_image);

                          $data = [];

                           foreach ($gallery as $key => $value) {

                              $value = url('media/gallery/' . $value);

                              $data[] = $value;
                          }

                          $dataaa[$k]['gallery_image'] = $data;


                        //liked & Rating

                          if(!empty($request->user_id)){
                                $wishlist = Wishlist::where('user_id','=',$request->user_id)
                                                    ->where('property_id','=',$v->id)
                                                    ->first();
                               
                                if(!empty($wishlist)){

                                    $dataaa[$k]['is_liked'] = true;  
                                }
                                else{
                                    $dataaa[$k]['is_liked'] = false;  
                                }

                            }
                          else{
                              $dataaa[$k]['is_liked'] = false;
                              $dataaa[$k]['rating'] = 0;  
                          }

                       //property extra details

                      $extra = OfficeExtraDetails::where('property_id','=',$v->id)->first();



                        if(!empty($extra)){

                         $dataaa[$k]['about_sharer']        =  $extra->how_to_find_us;
                           
                          $dataaa[$k]['describe_your_space']   =  $v->short_description;
                          $dataaa[$k]['insurance']             =  $extra->insurance;
                          $dataaa[$k]['covid_19_secure']       =  $extra->covid_19_secure;
                        }else{
                          $dataaa[$k]['about_sharer']          =  null;
                          $dataaa[$k]['describe_your_space']   =  Null;
                          $dataaa[$k]['how_to_find_us']        =  Null;
                          $dataaa[$k]['insurance']             =  Null;
                          $dataaa[$k]['covid_19_secure']       =  Null;

                        }

                        //Amenities

                        $attr_id = [];
                        $attr_name = [];

                        $amenties =  DB::table('property_attributes_values')
                                        ->where('property_id','=',$v->id)
                                        ->where('attribute_id','=','2')
                                        ->get();

                        foreach ($amenties as $a_k => $a_v) {

                             $attr_id[] = $a_v->attribute_value_id;

                        }

                        foreach ($attr_id as $att_k => $att_v) {

                            $att_name = DB::table('attributevalues')
                                        ->where('id','=',$att_v)
                                        ->first();
                             //dd($att_name->value);

                           if(!empty($att_name)){
                             $attr_name[$att_k]['value'] = $att_name->value;
                             $attr_name[$att_k]['icon'] = url($att_name->icon);
                           }

                        }

                         $dataaa[$k]['Amenities'] = $attr_name;

             		       //landload details 

                       $landload = User::where('id','=',$v->user_id)->first();

                       $dataaa[$k]['landload'] = $landload;


                       //spaces

                        // $propertySpace = DB::table('property_space_type')
                        //                 ->select('space_type_id')
                        //                 ->where('property_id','=',$v->id)->get();

                        $sapce = [];
                      

                         $space = Space::where('property_id','=',$v->id)
                                     ->where('is_approved', "publish")
                                     ->get();

                        foreach ($space as $sp => $spv) {

                          //cost of thhe day

                          if($spv->cost_type == "range"){

                            $date = \Carbon\Carbon::now()->format('D');

                            $costofday = SpaceDayPrice::where('space_id','=',$spv->id)
                                          ->where('day','=',$date)
                                          ->first();

                            $spv->cost = isset($costofday)?$costofday->price:'';


                          }

                           //space days price range

                           $spaceDays = SpaceDayPrice::select('day','price')->where('space_id','=',$spv->id)->get();

                        	//refund message

                        	$refund = Refund::where('id','=',$spv->booking_payment_refund)->first();

                        	$spv->booking_payment_refund = $refund->message;

                            $space_type = SpaceType::where('id','=',$spv->property_type_id)->first();

                            $space_extra = SpaceExtraDetails::where('space_id','=',$spv->id)->first();

                            $spv->thumb = url('media/thumbnail/' . $spv->thumb);

                            $space[$sp]['space_type'] = $space_type['title'];
                            $space[$sp]['no_of_desk'] = $spv->total_desk;
                            $space[$sp]['min_term'] = !empty($space_extra->min_term) ? $space_extra->min_term : "0";
                            $space[$sp]['max_term'] = !empty($space_extra->max_term) ? $space_extra->max_term :$spv->total_desk;
                            $space[$sp]['things_not_included'] = !empty($space_extra->things_not_included) ? $space_extra->things_not_included :null;
                            $space[$sp]['space_days_price_list'] = $spaceDays;

                            //Booking approval

                            if($spv->booking_approval == 0){

                              $spv->booking_approval = false;

                            }else{

                              $spv->booking_approval = true;
                            }





                         unset($spv->created_at);
                         unset($spv->updated_at);
                         unset($spv->featured_image);
                         unset($spv->gallary_image);
                         unset($spv->property_id);
                         unset($spv->user_id);


                        }

                        $dataaa[$k]['space'] = $space;

                       
                        // $dataaa[$k]['search'] = $request->search;
                        // $dataaa[$k]['lat'] = $request->lat;
                        // $dataaa[$k]['long'] = $request->long;
                        // $dataaa[$k]['user_id'] = $request->user_id;
                        // $dataaa[$k]['attr_id'] = $request->attr_id;
                        // $dataaa[$k]['rating'] = $request->rating;
                        // $dataaa[$k]['no_of_desk_min'] = $request->no_of_desk_min;
                        // $dataaa[$k]['no_of_desk_max'] = $request->no_of_desk_max;
                        // $dataaa[$k]['no_of_desk_greater'] = $request->no_of_desk_greater;
                        // $dataaa[$k]['no_of_desk_below'] = $request->no_of_desk_below;
                        // $dataaa[$k]['page'] = $request->page;
                        // $dataaa[$k]['limit'] = $request->limit;

                      

            unset($v->user_id);
            unset($v->gallary_image);
            unset($v->longitude);
            unset($v->latitude);
            unset($v->created_at);
            unset($v->updated_at);
            unset($v->is_approved);
            unset($v->total_desk);
            unset($v->thumbnail);
            }

             return response()->json(['status' => true, 'message' => "success",'request'=>$req, 'property' => $dataaa], 200);
         }
         else
         {
            return response()->json(['status' => false, 'message' => "Result not found",'request'=>$req, 'property' => null], 200);
         }
    }


    public function singleProperty(Request $request){

             $property = Property::where('id', '=',$request->property_id)
                            ->first();

              if(!empty($property)){

             //Get Property type
                  $property_space_type  =  DB::table('property_spaces')
                                        ->where('property_id','=',$property->id)
                                        ->select('property_type_id')
                                        ->distinct()
                                        ->get();

                    if(!empty($property_space_type)){

                       $get_type_title = [];

                        foreach ($property_space_type as $key_type => $value_type) {

                     $propertySpace = DB::table('property_type')
                                              ->select('title')
                                              ->where('id','=',$value_type->property_type_id)->first();

                              $get_type_title[] = $propertySpace->title;
                        }

                        $property['property_type'] = $get_type_title;

                    }

                     //Booking approval

                      if($property->booking_approval == 0){

                        $property->booking_approval = false;

                      }else{

                        $property->booking_approval = true;
                      }

                    //Get Addresses
                    $property_address = DB::table('property_locations')
                              ->where('property_id','=',$property->id)
                              ->first();

               if(!empty($property_address)){

                 $property_address->longitude = (double)$property_address->longitude;
                 $property_address->latitude = (double)$property_address->latitude;

                 if($property_address->address){

                 }
                  
                  $property['location'] = $property_address;
                }

              //Description

              $property['describe_your_space'] =  $property->short_description;

              //Approved

              if($property->is_approved == '1'){

                             $property['approved'] = true;

                        }
                        else{
                             $property['approved'] = false;

                        }

                        //Number  of Desk

                        $property['number_of_desk'] =  $property->total_desk;

                        //Thumbnail 

                        $property['featured_image'] =  url('media/thumbnail/' . $property->thumbnail);

                        //gallery

                         $gallery = json_decode($property->gallary_image);

                          $data = [];

                           foreach ($gallery as $key => $value) {

                              $value = url('media/gallery/' . $value);

                              $data[] = $value;
                          }

                          $property['gallery_image'] = $data;


                        //liked & Rating

                          if(!empty($request->user_id)){
                                $wishlist = Wishlist::where('user_id','=',$request->user_id)
                                                    ->where('property_id','=',$v->id)
                                                    ->first();
                               
                                if(!empty($wishlist)){

                                    $property['is_liked'] = true;  
                                }
                                else{
                                    $property['is_liked'] = false;  
                                }

                            }
                          else{
                              $property['is_liked'] = false;
                              $property['rating'] = 0;  
                          }

                       //property extra details

                      $extra = OfficeExtraDetails::where('property_id','=',$property->id)->first();



                        if(!empty($extra)){

                         $property['about_sharer']        =  $extra->how_to_find_us;
                           
                          $property['describe_your_space']   =  $property->short_description;
                          $property['insurance']             =  $extra->insurance;
                          $property['covid_19_secure']       =  $extra->covid_19_secure;
                        }else{
                          $property['about_sharer']        =  null;
                          $property['describe_your_space']   =  Null;
                          $property['how_to_find_us']        =  Null;
                          $property['insurance']             =  Null;
                          $property['covid_19_secure']       =  Null;

                        }

                        //Amenities

                        $attr_id = [];
                        $attr_name = [];

                        $amenties =  DB::table('property_attributes_values')
                                        ->where('property_id','=',$property->id)
                                        ->where('attribute_id','=','2')
                                        ->get();

                        foreach ($amenties as $a_k => $a_v) {

                             $attr_id[] = $a_v->attribute_value_id;

                        }

                        foreach ($attr_id as $att_k => $att_v) {

                            $att_name = DB::table('attributevalues')
                                        ->where('id','=',$att_v)
                                        ->first();
                             //dd($att_name->value);

                           if(!empty($att_name)){
                             $attr_name[$att_k]['value'] = $att_name->value;
                             $attr_name[$att_k]['icon'] = url($att_name->icon);
                           }

                        }

                         $property['Amenities'] = $attr_name;

                 //landload details 

                       $landload = User::where('id','=',$property->user_id)->first();

                       $property['landload'] = $landload;


                       //spaces

                        // $propertySpace = DB::table('property_space_type')
                        //                 ->select('space_type_id')
                        //                 ->where('property_id','=',$property->id)->get();

                        $sapce = [];
                      

                         $space = Space::where('property_id','=',$property->id)
                                     ->where('is_approved', "publish")
                                     ->get();

                        foreach ($space as $sp => $spv) {

                        	//refund message

                        	$refund = Refund::where('id','=',$spv->booking_payment_refund)->first();

                        	$spv->booking_payment_refund = $refund->message;

                            $space_type = SpaceType::where('id','=',$spv->property_type_id)->first();

                            $spv->thumb = url('media/thumbnail/' . $spv->thumb);

                            $space[$sp]['space_type'] = $space_type['title'];
                            $space[$sp]['no_of_desk'] = $spv->total_desk;

                                //Booking approval

                                  if($spv->booking_approval == 0){

                                    $spv->booking_approval = false;

                                  }else{

                                    $spv->booking_approval = true;
                                  }



                         unset($spv->created_at);
                         unset($spv->updated_at);
                         unset($spv->featured_image);
                         unset($spv->gallary_image);
                         unset($spv->property_id);
                         unset($spv->user_id);


                        }

                        $property['space'] = $space;

                      unset($property->user_id);
                      unset($property->gallary_image);
                      unset($property->longitude);
                      unset($property->latitude);
                      unset($property->created_at);
                      unset($property->updated_at);
                      unset($property->is_appropropertyed);
                      unset($property->total_desk);
                      unset($property->thumbnail);

                return response()->json(['status' => true, 'message' => "success",'property' => $property], 200);

              }
              else{

                 return response()->json(['status' => false, 'message' => "Result not found",
                  'property' => null], 200);

              }
                           

    }

    public function officelocations(){

        $data = [];

        $regions  = DB::table('regions')->select('name','phonecode','length')->get();

      if(count($regions) > 0 ){

         

        return response()->json(['status' => true, 'message' => "success", 'region' => $regions], 200);
      }
      else{

        return response()->json(['status' => false, 'message' => "unsuccess", 'region' => Null], 200);

      }


    }


    public function registeroffice(Request $request)
    {
        // dd($request->old_gallery_image);
        

        $validator = Validator::make($request->all(), [
            'property_title'            => 'required',
            'address_line_one'          => 'required',
            'postcode'                  => 'required',
            'lang'                      => 'required',
            'long'                      => 'required',
            'city'                      => 'required',
            'state'                     => 'required',
            'country'                   => 'required',
            //'number_of_desk'            => 'required',
            'disability_access'         => 'required',
            'describe_your_space'       => 'required',
            'how_to_find_us'            => 'required',
            'facilities_values'         => 'required',
            'insurance'                 => 'required',
            'covid_19_secure'           => 'required',
            'featured_image'            => 'required',
            'gallery_image'             => 'required',
            'user_id'               	=> 'required',
          
        ]);

        $booking_approval  = $request->booking_approval;

        if ($validator->fails()) {
            return response()->json(['status' => false, 'message' => implode("", $validator->errors()->all())], 200);
        }

        //get user role 

        $user = User::where('id','=',$request->user_id)->first();

        if(empty($user)){

        	return response()->json(['status' => false, 'message' => "user not found", 'data' => []], 200);

        }

        $userRole = $user->roles;

        $roleName = $userRole[0]->title;
        

        if($roleName == 'Landlord') {
            // 
            $facilities_attr_val_id = explode(",",$request->facilities_values);

            $attr_count = count($facilities_attr_val_id);

            $property = Property::updateOrCreate(['id' => $request->property_id], [
                'property_title'            => $request->property_title,
                'user_id'                   => $request->user_id,
                'booking_approval'          => !empty($booking_approval) && ($booking_approval == "true")? 1 : 0,
                'short_description'         => $request->describe_your_space,
                //'total_desk'                => $request->number_of_desk,
                //'thumbnail'                 => $name,
                //'gallary_image'             => json_encode($gallery_image_name)
            ]);

            $property_id = $property->id;

            // if ($files    =    $request->file('featured_image')) {
            //     $name    =    uniqid() . $files->getClientOriginalName();
            //     $files->move('media/thumbnail', $name);
            //     $property=Property::where('id','=',$property_id)->update([
            //         'thumbnail' => $name,
            //     ]);
            // }

            $image_data = $request->featured_image;

            if(gettype($image_data) == 'string'){

                $imgname = explode("media/thumbnail/",$image_data);
                  $property=Property::where('id','=',$property_id)->update([
                     'thumbnail'             => $imgname[1]
                  ]);
                
                }
            elseif(gettype($image_data) == 'object'){

               if ($files    =    $request->file('featured_image')) {
                  $name    =    uniqid() . $files->getClientOriginalName();
                  $files->move('media/thumbnail', $name);
                  $property=Property::where('id','=',$property_id)->update([
                      'thumbnail' => $name,
                  ]);
                }

            }

           

            $update_images = [];
          
            if(isset($request->gallery_image)){
                $gallery_image = json_decode($request->gallery_image,true);
                $image_updated_location = explode(',', $request->image_updated_location);

              foreach ($gallery_image as $g_key => $g_value) {

                    if(isset($image_updated_location[0]) && $image_updated_location[0] != null) {

                           if(in_array($g_key, $image_updated_location)) {

                            $update_images[$g_key] = $this->createImage($g_value,$g_key);

                           } 
                           else{

                            $exp_g_value = explode('media/gallery/', $g_value);

                            $update_images[$g_key] = $exp_g_value[1];

                           }
                    }

                    else{

                        $exp_g_value = explode('media/gallery/', $g_value);

                            $update_images[$g_key] = $exp_g_value[1];


                    }

               }


            }

          
            
          $property = Property::where('id','=',$property_id)->update([
              'gallary_image' => json_encode($update_images)
          ]);

        } else {

            //get user landload

            $user = User::where('id','=',$request->user_id)->first();
            $landloadId = $user->parent_id;
            $facilities_attr_val_id = explode(",",$request->facilities_values);
            $attr_count = count($facilities_attr_val_id);

            $image_data = $request->featured_image;

            if(gettype($image_data) == 'string'){

                $imgname = explode("media/thumbnail/",$image_data);
                  $property=Property::where('id','=',$property_id)->update([
                     'thumbnail'             => $imgname[1]
                  ]);
                
                }
            elseif(gettype($image_data) == 'object'){

               if ($files    =    $request->file('featured_image')) {
                  $name    =    uniqid() . $files->getClientOriginalName();
                  $files->move('media/thumbnail', $name);
                  $property=Property::where('id','=',$property_id)->update([
                      'thumbnail' => $name,
                  ]);
                }

            }

            $update_images = [];
          
            if(isset($request->gallery_image)){

                $gallery_image = json_decode($request->gallery_image,true);
                $image_updated_location = explode(',', $request->image_updated_location);

                // if(!isset($image_updated_location) || empty($image_updated_location) || $image_updated_location == null){

                //    foreach ($gallery_image as $g_key => $g_value) {

                //       $update_images[] = $this->createImage($g_value);

                //    }

                // }

               // if(!empty($image_updated_location) || $image_updated_location != null) {

                  foreach ($gallery_image as $g_key => $g_value) {

                       if(in_array($g_key, $image_updated_location)) {

                        $update_images[$g_key] = $this->createImage($g_value,$g_key);

                       } 
                       else{

                        $exp_g_value = explode('media/gallery/', $g_value);

                        $update_images[$g_key] = $exp_g_value[1];

                       }

                   }
               // }

              //  $delete_image_location = explode(',', $request->delete_image_location);

              //  if(!empty($delete_image_location) || $delete_image_location != null){

              //     foreach ($delete_image_location as  $d_value) {

              //       unset($update_images[$d_value]);
              //     }

              //  }


            }
            
            $property = Property::where('id','=',$property_id)->update([
                'gallary_image' => json_encode($update_images)
            ]);

            $property = Property::updateOrCreate(['id' => $request->property_id], [
                'property_title'    => $request->property_title,
                'user_id'           => $landloadId,
                'booking_approval'  => !empty($booking_approval) && ($booking_approval == "true")? 1 : 0,
                'short_description' => $request->describe_your_space,
                // 'total_desk'     => $request->number_of_desk,
                'thumbnail'         => $name,
                'gallary_image'     => json_encode($gallery_image_name)
            ]);

        }

        $location = PropertyLocation::updateOrCreate(['property_id' => $request->property_id], [
          'property_id'           => $property_id,
          'country'               => $request->country,
          'state'                 => $request->state,
          'city'                  => $request->city,
          'postcode'              => $request->postcode,
          'address'               => $request->address_line_one,
          'address_2'             => $request->address_line_two,
          'longitude'             => $request->long,
          'latitude'              => $request->lang
        ]);

        if(!empty($request->property_id)){

            PropertyAttributeValue::where('property_id','=',$request->property_id)->delete();

            for ($i=0; $i <= $attr_count; $i++) { 

                if(!empty($facilities_attr_val_id[$i])){
                    $attr_val =  PropertyAttributeValue::create([
                        'property_id'           => $property_id,
                        'attribute_id'          => '2',
                        'attribute_value_id'    => $facilities_attr_val_id[$i]
                    ]);
                }
            }

        }

        else {

            for ($i=0; $i <= $attr_count; $i++) { 

                if(!empty($facilities_attr_val_id[$i])){
                    $attr_val =  PropertyAttributeValue::create([
                        'property_id'           => $property_id,
                        'attribute_id'          => '2',
                        'attribute_value_id'    => $facilities_attr_val_id[$i]
                    ]);
                }
            }
        }

        $sharerDetails = OfficeExtraDetails::updateOrCreate(['property_id' => $request->property_id], [
            'property_id'         => $property_id,
            'disability_access' => !empty($request->disability_access) && ($request->disability_access == 'true') ? 1 : 0,
            'how_to_find_us'    => $request->how_to_find_us,
            'insurance'         => $request->insurance,
            'covid_19_secure'      => $request->covid_19_secure,
            'key_feature'           => $request->key_features,
            'things_not_included'  => $request->things_not_included,
            'available_desks'      => $request->available_desks,
        ]);

        // $property_id = $property->id;
        $property_data = $this->getPropertyData($property_id);
        // return $property_data;  

        return response()->json(['status' => true, 'message' => "success", 'data' => $property_data], 200);

    }

    public function registerspace(Request $request){



       $day_arr = [

                'Mon' => $request->mon,
                'Tue' => $request->tue,
                'Wed' => $request->wed,
                'Thu' => $request->thu,
                'Fri' => $request->fri,
                'Sat' => $request->sat,
                'Sun' => $request->sun,
        ];
         


      $availability_details = $request->availability_details;
      $availability_details = json_decode($availability_details, true);

    // dd($request->availability_type);


      // [{"type":"single","date": { "startDate":"12-10-21"},"desks":"10"},{"type":"range","date": { "startDate":"12-10-21", "endDate":"15-10-21"},"desk":"12"}]
      

      $validator = Validator::make($request->all(), [
        'featured_image'            => 'required',
        'space_title'          => 'required',
        'space_type_id'                  => 'required',
        'available_desk'                      => 'required',
        'price'                      => 'required',
        'min_term'                      => 'required',
        'max_term'                     => 'required',
        'key_feature'                   => 'required',
        'user_id'                       => 'required',
        'property_id'               => 'required',
      ]);

      $booking_approval  = $request->booking_approval;

      $image_data = $request->featured_image;

      if ($validator->fails()) {
        return response()->json(['status' => false, 'message' => implode("", $validator->errors()->all())], 200);
      }


      $space = Space::updateOrCreate(['id' => $request->space_id], [
        'property_id'                => $request->property_id,
        'user_id'                   => $request->user_id,
        'property_type_id'         => $request->space_type_id,
        'space_title'               => $request->space_title,
        'key_feature'               => $request->key_feature,
        'cost'                      => $request->price,
        'cost_type'                  => $request->cost_type,
        //'thumb'                      => $name_3,
        'availability_type'                      => $request->availability_type,
         'booking_payment_refund' => !empty($request->booking_payment_refund) ? $request->booking_payment_refund : 0,
        'total_desk'             => $request->available_desk,
        'booking_approval'          => !empty($booking_approval) && ($booking_approval == "true")? 1 : 0,
      ]);

      $space_id = $space->id;

      if(gettype($image_data) == 'string'){

        $imgname = explode("media/thumbnail/",$image_data);

         Space::where('id','=',$space->id)->update([
                                    'thumb'             => $imgname[1]
                                    ]);
      }
      elseif(gettype($image_data) == 'object'){

          if ($files1    =    $request->file('featured_image')) {
            $name_3   =    uniqid() . $files1->getClientOriginalName();
            $files1->move('media/thumbnail', $name_3);
          }

       Space::where('id','=',$space->id)->update([
                                    'thumb'             => $name_3
                                    ]);


      }

      SpaceExtraDetails::updateOrCreate(['space_id' => $request->space_id], [
        'space_id'                   => $space_id,
        'min_term'                      => $request->min_term,
        'max_term'                      => $request->max_term,
        'things_not_included'           => $request->things_not_included,
      ]);

      if(!empty($request->space_id)){

          $SpaceDayPrice = SpaceDayPrice::where('space_id','=',$request->space_id)->delete();

      }

      foreach ($day_arr as $key => $value) {


          SpaceDayPrice::insert( [
            'space_id'                   =>$space->id,
            'day'                          => $key,
            'price'                          => $value,
          ]);
      }

          //single space space range

              $max1 = max($day_arr);

              $min1 = min($day_arr);


             Space::where('id','=',$space->id)->update([

                                    'price_from'           => $min1,
                                    'price_to'              => $max1,
                              ]);

            //all space price range

            $costspace = SpaceDayPrice::select('price')->where('space_id','=',$space->id)->get();

            $price = [];

            foreach ($costspace as $key => $value) {

                $price[] = $value->price;
            }

            $max = max($price);

            $min = min($price);

            $space_av = Space::where('property_id','=',$request->property_id)->get();

            $availability_type = [];

             foreach ($space_av as $key => $value) {

                $availability_type[] = $value->availability_type;
            }
            $max1 = "";
            // $val ="";
            $stats = true;
            $final = $availability_type[0];
            foreach($availability_type as $key => $val){
                if($stats) {
                    if($key == 0){
                        $max1= $val;
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


            $property = Property::where('id','=',$request->property_id)
                        ->update([
                                    'price_from'           => $min,
                                    'price_to'           => $max,
                                   'availability_type' => $final,
            ]);

      if(!empty($availability_details) ||  $availability_details != null)
      {
          if(empty($request->space_id) || $request->space_id == null){

                foreach ($availability_details as $key => $value) {

                    if(!empty($value)){

                        if($value['type'] == 'range'){

                            AvailabilityDesk::create([

                                              'landload_id'     => $request->user_id,
                                              'space_id'        => $space_id,
                                              'available_desk'  =>$value['desk'],
                                               'to_date'         => $value['date']['endDate'],
                                              'from_date'       => $value['date']['startDate'],
                                              'type'             => 'range'

                                                  ]);
                        }
                        if($value['type'] == 'date'){

                              AvailabilityDesk::create([

                                              'landload_id'     => $request->user_id,
                                              'space_id'        => $space_id,
                                              'available_desk'  => $value['desk'],
                                               'from_date'       => $value['date']['startDate'],
                                              'to_date'         => $value['date']['startDate'],
                                              'type'             => 'date',


                                                      ]);

                      }
                    }
                    

                }
          }
          else{

            AvailabilityDesk::where('space_id','=',$request->space_id)->delete();

             foreach ($availability_details as $key => $value) {

               if(!empty($value)){

                    if($value['type'] == 'range'){

                        AvailabilityDesk::create([

                                          'landload_id'     => $request->user_id,
                                          'space_id'        => $space_id,
                                          'available_desk'  =>$value['desk'],
                                          'to_date'         => $value['date']['endDate'],
                                          'from_date'       => $value['date']['startDate'],
                                          'type'             => 'range',

                                              ]);
                    }
                    if($value['type'] == 'date'){

                          AvailabilityDesk::create([

                                          'landload_id'     => $request->user_id,
                                          'space_id'        => $space_id,
                                          'available_desk'  => $value['desk'],
                                           'from_date'       => $value['date']['startDate'],
                                          'to_date'         => $value['date']['startDate'],
                                          'type'             => 'date',


                                                  ]);


                    }
               }
              
             }


          }

      }
      else{
        AvailabilityDesk::where('space_id','=',$request->space_id)->delete();
      }

      return response()->json(['status' => true, 'message' => "success"], 200);

    }


    public function getProperty(Request $request){

        $validator = Validator::make($request->all(), [
            'property_id'            => 'required',
      
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => false, 'message' => implode("", $validator->errors()->all())], 200);
        }

        $property = Property::with('user')->where('id','=',$request->property_id)->get();

        // $property = $this->getPropertyData($request->property_id);

        if(count($property) > 0){

            foreach ($property as $key => $value) {

                if($value->booking_approval == 0){

                   $value->booking_approval = false;

                }
                else{
                  $value->booking_approval = true;
                }

                $gallery = json_decode($value->gallary_image);

                if(!empty($gallery)){
                    foreach ($gallery as $key1 => $value1) {

                            $value1 = url('media/gallery/' . $value1);

                            $data[] = $value1;
                        }

                          $property[$key]['gallery_image'] = $data;
                }else{
                    $property[$key]['gallery_image'] = [];
                }

                    $extra = OfficeExtraDetails::where('property_id','=',$request->property_id)->first();

                       $property[$key]['disability_access']   =  !empty($extra->disability_access) && ($extra->disability_access == "1") ? true : false;  
                       $property[$key]['describe_your_space']   =  !empty($value->short_description) ? $value->short_description : null;
                       $property[$key]['about_sharer']        =  !empty($extra->how_to_find_us) ? $extra->how_to_find_us : null;
                       $property[$key]['insurance']             =  !empty($extra->insurance) ? $extra->insurance : null;
                       $property[$key]['covid_19_secure']       =  !empty($extra->covid_19_secure) ? $extra->covid_19_secure : null;

            $location = PropertyLocation::where('property_id','=',$value->id)->first();

              if(!empty($location)){

                 $property[$key]['address_line_one']   =  $location->address;
                 $property[$key]['address_line_two']   =  $location->address_2;
                 $property[$key]['postcode']           =  $location->postcode;
                 $property[$key]['city']               =  $location->city;
                 $property[$key]['state']              =  $location->state;
                 $property[$key]['country']            =  $location->country;
                 $property[$key]['lang']               =  $location->latitude;
                 $property[$key]['long']               =  $location->longitude;


              }

               $property[$key]['number_of_desk']       =  $value->total_desk;
               $property[$key]['describe_your_space']  =  $value->short_description;
               $property[$key]['featured_image']       = url('media/thumbnail/' . $value->thumbnail);
               $property[$key]['rating'] = 0; 

                //liked & Rating

                        if(!empty($request->user_id)){
                            $wishlist = Wishlist::where('user_id','=',$request->user_id)
                                                ->where('property_id','=',$value->id)
                                                ->first();
                           
                            if(!empty($wishlist)){

                                $property[$key]['is_liked'] = true;  
                            }
                            else{
                                $property[$key]['is_liked'] = false;  
                            }

                        }
                        else{
                            $property[$key]['is_liked'] = false;
                            
                        }

                  //Approved

                    if($value->is_approved == 1){

                         $property[$key]['approved'] = true;

                    }
                    else{
                         $property[$key]['approved'] = false;

                    }


               //get amenties
                    $attr_id = [];
                    $attr_name = [];

                    $amenties =  DB::table('property_attributes_values')
                                    ->where('property_id','=',$value->id)
                                    ->where('attribute_id','=','2')
                                    ->get();

                    foreach ($amenties as $a_k => $a_v) {

                         $attr_id[] = $a_v->attribute_value_id;

                    }

                    foreach ($attr_id as $att_k => $att_v) {

                        $att_name = DB::table('attributevalues')
                                    ->where('id','=',$att_v)
                                    ->first();
                         //dd($att_name->value);

                       if(!empty($att_name)){
                         $attr_name[$att_k]['value'] = $att_name->value;
                         $attr_name[$att_k]['icon'] = url($att_name->icon);
                       }

                    }

                     $property[$key]['Amenities'] = $attr_name;


                     //get property type

                     $property_space_type  =  DB::table('property_spaces')
                                    ->where('property_id','=',$value->id)
                                    ->select('property_type_id')
                                    ->distinct()
                                    ->get();

                    if(!empty($property_space_type)){

                         $get_type_title = [];

                            foreach ($property_space_type as $key_type => $value_type) {

                           $propertySpace = DB::table('property_type')
                                                ->select('title')
                                                ->where('id','=',$value_type->property_type_id)->first();

                            $get_type_title[] = $propertySpace->title;
                            }

                         $property[$key]['property_type'] = $get_type_title;

                    }

                    //get space

                    // $propertySpace = DB::table('property_space_type')
                    //                 ->select('space_type_id')
                    //                 ->where('property_id','=',$value->id)->get();

                    $sapce = [];
                  

                    $space = Space::where('property_id','=',$value->id)->get();

                    foreach ($space as $sp => $spv) {

                    	//refund message

                    	$refund = Refund::where('id','=',$spv->booking_payment_refund)->first();

                    	$spv->booking_payment_refund = $refund->message;

                        $space_type = SpaceType::where('id','=',$spv->property_type_id)->first();

                        $space_extra = SpaceExtraDetails::where('space_id','=',$spv->id)->first();

                        $spv->thumb = url('media/thumbnail/' . $spv->thumb);

                        $space[$sp]['space_type'] = $space_type['title'];
                        $space[$sp]['no_of_desk'] = $spv->total_desk;
                        $space[$sp]['min_term'] = !empty($space_extra->min_term) ? $space_extra->min_term : "0";
                        $space[$sp]['max_term'] = !empty($space_extra->max_term) ? $space_extra->max_term :$spv->total_desk;
                        $space[$sp]['things_not_included'] = !empty($space_extra->things_not_included) ? $space_extra->things_not_included :null;

                        //Booking approval

                        if($spv->booking_approval == 0){

                          $spv->booking_approval = false;

                        }else{

                          $spv->booking_approval = true;
                        }

                    //availability

                    $availability = AvailabilityDesk::select('space_id','available_desk','to_date','from_date','type')->where('space_id','=',$spv->id)->get();

                  

                    if(count($availability) > 0){

                        $space[$sp]['availability']                       = $availability;

                    }
                    else{

                      $space[$sp]['availability']                       = [];

                    }

                    //space days price range
                    $spaceDays = SpaceDayPrice::select('day','price')->where('space_id','=',$spv->id)->get();
                    $space[$sp]['space_days_price_list'] = $spaceDays;

                    unset($spv->created_at);
                    unset($spv->updated_at);
                    unset($spv->featured_image);
                    unset($spv->gallary_image);
                    unset($spv->property_id);
                    unset($spv->user_id);

                }

                $property[$key]['space'] = $space;

                unset($value->gallary_image);
                unset($value->is_approved);
                unset($value->user_id);
                unset($value->short_description);
                unset($value->description);
                unset($value->thumbnail);
                unset($value->total_desk);
                unset($value->avg_rating);
                unset($value->created_at);
                unset($value->updated_at);

            }

            return response()->json(['status' => true, 'message' => "success",'data'=>$property], 200);

        } else{

            return response()->json(['status' => false, 'message' => "property not found",'data'=>[]], 200);

         }


    }

    public function getPropertyData($property_id)
    {
        // code...

        $property = Property::with('user')->where('id','=',$property_id)->get();

        if(count($property) > 0){

            foreach ($property as $key => $value) {

                if($value->booking_approval == 0){

                   $value->booking_approval = false;

                }
                else{
                  $value->booking_approval = true;
                }

                $gallery = json_decode($value->gallary_image);
                $data = [];

                  foreach ($gallery as $key1 => $value1) {

                            $value1 = url('media/gallery/' . $value1);

                            $data[] = $value1;
                        }

                          $property[$key]['gallery_image'] = $data;

                    $extra = OfficeExtraDetails::where('property_id','=',$property_id)->first();

                       $property[$key]['disability_access']   =  !empty($extra->disability_access) && ($extra->disability_access == "1") ? true : false;  
                       $property[$key]['describe_your_space']   =  !empty($value->short_description) ? $value->short_description : null;
                       $property[$key]['about_sharer']        =  !empty($extra->how_to_find_us) ? $extra->how_to_find_us : null;
                       $property[$key]['insurance']             =  !empty($extra->insurance) ? $extra->insurance : null;
                       $property[$key]['covid_19_secure']       =  !empty($extra->covid_19_secure) ? $extra->covid_19_secure : null;

            $location = PropertyLocation::where('property_id','=',$value->id)->first();

              if(!empty($location)){

                 $property[$key]['address_line_one']   =  $location->address;
                 $property[$key]['address_line_two']   =  $location->address_2;
                 $property[$key]['postcode']           =  $location->postcode;
                 $property[$key]['city']               =  $location->city;
                 $property[$key]['state']              =  $location->state;
                 $property[$key]['country']            =  $location->country;
                 $property[$key]['lang']               =  $location->latitude;
                 $property[$key]['long']               =  $location->longitude;


              }

               $property[$key]['number_of_desk']       =  $value->total_desk;
               $property[$key]['describe_your_space']  =  $value->short_description;
               $property[$key]['featured_image']       = url('media/thumbnail/' . $value->thumbnail);
               $property[$key]['rating'] = 0; 

                //liked & Rating

                        if(!empty($request->user_id)){
                            $wishlist = Wishlist::where('user_id','=',$request->user_id)
                                                ->where('property_id','=',$value->id)
                                                ->first();
                           
                            if(!empty($wishlist)){

                                $property[$key]['is_liked'] = true;  
                            }
                            else{
                                $property[$key]['is_liked'] = false;  
                            }

                        }
                        else{
                            $property[$key]['is_liked'] = false;
                            
                        }

                  //Approved

                    if($value->is_approved == 1){

                         $property[$key]['approved'] = true;

                    }
                    else{
                         $property[$key]['approved'] = false;

                    }


               //get amenties
                    $attr_id = [];
                    $attr_name = [];

                    $amenties =  DB::table('property_attributes_values')
                                    ->where('property_id','=',$value->id)
                                    ->where('attribute_id','=','2')
                                    ->get();

                    foreach ($amenties as $a_k => $a_v) {

                         $attr_id[] = $a_v->attribute_value_id;

                    }

                    foreach ($attr_id as $att_k => $att_v) {

                        $att_name = DB::table('attributevalues')
                                    ->where('id','=',$att_v)
                                    ->first();
                         //dd($att_name->value);

                       if(!empty($att_name)){
                         $attr_name[$att_k]['value'] = $att_name->value;
                         $attr_name[$att_k]['icon'] = url($att_name->icon);
                       }

                    }

                     $property[$key]['Amenities'] = $attr_name;


                     //get property type

                     $property_space_type  =  DB::table('property_spaces')
                                    ->where('property_id','=',$value->id)
                                    ->select('property_type_id')
                                    ->distinct()
                                    ->get();

                    if(!empty($property_space_type)){

                         $get_type_title = [];

                            foreach ($property_space_type as $key_type => $value_type) {

                           $propertySpace = DB::table('property_type')
                                                ->select('title')
                                                ->where('id','=',$value_type->property_type_id)->first();

                            $get_type_title[] = $propertySpace->title;
                            }

                         $property[$key]['property_type'] = $get_type_title;

                    }

                    //get space

                    // $propertySpace = DB::table('property_space_type')
                    //                 ->select('space_type_id')
                    //                 ->where('property_id','=',$value->id)->get();

                    $sapce = [];
                  

                     $space = Space::where('property_id','=',$value->id)->get();

                    foreach ($space as $sp => $spv) {

                        //refund message

                        $refund = Refund::where('id','=',$spv->booking_payment_refund)->first();

                        $spv->booking_payment_refund = $refund->message;

                        $space_type = SpaceType::where('id','=',$spv->property_type_id)->first();

                        $space_extra = SpaceExtraDetails::where('space_id','=',$spv->id)->first();

                        $spv->thumb = url('media/thumbnail/' . $spv->thumb);

                        $space[$sp]['space_type'] = $space_type['title'];
                        $space[$sp]['no_of_desk'] = $spv->total_desk;
                        $space[$sp]['min_term'] = !empty($space_extra->min_term) ? $space_extra->min_term : "0";
                        $space[$sp]['max_term'] = !empty($space_extra->max_term) ? $space_extra->max_term :$spv->total_desk;
                        $space[$sp]['things_not_included'] = !empty($space_extra->things_not_included) ? $space_extra->things_not_included :null;

                        //Booking approval

                        if($spv->booking_approval == 0){

                          $spv->booking_approval = false;

                        }else{

                          $spv->booking_approval = true;
                        }

                    //availability

                        $availability = AvailabilityDesk::select('space_id','available_desk','to_date','from_date')->where('space_id','=',$spv->id)->get();
                       

                        if(count($availability) > 0){

                            $space[$sp]['availability']                       = $availability;

                        }else{

                          $space[$sp]['availability']                       = [];
                        }



                      //space days price range

                       $spaceDays = SpaceDayPrice::select('day','price')->where('space_id','=',$spv->id)->get();

                       $space[$sp]['space_days_price_list'] = $spaceDays;

                       unset($spv->created_at);
                       unset($spv->updated_at);
                       unset($spv->featured_image);
                       unset($spv->gallary_image);
                       unset($spv->property_id);
                       unset($spv->user_id);


                    }

                    $property[$key]['space'] = $space;



              unset($value->gallary_image);
              unset($value->is_approved);
              unset($value->user_id);
              unset($value->short_description);
              unset($value->description);
              unset($value->thumbnail);
              unset($value->total_desk);
              unset($value->avg_rating);
              unset($value->created_at);
              unset($value->updated_at);

            }

            return $property; //response()->json(['status' => true, 'message' => "success",'data'=>$property], 200);
        }
    }

     public function getuserallProperty(Request $request){

        $validator = Validator::make($request->all(), [
            'user_id'            => 'required',
        ]);

            if ($validator->fails()) {
                return response()->json(['status' => false, 'message' => implode("", $validator->errors()->all())], 200);
            }

          //get user role 

          $user = User::where('id','=',$request->user_id)->first();

          if(empty($user)){

             return response()->json(['status' => false, 'message' => 'user does not exist'], 200);

          }

          $userRole = $user->roles;

          $allrole = [];

          foreach ($userRole as $role => $rols) {
            $allrole[] = $rols->title;
          }

         if(in_array('Landlord', $allrole)){

           $property = Property::with('user')->where('user_id','=',$request->user_id)->get();


         }

         else{

            $landloadId = $user->parent_id;

            $property = Property::with('user')->where('user_id','=',$landloadId)->get();

         }

       //  dd($property);


          if(count($property)>0){

                foreach ($property as $key => $value) {

                   if($value->booking_approval == 0){

                      $value->booking_approval = false;

                    }
                    else{
                      $value->booking_approval = true;
                    }

                    if($value->thumbnail){

                       $value->thumbnail = url('media/thumbnail/' . $value->thumbnail);

                    }

                   

                    $gallery = json_decode($value->gallary_image);

                         $data = [];
                    if(!empty($gallery)){

                      foreach ($gallery as $key1 => $value1) {

                                $value1 = url('media/gallery/' . $value1);

                                $data[] = $value1;
                            }
                    }

                              $property[$key]['gallery_image'] = $data;

                        $extra = OfficeExtraDetails::where('property_id','=',$value->id)->first();

                      if(!empty($extra)){
                            if($extra->disability_access == "1"){

                                $property[$key]['disability_access']   =  true;   

                            }
                           else{

                                $property[$key]['disability_access']   =  false;   

                            }

                           $property[$key]['describe_your_space']   =  $value->short_description;
                           $property[$key]['about_sharer']        =  $extra->how_to_find_us;
                           $property[$key]['insurance']             =  $extra->insurance;
                           $property[$key]['covid_19_secure']       =  $extra->covid_19_secure;
                      }
                      else{
                            $property[$key]['disability_access']   =  false;   
                            $property[$key]['describe_your_space']   =  Null;
                           $property[$key]['about_sharer']        =  Null;
                           $property[$key]['insurance']             =  Null;
                           $property[$key]['covid_19_secure']       =  Null;
                      }

                       $location = PropertyLocation::where('property_id','=',$value->id)->first();

                       if(!empty($location)){

                     $property[$key]['address_line_one']   =  $location->address;
                     $property[$key]['address_line_two']   =  $location->address_2;
                     $property[$key]['postcode']           =  $location->postcode;
                     $property[$key]['city']               =  $location->city;
                     $property[$key]['state']              =  $location->state;
                     $property[$key]['country']            =  $location->country;
                     $property[$key]['lang']               =  $location->latitude;
                     $property[$key]['long']               =  $location->longitude;


                  }

                   $property[$key]['number_of_desk']       =  $value->total_desk;
                   $property[$key]['describe_your_space']  =  $value->short_description;
                   $property[$key]['featured_image']       = url('media/thumbnail/' . $value->thumbnail);
                   $property[$key]['rating'] = 0; 

                    //liked & Rating

                    if(!empty($request->user_id)){
                            $wishlist = Wishlist::where('user_id','=',$request->user_id)
                                                  ->where('property_id','=',$value->id)
                                                   ->first();

                          if(!empty($wishlist)){

                            $property[$key]['is_liked'] = true;  
                          }
                          else{
                            $property[$key]['is_liked'] = false;  
                          }

                         $property[$key]['rating'] =  $value->avg_rating;

                    }
                  else{
                        $property[$key]['is_liked'] = false;
                        $property[$key]['rating'] = 0;  
                      }

                  unset($value->gallary_image);
                  unset($value->user_id);
                  unset($value->short_description);
                  unset($value->description);
                  unset($value->thumbnail);
                  unset($value->total_desk);
                  unset($value->avg_rating);
                  unset($value->created_at);
                  unset($value->updated_at);


                   //get space

                    // $propertySpace = DB::table('property_space_type')
                    //                 ->select('space_type_id')
                    //                 ->where('property_id','=',$value->id)->get();

                    $sapce = [];
                  

                     $space = Space::where('property_id','=',$value->id)->get();

                    foreach ($space as $sp => $spv) {

                		//refund message

                    	$refund = Refund::where('id','=',$spv->booking_payment_refund)->first();

                    	$spv->booking_payment_refund = !empty($refund->message) ? $refund->message : '';

                        $space_type = SpaceType::where('id','=',$spv->property_type_id)->first();

                        $space_extra = SpaceExtraDetails::where('space_id','=',$spv->id)->first();

                        $spv->thumb = url('media/thumbnail/' . $spv->thumb);

                        $space[$sp]['space_type'] = $space_type['title'];
                        $space[$sp]['no_of_desk'] = $spv->total_desk;
                        $space[$sp]['min_term'] = !empty($space_extra->min_term) ? $space_extra->min_term : "0";
                        $space[$sp]['max_term'] = !empty($space_extra->max_term) ? $space_extra->max_term :$spv->total_desk;
                        $space[$sp]['things_not_included'] = !empty($space_extra->things_not_included) ? $space_extra->things_not_included :null;

                          //Booking approval

                          if($spv->booking_approval == 0){

                            $spv->booking_approval = false;

                          }else{

                            $spv->booking_approval = true;
                          }

                        //availability

                        $availability = AvailabilityDesk::select('space_id','available_desk','to_date','from_date')->where('space_id','=',$spv->id)->get();
                       

                        if(count($availability) > 0){

                            $space[$sp]['availability']                       = $availability;

                        }
                        else{

                          $space[$sp]['availability']                       = [];
                        }



                       unset($spv->created_at);
                       unset($spv->updated_at);
                       unset($spv->featured_image);
                       unset($spv->gallary_image);
                       unset($spv->property_id);
                       unset($spv->user_id);
                     }

                     
                        $property[$key]['space'] = $space;

                   
                }

                return response()->json(['status' => true, 'message' => "success",'data'=>$property], 200);


             }
            else{

            return response()->json(['status' => false, 'message' => "unsuccess",'data'=>[]], 200);

             }


     }

      public function getpropertallspace(Request $request){

         $validator = Validator::make($request->all(), [
            'property_id'            => 'required'
            ]);

            if ($validator->fails()) {
                return response()->json(['status' => false, 'message' => implode("", $validator->errors()->all())], 200);
            }

        $space = Space::where('property_id','=',$request->property_id)->get();

        if(count($space) > 0){

            foreach ($space as $key => $value) {

                $availability = AvailabilityDesk::select('space_id','available_desk','to_date','from_date')->where('space_id','=',$value->id)->get();

				      //refund message

            	$refund = Refund::where('id','=',$value->booking_payment_refund)->first();

            	$value->booking_payment_refund = $refund->message;

                 $type = SpaceType::where('id','=',$value->property_type_id)->first();

                 $space[$key]['space_type']               = $type->title;

                $extra = SpaceExtraDetails::where('space_id','=',$value->id)->first();

               // $value->thumb = url('media/thumbnail/' . $value->thumb);

                //space days price range

                 $spaceDays = SpaceDayPrice::select('day','price')->where('space_id','=',$value->id)->get();

                 //availability desk

                $availability = AvailabilityDesk::select('space_id','available_desk','to_date','from_date','type')->where('space_id','=',$value->id)->get();

                    unset($value->featured_image);
                    unset($value->gallary_image);
                    unset($value->user_id);

                if(!empty($extra)){
                    $space[$key]['min_term']              = $extra->min_term;
                    $space[$key]['max_term']              = $extra->max_term;
                    $space[$key]['things_not_included']   = $extra->things_not_included;

                }
                else{
                    $space[$key]['min_term']              = "0";
                    $space[$key]['max_term']              = $value->tatol_desk;
                    $space[$key]['things_not_included']   = Null;

                }

                 $space[$key]['featured_image']              = url('media/thumbnail/' . $value->thumb);
                 $space[$key]['available_desk']              = $value->total_desk;
                 $space[$key]['price']                       = $value->cost;

                 if(count($availability) > 0){

                    $space[$key]['availability']                       = $availability;

                  }
                  else{

                    $space[$key]['availability']                       = [];
                  }


                 if(count($spaceDays) > 0){

                    $space[$key]['space_days_price_list'] = $spaceDays;

                 }

                 
                //Booking approval

                if($value->booking_approval == 0){

                  $value->booking_approval = false;

                }else{

                  $value->booking_approval = true;
                }

                
                
                 
                  unset($value->gallary_image);
                  unset($value->total_desk);
                  unset($value->thumb);
                  unset($value->cost);
                  unset($value->created_at);
                  unset($value->updated_at);
                  unset($value->user_id);
                  unset($value->property_type_id);
            }


        return response()->json(['status' => true, 'message' => "success",'data'=>$space], 200);

        }
        else{

            return response()->json(['status' => false, 'message' => "unsuccess",'data'=>[]], 200);

        }

      }

     public function getspace(Request $request){

        $validator = Validator::make($request->all(), [
            'space_id'            => 'required',
          
            ]);

            if ($validator->fails()) {
                return response()->json(['status' => false, 'message' => implode("", $validator->errors()->all())], 200);
            }

          $space = Space::where('id','=',$request->space_id)->first();

          $availability = AvailabilityDesk::select('space_id','available_desk','to_date','from_date','type')->where('space_id','=',$request->space_id)->get();



          if(!empty($space)){

          		//refund message

            	$refund = Refund::where('id','=',$space->booking_payment_refund)->first();

            	$space->booking_payment_refund = !empty($refund->message) ? $refund->message : '';

               $type = SpaceType::where('id','=',$space->property_type_id)->first();

              $space['space_type']               = $type->title;
          }

         // $space->thumb = url('media/thumbnail/' . $space->thumb);

          if(!empty($space)){

             $extra = SpaceExtraDetails::where('space_id','=',$space->id)->first();

                if(!empty($extra)){
                    $space['min_term']              = $extra->min_term;
                    $space['max_term']              = $extra->max_term;
                    $space['things_not_included']   = $extra->things_not_included;

                }
                else{
                    $space['min_term']              = "0";
                    $space['max_term']              = $space->total_desk;
                    $space['things_not_included']   = Null;

                }
                 $space['featured_image']              = url('media/thumbnail/' . $space->thumb);
                 $space['available_desk']              = $space->total_desk;
                 $space['price']                       = $space->cost;

                    //Booking approval

                  if($space->booking_approval == 0){

                    $space->booking_approval = false;

                  }else{

                    $space->booking_approval = true;
                  }

                  //availability

                  if(count($availability) > 0){

                    $space['availability']                       = $availability;

                  }
                  else{

                    $space['availability']                       = [];
                  }

                //spaces price list according days

                  $spaceDays = SpaceDayPrice::select('day','price')->where('space_id','=',$space->id)->get();

                  if(count($spaceDays) > 0){
                      $space['space_days_price_list'] = $spaceDays;
                  }
                 
                  unset($space->gallary_image);
                  unset($space->total_desk);
                  unset($space->thumb);
                  unset($space->cost);
                  unset($space->created_at);
                  unset($space->updated_at);
                  unset($space->user_id);
                  unset($space->property_type_id);

            return response()->json(['status' => true, 'message' => "success",'data'=>$space], 200);

          }
          else{
             return response()->json(['status' => false, 'message' => "unsuccess",'data'=>[]], 200);
          }


     }


    public function officeAttributes(Request $request){


        $attr_list = AttributeValue::select('id','attribute_id','value')->where('attribute_id', '=','2')
                    ->where('deleted_at', '=', Null)
                    ->get();

        if(!empty($attr_list)){
            return response()->json(['status' => true, 'message' => "success", 'facilities' => $attr_list], 200);
        }
        else{

             return response()->json(['status' => true, 'message' => "success", 'facilities' => Null], 200);
        }


    }


    public function deletePropery(Request $request){

        $validator = Validator::make($request->all(), [
            'property_id'            => 'required',
          
            ]);

            if ($validator->fails()) {
                return response()->json(['status' => false, 'message' => implode("", $validator->errors()->all())], 200);
            }

        $property = Property::where('id','=',$request->property_id)->first();

        if(!empty($property)){

          $property->delete();

          PropertyLocation::where('property_id','=',$request->property_id)->delete();

          PropertyAttributeValue::where('property_id','=',$request->property_id)->delete();

          OfficeExtraDetails::where('property_id','=',$request->property_id)->delete();

          $space = Space::where('property_id','=',$request->property_id)->get();

          if(count($space) > 0){

               foreach ($space as $key1 => $value1) {

              Space::where('id','=',$value1->id)->delete();
              SpaceExtraDetails::where('space_id','=',$value1->id)->delete();

            
            }
          }

         

          return response()->json(['status' => true, 'message' => 'Your Property has been deleted successfully.'], 200);


        }
        else{

          return response()->json(['status' => false, 'message' => 'property not found'], 200);

        }

    }

     public function deleteSpace(Request $request){

          $validator = Validator::make($request->all(), [
            'space_id'            => 'required',
          
          ]);

            if ($validator->fails()) {
                return response()->json(['status' => false, 'message' => implode("", $validator->errors()->all())], 200);
            }

           $space = Space::where('id','=',$request->space_id)->first();


          if(!empty($space)){

              $space->delete();

              SpaceExtraDetails::where('space_id','=',$space->id)->delete();

              return response()->json(['status' => true, 'message' => 'Your Space has been deleted successfully.'], 200);


          }
          else{

            return response()->json(['status' => false, 'message' => 'space not found'], 200);

          }

      
    }



    public function show($slug)
    {
        $blogs = Blog::where("slug", $slug)->first();
        if (isset($blogs)) {
            return response()->json(['status' => true, 'message' => "success", 'blog' => $blogs], 200);
        } else
            return response()->json(['status' => false, 'message' => "Blog not found", 'blog' => null], 200);
    }


    public function spacetype(){


        $property = SpaceType::all();

        if(count($property) > 0){

          return response()->json(['status' => true, 'message' => "success", 'data' => $property], 200);

          }
          else{

          return response()->json(['status' => true, 'message' => "unsuccess", 'data' => null], 200);

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

  public function createImage($img, $key = 0)
  {
    //var_dump($img);

      $folderPath = "media/gallery/";
      $image_parts = explode(";base64,", $img);
      $image_type_aux = explode("image/", $image_parts[0]);
       $image_type = $image_type_aux[1];
      $image_base64 = base64_decode($image_parts[1]);
      //$image_type = '.jpg';
      $file = uniqid()."_".$key. 'gallery_image.' . $image_type;
      file_put_contents("media/gallery/".$file, $image_base64);
      return $file;
      // echo $file;
      // exit;
      
  }



       


       
}
