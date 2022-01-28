<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Plans;
use App\PlanFeature;
use Illuminate\Support\Facades\Auth;

class PlanController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $d['title'] = "Manage Packages";
        $d['plan'] = Plans::orderBy('id', 'desc')->get();
        return view('admin.plan.index', $d);
    }

     public function create()
    {

        $d['title']         = "Add Packages";
        $d['Plan']          = Plans::orderBy('id', "asc")->get();
        $d['feature']       = PlanFeature::pluck('title','id');

        return view('admin.plan.add', $d);

    }

      public function store(Request $request)
        {
            //dd($request);
            //dd($request->sticky);
            $validated = $request->validate([
                'description' => 'required'
            ]);

            $gettype = $request->valid;
            $type  =  explode(" ",$gettype);

           
            $plan = Plans::updateOrCreate(['id' => $request->id], [
                'title'         => $request->title,
                'description'   => $request->description,
                'validity'      => $request->valid,
                'price'         => $request->price,
                'number'         => $type[0],
                'type'         => $type[1],
            ]);

            // print_r($request->attribute);
            // exit;

            $plan->features()->sync($request->input('attribute', []));
          
            return redirect('dashboard/plan/')->with('msg', 'Plans added or updated successfully');
        }

      public function edit($id)
        {
                // $title = 'Edit Plans';
                // $feature = PlanFeature::pluck('title','id');
                // $Plan = Plans::findOrFail($id);

           // $Plan->load('features');
            $d['title']         = "Edit Packages";
            $d['Plan']          = Plans::findOrFail($id);
            $d['feature']       = PlanFeature::pluck('title','id');

            $d['Plan']->load('features');

            return view('admin.plan.edit', $d);
        }
         public function show(Plans $Plan)
        {
             Plans::where('id','=',$Plan->id)->delete();
             

             return redirect('dashboard/plan/')->with('msg', 'Plans deleted successfully');
            
        }


        public function destroy($id)
        {

            // echo $id;
            // die;
            Plans::where('id','=',$id)->delete($id);

             return view('admin.plan.index');
        }


   
}
