<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CurrencyExchange extends Model
{
    use HasFactory;
    protected $fillable=['id','name','code','sign','status',
                         "country_name","country_code"];

    public function currencyRate()
    {
      return $this->hasMany(CurrencyExchangeRate::class,"source_id");
    }
}
