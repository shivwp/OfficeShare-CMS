<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\HomeSettings;
use Illuminate\Support\Facades\Auth;

class HomeSettingsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $d['title'] = "Home Settings";
        $d['homeSetting'] = HomeSettings::get();

      
        return view('admin.master.home-setting',$d);
        //return view('admin.master.test');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

        $d['title'] = "Add Module";
     
        return view('admin.master.home-setting-add', $d);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        //dd($request);

        $homesettings = HomeSettings::updateOrCreate(['id' => $request->id], [
            'name'      => $request->title,
            'contents'  => $request->content
        ]);

        $d['title'] = "Home Settings";
        $d['homeSetting'] = HomeSettings::get();

         return view('admin.master.home-setting',$d);

      
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $d['title'] = "Edit Home Settings";
        $d['homeSetting'] = HomeSettings::findOrFail($id);
        return view('admin.master.home-setting-add', $d);
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
