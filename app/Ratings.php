<?php



namespace App;



use Illuminate\Database\Eloquent\Factories\HasFactory;

use Illuminate\Database\Eloquent\Model;



class Ratings extends Model

{

    use HasFactory;

    protected $table='user_rating';

    protected $fillable = ['id','type','type_id','rating','user_id'];


}

