<?php

namespace App\Http\Controllers\Api;

use App\CurrencyExchange;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\CurrencyExchangeRate;
class CurrencyExchangeApiController extends Controller
{
    public function index()
    {
      $i=0;
      $rate=[];
      $collect='';
      $j=0;
      $cur=CurrencyExchange::with(["currencyRate"])->where('status',1)->first();
      if(isset($cur)){
            if(isset($cur->currencyRate)){
                foreach($cur->currencyRate as  $value2) {
                  $rate[$j++]=['id'=>$value2->id,
                  'countryName'=>$value2->currency['country_name'],
                  'countryCode'=>$value2->currency['country_code'],
                  'currencyValue'=>$value2->target_rate,
                  'currencyCode'=>$value2->currency['code'],
                  'currencySymbol'=>$value2->currency['sign'],
                ]; 
                }
            }
            $curCode=['currencyCode'=>$cur->code,"currencySymbol"=>$cur->sign];
            $collect=['baseCurrency'=>$curCode,'currencyRates'=>$rate
            ];  
            $rate=[];
            $j=0;
             return response()->json(["data"=>$collect],200);    
         }else{
            return response()->json(["data"=>[]],200); 
         }
     
    }
    public function changeCurrency(Request $request)
    {
        if(!empty($request->source_currency_id) && !empty($request->target_currency_id)){
            $cr=CurrencyExchangeRate::with("currency")
            ->where('source_id',$request->source_currency_id)
            ->where('target_id',$request->target_currency_id)->first();
            $collect=[];$i=0;
            if(isset($cr)){
              
            }
            return response()->json( $collect,200);
        }else{
            return response()->json(['msg'=>"Please provide currency id","status"=>false],200);
        }
     
    }
}
