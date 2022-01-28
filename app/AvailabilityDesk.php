<?php



namespace App;



use Illuminate\Database\Eloquent\Factories\HasFactory;

use Illuminate\Database\Eloquent\Model;



class AvailabilityDesk extends Model

{

    use HasFactory;

    protected $table='availability_desk';

    protected $fillable = ['id','landload_id','space_id','available_desk','from_date','to_date','type'];



  



}

