<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\PlanFeature;
use Illuminate\Http\Request;
use Session;

class PlanFeatureController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $feature = PlanFeature::orderBy('id', 'DESC');

        $d['feature'] =  $feature->paginate(10);

        $d['title'] = "Manage Package Feature";

        return view('admin.plan-feature.index', $d);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

         $d['title'] = "Add Package Feature";

        return view('admin.plan-feature.add', $d);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $d['title'] = "Package Feature";
        $plan = PlanFeature::updateOrCreate(['id' => $request->id], [
            'title' => $request->title,
            'features_order' => $request->features_order
        ]);


        $d['feature'] = PlanFeature::orderBy('id', 'desc')->get();

        return view('admin.plan-feature.index',$d);

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
            $d['title'] = "Edit Package";
            $d['planfeature'] = PlanFeature::findOrFail($id);
            return view('admin.plan-feature.add', $d);
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {

       

        PlanFeature::destroy($id);
        //
    }
}
