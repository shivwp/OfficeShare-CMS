<?php



namespace App;



use Illuminate\Database\Eloquent\Factories\HasFactory;

use Illuminate\Database\Eloquent\Model;



class SpaceType extends Model

{

    use HasFactory;

    protected $table='property_type';

    protected $fillable = ['id','title'];





  

}

