<?php



namespace App;



use Illuminate\Database\Eloquent\Factories\HasFactory;

use Illuminate\Database\Eloquent\Model;



class PropertyLocation extends Model

{

    use HasFactory;

    protected $table='property_locations';

    protected $fillable = ['id','property_id','country_id','state_id','city_id','postcode','address','address_2','longitude','latitude','city','state','country'];



    // public function state()
    // {

    //   return $this->hasMany(State::class,'country_id');
    // }

}

