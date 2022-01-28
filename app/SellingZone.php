<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SellingZone extends Model
{
    use HasFactory,SoftDeletes;
    protected $fillable=['id','country','state','city','postal_code','shipping_charge'];
    protected $dates=['deleted_at'];

    public function getCountry()
    {
    	return $this->belongsTo(Country::class,'country');
    }
     public function getState()
    {
    	return $this->belongsTo(State::class,'state');
    }
     public function getCity()
    {
    	return $this->belongsTo(City::class,'city');
    }
       public function shippingOption()
    {
    	return $this->hasMany(ShippingOption::class,'selling_zone_id');
    }
}
