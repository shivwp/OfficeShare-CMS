<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Office;
use App\Country;
use App\State;
use App\City;
use App\Attribute;
use App\AttributeValue;
use App\OfficeAttribute;
use App\OfficeAttributeValue;
use App\DeskType;
use App\OfficeDeskType;
use App\OfficeLocation;
use App\OfficeFeaturedImage;
use App\Tax;
use App\OfficeDesk;
use App\OfficeDeskTypeInfo;
use App\Property;
use App\Space;
use App\SpaceDayPrice;
use App\Refund;
use App\User;
use App\SpaceType;
use App\SpaceExtraDetails;
use App\PropertyLocation;
use Auth;
use Illuminate\Http\Request;
use Session;
use DB;
use App\Helper\Helper;
use App\AvailabilityDesk;
use Redirect;

class SpaceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        DB::enableQueryLog();

        $d['title'] = "Spaces";

        $d['properties'] = Property::all();
        $d['SpaceType'] = SpaceType::all();

        $space1 = Space::orderBy('id', 'DESC');

         if(!empty($request->search)){

            $space1->where('space_title', 'like', "%$request->search%");

        }
        if(!empty($request->filter)){

            $space1->where('is_approved','=',$request->filter);
        }

         if(!empty($request->property_id)){

            $space1->where('property_id','=',$request->property_id);
        }

        $d['space'] =  $space1->paginate(5)->withQueryString();

        //dd(DB::getQueryLog());

