<?php



namespace App;



use Illuminate\Database\Eloquent\Factories\HasFactory;

use Illuminate\Database\Eloquent\Model;



class Country extends Model

{

    use HasFactory;

    protected $table='countries';



    public function state()
    {

      return $this->hasMany(State::class,'country_id');
    }

}

