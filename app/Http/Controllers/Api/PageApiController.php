<?php
// 
namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;

use App\PageMeta;
use App\Pages;
use Validator;
use Illuminate\Http\Request;

class PageApiController extends Controller
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

   public function index(){

        $pg= $pp=Pages::select('id','name as page_name','title', 'content','slugs as slug','meta_title','meta_keyword')->where('status',1)->get();
        if(count($pg)>0){
         return response()->json(['status'=>true,'message'=>"success",'pages'=>$pg],200);    
        }else
        return response()->json(['status'=>false,'message'=>"Page not found",'pages'=>null],200);

    }
    public function show($slug)
    {
        $pg = $pp = Pages::select('id', 'name as page_name', 'title', 'content', 'slugs as slug', 'meta_title', 'meta_keyword', "meta_description", 'sections')->where('slugs',$slug)->first();
        if(isset($pg)) {
            if($pg->sections) {
             $pg['sections'] = json_decode($pg->sections);

            }
            return response()->json(['status'=>true,'message'=>"success",'pages'=>$pg],200);    
        } else {
            return response()->json(['status'=>false,'message'=>"Page not found",'pages'=>null],200); 
        }
    }

    public function pages(Request $request){

        $validator = Validator::make($request->all(), [
            'slug' => 'required'
        ]);

        if ($validator->fails()) {
            $er = [];
            $i = 0;
            foreach ($validator->errors() as $err) {
                $er[$i++] = $err[0];
                return $err;
            }
            return response()->json(['status' => false, 'message' => implode("", $validator->errors()->all()), 'user' => Null], 200);
        }
        $slug = $request->slug;

        $data =  Pages::select('title','content', 'sections', 'meta_title', 'meta_keyword', 'meta_description', 'id')->where('slugs','=',$slug)->first();
        
        if($data->sections) {
            // $data['sections'] = json_decode($data->sections);
            // if(isset($data['sections']->top_section->left_bg_image)) {
            //     $data['sections']->top_section->left_bg_image = url('images/homePage/'.$data['sections']->top_section->left_bg_image);
            // }

            // if(isset($data['sections']->top_section->right_bg_image)) {
            //     $data['sections']->top_section->right_bg_image = url('images/homePage/'.$data['sections']->top_section->right_bg_image);
            // }

            // 

            $PageMeta = PageMeta::where('page_id', $data->id)->get();
            $p_meta_values = [];

            foreach ($PageMeta as $key => $value) {
                // code...
                $p_meta_values[$value->meta_key] = $value->meta_value;
            }
            $data['sections'] = $p_meta_values;
            // dd($data['sections']->top_section->right_bg_image);
        }

        if(!empty($data)) {
            // 
            return response()->json(['status' => true, 'message' => "success", 'data' => $data], 200);
        } else {
            // 
            return response()->json(['status' => true, 'message' => "unsuccess", 'data' => Null], 200);
        }


    }


}

