<?php



namespace App;



use Illuminate\Database\Eloquent\Factories\HasFactory;

use Illuminate\Database\Eloquent\Model;



class Setting extends Model

{

    use HasFactory;

    protected $table="settings";

    protected $fillable=['id','name','value'];

    public $timestamps=true;

    public function countryName()

    {

    	return $this->belongsTo(Country::class,'value');

    }

        public function phoneCode()

    {

    	return $this->belongsTo(Country::class,'phone_country');

    }

}

