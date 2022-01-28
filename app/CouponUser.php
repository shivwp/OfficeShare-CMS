<?php
namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;

use Illuminate\Database\Eloquent\Model;

class CouponUser extends Model
{

     use HasFactory;
     protected $table='coupon_users';
     protected $fillable=['id','coupon_id','email_id'];


}

