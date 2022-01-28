<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Blog;
use App\BlogCategory;
use Illuminate\Support\Facades\Auth;

class QuestionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $d['title'] = "Manage Blogs";
        $d['blog'] = Blog::with('user')->orderBy('id', 'desc')->get();
        return view('admin.blog.index', $d);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

        $d['title'] = "Add Blog";
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
        //dd($request->sticky);
        $slug = isset($request->slug) ? str_replace(" ", "-", $request->slug) : str_replace(" ", "-", $request->title);
        $blog = Blog::updateOrCreate(['id' => $request->id], [
            'media_type' => $request->media_type,
            'title' => $request->title,
            'short_description' => $request->sd,
            'description' => $request->fd,
            'meta_title' => $request->meta_title,
            'meta_keyword' => $request->meta_keyword,
            'meta_description' => $request->meta_description,
            'user_id' => Auth::user()->id,
            'feature' => $request->feature == 1 ? 1 : 0,
            'sticky' => $request->sticky == 1 ? 1 : 0,
            'category_id' => $request->cat,
            'slug' => $slug
        ]);
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
        return redirect('dashboard/blog/')->with('msg', 'Blog added or updated successfully');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $d['title'] = "Edit Blog";
        $d['edblog'] = Blog::findOrFail($id);
        $d['blogcat'] = BlogCategory::orderBy('name', "asc")->get();
        return view('admin.blog.add', $d);
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
}
