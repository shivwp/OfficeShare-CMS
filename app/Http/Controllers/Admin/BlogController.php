<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Blog;
use App\BlogCategory;
use Illuminate\Support\Facades\Auth;

class BlogController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        $filter = $request->filter;

        $d['title'] = "Articles";
        $blog = Blog::with('user')->orderBy('id', 'desc');
        if(!empty($request->search)){

            $blog->where('title', 'like', "%$request->search%");

        }

        if(!empty($request->date)){

            $blog->whereDate('created_at','=',$request->date);
        }
        if($filter != null){

            $status = (int)$filter;


            $blog->where('status','=',$status);
        }
        if(!empty($request->filter_cat)){

            $blog->where('category_id','=',$request->filter_cat);
        }

        $d['blog'] = $blog->paginate(5)->withQueryString();

         $d['blog_Cat'] = BlogCategory::where('status','=',1)->get();


        return view('admin.blog.index', $d);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

        $d['title'] = "Add Articles";
        $d['blogcat'] = BlogCategory::orderBy('name', "asc")->get();
        return view('admin.blog.add', $d);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if($request->id && ($request->id == 4  ||   $request->id == 1)){

            $sticky = 1;
        }
        $slug = isset($request->slug) ? str_replace(" ", "-", $request->slug) : str_replace(" ", "-", $request->title);
        $blog = Blog::updateOrCreate(['id' => $request->id], [
            'media_type' => 'image',
            'title' => $request->title,
            'short_description' => $request->sd,
            'blog_view_type' => $request->viewtype,
            'description' =>str_replace('../../..',url('/'),$request->fd),
            'meta_title' => $request->meta_title,
            'meta_keyword' => $request->meta_keyword,
            'status' => isset($request->st) && $request->st == "0" ? 0 : 1,
            'meta_description' => $request->meta_description,
            'user_id' => Auth::user()->id,
            'feature' => $request->feature == 1 ? 1 : 0,
            'sticky' => isset($sticky) ? $sticky : 0,
            'category_id' => $request->cat,
            'slug' => $slug
        ]);
        if(!empty($request->video_url)){

            Blog::where('id','=',$blog->id)->update([

                    'video_url' => $request->video_url

                ]);

        }
        if ($request->has('media')) {
            $blog->media = $request->file('media')->move(
                'media',
                uniqid() . $request->file('media')->getClientOriginalName()
            );
            $blog->save();
        }
        if ($request->has('app_image')) {
            $blog->app_image = $request->file('app_image')->move(
                'media',
                uniqid() . $request->file('app_image')->getClientOriginalName()
            );
            $blog->save();
        }
        return redirect('dashboard/blog/')->with('msg', 'Blog added or Updated successfully');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $d['title'] = "Edit Articles";
        $d['edblog'] = Blog::findOrFail($id);
        $d['blogcat'] = BlogCategory::orderBy('name', "asc")->get();
        return view('admin.blog.add', $d);
    }

    public function changeStatus($id){

        $Blog = Blog::where('id','=',$id)->first();

             Blog::where('id','=',$id)->update([

                                            'status' => ($Blog->status == 1) ? 0 : 1

                                                ]);

         return redirect('dashboard/blog/')->with('msg', 'status changed successfully');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Blog::destroy($id);
    }

    public function upload(Request $request){
        $fileName= uniqid() .$request->file('file')->getClientOriginalName();
        $path=$request->file('file')->move('upload-blog-image', $fileName);
        return response()->json(['location'=>url($path)]); 
        
     

    }

    public function searchBlog(){

    return view('admin.blog.dd');

    }


  
}
