<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Api\UserApiController;
use Illuminate\Http\Request;
use App\BlogCategory;
use App\Blog;
class BlogCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    function __construct()
    {
        $this->obj = new UserApiController;
    }
    public function index()
    {
        $data=BlogCategory::orderBy('name','asc')->get();
        if(isset($data)){
          $this->obj->unsetdate($data);  
          return response()->json(['message'=>"Category received successfully", "status"=>true,"data"=>$data],200);  
        }else{
          return response()->json(['message'=>"Blog category not found", "status"=>true,"data"=>null],200);    
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
        $data=BlogCategory::where('slug',strtolower($slug))->first();
        if(isset($data)){
          $dt=Blog::where('category_id',$data->id)->skip(0)->take(20)->get();
          if(isset($dt)){   
           return response()->json(['message'=>"Category received successfully", "status"=>true,"data"=>$dt],200);  
          }else{
           return response()->json(['message'=>"No post found", "status"=>false,"data"=>null],200);    
          }
        }else{
          return response()->json(['message'=>"Blog category not found", "status"=>true,"data"=>null],200);    
        }
    }




}
