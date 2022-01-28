<?php

namespace App\Http\Controllers\Admin;

use App\CurrencyExchange;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\CurrencyExchangeRate;
use Gate;

class CurrencyExchangeController extends Controller
{
  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function index()
  {
    $d['title'] = "Add Currency";
    $d['defcurrency'] = CurrencyExchange::where('status', 1)->first();
    $d['currency'] = CurrencyExchange::latest()->get();
    return view('admin.currency-exchange.currency-exchange', $d);
  }

  /**
   * Store a newly created resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\Response
   */
  public function store(Request $request)
  {
    // abort_if(Gate::denies('currency_store'),'403 forbidden');
    CurrencyExchange::updateOrCreate(['id' => $request->id], [
      'name' => $request->name,
      "code" => $request->code,
      "sign" => $request->cursign,
      "status" => $request->stat == 1 ? 1 : 0,
      "country_name" => $request->country,
      "country_code" => strtoupper($request->country_code)
    ]);
    return redirect('dashboard/currency-exchange');
  }
  public function currencyExchangeRate(Request $request)
  {
    //  abort_if(Gate::denies('currency_store'),'403 forbidden');
    CurrencyExchangeRate::updateOrCreate(['id' => $request->rid], [
      'source_id' => $request->source_id,
      "target_id" => $request->target_id,
      'source_rate' => $request->sourcerate,
      'target_rate' => $request->targetrate,

    ]);
    return back();
  }
  /**
   * Display the specified resource.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function show($id)
  {
  }

  /**
   * Show the form for editing the specified resource.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function edit($id)
  {
    // abort_if(Gate::denies('color_edit'),'403 forbidden');
    $d['title'] = "Edit Currency";
    $d['defcurrency'] = CurrencyExchange::where('status', 1)->first();
    $d['edcurr'] = CurrencyExchange::findOrFail($id);
    return view('admin.currency-exchange.currency-exchange', $d);
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
    //  abort_if(Gate::denies('currency_delete'),'403 forbidden');
    CurrencyExchange::destroy($id);
  }
  public function makeDefaultCurrency($id, $st)
  {

    $cu = CurrencyExchange::where('id', $id)
      ->update(['status', 0]);
    $cu->status = $st;
    $cu->update();
  }
}
