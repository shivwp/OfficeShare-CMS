<?php



namespace App;



use Illuminate\Database\Eloquent\Factories\HasFactory;

use Illuminate\Database\Eloquent\Model;



class Space extends Model

{

    use HasFactory;

    protected $table='property_spaces';

    protected $fillable = ['id','property_id','user_id','property_type_id','space_title','featured_image','gallary_image','cost','total_desk','key_feature','thumb','booking_approval','booking_payment_refund','price_to','price_from','availability_type','cost_type'];



  

}