// 

        if(!empty($d['space'])){

            foreach($d['space'] as $key => $vl){

                $property = Property::where('id','=',$vl->property_id)->first();

                $space_type = SpaceType::where('id','=',$vl->property_type_id)->first();

                // $bookings = 

                // $total = $vl->total_desk;
                // $
                
				if(!empty($property)){
				 $user = User::where('id','=',$property->user_id)->first();
                }

                if(!empty($property)){

                   $d['space'][$key]['property'] =  $property->property_title;
                }

                 if(!empty($space_type)){

                   $d['space'][$key]['space_type'] =  $space_type->title;
                }
				
				 if(!empty($user)){

                   $d['space'][$key]['user'] =  $user->name;
                }

            }

        }
       


        return view('admin.space.index',$d);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

        $d['title'] = "Add Space";
        $d['property'] = Property::all();
        $d['space_type'] = SpaceType::all();
        $d['refund'] = Refund::all();


        return view('admin.space.add', $d);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // echo '<pre>';
        // print_r($request->availability_space);
        // echo '</pre>';
        // exit;
         
        $priviousid = Space::where('id','=',$request->id)->first();
        $property = Property::where('id','=',$request->property_id)->first();

        $priviousPropertyid = !empty($priviousid->property_id) ? $priviousid->property_id : '';

        $gallery_image_name = [];
        $featured_image_name = [];
        $day_arr = [];

        if($request->price_type == "single" && empty($request->cost)){

            //return Redirect::back();
            return Redirect::back()->withErrors(['msg' => 'Cost is required']);

        }elseif($request->price_type == "range" && (empty($request->Mon) && empty($request->Tue) && empty($request->Wed) && empty($request->Thu) && empty($request->Fri) && empty($request->Sat) && empty($request->Sun))){

            return Redirect::back()->withErrors(['msg' => 'Cost is required']);

        }

                $space = Space::updateOrCreate(['id' => $request->id], [
                    'property_id'                => $request->property_id,
                    'user_id'                   => Auth::user()->id,
                    'property_type_id'         => $request->space_type_id,
                    'space_title'               => $request->title,
                    'key_feature'               => $request->key_feature,
                    'availability_type'                      => $request->availability_type,
                    'featured_image'                 => json_encode($featured_image_name),
                    'total_desk'             => $request->desk,
                    'booking_payment_refund' => $request->refund,
                    'cost_type' => $request->price_type
                ]);

                 $allspace = Space::where('property_id','=',$request->property_id)->sum('total_desk');

                $space_id = $space->id;
                $cost = $request->cost;

                if ($files1    =    $request->file('thumb')) {
                        $name_3   =    uniqid() . $files1->getClientOriginalName();
                        $files1->move('media/thumbnail', $name_3);
                        $space->update([
                            'thumb' => $name_3,
                        ]);
                }

                 if ($files = $request->file('gallery_image')) 
                    {
                        foreach ($files as $file) {
                            $name_1 = $file->getClientOriginalName();
                            $file->move('media/gallery', $name_1);
                              $gallery_image_name[] =  $name_1;
                        }
                         $space->update([
                            'gallary_image'             => json_encode($gallery_image_name)
                            ]);
                    }

                $space_desk_count =  Space::where('property_id','=',$request->property_id)
                                            ->sum('total_desk');



                $SpaceExtraDetails = SpaceExtraDetails::updateOrCreate(['space_id' => $space->id], [
                    'space_id'                   =>$space->id,
                    'min_term'                      => $request->min_term,
                    'max_term'                      => $request->max_term,
                    'things_not_included'           => $request->things_not_included,
                ]);

                // COST RANGE

                if(isset($request->cost_range)){

                     $day_arr = [

                        'Mon' => $request->Mon,
                        'Tue' => $request->Tue,
                        'Wed' => $request->Wed,
                        'Thu' => $request->Thu,
                        'Fri' => $request->Fri,
                        'Sat' => $request->Sat,
                        'Sun' => $request->Sun,


                    ];

                   

                    $space = Space::where('id','=',$space->id)
                                    ->update([

                                      'cost' => $request->Mon

                                    ]);
                }
                else if(isset($request->single_cost)){

                    $day_arr = [

                        'Mon' => $cost,
                        'Tue' => $cost,
                        'Wed' => $cost,
                        'Thu' => $cost,
                        'Fri' => $cost,
                        'Sat' => $cost,
                        'Sun' => $cost,


                    ];

                      $space = Space::where('id','=',$space->id)
                                    ->update([

                                      'cost' => $cost,

                                    ]);

                }
                    if(isset($request->id)){

                        $SpaceDayPrice = SpaceDayPrice::where('space_id','=',$request->id)->delete();

                    }

                    foreach ($day_arr as $key => $value) {
                        SpaceDayPrice::insert( [
                            'space_id'                   =>$space_id,
                            'day'                          => $key,
                            'price'                          => $value,
                        ]);
                    }

                    //single space space range

                    $max1 = max($day_arr);

                    $min1 = min($day_arr);


                    Space::where('id','=',$space_id)->update([

                        'price_from'           => $min1,
                        'price_to'              => $max1,


                    ]);

                    $singleSpace = Space::where('id','=',$space_id)->first();

                    if($singleSpace->is_approved == 'publish' && !empty($priviousPropertyid)){

                        if($singleSpace->is_approved == 'publish'){

                        $updateproperty = Helper::updatePropertySpace($space_id);

                     }
                  

                        $updateproperty = Helper::updatePropertySpaceOnUpdate($space_id,$priviousPropertyid);


                    }

                    if(!empty($request->availability_space[1]['available_desk']))
                    {

                        if(!empty($request->availability_space[1]['single_date']) || (!empty($request->availability_space[1]['from_date']) && !empty($request->availability_space[1]['to_date'])))
                        {

                               if(empty($request->id)){

                                  foreach ($request->availability_space as $value) {

                                      if($value['group1'] == 'date'){

                                          AvailabilityDesk::create([

                                              'landload_id'     => $property->user_id,
                                              'space_id'        => $space_id,
                                              'available_desk'  =>$value['available_desk'],
                                              'to_date'         => ($value['single_date']),
                                              'from_date'       => $value['single_date'],
                                              'type'            => $value['group1'],

                                          ]);
                                      }
                                      if($value['group1'] == 'range'){

                                          AvailabilityDesk::create([

                                              'landload_id'     => $property->user_id,
                                              'space_id'        => $space_id,
                                              'available_desk'  => $value['available_desk'],
                                              'from_date'       => $value['from_date'],
                                              'to_date'         => $value['to_date'],
                                              'type'            => $value['group1'],


                                          ]);


                                      }
                                  }
                               }
                                else{

                                    AvailabilityDesk::where('space_id','=',$request->id)->delete();

                                      foreach ($request->availability_space as  $value) {

                                          if($value['group1'] == 'date'){

                                              AvailabilityDesk::create([

                                                  'landload_id'     => $property->user_id,
                                                  'space_id'        => $space_id,
                                                  'available_desk'  =>$value['available_desk'],
                                                  'to_date'         => $value['single_date'],
                                                  'from_date'       => $value['single_date'],
                                                  'type'            => $value['group1'],

                                              ]);
                                          }
                                    
                                          if($value['group1'] == 'range'){

                                              AvailabilityDesk::create([

                                                  'landload_id'     => $property->user_id,
                                                      'space_id'        => $space_id,
                                                      'available_desk'  =>$value['available_desk'],
                                                      'to_date'         => $value['to_date'],
                                                      'from_date'       => $value['from_date'],
                                                      'type'            => $value['group1'],


                                              ]);


                                          }

                                      }

                                }   

                        }
                       
                    }
                     if(empty($request->availability_space[1])){

                        AvailabilityDesk::where('space_id','=',$request->id)->delete();

                     }
                   

                     


              return redirect('dashboard/space/')->with('msg', 'Space Added or Updated successfully');


    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {

            $d['title']         = "Edit Space";
            $d['space']          = Space::findOrFail($id);
            $d['property'] = Property::all();
            $d['space_type'] = SpaceType::all();
            $d['refund'] = Refund::all();

            $d['mon'] = SpaceDayPrice::select('day','price')->where('day','=','Mon')->where('space_id','=',$id)->first();
            $d['tue'] = SpaceDayPrice::select('day','price')->where('day','=','Tue')->where('space_id','=',$id)->first();
            $d['wed'] = SpaceDayPrice::select('day','price')->where('day','=','Wed')->where('space_id','=',$id)->first();
            $d['thu'] = SpaceDayPrice::select('day','price')->where('day','=','Thu')->where('space_id','=',$id)->first();
            $d['fri'] = SpaceDayPrice::select('day','price')->where('day','=','Fri')->where('space_id','=',$id)->first();
            $d['sat'] = SpaceDayPrice::select('day','price')->where('day','=','Sat')->where('space_id','=',$id)->first();
            $d['sun'] = SpaceDayPrice::select('day','price')->where('day','=','Sun')->where('space_id','=',$id)->first();

            $d['availabilityDesk'] = AvailabilityDesk::where('space_id','=',$id)->get();
           

            $d['SpaceExtraDetails'] = SpaceExtraDetails::where('space_id','=',$id)->first();

            return view('admin.space.add', $d);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

   

      

      public function filerSpaceByType(Request $request){

        $space = Space::where('property_type_id','=',$request->space_type_id)->paginate(5)->withQueryString();

        if(!empty($space)){

            foreach($space as $key => $vl){

                $property = Property::where('id','=',$vl->property_id)->first();

                $space_type = SpaceType::where('id','=',$vl->property_type_id)->first();
                
                 $user = User::where('id','=',$vl->user_id)->first();

                if(!empty($property)){

                   $space[$key]['property'] =  $property->property_title;
                }

                 if(!empty($space_type)){

                   $space[$key]['space_type'] =  $space_type->title;
                }
                
                 if(!empty($user)){

                   $space[$key]['user'] =  $user->name;
                }

            }

        }

        $title = "Spaces";
        $properties =  $properties = Property::all();
        $SpaceType = SpaceType::all();

        return view('admin.space.index',compact('space','title','SpaceType','properties'));




      }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    { 
         // $Space = Space::where('id','=',$id)->first();
         // $property = $Space->property_id;
         // Space::destroy($id);
         // SpaceExtraDetails::where('space_id','=',$id)->delete();

         // $allspace = Space::where('property_id','=',$property)->sum('total_desk');

         // $property = Property::where('id','=',$property)
         //              ->update([
         //                'total_desk' => !empty($allspace) ? $allspace : '0'
         //              ]);

        $updateproperty = Helper::updatePropertySpaceOnDelete($id);
    }

    public function changeSpaceStatus(Request $request){

       
        if(isset($request->approve)){

            $approve = 'Approved';

            $spaceId = $request->id;

             Space::where('id','=',$request->id)->update([

                        "is_approved"               =>  'publish'

                            ]);

         $updateproperty = Helper::updatePropertySpace($spaceId);

        }
        elseif($request->enquiry){
           Space::where('id','=',$request->id)->update([

                        "booking_approval"               =>  "1"

                            ]);


            return redirect('dashboard/space/')->with('msg', 'Space Type Inquiry');

        }
        else{

             $approve = 'Rejected';

              Space::where('id','=',$request->id)->update([

                        "is_approved"               =>  'rejected'

                            ]);

        }


        return redirect('dashboard/space/')->with('msg', 'Space '.$approve);

    }
}
