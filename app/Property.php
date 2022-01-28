<?php



namespace App;



use Illuminate\Database\Eloquent\Factories\HasFactory;

use Illuminate\Database\Eloquent\Model;



class Property extends Model

{

    use HasFactory;

    protected $table='property';

    protected $fillable = ['id','user_id','property_title','short_description','description','total_desk','available_desk','thumbnail','gallary_image','city','is_approved','price_from','price_to'];



    public function space()
    {

      return $this->hasMany(Space::class,'property_id');
    }

  

    public function user()
    {

      return $this->hasOne(User::class,'id','user_id');
    }

     public function location()
    {

      return $this->hasOne(PropertyLocation::class,'property_id','id');
    }



}

