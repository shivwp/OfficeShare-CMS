<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Pages;
use App\PageMeta;
use Illuminate\Http\Request;
use Gate;
use Symfony\Component\HttpFoundation\Response;
class PagesController extends Controller
{
    public function index()
    {
       // abort_if(Gate::denies('page_access'),'403 forbidden');
        $d['title']="Manage Page";
        $pg=Pages::orderBy('id', 'DESC');
        $d['page_title']=$pg;
        $d['page']=$pg->paginate(10);
        return view('admin.pages.index',$d);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
      //abort_if(Gate::denies('page_create'),'403 forbidden');
       $d['title']="Add Page";

       return view('admin.pages.add-page',$d);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        
        if(request()->post())
        {
            $data = [
                'name' => $request->name,
                'title' => $request->title,
                'content' => $request->content,
                'slugs' => $request->slug,
                'meta_title' => $request->meta_title,
                'meta_keyword' => $request->meta_keyword,
                'meta_description' => $request->meta_description,
            ];


            if($request->slug == 'home') {
                // 
                $content = $meta_values =  [];
                $page_content = '';

                $meta_values['left_heading_title'] = $request->left_heading_title;
                $meta_values['left_heading_desc'] = $request->left_heading_desc;
                $meta_values['right_heading_title'] = $request->right_heading_title;
                $meta_values['right_heading_desc'] = $request->right_heading_desc;
                $meta_values['right_filter_title'] = $request->right_filter_title;
                $meta_values['right_app_title'] = $request->right_app_title;
                $meta_values['left_video_url'] = $request->left_video_url;

                $meta_values['left_heading_title2'] = $request->left_heading_title2;
                $meta_values['left_heading_short_desc'] = $request->left_heading_short_desc;
                $meta_values['left_heading_desc2'] = $request->left_heading_desc2;
                $meta_values['right_app_downloads'] = $request->right_app_downloads;
                $meta_values['number_of_blogs'] = $request->number_of_blogs;
            
                if($request->has('left_bg_image')) {
                    $file=$request->left_bg_image;
                    if($file) {
                        $name='home_page1'.uniqid().$file->getClientOriginalName();
                        $file->move('images/homePage', $name);
                        $meta_values['left_bg_image'] = $name;
                    }
                }

                if($request->has('right_bg_image')) {
                    $file=$request->right_bg_image;
                    if($file) {
                        $name='home_page2'.uniqid().$file->getClientOriginalName();
                        $file->move('images/homePage', $name);
                        $meta_values['right_bg_image'] = $name;
                    }
                }

                foreach ($meta_values as $key => $value) {
                    // code...
                    $PageMeta = PageMeta::updateOrCreate(['page_id'=>$request->pid, 'meta_key' => $key],[
                        'meta_value' => $value
                    ]);   
                }
                
                $data['sections'] = json_encode($content);
                $data['content'] = $page_content;

            }
            
            $pg = Pages::updateOrCreate(['id' => $request->pid], $data);

            if($request->has('pid')) {
                // 
                return redirect('dashboard/pages/');
            }else{
                session()->flash('msg',"Page added successfully");
                return back();
            }

        }

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $page=Pages::findOrFail($id);
        $d['content']=$page->page_subtitle_content;
       return view("admin.pages.view-page",$d);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        abort_if(Gate::denies('page_edit'),'403 forbidden');
        $title="Edit Page";
        $p=Pages::findOrFail($id);
        if($p->slugs == 'home') {
            // 
            $p['sections'] = json_decode($p->sections);
            $PageMeta = PageMeta::where('page_id', $id)->get();
            $p_meta_values = [];

            foreach ($PageMeta as $key => $value) {
                // code...
                $p_meta_values[$value->meta_key] = $value->meta_value;
            }
            $p['meta_value'] = $p_meta_values;

            return view('admin.pages.home-page',['page'=>$p,'title'=>$title]);
        }
        return view('admin.pages.add-page',['page'=>$p,'title'=>$title]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        abort_if(Gate::denies('page_delete'),'403 forbidden');
        if(request()->ajax()){
          $pg=Pages::findOrFail($id);
          $pg->delete();
          return response()->json(['msg'=>'Removed successfully']);
        }
    }
}
