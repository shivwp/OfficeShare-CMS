<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\BlogCategory;

class BlogCategoryController extends Controller
{
  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function index(Request $request)
  {
    $d['title'] = "Manage Blog Category";
    $blogc      = BlogCategory::orderBy('id','DESC');

     if(!empty($request->search)){

            $blogc->where('name', 'like', "%$request->search%");

        }

        if($request->filter != null){

            $blogc->where('status','=',$request->filter);
        }


    $d['blogc'] = $blogc->paginate(10);
    return view('admin.blogcategory.index', $d);
  }

  /**
   * Store a newly created resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\Response
   */
  public function store(Request $request)
  {
    BlogCategory::updateOrCreate(
      ['id' => $request->id],
      [
        "name" => $request->name,
        "slug" => str_replace(" ", "-", strtolower($request->slug))
      ]
    );
    return redirect('dashboard/blog-category');
  }



  /**
   * Show the form for editing the specified resource.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function edit($id)
  {
    $d['title'] = "Edit Blog Category";
    $d['edblog'] = BlogCategory::find($id);
    return view('admin.blogcategory.index', $d);
  }



  /**
   * Remove the specified resource from storage.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function destroy($id)
  {
    BlogCategory::destroy($id);
  }

  public function changeStatus($id, $st)
  {
    BlogCategory::where('id', $id)->update(['status' => $st]);
    return redirect('admin/blog-category');
  }
}
