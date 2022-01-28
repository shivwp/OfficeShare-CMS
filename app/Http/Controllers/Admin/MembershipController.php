<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Membership;

class MembershipController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index()
    {
        $d['title'] = "Manage Memebership";
        $d['mem'] = Membership::all();
        return view('admin.subscription.index', $d);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $d['title'] = "Create Memebership";
        return view('admin.subscription.create', $d);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $mem = Membership::updateOrCreate(['id' => $request->id], [
            "name" => $request->name,
            "price" => $request->price,
            "plateform_charges" => $request->pch,
            "subscribstion_type" => $request->memtype,
            "noofproperty" => $request->noofproperty,
            "description" => $request->name,
            "slug" => str_replace(" ", "-", $request->slug),
            "meta_title" => $request->meta_title,
            "meta_keyword" => $request->meta_key,
            "meta_description" => $request->meta_description,
            "features" => $request->features
        ]);
        return redirect('dashboard/membership')->with('msg', 'Added or Updated successfully');
    }



    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $d['title'] = "Edit Memebership";
        $d['edmem'] = Membership::find($id);
        return view('admin.subscription.create', $d);
    }



    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {

        Membership::destroy($id);
    }

    public function changeStatus($id, $st)
    {
        Membership::where('id', $id)->update(['status' => $st]);
        return redirect('admin/membership');
    }
}
