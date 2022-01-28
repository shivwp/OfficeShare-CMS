<?php
namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;

use Illuminate\Database\Eloquent\Model;

class CouponUsedByUser extends Model
{

     use HasFactory;
     protected $table='coupon_used_by_users';
     protected $fillable=['id','coupon_id','user_id','no_of_time'];


}

