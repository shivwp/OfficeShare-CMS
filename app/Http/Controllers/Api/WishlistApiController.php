<?php

namespace App\Http\Controllers\Api;

use App\OfficeDesk;
use App\Wishlist;
use App\Office;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Attribute;
use App\AttributeValue;
use App\OfficeAttribute;
use App\OfficeAttributeValue;
use App\Property;
use App\Space;
use App\SpaceType;
use App\Refund;
use App\SpaceExtraDetails;
use phpDocumentor\Reflection\Types\Null_;
use Validator;
use App\OfficeExtraDetails;
use App\User;
use App\SpaceDayPrice;
use App\Helper\Helper;

class WishlistApiController extends Controller
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

      //die('fgdfg');
      // $orderId = 'dsfs';
      // $msg = 'dsfs';

      // $notification = Helper::saveBookingActivity($orderId,$msg);

      // return $notification;
      // die;

          $validator = Validator::make($request->all(), [
            'user_id' => 'required'
          ]);
          if ($validator->fails()) {
              $er = [];
              $i = 0;
              foreach ($validator->errors() as $err) {
                  $er[$i++] = $err[0];
                  return $err;
              }
              return response()->json(["status" => false,"message" => implode("", $validator->errors()->all())], 200);
          }

          $allOffice = [];


          $wishlist = Wishlist::where('user_id','=',$request->user_id)->get();

          if(count($wishlist) > 0){

              foreach ($wishlist as $key => $value) {

                  $dataaa = Property::select('property.*','property_locations.longitude','property_locations.latitude')
                  ->where('is_approved', "publish")
                  ->where('property.id','=',$value->property_id)
                  ->join('property_locations', 'property.id', '=', 'property_locations.property_id')
                  ->get();

                  foreach ($dataaa as $k => $v) 
                  {

                    //Get Property type
                    $property_space_type  =  DB::table('property_spaces')
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

                        // if($property_address->address){

                        // }

                      $dataaa[$k]['location'] = $property_address;
                    }

                    //Description

                    $dataaa[$k]['describe_your_space'] =  $v->short_description;

                    //price range

                    $spaceforcosttype = Space::where('property_id','=',$v->id)
                                              ->where('cost_type','=','range')
                                               ->where('is_approved','=','publish')
                                              ->first();

                    if(!empty($spaceforcosttype)){

                      $cost_type = $spaceforcosttype->cost_type;

                    }

                    $dataaa[$k]['cost_type'] = !empty($cost_type) ? $cost_type : 'single';

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
                        $dataaa[$k]['about_sharer']        =  null;
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
                    // ->select('space_type_id')
                    // ->where('property_id','=',$v->id)->get();

                    $sapce = [];


                    $space = Space::where('property_id','=',$v->id)->get();

                    foreach ($space as $sp => $spv) {

                          //cost of thhe day

                          if($spv->cost_type == "range"){

                            $date = \Carbon\Carbon::now()->format('D');

                            $costofday = SpaceDayPrice::where('space_id','=',$spv->id)
                                          ->where('day','=',$date)
                                          ->first();

                            $spv->cost = $costofday->price;


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
                        $space[$sp]['min_term'] = !empty($space_extra->min_term) ? $space_extra->min_term : "5";
                        $space[$sp]['max_term'] = !empty($space_extra->max_term) ? $space_extra->max_term :"5";
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

                    if (!empty($dataaa[0])) {
                      array_push($allOffice, $dataaa[0]);
                    }

                  }

              return response()->json(['status' => true, 'message' => "success" ,'property' => $allOffice], 200);

          }
          else{

            return response()->json(['status' => true, 'message' => "No properties found" ,'property' => []], 200);

          }

        
    }

    public function addtowishlist(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'property_id' => 'required',
            'user_id' => 'required'
        ]);
        if ($validator->fails()) {
            $er = [];
            $i = 0;
            foreach ($validator->errors() as $err) {
                $er[$i++] = $err[0];
                return $err;
            }
            return response()->json(["status" => false,"message" => implode("", $validator->errors()->all())], 200);
        }

        // $office_desk = OfficeDesk::where('office_id', '=', $request->office_id)->where('desk_id', '=', $request->desk_id)->where('is_available', '=', '1')->get();

        $already_exist = Wishlist::where('user_id','=',$request->user_id)->where('property_id', '=', $request->property_id)->first();

        if (empty($already_exist)) {

            $addtowishlist = Wishlist::create([
                'property_id' => $request->property_id,
                'user_id' => $request->user_id
            ]);

            return response()->json(['status' => true, 'message' => " Property added successfully in to Liked Spaces"], 200);
        } else {

            return response()->json(['status' => false, 'message' => "Property already exist in Liked Spaces"], 200);
        }
    }


    public function removefromwishlist(Request $request)
    {

        $validator = Validator::make($request->all(), [

           'property_id' => 'required',
            'user_id' => 'required'
        ]);
        if ($validator->fails()) {
            $er = [];
            $i = 0;
            foreach ($validator->errors() as $err) {
                $er[$i++] = $err[0];
                return $err;
            }
            return response()->json(["error" => implode("", $validator->errors()->all()), "status" => false], 403);
        }
        $data = Wishlist::where('user_id', '=', $request->user_id)
                        ->where('property_id', '=', $request->property_id)
            ->first();
        if (!empty($data)) {

            $delete = Wishlist::where('user_id', '=', $request->user_id)
                ->where('property_id', '=', $request->property_id)
                ->delete();
            return response()->json(['status' => true, 'message' => "Property removed successfully from Liked Spaces"], 200);
        } else {

            return response()->json(['status' => false, 'message' => "Property not exist in Liked Spaces"], 200);
        }
    }



    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($slug)
    {
        $blogs = Blog::where("slug", $slug)->first();
        if (isset($blogs)) {
            return response()->json(['status' => true, 'message' => "success", 'blog' => $blogs], 200);
        } else
            return response()->json(['status' => false, 'message' => "Blog not found", 'blog' => null], 200);
    }
}
