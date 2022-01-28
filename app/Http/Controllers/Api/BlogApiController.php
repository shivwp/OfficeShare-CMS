<?php

namespace App\Http\Controllers\Api;

use App\Blog;
use Validator;
use App\Setting;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class BlogApiController extends Controller
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
    public function index($skip = 0)
    {

        $stk =  Blog::where('sticky', '=', '1')
                ->where('blog_view_type', '=', 'mobile')
                ->where('status', '=', 1)
                ->orderBy('id', 'DESC')->take(2)->get();

        $non_stk = Blog::where('sticky', '=', '0')
                        ->where('blog_view_type', '=', 'mobile')
                        ->where('status', '=', 1)
                        ->orderBy('id', 'DESC')->take(2)
                        ->get();

        //  $data->emergency['content']=$this->divContent($data->emergency['content']);

        $data = [];

        foreach ($stk as $key => $val) {
            if (!empty($val)) {
                $val->media = url($val->media);
                $val->description = $this->divContent($val->description);
            }

            $data[] = $val;

            for ($j = ($key * 2); $j <  2; $j++) {
                if (!empty($non_stk)) {
                    $non_stk[$j]->media =  url($non_stk[$j]->media);
                    $val->description = $this->divContent($val->description);
                }
                $data[] = $non_stk[$j];
                //unset($non_stk[$j]);

            }
        }

         foreach ($data as $key => $value) {
            // code...
            ob_start();

            ?>
          
            <script src="https://fast.wistia.com/embed/medias/dpwxs5g6az.jsonp" async></script><script src="https://fast.wistia.com/assets/external/E-v1.js" async></script>
            <?php 
            $htm = ob_get_contents();
            ob_clean();
            $value->description .= $htm;

            if(!empty($value->video_url) && $value->video_url!=null){

                $value->description = '';
              $value->video_url = $value->video_url;

            }
        }

        ?>

        <?php

        return response()->json(['status' => true, 'message' => "success", 'blogs' => $data], 200);
    }
    public function stickyblog(Request $request)
    {
        //dd($request->type);

        $stk =  Blog::with('user')->where('sticky', '=', '1')->take(10)->get();

        $non_stk = Blog::with('user')->where('sticky', '=', '0')->orderBy('id', 'DESC')->take(20)->get();

        /* dd($stk);
        dd($non_stk); */

        $data = [];
        $data_push = [];

        foreach ($stk as $key => $val) {

            $data[] = $val;

            for ($j = ($key * 2); $j < (($key * 2) + 2); $j++) {
                $data[] = $non_stk[$j];
                //unset($non_stk[$j]);
            }
        }

        return response()->json(['status' => true, 'message' => "success", 'blogs' => $data], 200);
    }

     public function blogforweb(){

         $data =  Blog::where('blog_view_type', '=', 'web')->where('status', '=', 1)->take(4)->get();

         foreach ($data as $key => $value) {
             if (!empty($value)) {
                     $value->media = url($value->media);
                 }
         }

         if(count($data) > 0){

             return response()->json(['status' => true, 'message' => "success", 'blogs' => $data], 200);

         }else{

             return response()->json(['status' => true, 'message' => "unsuccess", 'blogs' => Null], 200);

         }

     }


     public function blogsingle(Request $request){

          $validator = Validator::make($request->all(), [
            'id' => 'required'
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


         $data =  Blog::where('id', '=', $request->id)->first();

         if(!empty($data)){

            return response()->json(['status' => true, 'message' => "success", 'blog' => $data], 200);

         }else{

            return response()->json(['status' => true, 'message' => "unsuccess", 'blog' => Null], 200);

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

    public function divContent($data)
      {
        $css=Setting::select('value')->where('id','=','18')->first(); 
        return"<style type='text/css'>".
            "@import url('https://fonts.googleapis.com/css2?family=Poppins:wght@200&display=swap');".
            ".body_style{font-family: 'Roboto', sans-serif !important; }".$css->value.   
             "</style>".
            "<div class='body_style' >".$data."</div>";
      }
}
