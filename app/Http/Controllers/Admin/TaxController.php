<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Tax;
use Facade\FlareClient\Http\Response;
use Gate;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class TaxController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //abort_if(Gate::denies('tax_access'),Response::HTTP_FORBIDDEN,'403 forbidden');
        abort_if(Gate::denies('tax_access'), '403 Forbidden');
        $d['title'] = "Add Taxes";
        $d['tax'] = Tax::orderBy('tax_type')->get();
        return view('admin.tax.index', $d);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        Tax::updateOrCreate(
            ['id' => $request->id],
            [
                'tax_type' => $request->tax_type,
                'tax' => Str::upper($request->tax)
            ]
        );
        return redirect('dashboard/tax/')->with('msg', "Tax Added or Updated");
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
        abort_if(Gate::denies('tax_edit'), '403 Forbidden');
        $d['title'] = "Edit Taxes";
        $d['taxs'] = Tax::findOrFail($id);
        return view('admin.tax.index', $d);
    }



    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if (request()->ajax()) {
            Tax::destroy($id);
            return response()->json(['msg' => "removed successfully"]);
        }
    }
    public function changeStatus($id)
    {
        $tax = Tax::findOrFail($id);
        if ($tax->status == 1) {
            $tax->status = 0;
            $tax->update();
        } else {
            $tax->status = 1;
            $tax->update();
        }
        return back();
    }
}
