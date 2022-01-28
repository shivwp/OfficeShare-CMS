<?php



namespace App;



use Illuminate\Database\Eloquent\Factories\HasFactory;

use Illuminate\Database\Eloquent\Model;



class ShippingAddress extends Model

{

    use HasFactory;

    protected $table="shipping_address";

    protected $fillable =['name',"phone","alternate_phone","address","address2","address_type","city","country",'state','zip_code','landmark','user_id'];

    public $timestamps=true;

     public function countryname()

    {

    	return $this->belongsTo(Country::class,'country');

    }

     public function statename()

    {

    	return $this->belongsTo(State::class,'state');

    }

     public function cityname()

    {

    	return $this->belongsTo(City::class,'city');

    }
    public function billing_countryname()

    {

    	return $this->belongsTo(Country::class,'billing_country');

    }

     public function billing_statename()

    {

    	return $this->belongsTo(State::class,'billing_state');

    }

     public function billing_cityname()

    {

    	return $this->belongsTo(City::class,'billing_city');

    }


}

