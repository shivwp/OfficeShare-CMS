<?php



namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;

use Illuminate\Database\Eloquent\Model;

class CouponProduct extends Model
{

     use HasFactory;
     protected $table='coupon_products';
     protected $fillable=['id','coupon_id','product_id','exclude_product_id'];

}

