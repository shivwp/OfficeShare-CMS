<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\StripePayment;

class StripePaymentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $d['title'] = "Add Payment method";
        $d['stripe'] = StripePayment::all();
        return view('admin.stripe.index', $d);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        StripePayment::updateOrCreate(
            ['id' => $request->id],
            [
                "payment_gateway" => $request->name,
                "secret_key" => $request->sec_key,
                "publishing_key" => $request->pub_key
            ]
        );
        return redirect('dashboard/stripe-setup');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $d['title'] = "Edit Payment method";
        $d['edstripe'] = StripePayment::findOrFail($id);
        return view('admin.stripe.index', $d);
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
            StripePayment::destroy($id);
        }
    }

    public function changeStatus($id, $st)
    {
        $sp = StripePayment::findOrFail($id);
        $sp->status = $st;
        $sp->update();
        return back();
    }
}
