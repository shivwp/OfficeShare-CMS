<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CurrencyExchangeRate extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable=['source_id','target_id','source_rate','target_rate'];
    public function currency()
    {
      return $this->belongsTo(CurrencyExchange::class,"target_id");
    }
    public function sourceCurrency()
    {
      return $this->belongsTo(CurrencyExchange::class,"source_id");
    }
}
