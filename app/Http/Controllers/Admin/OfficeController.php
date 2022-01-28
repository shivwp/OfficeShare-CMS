<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Office;
use App\Country;
use App\State;
use App\City;
use App\User;
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
use App\SpaceType;
use App\OfficeExtraDetails;
use App\PropertyLocation;
use App\PropertyAttributeValue;
use App\SpaceExtraDetails;
use Auth;
use Illuminate\Http\Request;
use Session;
use DB;
use Role;

class OfficeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

     $property = Property::orderBy('id', 'DESC');

        if(!empty($request->search)){

            $property->where('property_title', 'like', "%$request->search%");

        }
        if(!empty($request->filter)){

            $property->where('is_approved','=',$request->filter);
        }

        $d['property'] = $property->paginate(5)->withQueryString();
       

        $d['title'] = "Properties";
		
		if(!empty($d['property'])){
			
			foreach($d['property'] as $key => $val){
				
				$user = User::where('id','=',$val['user_id'])->first();
				
				if(!empty($user)){

                    $d['property'][$key]['user_name'] = $user->name;
                }
				
				
			}
			
		}

        return view('admin.office.index', $d);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

        $d['title']                 = "Add Property";
        $d['countries']             = Country::all();
        $d['attributes']            = AttributeValue::where('attribute_id','=','2')->orderBy('id', 'ASC')->get();
        $d['desks']                 = DeskType::get();
        $d['tax_type']              = Tax::get();
        $d['space_type']            = SpaceType::pluck('title','id');
        $d['landload']              = User::join('role_user', 'role_user.user_id', '=', 'users.id')->where('role_user.role_id', '=', '3')->get();
        
        return view('admin.office.add', $d);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        
        $attr_count = count($request->attribute);

        $gallery_image_name = [];



        if ($files    =    $request->file('thumbnail')) {
                $name    =    uniqid() . $files->getClientOriginalName();
                $files->move('media/thumbnail', $name);
        }

         if ($files = $request->file('gallery_image')) 
            {
                foreach ($files as $file) {
                    $name_1 = $file->getClientOriginalName();
                    $file->move('media/gallery', $name_1);

                     $gallery_image_name[] =  $name_1;

                }
            }


            $property = Property::updateOrCreate(['id' => $request->id], [
                'property_title'           => $request->title,
                'user_id'                   => $request->landload,
                'short_description'               => $request->short_description,
                'thumbnail'                 => $name,
                'gallary_image'             => json_encode($gallery_image_name)
            ]);

            $property_id = $property->id;

           // $property->spacetype()->sync($request->input('property_type', []));

            $city_name = DB::table('cities')
                                    ->select('name')
                                      ->where('id','=', $request->city)
                                       ->first();

         //  dd($city_name);



            $location = PropertyLocation::updateOrCreate(['property_id' => $request->id], [
                'property_id'           => $property_id,
                'country'               => $request->country,
                'state'                 => $request->state,
                'city'                  => $request->city,
                'postcode'              => $request->postcode,
                'address'               => $request->address,
                'longitude'             => $request->lang,
                'latitude'              => $request->lat
            ]);

            for ($i=0; $i <= $attr_count; $i++) { 

              if(!empty($request->attribute[$i])){
                   $attr_val =  PropertyAttributeValue::updateOrCreate(['id' => $request->id], [
                                'property_id'           => $property_id,
                                'attribute_id'          => '2',
                                'attribute_value_id'    => $request->attribute[$i]
                            ]);
                }

               
            }

            $propertExtraDetails = OfficeExtraDetails::updateOrCreate(['property_id' => $request->id],[

                    'disability_access'           => $request->disability_access,
                    'how_to_find_us'                => $request->how_to_find_us,
                    'insurance'                 => $request->insurance,
                    'covid_19_secure'                 => $request->covid_19_secure,
                    'property_id'                 => $property_id,

            ]);

              return redirect('dashboard/office/')->with('msg', 'Property added successfully');



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
            $d['title']                 = "Edit Property";
            $d['property']              = Property::findOrFail($id);
            $d['countries']             = Country::all();
            $d['space_type']            = SpaceType::pluck('title','id');
            $d['location']              = PropertyLocation::where('property_id','=',$id)->first();
            $d['attributes']            = AttributeValue::where('attribute_id','=','2')->orderBy('id', 'ASC')->get();
			$d['selected_attributes']            = PropertyAttributeValue::where('property_id','=',$id)->where('attribute_id','=','2')->get();
			
			$atr_id = [];
			
			foreach($d['selected_attributes'] as $k => $v){
				
				$atr_id[] = $v->attribute_value_id;
				
			}
			
			$d['atr_id_data']  = $atr_id;

            //$d['property']->load('spacetype');

            $d['propertExtraDetails'] = OfficeExtraDetails::where('property_id','=',$id)->first();
             $d['landload']              = User::join('role_user', 'role_user.user_id', '=', 'users.id')->where('role_user.role_id', '=', '3')->get();

               

            return view('admin.office.edit', $d);
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
        
         $attr_count = count($request->attribute);

        $gallery_image_name = [];
       
            $property = Property::updateOrCreate(['id' => $request->id], [
                'property_title'           => $request->title,
                'user_id'                   => $request->landload,
                'short_description'               => $request->short_description,
             
            ]);

            $property_id = $property->id;

           // $property->spacetype()->sync($request->input('property_type', []));
			
			 if ($files    =    $request->file('thumbnail')) {
                $name    =    uniqid() . $files->getClientOriginalName();
                $files->move('media/thumbnail', $name);
				$property=Property::where('id','=',$request->id)->update([
				      'thumbnail' => $name,
				]);
        	}

         if ($files = $request->file('gallery_image')) 
            {
                foreach ($files as $file) {
                    $name_1 = $file->getClientOriginalName();
                    $file->move('media/gallery', $name_1);

                     $gallery_image_name[] =  $name_1;
					

                }
               
            }
            $result = [];
            $varimg = json_decode($request->gallery_img);
            if(!empty($gallery_image_name)) {
                $result = array_merge($varimg, $gallery_image_name);
                //dd( $result);
            } else {
                $result = $varimg;
            }
           
            $property =Property::where('id','=',$request->id)->update([
                'gallary_image'             => json_encode($result)
                ]);

            $city_name = DB::table('cities')
                                    ->select('name')
                                      ->where('id','=', $request->city)
                                       ->first();

            //  dd($city_name);



            $location = PropertyLocation::updateOrCreate(['property_id' => $request->id], [
                'property_id'           => $property_id,
                'country'               => $request->country,
                'state'                 => $request->state,
                'city'                  => $request->city,
                'postcode'              => $request->postcode,
                'address'               => $request->address,
                'longitude'             => $request->lang,
                'latitude'              => $request->lat
            ]);

            //die('sdfs');
			
			PropertyAttributeValue::where('property_id','=',$request->id)->delete();

            for ($i=0; $i <= $attr_count; $i++) { 

              if(!empty($request->attribute[$i])){
                   $attr_val =  PropertyAttributeValue::create([
                                'property_id'           => $property_id,
                                'attribute_id'          => '2',
                                'attribute_value_id'    => $request->attribute[$i]
                            ]);
                }

               
            }

            $propertExtraDetails = OfficeExtraDetails::updateOrCreate(['property_id' => $request->id],[

                    'disability_access'           => $request->disability_access,
                    'how_to_find_us'                => $request->how_to_find_us,
                    'insurance'                 => $request->insurance,
                    'covid_19_secure'                 => $request->covid_19_secure,
                    'property_id'                 => $property_id,

            ]);

              return redirect('dashboard/office/')->with('msg', 'Property updated successfully');

    }

     public function changeStatus(Request $request){

        if(isset($request->approve)){

            $approve = 'Approved';

             Property::where('id','=',$request->id)->update([

                        "is_approved"               =>  'publish'

                            ]);

        }
        else{

             $approve = 'Rejected';

              Property::where('id','=',$request->id)->update([

                        "is_approved"               =>  'rejected'

                            ]);

        }

        return redirect('dashboard/office')->with('msg', 'Property '.$approve);
     }

      public function showspace($id){

        $Space = Space::where('property_id','=',$id)->get();

        $title = "Spaces";

        if(!empty($Space)){

            foreach($Space as $key => $vl){

                $property = Property::where('id','=',$vl->property_id)->first();

                $space_type = SpaceType::where('id','=',$vl->property_type_id)->first();

                if(!empty($property)){

                   $Space[$key]['property'] =  $property->property_title;
                }

                 if(!empty($space_type)){

                   $Space[$key]['space_type'] =  $space_type->title;
                }

            }

        }

        
        
        return view('admin.office.show-property-space', compact('Space','title'));


      }

      public function userproperty($user_id){



         $d['title']                 = "Landload Properties";
         $d['property']              = Property::where('user_id','=',$user_id)->get();

         if(count($d['property'])>0){
            
            foreach($d['property'] as $key => $val){
                
                $user = User::where('id','=',$val->user_id)->first();
                
                $d['property'][$key]['user_name'] = $user->name;
                
                
            }
            
        }


        return view('admin.office.index', $d);

      }

     

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
       
         Property::destroy($id);

         $PropertyLocation = PropertyLocation::where('property_id','=',$id)->first();

         if(!empty($PropertyLocation)){

            PropertyLocation::where('property_id','=',$id)->delete();

         }

         $PropertyAttributeValue = PropertyAttributeValue::where('property_id','=',$id)->get();

          if(count($PropertyAttributeValue) > 0){

            PropertyAttributeValue::where('property_id','=',$id)->delete();


         }

         $OfficeExtraDetails = OfficeExtraDetails::where('property_id','=',$id)->first();

         if(!empty($OfficeExtraDetails)){

            OfficeExtraDetails::where('property_id','=',$id)->delete();

            }

         $Space = Space::where('property_id','=',$id)->get();

         if(count($Space) > 0){

                 foreach ($Space as $key => $value) {

                     $Space = Space::where('id','=',$value->id)->delete();

                     $SpaceExtraDetails = SpaceExtraDetails::where('space_id','=',$value->id)->delete();
                 }
            }

        //
    }

    public static function createImage($img, $path)
    {

        $folderPath = $path;
        $image_parts = explode(";base64,", $img);
        /*$image_type_aux = explode("image/", $image_parts[0]);
         $image_type = $image_type_aux[1];*/
        $image_base64 = base64_decode($img);
        $extension = explode('/', mime_content_type($img))[1];
        $file = $folderPath .'/' . uniqid().".". $extension;
        file_put_contents($file, $image_base64);
        $basename =  basename(url($file));
        return $basename;
        
    }

     public function uploadImages(Request $request)
    {
        # code...
        // dd($request);
       // echo '<pre>';
        dd($request);
        //echo '</pre>';
        $gallery_image_name = [];
        $files = $request->file('file');
        echo "adfasd";
        dd($files);
        if ($files) 
        { 
            
            foreach ($files as $file) {
                $name = $file->getClientOriginalName();
                $file->move('media/gallery', $name);

                 $gallery_image_name[] =  $name;
                 // $property =Property::where('id','=',$request->id)->update([
                 // 'gallary_image'             => json_encode($gallery_image_name)
            }
            return $files;
        }

        return $gallery_image_name;
        exit;
        return 'done';//.$gallery_image_name;
    }

    public function removePropertyImage(Request $request){

        echo 'sdf';


    }




}
