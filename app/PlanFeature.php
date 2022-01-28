<?php



namespace App;



use Illuminate\Database\Eloquent\Factories\HasFactory;

use Illuminate\Database\Eloquent\Model;



class PlanFeature extends Model

{

    use HasFactory;
    protected $hidden = array('pivot');

    protected $table='plan_features';

    protected $fillable = ['id','title', 'features_order'];


    // public function plans()
    // {
    //     return $this->belongsToMany(Plans::class);

    // }


    // public function state()
    // {

    //   return $this->hasMany(State::class,'country_id');
    // }

}

