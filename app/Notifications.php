<?php



namespace App;



use Illuminate\Database\Eloquent\Factories\HasFactory;

use Illuminate\Database\Eloquent\Model;



class Notifications extends Model

{

    use HasFactory;

    protected $table='notifications';

    protected $fillable = ['id','title','body','image','type'];


}

